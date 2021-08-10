<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewNavbar extends \Programster\AbstractView\AbstractView
{
    private array $m_links;
    
    
    public function __construct(Link ...$links)
    {
        $this->m_links = $links;
    }
    
    
    protected function renderContent() 
    {
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Models</a></li>
        <?php if(count($this->m_links) > 0): ?>
            <?php foreach ($this->m_links as $link): ?>
                <?php if ($link->getHref() !== null): ?>
                    <li class="breadcrumb-item"><a href="<?= $link->getHref(); ?>"><?= $link->getLabel(); ?></a></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><?= $link->getLabel(); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif;?>
      </ol>
    </nav>
  </div>
</nav>





<?php
    }
}

