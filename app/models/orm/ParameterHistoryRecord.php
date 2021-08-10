<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ParameterHistoryRecord extends AbstractTableRowObject
{
    protected $m_parameterGroupId;
    protected $m_parameterId;
    protected $m_name;
    protected $m_testValue;
    protected $m_dataPointId;


    protected function getAccessorFunctions() : array
    {
        return array(
            "parameter_id" => function() : string { return $this->m_parameterId; },
            "parameter_group_id" => function() { return $this->m_parameterGroupId; },
            "name" => function() { return $this->m_name; },
            "test_value" => function() : ?string { return $this->m_testValue; },
            "data_point_id" => function() { return $this->m_dataPointId; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "parameter_id" => function($x) { $this->m_parameterId = $x; },
            "parameter_group_id" => function($x) { $this->m_parameterGroupId = $x; },
            "name" => function($x) { $this->m_name = $x; },
            "test_value" => function($x) : void { $this->m_testValue = $x; },
            "data_point_id" => function($x) { $this->m_dataPointId = $x; },
        );
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
    public static function createNew(
        ParameterGroupRecord $parameterGroup, 
        string $name, 
        ParameterRecord $parameterRecord,
        ActiveDataPointRecord $dataPoint,
        ?JsonSerializable $testValue
    ) : ParameterHistoryRecord
    {
        $object = new ParameterHistoryRecord();
        $object->id = \Programster\PgsqlObjects\Utils::generateUuid();
        $object->m_name = $name;
        $object->m_parameterId = $parameterRecord->getId();
        $object->m_parameterGroupId = $parameterGroup->getId();
        $object->m_testValue = $testValue;
        $object->m_dataPointId = $dataPoint->getId();
        $object->m_isSavedInDatabase = false;
        return $object;
    }
    
    
    /**
     * Create a new parameter record from a parameter object, and the group that it should belong to.
     * @param Parameter $activeParameterRecord
     * @param ParameterGroupRecord $parameterGroup
     * @return ParameterRecord
     */
    public static function createNewFromActiveParameterRecord(
        ActiveParameterRecord $activeParameterRecord, 
    ) : ParameterHistoryRecord
    {
        $object = new ParameterHistoryRecord();
        $object->id = \Programster\PgsqlObjects\Utils::generateUuid();
        $object->m_name = $activeParameterRecord->getName();
        $object->m_parameterId = $activeParameterRecord->getParameterId();
        $object->m_parameterGroupId = $activeParameterRecord->getParameterGroupId();
        $object->m_testValue = $activeParameterRecord->getTestValue();
        $object->m_dataPointId = $activeParameterRecord->getDataPointId();
        $object->m_isSavedInDatabase = false;
        return $object;
    }
    
    
    public function fetchActiveDataPoint() : ParameterHistoryRecord
    {
        return ActiveDataPointTable::getInstance()->loadForParameter($this);
    }
    
    
    public function fetchDataPoint() : DataPointRecord
    {
        return DataPointTable::getInstance()->load($this->m_dataPointId);
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
        return ParameterHistoryTable::getInstance();
    }


    # Accessors
    public function getParameterId() : string { return $this->m_parameterId; }
    public function getParameterGroupId() { return $this->m_parameterGroupId; }
    public function getName() { return $this->m_name; }
    public function getTestValue() { return $this->m_testValue; }
    public function getDataPointId() { return $this->m_dataPointId; }


    # Setters
    public function setParameterId($x) : void { $this->m_parameterId = $x;}
    public function setParameterGroupId($x) { $this->m_parameterGroupId = $x; }
    public function setName($x) { $this->m_name = $x; }
    public function setTestValue($x) { $this->m_testValue = $x; }
    public function setDataPointId($x) { $this->m_dataPointId = $x; }
}


