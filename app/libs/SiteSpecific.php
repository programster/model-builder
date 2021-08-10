<?php

/* 
 * A dumping ground for useful global static functions.
 */

class SiteSpecific
{
    public static function getDb() : \Programster\PgsqlObjects\PgSqlConnection
    {
        static $db = null;
        
        if ($db === null)
        {
            $db = \Programster\PgsqlObjects\PgSqlConnection::create(
                DB_HOST, 
                DB_NAME, 
                DB_USER,
                DB_PASSWORD
            );
        }
        
        return $db;
    }
    
    
    /**
     * Return whether the user is currently logged in or not.
     * @return bool
     */
    public static function isLoggedIn() : bool
    {
        return true;
    }
    
    
    /**
     * Generate the hash of an array of data. The order of the keys doesn't matter if it is an assoc array.
     * @return string - the generated hash.
     */
    public static function generateArrayHash(array $data) : string
    {
        ksort($data);
        $stringForm = \Safe\json_encode($data);
        return hash("sha256", $stringForm, false);
    }
}
