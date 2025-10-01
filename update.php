<?php

/** @var rex_addon $this */

// Rename table from user_passwordreset to be_password for version 3.0.0+
if (version_compare($this->getVersion(), '3.0.0', '<')) {
    $oldTableName = rex::getTable('user_passwordreset');
    $newTableName = rex::getTable('be_password');
    
    // Check if old table exists and new table doesn't exist yet
    $sql = rex_sql::factory();
    $oldTableExists = in_array($oldTableName, $sql->getTables(), true);
    $newTableExists = in_array($newTableName, $sql->getTables(), true);
    
    if ($oldTableExists && !$newTableExists) {
        // Rename the table
        rex_sql::factory()->setQuery('RENAME TABLE `' . $oldTableName . '` TO `' . $newTableName . '`');
    }
}
