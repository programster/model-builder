<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ParameterRecord extends AbstractTableRowObject
{
    protected function getAccessorFunctions() : array
    {
        return array();
    }

    protected function getSetFunctions() : array
    {
        return array();
    }
    
    
    /**
     * Creates a parameter assigned to a parameter group.
     * @param ParameterGroupRecord $parameterGroup - the parameter group the parameter is assigned to. 
     * @param string $parameterName - the name of the variable the data will be assigned to. E.g. variable name in process code.
     * @param ActiveDataPointRecord $dataPoint - the data point that will act as the data source for the parameter variable.
     * @param JsonSerializable|null $testValue - the value to provide this parameter when testing. If null, then will
     * load the test value from the sample value of the data point.
     * @return ParameterRecord
     */
    public static function createNew() : ParameterRecord
    {
        $object = new ParameterRecord();
        $object->id = \Programster\PgsqlObjects\Utils::generateUuid();
        $object->m_isSavedInDatabase = false;
        return $object;
    }
    
    
    public function validateInputs(array $data) : array
    {
        return $data;
    }


    protected function filterInputs(array $data) : array
    {
        return $data;
    }


    public function getTableHandler() : TableInterface
    {
        return ParameterTable::getInstance();
    }
}


