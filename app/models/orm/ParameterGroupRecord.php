<?php

use \Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class ParameterGroupRecord extends AbstractTableRowObject
{
    protected $m_authorId;
    protected $m_createdAt;


    protected function getAccessorFunctions() : array
    {
        return array(
            "author_id" => function() { return $this->m_authorId; },
            "created_at" => function() { return $this->m_createdAt; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "author_id" => function($x) { $this->m_authorId = $x; },
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
        return ParameterGroupTable::getInstance();
    }
    
    
    public static function createNew(UserRecord $author) : ParameterGroupRecord
    {
        return ParameterGroupRecord::createNewFromArray([
            'author_id' => $author->getId(),
            'created_at' => time(),
        ]);
    }


    # Accessors
    public function getAuthorId() { return $this->m_authorId; }
    public function getCreatedAt() { return $this->m_createdAt; }


    # Setters
    public function setAuthorId($x) { $this->m_authorId = $x; }
    public function setCreatedAt($x) { $this->m_createdAt = $x; }
}


