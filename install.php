<?php
if(null === rex_config::get('be_password', 'mail_subject_de')){
    rex_config::set('be_password', 'mail_subject_de', $this->i18n('be_password_default_subject'));
}

if(null === rex_config::get('be_password', 'mail_body_html_de')){
    rex_config::set('be_password', 'mail_body_html_de', '<p>'.$this->i18n('be_password_default_instruction') .'<a href="{{url}}" title="'.$this->i18n('be_password_default_link').'">'.$this->i18n('be_password_default_link') .'</a></p>');
}