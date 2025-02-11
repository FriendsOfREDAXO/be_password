<?php
spl_autoload_register(function ($class) {
    $prefix = 'BePassword';
    $base_dir = dirname(__FILE__) . '/src';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

if ('login' == rex_be_controller::getCurrentPage()) {
    rex_view::addJsFile(rex_url::addonAssets('be_password', 'javascript/be_password.js'));
    rex_view::addCssFile(rex_url::addonAssets('be_password', 'be_password.css'));
}

rex_extension::register('PACKAGES_INCLUDED', function () {
    // mailer-klasse selbst laden... warum auch immer
    require_once __DIR__ . '/../phpmailer/lib/mailer.php';
    if (
        rex_get('be_password_request', 'string', '') !== ''
        && 2 <= substr_count(rex_get('be_password_request', 'string', ''), '/')
    ) {
        $a = explode('/', rex_get('be_password_request', 'string', ''));
        $controller = ucfirst($a[1]);
        $action = $a[2] . 'Action';
        if (isset($a[3])) {
            $arg = $a[3];
        } else {
            $arg = '';
        }
        $controller_file = ucfirst($a[1]) . 'Controller.php';
        $controller_class = 'FriendsOfRedaxo\BePassword\Controller\\' . ucfirst($a[1]) . 'Controller';
        $c = new $controller_class();
        $content = $c->{$action}($arg);
        echo $content;
        die();
    }
});
