<?php
spl_autoload_register(function ($class) {
    $prefix = 'FriendsOfRedaxo\BePassword';
    $base_dir = dirname(__FILE__) . '/lib';

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
        isset($_GET['be_password_request'])
        && 2 <= substr_count($_GET['be_password_request'], '/')
    ) {
        $a = explode('/', $_GET['be_password_request']);
        $controller = ucfirst($a[1]);
        $action = $a[2] . 'Action';
        
        // Validate controller and action names to prevent security issues
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $controller) || 
            !preg_match('/^[a-zA-Z][a-zA-Z0-9]*Action$/', $action)) {
            return;
        }
        
        $arg = isset($a[3]) ? $a[3] : '';
        $controller_file = $controller . 'Controller.php';
        $controller_class = 'FriendsOfRedaxo\BePassword\Controller\\' . $controller . 'Controller';
        
        // Check if class exists before instantiation
        if (class_exists($controller_class)) {
            $c = new $controller_class();
            if (method_exists($c, $action)) {
                $content = $c->{$action}($arg);
                echo $content;
                die();
            }
        }
    }
});
