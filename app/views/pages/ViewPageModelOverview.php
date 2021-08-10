<?php

/* 
 * The page to display the overview of a model. E.g. its architecture diagram and all of its processes/datapoints.
 */

class ViewPageModelOverview extends \Programster\AbstractView\AbstractView
{
    private array $m_activeProcesses;
    private array $m_activeDataPoints;
    
    
    public function __construct(array $activeProcesses, array $activeDatapoints)
    {
        $this->m_activeProcesses = $activeProcesses;
        $this->m_activeDataPoints = $activeDatapoints;
    }

    protected function renderContent() 
{
?>



<div class="container-xxl">
    <h2>Processes</h2>
    <?= new ViewBootstrapButtonLink(ButtonType::createPrimary(), "Add New", "/processes/create"); ?> <br><br>
    <?= new ViewTableOfProcesses(...$this->m_activeProcesses); ?>
    
    <br><br>
    
    <h2>Data Points</h2>
    <?= new ViewBootstrapButtonLink(ButtonType::createPrimary(), "Add New", "/data-points/create"); ?> <br><br>
    <?= new ViewTableOfDataPoints(...$this->m_activeDataPoints); ?>
</div>


<?php
    }

}

