<?php

/*
 * An exception to throw if there were any warnings/errors in evaluating the code that was sent up.
 */

class ExceptionEvalError extends Exception implements JsonSerializable
{
    private string $m_message;
    private $m_severity;
    private string $m_file;
    private $m_line;
    
    
    public function __construct(string $message, $severity, $file, $line)
    {
        $this->m_message = $message;
        $this->m_severity = $severity;
        $this->m_file = $file;
        $this->m_line = $line;
    }

    
    public function jsonSerialize(): mixed 
    {
        return array(
            'message' => $this->m_message,
            'severity' => $this->m_severity,
            'file' => $this->m_file,
            'line' => $this->m_line,
        );
    }
}