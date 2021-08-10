<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ViewPageModelList extends \Programster\AbstractView\AbstractView
{
    private array $m_modelRecords;
    
    
    public function __construct(ModelRecord ...$models)
    {
        $this->m_modelRecords = $models;
    }

    protected function renderContent() 
{
?>



<div class="container">
    <br>
    <h1>Model Builder</h1>
    <hr>
    <h2>Models</h2>
    <?= new ViewBootstrapButtonLink(ButtonType::createPrimary(), "Add New", "/models/create"); ?> <br><br>
    <?= new ViewTableOfModels(...$this->m_modelRecords); ?>
</div>


<?php
    }

}

