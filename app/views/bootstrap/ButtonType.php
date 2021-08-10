<?php

/* 
 * An "enum" for the various different button types.
 */

class ButtonType implements Stringable
{
    private string $m_type;
    
    
    private function __construct(string $type)
    {
        $this->m_type = $type;
    }
    
    
    public static function createPrimary() 
    {
        return new ButtonType("primary");
    }
    
    
    public static function createSecondary() 
    {
        return new ButtonType("secondary");
    }
    
    
    public static function createSuccess() 
    {
        return new ButtonType("success");
    }
    
    
    public static function createDanger() 
    {
        return new ButtonType("danger");
    }
    
    
    public static function createWarning() 
    {
        return new ButtonType("warning");
    }
    
    
    public static function createInfo() 
    {
        return new ButtonType("info");
    }
    
    
    public static function createLight() 
    {
        return new ButtonType("light");
    }
    
    
    public static function createDark() 
    {
        return new ButtonType("dark");
    }
    
    
    public static function createLink() 
    {
        return new ButtonType("link");
    }

    
    public function __toString(): string 
    {
        return $this->m_type;
    }
}