<?php

/* 
 * The view for hte page for editing a data point.
 */

class ViewPageEditDataPoint extends \Programster\AbstractView\AbstractView
{
    private ActiveDataPointRecord | DataPointHistoryRecord $m_dataPoint;
    
    public function __construct(ActiveDataPointRecord | DataPointHistoryRecord $dataPoint)
    {
        $this->m_dataPoint = $dataPoint;
    }
    
    
    protected function renderContent() 
    {
        $prettySchema = Safe\json_encode($this->m_dataPoint->getValidationSchemaArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $prettyExampleValue = Safe\json_encode($this->m_dataPoint->getSampleValueArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>



<br>

<div class="container">
    <h2>Edit Data Point</h2>
    <br>

    <form>

        <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $this->m_dataPoint->getName();?>">
        </div>

        <br>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description"><?= $this->m_dataPoint->getDescription();?></textarea>
        </div>
        
        <br>
        <div class="form-group">
            <label for="validationSchema">Validation Schema</label>
            <textarea style="height: 400px; font-size: 14px;" id="validationSchema" placeholder="" class="form-control"><?= $prettySchema ?></textarea>
        </div>
        
        <br>
        
        <div class="form-group">
            <label for="exampleValue">Example Value</label>
            <textarea style="height: 400px; font-size: 14px;" id="exampleValue" placeholder="" class="form-control"><?= $prettyExampleValue; ?></textarea>
        </div>

        <br>
        
        <button type="submit" class="btn btn-primary">Save</button>
        
        <br><br>
    </form>
</div>



<?php
    }
}