<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ProcessRecord extends AbstractTableRowObject implements JsonSerializable
{
    protected string $m_modelId;


    protected function getAccessorFunctions() : array
    {
        return array(
            "model_id" => function() { return $this->m_modelId; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "model_id" => function($x) {$this->m_modelId = $x; },
        );
    }
    
    
    /**
     * Creates a new process that belongs to the specified model.
     * @param ModelRecord $model
     * @return ModelRecord
     */
    public static function createNew(ModelRecord $model) : ProcessRecord
    {
        $arrayForm = [
            'model_id' => $model->getId(),
        ];
        
        return ProcessRecord::createNewFromArray($arrayForm);
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
        return ProcessTable::getInstance();
    }
    
    
    public function jsonSerialize(): array 
    {
        return $this->getArrayForm();
    }


    # Accessors
    public function getModelId() : string { return $this->m_modelId; }


    # Setters
    public function setModelId($x) { $this->m_modelId = $x; }

    

}


