<?php
// check for new login page that comes with fragment for background image
$isNewLoginPage = file_exists(rex_path::core('fragments/core/login_background.php'));

$email = rex_request('email', 'string');
$content = $successMessage = $errorMessage = $buttons = '';


if (!empty($success)) {
    $successMessage = '<div class="rex-js-login-message">' . rex_view::success($success) . '</div>';
}

if (!empty($error)) {
    $errorMessage = '<div class="rex-js-login-message">' . rex_view::error($error) . '</div>';
}

if (!$isNewLoginPage) {
    echo $successMessage;
    echo $errorMessage;
}
else {
    $content .= $successMessage;
    $content .= $errorMessage;
}


if (empty($success)) {

    $content .= '<fieldset>';

    $formElements = [];

    $inputGroups = [];
    $n = [];
    $n['field'] = '<input class="form-control" type="email" value="' . htmlspecialchars($email) . '" id="be_password_email_input" name="email" />';
    $n['left'] = '<i class="rex-icon rex-icon-envelope"></i>';
    $inputGroups[] = $n;

    $fragment = new rex_fragment();
    $fragment->setVar('elements', $inputGroups, false);
    $inputGroup = $fragment->parse('core/form/input_group.php');

    $n = [];
    $n['label'] = '<label for="be_password_email_input">' . rex_i18n::msg('be_password_email') . ':</label>';
    $n['field'] = $inputGroup;
    $n['class'] = 'rex-form-group-vertical';
    $formElements[] = $n;

    $fragment = new rex_fragment();
    $fragment->setVar('elements', $formElements, false);
    $content .= $fragment->parse('core/form/form.php');

    $content .= '</fieldset>';

    $formElements = [];
    $n = [];
    $n['field'] = '<a class="btn btn-link be_password_cancel" href="' . rex_url::currentBackendPage() . '">' . rex_i18n::msg('be_password_cancel') . '</a>';
    $formElements[] = $n;

    $n = [];
    $n['field'] = '<button class="btn btn-primary btn-block" type="submit">' . rex_i18n::msg('be_password_send') . '</button>';
    $formElements[] = $n;

    $fragment = new rex_fragment();
    $fragment->setVar('elements', $formElements, false);
    $buttons = $fragment->parse('core/form/submit.php');
}

if (!(!empty($success) && !$isNewLoginPage)) {

    $fragment = new rex_fragment();
    $fragment->setVar('title', rex_i18n::msg('be_password_reset_password'), false);
    $fragment->setVar('body', $content, false);
    $fragment->setVar('buttons', $buttons, false);
    $content = $fragment->parse('core/page/section.php');
}

$content = '
    <form class="has-handler" data-handler="submit:BePassHandler:showForm" method="post">
        ' . (isset($csrf_token) ? $csrf_token->getHiddenField() : '') . '
        ' . $content . '
    </form>
    <script>
        (function() {
            var inputField = $("#be_password_email_input");
            var initialValue = inputField.val();
            inputField.val("").val(initialValue).focus(); // focus with trailing caret
        }());
    </script>';

echo $content;
