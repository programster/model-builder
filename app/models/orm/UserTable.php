<?php



class UserTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return __NAMESPACE__ . '\UserRecord';
    }


    public function getTableName() : string
    {
        return 'user';
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
