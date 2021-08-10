<?php



class ActiveDataPointTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return __NAMESPACE__ . '\ActiveDataPointRecord';
    }


    public function getTableName() : string
    {
        return 'active_data_point';
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
     * FEtch the active data points for the specified model.
     * @param ModelRecord $model
     * @return array
     */
    public function loadForModel(ModelRecord $model) : array
    {
        $escapedModelId = $this->getDb()->escapeValue($model->getId());
        $escapedDataPointTableName = DataPointTable::getInstance()->getEscapedTableName();
        
        $query = 
            "SELECT * FROM {$this->getEscapedTableName()} WHERE data_point_id IN (
                SELECT id from {$escapedDataPointTableName} WHERE model_id={$escapedModelId}
            )";
                
        $result = $this->getDb()->query($query);
        return $this->convertPgResultToObjects($result);
    }
    
    
    public function loadForParameter(ActiveParameterRecord $parameterRecord) : ActiveDataPointRecord
    {        
        $records = $this->loadWhereAnd(['data_point_id' => $parameterRecord->getDataPointId()]);
        return \Programster\CoreLibs\ArrayLib::getFirstElement($records);
    }
}
