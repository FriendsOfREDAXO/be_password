<?php

use BePassword\Services\FilterService;


$subject = rex_config::get('be_password', 'mail_subject_de');
$body = rex_config::get('be_password', 'mail_body_html_de');
$success = '';

if (isset($_POST['subject'])) {
    $fs = new FilterService();
    $subject = $fs->filterString($_POST['subject']);
    $body = $fs->filterText($_POST['body']);
    rex_config::set('be_password', 'mail_subject_de', $subject);
    rex_config::set('be_password', 'mail_body_html_de', $body);
    $success = $this->i18n('be_password_confirm');
}


// Ausgabe
echo rex_view::title('Password-Reset Mail');
if ('' != $success):?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<form action="" method="post">
    <section class="rex-page-section">
        <div class="panel panel-edit">
            <header class="panel-heading">
                <div class="panel-title">
                   <?php $this->i18n('be_password_mail_legend') ?>
                </div>
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label><?php echo $this->i18n('be_password_mail_subject') ?></label>
                    <input
                            class="form-control"
                            type="text"
                            name="subject"
                            value="<?php echo htmlspecialchars($subject); ?>"
                    />
                </div>
                <div class="form-group">
                    <?php echo $this->i18n('be_password_mail_template') ?>
                    <textarea
                            class="form-control redactorEditor-full"
                            id="redactor_1"
                            name="body"
                    ><?php echo htmlspecialchars($body); ?></textarea>
                    <div><i><?php echo $this->i18n('be_password_mail_placeholder') ?> {{url}}</i></div>
                </div>
                <div class="form-group">
                    <button
                            class="btn btn-default"
                            type="submit"
                    ><?php echo $this->i18n('be_password_save') ?></button>
                    </button>
                    <div>
    </section>
</form>
