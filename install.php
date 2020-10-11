<?php
rex_sql_table::get(rex::getTable('user_passwordreset'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('user_id', 'int(10) unsigned', false))
    ->ensureColumn(new rex_sql_column('reset_password_token', 'varchar(255)'))
    ->ensureColumn(new rex_sql_column('reset_password_token_expires', 'datetime'))
    ->ensure();
