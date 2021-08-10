<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class DeletedProcessRecord extends AbstractTableRowObject
{
    protected $m_processId;
    protected $m_authorId;
    protected $m_deletedAt;


    protected function getAccessorFunctions() : array
    {
        return array(
            "process_id" => function() { return $this->m_processId; },
            "author_id" => function() { return $this->m_authorId; },
            "deleted_at" => function() { return $this->m_deletedAt; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "process_id" => function($x) { $this->m_processId = $x; },
            "author_id" => function($x) { $this->m_authorId = $x; },
            "deleted_at" => function($x) { $this->m_deletedAt = $x; },
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
        return DeletedProcessTable::getInstance();
    }


    # Accessors
    public function getProcessId() { return $this->m_processId; }
    public function getAuthorId() { return $this->m_authorId; }
    public function getDeletedAt() { return $this->m_deletedAt; }


    # Setters
    public function setProcessId($x) { $this->m_processId = $x; }
    public function setAuthorId($x) { $this->m_authorId = $x; }
    public function setDeletedAt($x) { $this->m_deletedAt = $x; }
}


