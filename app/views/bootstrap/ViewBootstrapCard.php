<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewBootstrapCard extends \Programster\AbstractView\AbstractView
{
    private string $m_imageSource;
    private string $m_body;
    private string $m_imageAltText;
    private string $m_title;
    
    
    /**
     * 
     * @param string|Stringable $title - the title of the card
     * @param string|Stringable $body - the main text/body of the card
     * @param string|Stringable $imageSource - the web path (not filepath) to the image to show.
     * @param string|Stringable $imageAltText - the alt text for the image
     */
    public function __construct(
        string|Stringable $title, 
        string|Stringable $body, 
        string|Stringable $imageSource,
        string|Stringable $imageAltText = "" 
    )
    {
        $this->m_title = $title;
        $this->m_body = $body;
        $this->m_imageAltText = $imageAltText;
        $this->m_imageSource = $imageSource;
    }
    
    
    protected function renderContent() 
    {
?>

<div class="card" style="width: 18rem;">
    <img class="card-img-top" src="<?= $this->m_imageSource; ?>" alt="<?= $this->m_imageAltText; ?>">
    <div class="card-body">
        <h5 class="card-title"><?= $this->m_title; ?></h5>
        <p class="card-text"><?= $this->m_body; ?></p> 
    </div>
</div>


<?php
    }

}