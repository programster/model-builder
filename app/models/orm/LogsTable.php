<?php



class LogsTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return __NAMESPACE__ . '\LogsRecord';
    }


    public function getTableName() : string
    {
        return 'logs';
    }


    public function validateInputs(array $data) : array
    {
        return $data;
    }


    public function getFieldsThatAllowNull() : array
    {
        return array();
    }


    public function getFieldsThatHaveDefaults() : array
    {
        return array();
    }
}
