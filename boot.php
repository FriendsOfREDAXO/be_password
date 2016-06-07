<?php 
spl_autoload_register(function ($class) {
    $prefix = 'BePassword';
    $base_dir = dirname(__FILE__).'/src';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});
if('login' == rex_be_controller::getCurrentPage()){
    rex_view::addJsFile(rex_url::addonAssets('be_password', 'javascript/be_password.js'));
}
if(
    isset($_GET['be_password_request'])
    && 2 <= substr_count($_GET['be_password_request'], '/')
){
    $a = explode('/', $_GET['be_password_request']);
    $controller = ucfirst($a[1]);
    $action = $a[2].'Action';
    if(isset($a[3])){
        $arg = $a[3];
    }else{
        $arg = '';
    }
    $controller_file = ucfirst($a[1]).'Controller.php';
    $controller_class = 'BePassword\Controller\\'.ucfirst($a[1]).'Controller';
    $c = new $controller_class();
    $content = $c->{$action}($arg);
    echo $content;
    die();
    // Check if there is an account
    $db = rex_sql::factory();
    $rows = $db->getArray("SELECT * FROM rex_user WHERE email=?", array(
        $_POST['email'],
    ));
    if(!is_array($rows) || 1 != count($rows)){
        $content = file_get_contents(__DIR__.'/assets/views/request_error.html');
        echo $content;
        die();
    }
    require __DIR__.'/src/Services/RandomService.php';
    $rs = new RandomService();
    $user_id = $rows[0]['id'];
    $random = $rs->createToken(8);

    $res = @mail($rows[0]['email'], 'Neues Passwort', $random);
    if(false === $res){
        $content = '<div class="alert alert-danger">Mail could not be sent</div>';
    }else{
        $sql = "UPDATE rex_user SET password='".crypt($random)."' WHERE
            id=".$user_id;
        $db->prepareQuery($sql);
        $db->execute();
        $content = file_get_contents(__DIR__.'/assets/views/request_sent.html');
    }
    echo $content;
    die();
}


