<?php

/* 
 * A bootstrap container.
 */

class ViewBootstrapContainer extends \Programster\AbstractView\AbstractView
{
    private string|Stringable $m_content;
    
    
    public function __construct(string | Stringable $content)
    {
        $this->m_content = $content;
    }
    
    
    protected function renderContent() 
    {
?>


<div class="container"> <?= $this->m_content; ?></div>

    
<?php
    }

}