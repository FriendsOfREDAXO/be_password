<?php
if(null === rex_config::get('be_password', 'mail_subject_de')){
    rex_config::set('be_password', 'mail_subject_de', 'Passwort-Reset');
}

if(null === rex_config::get('be_password', 'mail_body_html_de')){
    rex_config::set('be_password', 'mail_body_html_de', '<p>Um das Passwort neu zu setzen, klicken Sie auf diesen Link: <a href="{{url}}">Passwort neu erstellen</a></p>');
}
