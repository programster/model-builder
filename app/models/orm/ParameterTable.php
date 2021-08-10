<?php



class ParameterTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return ParameterRecord::class;
    }


    public function getTableName() : string
    {
        return 'parameter';
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
