<?php
namespace BePassword\Controller;
use BePassword\Services\RenderService;
use BePassword\Services\FilterService;
use BePassword\Services\RandomService;

class DefaultController
{
    public function indexAction(){
        $rs = new \BePassword\Services\RenderService();

        return $rs->render(
            'views/index.php', array());
    }

    public function formAction(){
        $error = '';
        $success = '';
        $rs = new RenderService();
        $rand = new RandomService();

        if(isset($_POST['email'])){
            // Check if there is an account
            $db = \rex_sql::factory();
            $rows = $db->getArray("SELECT * FROM rex_user WHERE email=?", array(
                $_POST['email'],
            ));
            if(!is_array($rows) || 0 == count($rows)){
                $error = 'Kein Account gefunden';
            }elseif(1< count($rows)){
                $error = 'Mehrere Accounts mit der gleichen Emailadresse vorhanden';
            }
            if('' == $error){
                $row = $rows[0];
                $user_id = $row['id'];
                // Entferne alle bisherigen reset-tokens für diesen user
                $db = \rex_sql::factory();
                $db->setTable(\rex::getTable('user_passwordreset'));
                $db->setWhere(array('user_id'=>$user_id));
                $db->delete();
                // Erzeuge neuen Token
                $token = $rand->createToken(100);
                $url = \rex::getServer().'redaxo/index.php?be_password_reset_token='.$token;
                $body = $rs->render('views/mail_reset_link.php', array(
                    'url' => $url,
                ));
                $subject = \rex_config::get('be_password', 'mail_subject_de');
                $mail = new \rex_mailer();
                $mail->Body    = \rex_config::get('be_password', 'mail_body_html_de');
                $mail->Body    = str_replace('{{url}}', $url, $mail->Body);
                $mail->AltBody = strip_tags($mail->Body);
                $mail->Subject = $subject;
                $mail->AddAddress($row['email'], '');
                $res = $mail->Send();
                if(false === $res){
                    $error = 'E-Mail konnte nicht gesendet werden';
                }else{
                    $db->setTable(\rex::getTable('user_passwordreset'));
                    $db->setValue('reset_password_token_expires', date("Y-m-d H:i:s", time()+3600))
                        ->setValue('user_id', $user_id)
                        ->setValue('reset_password_token', $token);
                    $db->insert();
                    $success = 'Link zum setzen des neuen Passworts wurde gesendet';
                }
            }
        }

        return $rs->render(
            'views/form.php', array(
                'error' => $error,
                'success' => $success,
            ));

    }

    public function resetAction(){
        $render_service = new RenderService();
        $filter_service = new FilterService();
        $error = '';
        $success='';
        $token = isset($_GET['token'])?$_GET['token'] : '';

        $db = \rex_sql::factory();
        $sql = "SELECT * 
            FROM `". \rex::getTable('user_passwordreset')."`
            WHERE reset_password_token=?
            AND reset_password_token_expires>?";
        $rows = $db->getArray($sql, array(
                $token,
                date("Y-m-d H:i:s", time()),
            ));
        if(!is_array($rows) || 1 != count($rows)){
            $error = 'Dieser Reset-Token ist nicht mehr gültig.';
        }else{
            $user_id = $rows[0]['user_id'];
        }
        if('' == $error && isset($_POST['pw_hash'])){
            // Setze passwort neu
            $password = \rex_login::passwordHash($_POST['pw_hash'], true);
            $db->setTable('rex_user');
            $db->setWhere(array('id' => $user_id));
            $db->setValue('password', $password);
            $db->update();
            
            // Lösche tokens
            $db = \rex_sql::factory();
            $db->setTable(\rex::getTable('user_passwordreset'));
            $db->setWhere( array( 'user_id' => $user_id));
            $db->delete();
            $success = 'Passwort wurde gesetzt';

        }
        return $render_service->render(
            'views/reset.php', array(
                'error' => $error,
                'success' => $success,
                'token' => $token,
            ));
    }
}
