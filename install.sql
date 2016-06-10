DROP TABLE IF EXISTS `%TABLE_PREFIX%user_passwordreset`;
CREATE TABLE `rex_user_passwordreset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `reset_password_token` varchar(255) DEFAULT '',
  `reset_password_token_expires` datetime, 
  PRIMARY KEY (`id`)
);
