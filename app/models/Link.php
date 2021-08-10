<?php

/* 
 */

class Link
{
    private string $m_label;
    private ?string $m_href;
    
    
    public function __construct(string $label, ?string $href)
    {
        $this->m_label = $label;
        $this->m_href = $href;
    }
    
    
    # Accessors
    public function getLabel() : string { return $this->m_label; }
    public function getHref() : ?string { return $this->m_href; }
}
