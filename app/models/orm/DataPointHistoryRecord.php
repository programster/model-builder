<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class DataPointHistoryRecord extends AbstractTableRowObject
{
    protected $m_dataPointId;
    protected $m_name;
    protected string $m_description;
    protected $m_validationSchema;
    protected $m_sampleValue;
    protected $m_hash;
    protected $m_authorId;


    protected function getAccessorFunctions() : array
    {
        return array(
            "data_point_id" => function() { return $this->m_dataPointId; },
            "name" => function() { return $this->m_name; },
            "description" => function() : string { return $this->m_description; },
            "validation_schema" => function() { return $this->m_validationSchema; },
            "sample_value" => function() { return $this->m_sampleValue; },
            "hash" => function() { return $this->m_hash; },
            "author_id" => function() { return $this->m_authorId; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "data_point_id" => function($x) { $this->m_dataPointId = $x; },
            "name" => function($x) { $this->m_name = $x; },
            "description" => function($x) : void { $this->m_description = $x; },
            "validation_schema" => function($x) { $this->m_validationSchema = $x; },
            "sample_value" => function($x) { $this->m_sampleValue = $x; },
            "hash" => function($x) { $this->m_hash = $x; },
            "author_id" => function($x) { $this->m_authorId = $x; },
        );
    }
    
    
    /**
     * Create a new history item for the data point history table from an active data point record.
     * You should use this function if you created a new active data point record, and need to record it in the history.
     * Warning - this creates the object. It is up to you to save it when is appropriate.
     * @param ActiveDataPointRecord $record
     * @param UserRecord $author
     * @return DataPointHistoryRecord - the new data point history record.
     */
    public static function createFromActiveDataPointRecord(
        ActiveDataPointRecord $record,
        UserRecord $author
    ) : DataPointHistoryRecord
    {
        $rowForm = array(
            'data_point_id' => $record->getDataPointId(),
            'name' => $record->getName(), 
            'description' => $record->getDescription(),
            'validation_schema' => $record->getValidationSchema(),
            'sample_value' => $record->getSampleValue(),
            'hash' => $record->getHash(),
            'author_id' => $author->getId()
        );
        
        $object = DataPointHistoryRecord::createNewFromArray($rowForm);
        return $object;
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
        return DataPointHistoryTable::getInstance();
    }


    # Accessors
    public function getDataPointId() { return $this->m_dataPointId; }
    public function getName() { return $this->m_name; }
    public function getDescription() { return $this->m_description; }
    public function getValidationSchema() { return $this->m_validationSchema; }
    public function getSampleValue() { return $this->m_sampleValue; }
    public function getHash() { return $this->m_hash; }
    public function getAuthorId() { return $this->m_authorId; }


    # Setters
    public function setDataPointId($x) { $this->m_dataPointId = $x; }
    public function setName($x) { $this->m_name = $x; }
    public function setDescription($x) { $this->m_description = $x; }
    public function setValidationSchema($x) { $this->m_validationSchema = $x; }
    public function setSampleValue($x) { $this->m_sampleValue = $x; }
    public function setHash($x) { $this->m_hash = $x; }
    public function setAuthorId($x) { $this->m_authorId = $x; }
}


