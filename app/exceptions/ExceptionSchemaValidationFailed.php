<?php

/* 
 * An exception to throw if you are missing a required parameter/argument.
 */

class ExceptionSchemaValidationFailed extends Exception implements JsonSerializable
{
    private string $m_schema;
    private string $m_data;
    private Exception $m_underlyingException;
    
    
    public function __construct(string $schema, string $data, Exception $underlyingException)
    {
        $this->m_schema = $schema;
        $this->m_data = $data;
        $this->m_underlyingException = $underlyingException;
        $this->message = "Schema validation failed.";
    }
    
    
    public function jsonSerialize(): mixed 
    {
        return array(
            'message' => $this->getUnderlyingException()->getMessage(),
            'schema' => $this->getSchema(),
            'invalid_data' => $this->m_data,
        );
    }
    
    
    # Accessors
    public function getSchema() : string { return $this->m_schema; }
    public function getData() : string { return $this->m_data; }
    public function getUnderlyingException() { return $this->m_underlyingException; }
}