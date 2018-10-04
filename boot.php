<?php 
if ('login' == rex_be_controller::getCurrentPage()) {
    rex_view::addJsFile(rex_url::addonAssets('be_password', 'javascript/be_password.js'));
    rex_view::addCssFile(rex_url::addonAssets('be_password', 'be_password.css'));
    if (
        isset($_GET['be_password_request'])
        && 2 <= substr_count($_GET['be_password_request'], '/')
    ) {
        $a = explode('/', $_GET['be_password_request']);
        $controller = ucfirst($a[1]);
        $action = $a[2] . 'Action';
        if (isset($a[3])) {
            $arg = $a[3];
        } else {
            $arg = '';
        }
        $controller_file = ucfirst($a[1]) . 'Controller.php';
        $controller_class = 'BePassword\Controller\\' . ucfirst($a[1]) . 'Controller';
        $c = new $controller_class();
        $content = $c->{$action}($arg);
        echo $content;
        die();
    }
}