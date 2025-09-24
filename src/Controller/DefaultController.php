<?php

namespace BePassword\Controller;

use BePassword\Services\RenderService;
use BePassword\Services\FilterService;
use BePassword\Services\RandomService;
use rex_i18n;

class DefaultController
{
    public function indexAction()
    {
        $rs = new \BePassword\Services\RenderService();

        return $rs->render(
            'views/index.php',
            array()
        );
    }

    public function formAction()
    {
        $error = '';
        $success = '';
        $rs = new RenderService();
        $rand = new RandomService();

        if (isset($_POST['email'])) {
            // Validate email format
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = rex_i18n::msg('be_password_error_invalid_email');
            } else {
                // Check if there is an account
                $db = \rex_sql::factory();
                $rows = $db->getArray("SELECT * FROM rex_user WHERE email=?", array(
                    $email,
                ));
                if (!is_array($rows) || 0 == count($rows)) {
                    // Kein Account gefunden
                    // Aus Datenschutzgründen zeigen wir trotzdem eine Erfolgsmeldung
                    $success = rex_i18n::msg('be_password_success_mail');
                } elseif (1 < count($rows)) {
                    // Mehrere Accounts mit der gleichen Emailadresse vorhanden
                    // Aus Datenschutzgründen zeigen wir trotzdem eine Erfolgsmeldung
                    $success = rex_i18n::msg('be_password_success_mail');
                }
                if ('' == $success && '' == $error) {
                    $row = $rows[0];
                    $user_id = $row['id'];
                    // Entferne alle bisherigen reset-tokens für diesen user
                    $db = \rex_sql::factory();
                    $db->setTable(\rex::getTable('user_passwordreset'));
                    $db->setWhere(array('user_id' => $user_id));
                    $db->delete();
                    // Erzeuge neuen Token
                    try {
                        $token = $rand->createToken(100);
                    } catch (Exception $e) {
                        $error = rex_i18n::msg('be_password_error_server');
                        $token = null;
                    }
                    
                    if (null !== $token) {
                        $url = \rex::getServer() . 'redaxo/index.php?be_password_reset_token=' . $token;
                        $body = $rs->render('views/mail_reset_link.php', array(
                            'url' => $url,
                        ));
                        $subject = rex_i18n::msg('be_password_mail_title');
                        $mail = new \rex_mailer();
                        $mail->Body = rex_i18n::rawMsg('be_password_mail_text', $url);
                        $mail->AltBody = strip_tags($mail->Body);
                        $mail->Subject = $subject;
                        $mail->AddAddress($row['email'], '');
                        $res = $mail->Send();
                        if (false === $res) {
                            $error = rex_i18n::msg('be_password_error_server');
                        } else {
                            try {
                                $db->setTable(\rex::getTable('user_passwordreset'));
                                $db->setValue('reset_password_token_expires', date("Y-m-d H:i:s", time() + 3600))
                                    ->setValue('user_id', $user_id)
                                    ->setValue('reset_password_token', $token);
                                $db->insert();
                                $success = rex_i18n::msg('be_password_success_mail');
                            } catch (Exception $e) {
                                $error = rex_i18n::msg('be_password_error_server');
                            }
                        }
                    }
                }
            }
        }

        return $rs->render(
            'views/form.php',
            array(
            'error' => $error,
            'success' => $success,
        )
        );
    }

    public function resetAction()
    {
        $render_service = new RenderService();
        $filter_service = new FilterService();
        $error = '';
        $success = '';
        $showForm = false;
        $token = isset($_GET['token']) ? $_GET['token'] : '';
        
        // Validate token format - should be alphanumeric
        if (!empty($token) && !preg_match('/^[a-zA-Z0-9]+$/', $token)) {
            $error = rex_i18n::msg('be_password_error_token');
            $token = ''; // Clear invalid token
        }
        
        $pw = \rex_request::post('pw');

        $db = \rex_sql::factory();
        $sql = "SELECT *
            FROM `" . \rex::getTable('user_passwordreset') . "`
            WHERE reset_password_token=?
            AND reset_password_token_expires>?";
        $rows = $db->getArray($sql, array(
            $token,
            date("Y-m-d H:i:s", time()),
        ));
        if (!is_array($rows) || 1 != count($rows)) {
            $error = rex_i18n::msg('be_password_error_token');
            $user_id = null;
        } else {
            $user_id = $rows[0]['user_id'];
        }

        // Prüfe Passwort-Regeln
        if ('' == $error && !empty($pw) && null !== $user_id) {
            if (class_exists('\rex_backend_password_policy')) {
                if (true !== $msg = \rex_backend_password_policy::factory(\rex::getProperty('password_policy', []))->check($pw, $user_id)) {
                    $error = $msg;
                    $showForm = true;
                }
            }
        }

        if ('' == $error && !empty($pw) && null !== $user_id) {
            // Setze passwort neu
            try {
                $password = \rex_login::passwordHash($pw);
                $db->setTable('rex_user');
                $db->setWhere(array('id' => $user_id));
                $db->setValue('password', $password);
                $db->setValue('login_tries', 0);
                $db->update();

                // Lösche tokens
                $db = \rex_sql::factory();
                $db->setTable(\rex::getTable('user_passwordreset'));
                $db->setWhere(array('user_id' => $user_id));
                $db->delete();
                $success = rex_i18n::msg('be_password_success_new_password') . ' <a href="' . \rex_url::currentBackendPage() . '">' . rex_i18n::msg('be_password_success_go_to_login') . '</a>.';
            } catch (Exception $e) {
                $error = rex_i18n::msg('be_password_error_server');
            }
        }
        return $render_service->render(
            'views/reset.php',
            array(
            'error' => $error,
            'success' => $success,
            'token' => $token,
        )
        );
    }
}
