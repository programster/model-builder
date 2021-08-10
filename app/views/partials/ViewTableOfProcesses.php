<?php

/* 
 * A view of the models in the system.
 */

class ViewTableOfProcesses extends \Programster\AbstractView\AbstractView
{
    private array $m_activeProcessRecords;
    
    
    public function __construct(ActiveProcessRecord ...$processes)
    {
        $this->m_activeProcessRecords = $processes;
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
        <?php foreach ($this->m_activeProcessRecords as $activeProcessRecord): ?>
        <?php 
        /* @var $activeProcessRecord ActiveProcessRecord */
        $latestHistoryRecord = $activeProcessRecord->fetchLatestHistoryRecord();
        $lastUpdatedAt = $latestHistoryRecord->getCreatedAt();
        $lastUpdatedAtString = date('jS F Y', $lastUpdatedAt) . "<br>" . date('h:i:s', $lastUpdatedAt);

        ?>
        <tr>
            <td><?= $activeProcessRecord->getName(); ?></td>
            <td><?= $activeProcessRecord->getDescription(); ?></td>
            <td><?= $lastUpdatedAtString ?></td>
            <td><?= new ViewBootstrapButtonLink(ButtonType::createPrimary(), "Edit", "/processes/{$activeProcessRecord->getProcessId()}"); ?> <?= new ViewBootstrapButtonLink(ButtonType::createDanger(), "Delete", "/processes/{$activeProcessRecord->getProcessId()}/delete"); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
    }

}