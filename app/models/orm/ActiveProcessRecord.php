<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ActiveProcessRecord extends AbstractTableRowObject implements JsonSerializable
{
    protected string $m_processId;
    protected string $m_name;
    protected string $m_description;
    protected string $m_code;
    protected string $m_outputDataPointId;
    protected string $m_parameterGroupId;
    protected string $m_hash;


    protected function getAccessorFunctions() : array
    {
        return array(
            "process_id" => function() : string { return $this->m_processId; },
            "name" => function() : string { return $this->m_name; },
            "description" => function() : string { return $this->m_description; },
            "code" => function() : string { return $this->m_code; },
            "output_data_point_id" => function() : string { return $this->m_outputDataPointId; },
            "parameter_group_id" => function() : string { return $this->m_parameterGroupId; },
            "hash" => function() : string { return $this->m_hash; },
        );
    }

    
    protected function getSetFunctions() : array
    {
        return array(
            "process_id" => function($x) { $this->m_processId = $x; },
            "name" => function($x) { $this->m_name = $x; },
            "description" => function($x) { $this->m_description = $x; },
            "code" => function($x) { $this->m_code = $x; },
            "output_data_point_id" => function($x) { $this->m_outputDataPointId = $x; },
            "parameter_group_id" => function($x) { $this->m_parameterGroupId = $x; },
            "hash" => function($x) { $this->m_hash = $x; },
        );
    }
    
    
    /**
     * Create a new active process record object from the provided details.
     * WARNING - this creates the object, it is up to you to save it to the database when convenient.
     * @param ProcessRecord $processRecord
     * @param string $name
     * @param string $description
     * @param string $code
     * @param ActiveDataPointRecord $outputDataPoint
     * @param ParameterGroupRecord $parameterGroup
     * @param UserRecord $author
     * @return ActiveProcessRecord
     */
    public static function createNew(
        ProcessRecord $processRecord, 
        string $name, 
        string $description, 
        string $code, 
        ActiveDataPointRecord $outputDataPoint, 
        ParameterGroupRecord $parameterGroup, 
    ) : ActiveProcessRecord
    {
       $arrayForm = array(
            "process_id" => $processRecord->getId(),
            "name" => $name,
            "description" => $description,
            "code" => $code,
            "output_data_point_id" => $outputDataPoint->getDataPointId(),
            "parameter_group_id" => $parameterGroup->getId(),
        );
            
        $arrayForm['hash'] = SiteSpecific::generateArrayHash($arrayForm); 
        
        return ActiveProcessRecord::createNewFromArray($arrayForm);
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
        return ActiveProcessTable::getInstance();
    }
    
    
    public function fetchLatestHistoryRecord() : ProcessHistoryRecord
    {
        return ProcessHistoryTable::getInstance()->loadLatestRecordForActiveProcess($this);
    }
    
    
    /**
     * Fetch the active output data point for this active process.
     * @return ActiveDataPointRecord
     */
    public function fetchOutputActiveDataPoint() : ActiveDataPointRecord
    {
        $whereParams = ['data_point_id' => $this->getOutputDataPointId()];
        $records = ActiveDataPointTable::getInstance()->loadWhereAnd($whereParams);
        return \Programster\CoreLibs\ArrayLib::getFirstElement($records);
    }
    
    
    public function jsonSerialize(): array 
    {
        return array(
            "process_id" => $this->m_processId,
            "name" => $this->m_name,
            "description" => $this->m_description,
            "code" => $this->m_code,
            "output_data_point_id" => $this->m_outputDataPointId,
            "parameter_group_id" => $this->m_parameterGroupId,
            "hash" => $this->m_hash,
        );
    }


    # Accessors
    public function getProcessId() : string { return $this->m_processId; }
    public function getName() : string { return $this->m_name; }
    public function getDescription() { return $this->m_description; }
    public function getCode() : string { return $this->m_code; }
    public function getOutputDataPointId() { return $this->m_outputDataPointId; }
    public function getParameterGroupId() : string { return $this->m_parameterGroupId; }
    public function getHash() : string { return $this->m_hash; }


    # Setters
    public function setProcessId(string $x) { $this->m_processId = $x; }
    public function setName(string $x) { $this->m_name = $x; }
    public function setDescription(string $x) { $this->m_description = $x; }
    public function setCode(string $x) { $this->m_code = $x; }
    public function setOutputDataPointId(string $x) { $this->m_outputDataPointId = $x; }
    public function setParameterGroupId(string $x) { $this->m_parameterGroupId = $x; }
    public function setHash(string $x) { $this->m_hash = $x; }
}


