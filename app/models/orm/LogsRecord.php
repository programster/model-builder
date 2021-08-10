<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class LogsRecord extends AbstractTableRowObject
{
    protected $m_priority;
    protected $m_title;
    protected $m_context;
    protected $m_createdAt;


    protected function getAccessorFunctions() : array
    {
        return array(
            "priority" => function() { return $this->m_priority; },
            "title" => function() { return $this->m_title; },
            "context" => function() { return $this->m_context; },
            "created_at" => function() { return $this->m_createdAt; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "priority" => function($x) { $this->m_priority = $x; },
            "title" => function($x) { $this->m_title = $x; },
            "context" => function($x) { $this->m_context = $x; },
            "created_at" => function($x) { $this->m_createdAt = $x; },
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
        return LogsTable::getInstance();
    }


    # Accessors
    public function getPriority() { return $this->m_priority; }
    public function getTitle() { return $this->m_title; }
    public function getContext() { return $this->m_context; }
    public function getCreatedAt() { return $this->m_createdAt; }


    # Setters
    public function setPriority($x) { $this->m_priority = $x; }
    public function setTitle($x) { $this->m_title = $x; }
    public function setContext($x) { $this->m_context = $x; }
    public function setCreatedAt($x) { $this->m_createdAt = $x; }
}


