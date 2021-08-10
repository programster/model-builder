<?php


use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ModelRecord extends AbstractTableRowObject
{
    protected string $m_name;
    protected string $m_description;


    protected function getAccessorFunctions() : array
    {
        return array(
            "name" => function() : string { return $this->m_name; },
            "description" => function() : string { return $this->m_description; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "name" => function($x) { $this->m_name = $x; },
            "description" => function($x) { $this->m_description = $x; },
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
        return ModelTable::getInstance();
    }


    /**
     * Creates a new model object.
     * @param string $name
     * @param string $description
     * @return Model
     */
    public static function createNew(string $name, string $description) : ModelRecord
    {        
        $model = new ModelRecord();
        $model->m_name = $name;
        $model->m_description = $description;
        $model->m_isSavedInDatabase = false;
        return $model;
    }
    
    
    /**
     * Gets the unix timestamp of when this model was last updated.
     */
    public function fetchLastUpdatedAt() : ?int
    {
        $db = $this->getTableHandler()->getDb();
        $escapedModelId = $db->escapeValue($this->m_id);
        
        $query = 
            "SELECT MAX(created_at) as max_created_at"
            . " FROM process_history"
            . " WHERE process_id IN (SELECT id FROM process WHERE model_id = {$escapedModelId})";
                
        $result = $db->query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to select max created at for model");
        }
        
        $row = pg_fetch_assoc($result);
        return $row['max_created_at'];
    }
    
    
    public function fetchActiveProcesses() : array
    {
        return ActiveProcessTable::getInstance()->loadForModel($this);
    }
    
    public function fetchActiveDataPoints() : array
    {
        return ActiveDataPointTable::getInstance()->loadForModel($this);
    }


    # Accessors
    public function getName() : string { return $this->m_name; }
    public function getDescription() : string { return $this->m_description; }


    # Setters
    public function setName($x) : void { $this->m_name = $x; }
    public function setDescription($x) : void { $this->m_description = $x; }
}


