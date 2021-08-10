<?php

/* 
 * A view of the models in the system.
 */

class ViewTableOfModels extends \Programster\AbstractView\AbstractView
{
    private array $m_models;
    
    
    public function __construct(ModelRecord ...$models)
    {
        $this->m_models = $models;
    }
    
    
    protected function renderContent() {
?>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Updated At</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->m_models as $modelRecord): ?>
        <?php 
        /* @var $modelRecord ModelRecord */
        $lastUpdatedAt = $modelRecord->fetchLastUpdatedAt();         
        $lastUpdatedAtString = ($lastUpdatedAt !== null) ? date('jS F Y', $lastUpdatedAt) . "<br>" . date('h:i:s', $lastUpdatedAt) : "";


        ?>
        <tr>
            <td><?= $modelRecord->getName(); ?></td>
            <td><?= $modelRecord->getDescription(); ?></td>
            <td><?= $lastUpdatedAtString ?></td>
            <td><?= new ViewBootstrapButtonLink(ButtonType::createPrimary(), "Edit", "/models/{$modelRecord->getId()}"); ?> <?= new ViewBootstrapButtonLink(ButtonType::createDanger(), "Delete", "/models/{$modelRecord->getId()}/delete"); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
    }

}