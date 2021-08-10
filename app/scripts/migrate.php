<?php

/* 
 * Run the database migrations.
 */

require_once(__DIR__ . '/../bootstrap.php');

$manager = new Programster\PgsqlMigrations\MigrationManager(
    __DIR__ . '/../migrations', 
    SiteSpecific::getDb()->getResource()
);

$manager->migrate();