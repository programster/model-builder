<?php

/* 
 * An exception to throw if you are missing a required parameter/argument.
 */

class ExceptionMissingParameter extends Exception
{
    private string $m_parameterName; 
    
    
    public function __construct(string $parameterName, int $code = 0, Throwable|null $previous = null)
    {
        $this->m_parameterName = $parameterName;
        parent::__construct("Missing required parameter: {$parameterName}", $code, $previous);
    }
}