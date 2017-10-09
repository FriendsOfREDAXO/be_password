<form class="has-handler"
      method="POST"
      data-handler="submit:BePassHandler:showForm"
>
    <section class="rex-page-section">
        <div class="panel panel-default">
            <header class="panel-heading"><div class="panel-title">Passwort vergessen</div></header>
            <div class="panel-body">
                <?php if ('' != $error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php elseif ('' != $success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="be_password_email_input">E-Mail-Adresse</label>
                        <input class="form-control" id="be_password_email_input" type="email" name="email" required/>
                    </div>
                <?php endif; ?>
            </div>
            <footer class="panel-footer">
                <div class="rex-form-panel-footer">
                    <div class="btn-toolbar">
                        <?php if ('' != $error): ?>
                            <a class="btn btn-primary" href="">Weiter</a>
                        <?php elseif ('' != $success): ?>
                            <a class="btn btn-primary" href="">Weiter</a>
                        <?php else: ?>
                            <div class="be_password_submit_link"><a class="" href="">Abbrechen</a></div>
                            <button type="submit" class="btn btn-primary">Senden</button>
                        <?php endif; ?>
                    </div>
                </div>
            </footer>
        </div>
    </section>
</form>
