<form class="has-handler" 
    method="POST"
    data-handler="submit:BePassHandler:showForm"
    >
<section class="rex-page-section">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>Passwort vergessen</h4>
            <fieldset>
                    <?php if ('' != $error):?>
                        <div class="alert alert-danger"><?php echo $error;?></div>
                        <div><a class="btn btn-success" href="">Weiter</a></div>
                    <?php elseif ('' != $success):?>
                        <div class="alert alert-success"><?php echo $success;?></div>
                        <div><a class="btn btn-success" href="">Weiter</a></div>
                    <?php else:?>
                        <div class="form-group">
                        <label>E-Mail-Adresse</label>
                        <input class="form-control" type="email" name="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-success"
                            >Senden</button>
                        </div>
                        <div class="form-group">
                            <a class="" href="">Abbrechen</a>
                        </div>
                    <?php endif;?>
                    
            </fieldset>
        </div>
    </div>
</section>
</form>
