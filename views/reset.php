<form class="has-handler"
      method="POST"
      data-handler="submit:BePassHandler:showReset"
      data-token="<?php echo $token; ?>"
>
    <section class="rex-page-section">
        <div class="panel panel-default">
            <header class="panel-heading">
                <div class="panel-title">Neues Passwort eingeben</div>
            </header>
            <div class="panel-body">
                <?php if ('' != $error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php elseif ('' != $success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="be_password_password_input">Neues Passwort</label>
                        <input id="be_password_password_input" type="password" name="password" class="form-control" required/>
                    </div>
                <?php endif; ?>
            </div>
            <footer class="panel-footer">
                <div class="rex-form-panel-footer">
                    <div class="btn-toolbar">
                        <?php if ('' != $error): ?>
                        <?php elseif ('' != $success): ?>
                            <a class="btn btn-primary" href="index.php">Weiter</a>
                        <?php else: ?>
                            <div class="be_password_submit_link">
                                <a class="" href="">Abbrechen</a>
                            </div>
                            <button type="submit" class="btn btn-primary">Senden</button>
                        <?php endif; ?>
                    </div>
                </div>
            </footer>
        </div>
    </section>
</form>
