<form class="has-handler" 
    method="POST"
    data-handler="submit:BePassHandler:showReset"
    data-token="<?php echo $token;?>"
    >
<section class="rex-page-section">
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <?php if ('' != $error):?>
                    <div class="alert alert-danger"><?php echo $error;?></div>
                <?php elseif ('' != $success):?>
                    <div class="alert alert-success"><?php echo $success;?></div>
                    <div><a class="btn btn-success" href="">Weiter</a></div>
                <?php else:?>
                    <div class="form-group"> 
                        <label>Neues Passwort</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control"
                            placeholder="Neues Passwort" />
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
