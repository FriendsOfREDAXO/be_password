<?php
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
        $action = $a[2] . 'Action';
        if (isset($a[3])) {
            $arg = $a[3];
        } else {
            $arg = '';
        }
        $c = new FriendsOfRedaxo\BePassword\BePassword;
        $content = $c->{$action}($arg);
        echo $content;
        die();
    }
});
