<?php


abstract class AbstractTable extends \Programster\PgsqlObjects\AbstractTable
{
    public function getDb(): \Programster\PgsqlObjects\PgSqlConnection
    {
        return SiteSpecific::getDb();
    }
}