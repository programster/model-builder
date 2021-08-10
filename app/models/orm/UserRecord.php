<?php


use Programster\PgsqlObjects\AbstractTableRowObject;
use \Programster\PgsqlObjects\TableInterface;


class UserRecord extends AbstractTableRowObject
{
    protected string $m_firstName;
    protected string $m_lastName;
    protected string $m_email;
    protected string $m_hashedPassword;
    protected int $m_updatedAt;
    protected int $m_createdAt;


    protected function getAccessorFunctions() : array
    {
        return array(
            "first_name" => function() : string { return $this->m_firstName; },
            "last_name" => function() : string { return $this->m_lastName; },
            "email" => function() : string { return $this->m_email; },
            "hashed_password" => function() : string { return $this->m_hashedPassword; },
            "updated_at" => function() : int { return $this->m_updatedAt; },
            "created_at" => function() : int { return $this->m_createdAt; },
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
            "first_name" => function($x) : void { $this->m_firstName = $x; },
            "last_name" => function($x) : void { $this->m_lastName = $x; },
            "email" => function($x) : void { $this->m_email = $x; },
            "hashed_password" => function($x) : void { $this->m_hashedPassword = $x; },
            "updated_at" => function($x) : void { $this->m_updatedAt = $x; },
            "created_at" => function($x) : void { $this->m_createdAt = $x; },
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
        return UserTable::getInstance();
    }
    
    
    /**
     * Create a new user object. You will need to manually save if you wish for it to be persisted.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param string|null $uuid
     * @return UserRecord
     */
    public static function createNew(
        string $firstName, 
        string $lastName, 
        string $email, 
        string $password, 
        ?string $uuid = null
    ) : UserRecord
    {
        $timeNow = time();
        $id = $uuid ?? \Programster\PgsqlObjects\Utils::generateUuid();
        
        $arrayForm = array(
            'id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'hashed_password' => password_hash($password, PASSWORD_DEFAULT),
            'updated_at' => $timeNow,
            'created_at' => $timeNow,
        );
        
        return UserRecord::createNewFromArray($arrayForm);
    }


    # Accessors
    public function getFirstName() : string { return $this->m_firstName; }
    public function getLastName() : string { return $this->m_lastName; }
    public function getEmail() : string { return $this->m_email; }
    public function getHashedPassword() : string { return $this->m_hashedPassword; }
    public function getUpdatedAt() : int { return $this->m_updatedAt; }
    public function getCreatedAt() : int { return $this->m_createdAt; }


    # Setters
    public function setFirstName($x) : void { $this->m_firstName = $x; }
    public function setLastName($x) : void { $this->m_lastName = $x; }
    public function setEmail($x) : void { $this->m_email = $x; }
    public function setHashedPassword($x) : void { $this->m_hashedPassword = $x; }
    public function setUpdatedAt($x) : void { $this->m_updatedAt = $x; }
    public function setCreatedAt($x) : void { $this->m_createdAt = $x; }
}


