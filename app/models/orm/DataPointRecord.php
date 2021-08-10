<?php


use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class DataPointRecord extends AbstractTableRowObject
{
    protected string $m_modelId;
    
    
    protected function getAccessorFunctions() : array
    {
        return array(
            'model_id' => function() : string { return $this->m_modelId; }
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            'model_id' => function($x) : void { $this->m_modelId = $x; }
        );
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
        return DataPointTable::getInstance();
    }
    
    
    # Accessors
    public function getModelId() : string { return $this->m_modelId; }
}


