<?php
rex_sql_table::get(rex::getTable('be_password_reset'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('user_id', 'int(10) unsigned', false))
    ->ensureColumn(new rex_sql_column('token', 'varchar(255)'))
    ->ensureColumn(new rex_sql_column('token_expires', 'datetime'))
    ->ensure();
