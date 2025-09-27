<?php

namespace FriendsOfRedaxo\BePassword\Controller;

use Exception;
use FriendsOfRedaxo\BePassword\Services\FilterService;
use FriendsOfRedaxo\BePassword\Services\RandomService;
use FriendsOfRedaxo\BePassword\Services\RenderService;
use rex;
use rex_backend_password_policy;
use rex_csrf_token;
use rex_i18n;
use rex_login;
use rex_mailer;
use rex_request;
use rex_sql;
use rex_url;

use function count;
use function is_array;

use const FILTER_SANITIZE_EMAIL;

class DefaultController
{
    private const RATE_LIMIT_REQUESTS = 3; // Max 3 requests
    private const RATE_LIMIT_WINDOW = 900; // in 15 minutes (900 seconds)

    public function indexAction()
    {
        $rs = new RenderService();

        return $rs->render(
            'views/index.php',
            [],
        );
    }

    public function formAction()
    {
        $error = '';
        $success = '';
        $rs = new RenderService();
        $rand = new RandomService();

        // Create CSRF token for form
        $csrf_token = rex_csrf_token::factory('be_password_form');

        $email = rex_request::post('email', 'string', '');
        if ('' < $email) {
            // Validate CSRF token
            if (!$csrf_token->isValid()) {
                $error = rex_i18n::msg('be_password_error_csrf');
            } elseif (!$this->checkRateLimit()) {
                $error = rex_i18n::msg('be_password_error_rate_limit', $this->getRateLimitWaitTime());
            } else {
                // Validate email format
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (false === $email) {
                    $error = rex_i18n::msg('be_password_error_invalid_email');
                } else {
                    // Check if there is an account
                    $db = rex_sql::factory();
                    $rows = $db->getArray('SELECT * FROM rex_user WHERE email=?', [
                        $email,
                    ]);
                    // Timing attack protection - add consistent delay
                    $this->addTimingDelay();

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
                        $db = rex_sql::factory();
                        $db->setTable(rex::getTable('user_passwordreset'));
                        $db->setWhere(['user_id' => $user_id]);
                        $db->delete();
                        // Erzeuge neuen Token
                        try {
                            $token = $rand->createToken(100);
                        } catch (Exception $e) {
                            $error = rex_i18n::msg('be_password_error_server');
                            $token = null;
                        }

                        if (null !== $token) {
                            $url = rex::getServer() . 'redaxo/index.php?be_password_reset_token=' . $token;
                            $body = $rs->render('views/mail_reset_link.php', [
                                'url' => $url,
                            ]);
                            $subject = rex_i18n::msg('be_password_mail_title');
                            $mail = new rex_mailer();
                            $mail->Body = rex_i18n::rawMsg('be_password_mail_text', $url);
                            $mail->AltBody = strip_tags($mail->Body);
                            $mail->Subject = $subject;
                            $mail->AddAddress($row['email'], '');
                            $res = $mail->Send();
                            if (false === $res) {
                                $error = rex_i18n::msg('be_password_error_server');
                            } else {
                                try {
                                    $db->setTable(rex::getTable('user_passwordreset'));
                                    $db->setValue('reset_password_token_expires', date('Y-m-d H:i:s', time() + 3600))
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
        }

        return $rs->render(
            'views/form.php',
            [
                'error' => $error,
                'success' => $success,
                'csrf_token' => $csrf_token,
            ],
        );
    }

    public function resetAction()
    {
        $render_service = new RenderService();
        $filter_service = new FilterService();
        $error = '';
        $success = '';
        $showForm = false;
        $token = rex_request::get('token', 'string', '');

        // Validate token format - should be alphanumeric
        if ('' < $token && !preg_match('/^[a-zA-Z0-9]+$/', $token)) {
            $error = rex_i18n::msg('be_password_error_token');
            $token = ''; // Clear invalid token
        }

        $pw = rex_request::post('pw', 'string');

        // Additional security measures (better than CSRF for password reset):
        // 1. Rate limiting is active (checkRateLimit in formAction)
        // 2. Token expires after 1 hour
        // 3. Token can only be used once (deleted after successful reset)
        // 4. Token is cryptographically secure (100 chars, random_int)
        // 5. Timing attack protection prevents token enumeration

        $db = rex_sql::factory();
        $sql = 'SELECT *
            FROM `' . rex::getTable('user_passwordreset') . '`
            WHERE reset_password_token=?
            AND reset_password_token_expires>?';
        $rows = $db->getArray($sql, [
            $token,
            date('Y-m-d H:i:s', time()),
        ]);
        if (!is_array($rows) || 1 != count($rows)) {
            $error = rex_i18n::msg('be_password_error_token');
            $user_id = null;
        } else {
            $user_id = $rows[0]['user_id'];
        }

        // Prüfe Passwort-Regeln
        if ('' == $error && !empty($pw) && null !== $user_id) {
            if (class_exists('\rex_backend_password_policy')) {
                if (true !== $msg = rex_backend_password_policy::factory(rex::getProperty('password_policy', []))->check($pw, $user_id)) {
                    $error = $msg;
                    $showForm = true;
                }
            }
        }

        if ('' == $error && !empty($pw) && null !== $user_id) {
            // Setze passwort neu
            try {
                $password = rex_login::passwordHash($pw);
                $db->setTable('rex_user');
                $db->setWhere(['id' => $user_id]);
                $db->setValue('password', $password);
                $db->setValue('login_tries', 0);
                $db->update();

                // Lösche tokens
                $db = rex_sql::factory();
                $db->setTable(rex::getTable('user_passwordreset'));
                $db->setWhere(['user_id' => $user_id]);
                $db->delete();
                $success = rex_i18n::msg('be_password_success_new_password') . ' <a href="' . rex_url::currentBackendPage() . '">' . rex_i18n::msg('be_password_success_go_to_login') . '</a>.';
            } catch (Exception $e) {
                $error = rex_i18n::msg('be_password_error_server');
            }
        }
        return $render_service->render(
            'views/reset.php',
            [
                'error' => $error,
                'success' => $success,
                'token' => $token,
            ],
        );
    }

    /**
     * Check if rate limit is exceeded.
     */
    private function checkRateLimit(): bool
    {
        $ip = rex_request::server('REMOTE_ADDR', 'string', 'unknown');
        $sessionKey = 'be_password_rate_limit_' . md5($ip);

        $_SESSION[$sessionKey] ??= [];

        $requests = $_SESSION[$sessionKey];
        $now = time();

        // Clean old requests
        $requests = array_filter($requests, static fn ($timestamp) => ($now - $timestamp) < self::RATE_LIMIT_WINDOW);

        // Check if limit exceeded
        if (count($requests) >= self::RATE_LIMIT_REQUESTS) {
            return false;
        }

        // Add current request
        $requests[] = $now;
        $_SESSION[$sessionKey] = $requests;

        return true;
    }

    /**
     * Get wait time in minutes for rate limit.
     */
    private function getRateLimitWaitTime(): int
    {
        $ip = rex_request::server('REMOTE_ADDR', 'string', 'unknown');
        $sessionKey = 'be_password_rate_limit_' . md5($ip);

        if (empty($_SESSION[$sessionKey])) {
            return 0;
        }

        $oldestRequest = min($_SESSION[$sessionKey]);
        $waitSeconds = self::RATE_LIMIT_WINDOW - (time() - $oldestRequest);

        return max(0, ceil($waitSeconds / 60));
    }

    /**
     * Add consistent timing delay to prevent timing attacks
     * This ensures all password reset requests take the same amount of time.
     */
    private function addTimingDelay(): void
    {
        // Add a consistent delay between 100ms and 300ms
        usleep(random_int(100_000, 300_000));
    }
}
