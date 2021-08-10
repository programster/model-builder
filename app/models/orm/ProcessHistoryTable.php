<?php



class ProcessHistoryTable extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return __NAMESPACE__ . '\ProcessHistoryRecord';
    }


    public function getTableName() : string
    {
        return 'process_history';
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
     * Fetches the latest process history record for the given active process.
     * @param ActiveProcessRecord $processRecord
     * @return ProcessHistoryRecord
     */
    public function loadLatestRecordForActiveProcess(ActiveProcessRecord $processRecord) : ProcessHistoryRecord
    {
        $escapedProcessId = $this->getDb()->escapeValue($processRecord->getProcessId());
        $query = "SELECT * FROM {$this->getEscapedTableName()} WHERE process_id={$escapedProcessId} ORDER BY created_at DESC limit 1";
        $result = $this->getDb()->query($query);
        $objects = $this->convertPgResultToObjects($result);
        return \Programster\CoreLibs\ArrayLib::getFirstElement($objects);
    }
}
