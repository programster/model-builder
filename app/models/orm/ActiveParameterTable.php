<?php



class ActiveParameterTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return ActiveParameterRecord::class;
    }


    public function getTableName() : string
    {
        return 'active_parameter';
    }


    public function validateInputs(array $data) : array
    {
        return $data;
    }


    public function getFieldsThatAllowNull() : array
    {
        return array('test_value');
    }


    public function getFieldsThatHaveDefaults() : array
    {
        return array();
    }
    
    
    /**
     * Fetch the parameters for the given process object.
     * @param ProcessHistoryRecord|ActiveProcessRecord $process
     * @return type
     */
    public function loadForProcess(ProcessHistoryRecord | ActiveProcessRecord $process)
    {
        return $this->loadWhereAnd(['parameter_group_id' =>  $process->getParameterGroupId()]);
    }
}
