<?php

if ('login' === rex_be_controller::getCurrentPage()) {
    rex_view::addJsFile(rex_url::addonAssets('be_password', 'javascript/be_password.js'));
    rex_view::addCssFile(rex_url::addonAssets('be_password', 'be_password.css'));
}

rex_extension::register('PACKAGES_INCLUDED', static function () {
    $bePwdRequest = rex_request('be_password_request', 'string', '');
    if (
        '' < $bePwdRequest && 2 <= substr_count($bePwdRequest, '/')
    ) {
        $a = explode('/', $bePwdRequest);
        $controller = ucfirst($a[1]);
        $action = $a[2] . 'Action';

        // Validate controller and action names to prevent security issues
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $controller)
            || !preg_match('/^[a-zA-Z][a-zA-Z0-9]*Action$/', $action)) {
            return;
        }

        $arg = $a[3] ?? '';
        $controller_class = 'FriendsOfRedaxo\BePassword\Controller\\' . $controller . 'Controller';

        // Check if class exists before instantiation
        if (class_exists($controller_class)) {
            $c = new $controller_class();
            if (method_exists($c, $action)) {
                $content = call_user_func([$c, $action], $arg);
                echo $content;
                exit;
            }
        }
    }
});
