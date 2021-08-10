<?php

/* 
 * This service facilitates interfacing with the datapoints by working with the relevant database objects.
 */


class ProcessService
{

    /**
     * Create a brand new service in the database, with all the things that relate to it.
     * @param string $name - the name for the new process
     * @param string $description - the description of what hte process does
     * @param ModelRecord $model - the model this process belongs to.
     * @param string $code - the code that the process executes
     * @param UserRecord $author - the person who created this new process
     * @param ActiveDataPointRecord $outputDataPoint - the data point that the process outputs to.
     * @param Parameter $parameters - the parameters the process takes.
     * @return ActiveProcessRecord - the newly created active process.
     * @throws Exception
     */
    public static function createNewProcess(
        string $name,
        string $description,
        ModelRecord $model,
        string $code,
        UserRecord $author,
        ActiveDataPointRecord $outputDataPoint,
        Parameter ...$parameters
    ) : ActiveProcessRecord
    {
        if (count($parameters) < 0)
        {
            throw new Exception("At least one parameter needs to be provided when creating a new process.");
        }
        
        $processRecord = ProcessRecord::createNew($model);
        $parameterGroup = ParameterGroupRecord::createNew($author);
        $parameterGroup->save();
        
        $parameterRecords = [];
        $activeParameterRecords = [];
        $parameterHistoryRecords = [];
        
        
        foreach ($parameters as $parameter)
        {
            /* @var $parameter Parameter */
            $newParameterRecord = ParameterRecord::createNewFromArray([]);
            $parameterRecords[] = $newParameterRecord;
            $newParameterRecord->save();
            $activeParameterRecord = ActiveParameterRecord::createNewFromParameter($parameter, $newParameterRecord, $parameterGroup);
            $activeParameterRecord->save();
            $activeParameterRecords[] = $activeParameterRecord;
            $parameterHistoryRecord = ParameterHistoryRecord::createNewFromActiveParameterRecord($activeParameterRecord);
            $parameterHistoryRecord->save();
            $parameterHistoryRecords[] = $parameterHistoryRecord;
        }
        
        $activeProcess = ActiveProcessRecord::createNew(
            $processRecord, 
            $name, 
            $description, 
            $code, 
            $outputDataPoint, 
            $parameterGroup, 
            $author
        );
        
        $processHistory = ProcessHistoryRecord::createFromActiveProcess($activeProcess, $author);
        
        # @TODO - mass save objects in one transaction
        $parameterGroup->save();
        $outputDataPoint->save();
        $processRecord->save();
        

        foreach ($parameterRecords as $record)
        {
            $record->save();
        }
        
        foreach ($activeParameterRecords as $record)
        {
            $record->save();
        }
        
        foreach ($parameterHistoryRecords as $record)
        {
            $record->save();
        }
        
        $processHistory->save();
        $activeProcess->save();
        
        return $activeProcess;
    }
}
