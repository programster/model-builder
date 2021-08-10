<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ActiveDataPointRecord extends AbstractTableRowObject implements JsonSerializable
{
    protected string $m_dataPointId;
    protected string $m_name;
    protected string $m_description;
    protected string $m_validationSchema;
    protected string $m_sampleValue;
    protected string $m_createdAt;
    protected string $m_hash;


    protected function getAccessorFunctions() : array
    {
        return array(
            "data_point_id" => function() : string { return $this->m_dataPointId; },
            "name" => function() : string { return $this->m_name; },
            "description" => function() : string { return $this->m_description; },
            "validation_schema" => function() : string { return $this->m_validationSchema; },
            "sample_value" => function() : string { return $this->m_sampleValue; },
            "created_at" => function() : string { return $this->m_createdAt; },
            "hash" => function() : string { return $this->m_hash; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "data_point_id" => function($x) : void { $this->m_dataPointId = $x; },
            "name" => function($x) : void { $this->m_name = $x; },
            "description" => function($x) : void { $this->m_description = $x; },
            "validation_schema" => function($x) : void { $this->m_validationSchema = $x; },
            "sample_value" => function($x) : void { $this->m_sampleValue = $x; },
            "created_at" => function($x) : void { $this->m_createdAt = $x; },
            "hash" => function($x) : void { $this->m_hash = $x; },
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
        return ActiveDataPointTable::getInstance();
    }
    
    
    /**
     * Create a new active data point record.
     * @param DataPointRecord $dataPoint
     * @param string $name
     * @param JsonSerializable|array|stdClass $validationSchema
     * @param JsonSerializable|array|stdClass $sampleValue
     * @return ActiveDataPointRecord
     */
    public static function createNew(
        DataPointRecord $dataPoint, 
        string $name, 
        string $description,
        JsonSerializable | array | stdClass $validationSchema, 
        JsonSerializable | array | stdClass $sampleValue
    ) : ActiveDataPointRecord
    {
        $rowForm = array(
            'data_point_id' => $dataPoint->getId(),
            'name' => $name, 
            'description' => $description,
            'validation_schema' => json_encode($validationSchema),
            'sample_value' => json_encode($sampleValue),
        );
        
        $hash = SiteSpecific::generateArrayHash($rowForm);
        
        $rowForm['hash'] = $hash;
        $rowForm['created_at'] = time();
        $object = ActiveDataPointRecord::createNewFromArray($rowForm);
        return $object;
    }
    
    
    public function fetchLastUpdatedAt()
    {
        return $this->m_createdAt;
    }
    
    
    public function getValidationSchemaArray() : array
    {
        return json_decode($this->m_validationSchema, true);
    }
    
    
    public function getSampleValueArray() : array
    {
        return json_decode($this->m_sampleValue, true);
    }
    
    
    public function jsonSerialize(): mixed 
    {
        return $this->getArrayForm();
    }


    # Accessors
    public function getDataPointId() : string { return $this->m_dataPointId; }
    public function getName() : string { return $this->m_name; }
    public function getDescription() : string { return $this->m_description; }
    public function getValidationSchema() : string { return $this->m_validationSchema; }
    public function getSampleValue() : string { return $this->m_sampleValue; }
    public function getCreatedAt() : int { return $this->m_createdAt; }
    public function getHash() : string { return $this->m_hash; }


    # Setters
    public function setDataPointId($x) : void { $this->m_dataPointId = $x; }
    public function setName($x) : void { $this->m_name = $x; }
    public function setDescription($x) : void { $this->m_description = $x; }
    public function setValidationSchema($x) : void { $this->m_validationSchema = $x; }
    public function setSampleValue($x) : void { $this->m_sampleValue = $x; }
    public function setCreatedAt($x) : void { $this->m_createdAt = $x; }
    public function setHash($x) : void { $this->m_hash = $x; }

    

}


