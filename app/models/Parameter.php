<?php



class Parameter
{
    private string $m_variableName;
    private ActiveDataPointRecord $m_activeDataPoint;
    private ?JsonSerializable $m_testValue;
    
    
    public function __construct(string $variableName, ActiveDataPointRecord $dataPoint, ?JsonSerializable $testValue)
    {
        $this->m_variableName = $variableName;
        $this->m_activeDataPoint = $dataPoint;
        $this->m_testValue = $testValue;
    }
    
    
    public function getActiveDataPoint() : ActiveDataPointRecord
    {
        return $this->m_activeDataPoint;
    }
    
    
    public function getVariableName() : string
    {
        return $this->m_variableName;
    }
    
    
    public function getTestValue() : ?JsonSerializable
    {
        return $this->m_testValue;
    }
}