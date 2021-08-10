<?php



class ActiveProcessTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return __NAMESPACE__ . '\ActiveProcessRecord';
    }


    public function getTableName() : string
    {
        return 'active_process';
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
    
    
    /**
     * Fetch the active processes that belong to the specified model.
     * @param ModelRecord $model
     * @return array
     */
    public function loadForModel(ModelRecord $model) : array
    {
        $escapedModelId = $this->getDb()->escapeValue($model->getId());
        $escapedProcessTableName = ProcessTable::getInstance()->getEscapedTableName();
        
        $query = 
            "SELECT * FROM {$this->getEscapedTableName()} WHERE process_id IN (
                SELECT id from {$escapedProcessTableName} WHERE model_id={$escapedModelId}
            )";
                
        $result = $this->getDb()->query($query);
        return $this->convertPgResultToObjects($result);
    }
    
    
    /**
     * Fetch the active process record for the given process.
     * @param ProcessRecord $process
     * @return ActiveProcessRecord
     */
    public function loadForProcess(ProcessRecord $process) : ActiveProcessRecord
    {
        $records = $this->loadWhereAnd(['process_id' => $process->getId()]);
        return \Programster\CoreLibs\ArrayLib::getFirstElement($records);
    }
}
