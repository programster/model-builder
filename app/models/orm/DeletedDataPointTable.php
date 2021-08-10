<?php



class DeletedDataPointTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return __NAMESPACE__ . '\DeletedDataPointRecord';
    }


    public function getTableName() : string
    {
        return 'deleted_data_point';
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
