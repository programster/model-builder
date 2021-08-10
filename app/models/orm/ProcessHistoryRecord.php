<?php


use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ProcessHistoryRecord extends AbstractTableRowObject implements JsonSerializable
{
    protected $m_processId;
    protected $m_name;
    protected $m_description;
    protected $m_code;
    protected $m_outputDataPointId;
    protected $m_parameterGroupId;
    protected $m_hash;
    protected $m_authorId;
    protected $m_createdAt;


    protected function getAccessorFunctions() : array
    {
        return array(
            "process_id" => function() { return $this->m_processId; },
            "name" => function() { return $this->m_name; },
            "description" => function() { return $this->m_description; },
            "code" => function() { return $this->m_code; },
            "output_data_point_id" => function() { return $this->m_outputDataPointId; },
            "parameter_group_id" => function() { return $this->m_parameterGroupId; },
            "hash" => function() { return $this->m_hash; },
            "author_id" => function() { return $this->m_authorId; },
            "created_at" => function() { return $this->m_createdAt; },
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
            "author_id" => function($x) { $this->m_authorId = $x; },
            "created_at" => function($x) { $this->m_createdAt = $x; },
        );
    }
    
    
    /**
     * Create a new history line item for a process, using the process details, and the author of it.
     * @param ActiveProcessRecord $activeProcess - the new active process.
     * @param UserRecord $author - the user who apparently made this new version
     * @return ProcessHistoryRecord - the generated history record.
     */
    public static function createFromActiveProcess(
        ActiveProcessRecord $activeProcess, 
        UserRecord $author
    ) : ProcessHistoryRecord
    {
        $arrayForm = array(
            "process_id" => $activeProcess->getProcessId(),
            "name" => $activeProcess->getName(),
            "description" => $activeProcess->getDescription(),
            "code" => $activeProcess->getCode(),
            "output_data_point_id" => $activeProcess->getOutputDataPointId(),
            "parameter_group_id" => $activeProcess->getParameterGroupId(),
            'hash' => $activeProcess->getHash(),
            'author_id' => $author->getId(),
            'created_at' => time(),
        );
        
        return ProcessHistoryRecord::createNewFromArray($arrayForm);
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
        return ProcessHistoryTable::getInstance();
    }
    
    
    public function jsonSerialize(): array 
    {
        return $this->getArrayForm();
    }


    # Accessors
    public function getProcessId() { return $this->m_processId; }
    public function getName() { return $this->m_name; }
    public function getDescription() { return $this->m_description; }
    public function getCode() { return $this->m_code; }
    public function getOutputDataPointId() { return $this->m_outputDataPointId; }
    public function getParameterGroupId() { return $this->m_parameterGroupId; }
    public function getHash() { return $this->m_hash; }
    public function getAuthorId() { return $this->m_authorId; }
    public function getCreatedAt() { return $this->m_createdAt; }


    # Setters
    public function setProcessId($x) { $this->m_processId = $x; }
    public function setName($x) { $this->m_name = $x; }
    public function setDescription($x) { $this->m_description = $x; }
    public function setCode($x) { $this->m_code = $x; }
    public function setOutputDataPointId($x) { $this->m_outputDataPointId = $x; }
    public function setParameterGroupId($x) { $this->m_parameterGroupId = $x; }
    public function setHash($x) { $this->m_hash = $x; }
    public function setAuthorId($x) { $this->m_authorId = $x; }
    public function setCreatedAt($x) { $this->m_createdAt = $x; }
}


