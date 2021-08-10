<?php

/* 
 * This service facilitates interfacing with the datapoints by working with the relevant database objects.
 */


class DataPointService
{
    /**
     * Creates a new data point in the database, taking care of saving it to the history etc.
     * @param string $name - the name for the data point
     * @param JsonSerializable|array $validationSchema - the validation schema
     * @param JsonSerializable|array $sampleValue - the sample value for the data point.
     * @param UserRecord $author - the user who should be marked down as the author in the history.
     */
    public static function createNewDataPoint(
        string $name, 
        string $description,
        ModelRecord $model,
        JsonSerializable | array | stdClass $validationSchema, 
        JsonSerializable | array | stdClass $sampleValue, 
        UserRecord $author
    ) : ActiveDataPointRecord
    {
        $dataPoint = DataPointRecord::createNewFromArray(['model_id' => $model->getId()]);
        $activeDataPoint = ActiveDataPointRecord::createNew($dataPoint, $name, $description, $validationSchema, $sampleValue);
        $dataPointHistory = DataPointHistoryRecord::createFromActiveDataPointRecord($activeDataPoint, $author);
        
        // @todo - support giving record objects to s service that saves in a single transaction.
        $dataPoint->save();
        $activeDataPoint->save();
        $dataPointHistory->save();
        
        return $activeDataPoint;
    }
}
