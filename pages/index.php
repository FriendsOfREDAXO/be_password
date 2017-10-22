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
    $success = 'Änderungen gespeichert';
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
                    Email für Passwort-Reset anpassen
                </div>
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label>Mail-Betreff</label>
                    <input
                            class="form-control"
                            type="text"
                            name="subject"
                            value="<?php echo htmlspecialchars($subject); ?>"
                    />
                </div>
                <div class="form-group">
                    <label>Mail-Inhalt</label>
                    <textarea
                            class="form-control redactorEditor-full"
                            id="redactor_1"
                            name="body"
                    ><?php echo htmlspecialchars($body); ?></textarea>
                    <div><i>Platzhalter für URL: {{url}}</i></div>
                </div>
                <div class="form-group">
                    <button
                            class="btn btn-default"
                            type="submit"
                    >Speichern
                    </button>
                    <div>
    </section>
</form>
