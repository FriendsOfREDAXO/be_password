<?php
if ($success != '') {
    echo '<div class="rex-js-login-message">' . rex_view::success($success) . "</div>";
}

if ($error != '') {
    echo '<div class="rex-js-login-message">' . rex_view::error($error) . "</div>";
}

if ($success == '') {

    $email = rex_request('email', 'string');

    $content = '';
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
    $n['label'] = '<label for="be_password_email_input">E-Mailadresse:</label>';
    $n['field'] = $inputGroup;
    $n['class'] = 'rex-form-group-vertical';
    $formElements[] = $n;

    $fragment = new rex_fragment();
    $fragment->setVar('elements', $formElements, false);
    $content .= $fragment->parse('core/form/form.php');

    $content .= '</fieldset>';

    $formElements = [];
    $n = [];
    $n['field'] = '<button class="btn btn-primary" type="submit">Senden</button>';
    $formElements[] = $n;

    $n = [];
    $n['field'] = '<a class="btn btn-link be_password_cancel" href="' . rex_url::currentBackendPage() . '">Abbrechen</a>';
    $formElements[] = $n;

    $fragment = new rex_fragment();
    $fragment->setVar('elements', $formElements, false);
    $buttons = $fragment->parse('core/form/submit.php');

    $fragment = new rex_fragment();
    $fragment->setVar('title', 'Passwort zurücksetzen', false);
    $fragment->setVar('body', $content, false);
    $fragment->setVar('buttons', $buttons, false);
    $content = $fragment->parse('core/page/section.php');

    $content = '
        <form class="has-handler" data-handler="submit:BePassHandler:showForm" method="post">
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
}
