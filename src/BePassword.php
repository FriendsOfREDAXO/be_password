<?php

namespace FriendsOfRedaxo\BePassword;

use FriendsOfRedaxo\BePassword\Services\Render;
use rex_i18n;

class BePassword
{
    public function indexAction() :string
    {
        $rs = new \FriendsOfRedaxo\BePassword\Services\Render();

        return $rs->render(
            'fragments/be_password/index.php',
            array()
        );
    }

    public function formAction() :string
    {
        $error = '';
        $success = '';
        $rs = new Render();

        if (isset($_POST['email'])) {
            // Check if there is an account
            $db = \rex_sql::factory();
            $rows = $db->getArray("SELECT * FROM rex_user WHERE email=?", array(
                $_POST['email'],
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
            if ('' == $success) {
                $row = $rows[0];
                $user_id = $row['id'];
                // Entferne alle bisherigen reset-tokens für diesen user
                $db = \rex_sql::factory();
                $db->setTable(\rex::getTable('be_password_reset'));
                $db->setWhere(array('user_id' => $user_id));
                $db->delete();
                // Erzeuge neuen Token
                $token = self::createToken();
                $url = \rex::getServer() . 'redaxo/index.php?be_password_reset_token=' . $token;
                $body = $rs->render('fragments/be_password/mail_reset_link.php', array(
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
                    $db->setTable(\rex::getTable('be_password_reset'));
                    $db->setValue('token_expires', date("Y-m-d H:i:s", time() + 3600))
                        ->setValue('user_id', $user_id)
                        ->setValue('token', $token);
                    $db->insert();
                    $success = rex_i18n::msg('be_password_success_mail');
                }
            }
        }

        return $rs->render(
            'fragments/be_password/form.php',
            array(
            'error' => $error,
            'success' => $success,
        )
        );
    }

    public function resetAction() :string
    {
        $render_service = new Render();
        $error = '';
        $success = '';
        $token = rex_get('token', 'string', '');
        $pw = \rex_request::post('pw');

        $db = \rex_sql::factory();
        $sql = "SELECT *
            FROM `" . \rex::getTable('be_password_reset') . "`
            WHERE token=?
            AND token_expires>?";
        $rows = $db->getArray($sql, array(
            $token,
            date("Y-m-d H:i:s", time()),
        ));
        if (!is_array($rows) || 1 != count($rows)) {
            $error = rex_i18n::msg('be_password_error_token');
        } else {
            $user_id = $rows[0]['user_id'];
        }

        // Prüfe Passwort-Regeln
        if ('' == $error && !empty($pw)) {
            if (true !== $msg = \rex_backend_password_policy::factory(\rex::getProperty('password_policy', []))->check($pw, $user_id)) {
                $error = $msg;
                $showForm = true;
            }
        }

        if ('' == $error && !empty($pw)) {
            // Setze passwort neu
            $password = \rex_login::passwordHash($pw);
            $db->setTable('rex_user');
            $db->setWhere(array('id' => $user_id));
            $db->setValue('password', $password);
            $db->setValue('login_tries', 0);
            $db->update();

            // Lösche tokens
            $db = \rex_sql::factory();
            $db->setTable(\rex::getTable('be_password_reset'));
            $db->setWhere(array('user_id' => $user_id));
            $db->delete();
            $success = rex_i18n::msg('be_password_success_new_password') . ' <a href="' . \rex_url::currentBackendPage() . '">' . rex_i18n::msg('be_password_success_go_to_login') . '</a>.';
        }
        return $render_service->render(
            'fragments/be_password/reset.php',
            array(
            'error' => $error,
            'success' => $success,
            'token' => $token,
        )
        );
    }
    
    public static function createToken() :string
    {
        return bin2hex(random_bytes(32));
    }
}
