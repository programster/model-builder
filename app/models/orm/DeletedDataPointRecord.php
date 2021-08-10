<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class DeletedDataPointRecord extends AbstractTableRowObject
{
    protected $m_dataPointId;
    protected $m_authorId;
    protected $m_deletedAt;


    protected function getAccessorFunctions() : array
    {
        return array(
            "data_point_id" => function() { return $this->m_dataPointId; },
            "author_id" => function() { return $this->m_authorId; },
            "deleted_at" => function() { return $this->m_deletedAt; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "data_point_id" => function($x) { $this->m_dataPointId = $x; },
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
        return DeletedDataPointTable::getInstance();
    }


    # Accessors
    public function getDataPointId() { return $this->m_dataPointId; }
    public function getAuthorId() { return $this->m_authorId; }
    public function getDeletedAt() { return $this->m_deletedAt; }


    # Setters
    public function setDataPointId($x) { $this->m_dataPointId = $x; }
    public function setAuthorId($x) { $this->m_authorId = $x; }
    public function setDeletedAt($x) { $this->m_deletedAt = $x; }
}


