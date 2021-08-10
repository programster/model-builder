<?php

/* 
 * A view of the models in the system.
 */

class ViewTableOfDataPoints extends \Programster\AbstractView\AbstractView
{
    private array $m_activeDataPoints;
    
    
    public function __construct(ActiveDataPointRecord ...$dataPoints)
    {
        $this->m_activeDataPoints = $dataPoints;
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
        <?php foreach ($this->m_activeDataPoints as $activeDataPointRecord): ?>
        <?php 
        $lastUpdatedAt = $activeDataPointRecord->fetchLastUpdatedAt();         
        $lastUpdatedAtString = ($lastUpdatedAt !== null) ? date('jS F Y', $lastUpdatedAt) . "<br>" . date('h:i:s', $lastUpdatedAt) : "";

        ?>
        <tr>
            <td><?= $activeDataPointRecord->getName(); ?></td>
            <td><?= $activeDataPointRecord->getDescription(); ?></td>
            <td nowrap><?= $lastUpdatedAtString ?></td>
            <td nowrap><?= new ViewBootstrapButtonLink(ButtonType::createPrimary(), "Edit", "/data-points/{$activeDataPointRecord->getDataPointId()}"); ?> <?= new ViewBootstrapButtonLink(ButtonType::createDanger(), "Delete", "/data-points/{$activeDataPointRecord->getDataPointId()}/delete"); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
    }

}