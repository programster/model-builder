<?php

/* 
 * The view for hte page for editing a data point.
 */

class ViewPageEditProcess extends \Programster\AbstractView\AbstractView
{
    private ActiveProcessRecord |ProcessHistoryRecord $m_process;
    private array $m_modelDataPoints;
    private array $m_parameters;
    
    
    public function __construct(ActiveProcessRecord |ProcessHistoryRecord $process, ActiveDataPointRecord ...$modelDataPoints)
    {
        $this->m_process = $process;
        $this->m_modelDataPoints = $modelDataPoints;
        $this->m_parameters = ActiveParameterTable::getInstance()->loadForProcess($process);
    }
    
    
    protected function renderContent() 
    {
?>

<script src="/js/Utils.js"></script>
<script src="/js/EditProcessPage.js"></script>
  



<div id="vueJsApp" class="container">
    
   
    <h2>Edit Process</h2>

    <form id="submitProcessForm" onsubmit="return EditProcessPage.getInstance().onFormSubmit(this);">
        
        <input type="hidden" name="process_id" id="formHiddenProcessId" :value=process.id >
        
        <div class="form-group">
            <h5>Name</h5>
            <input type="text" class="form-control" :value=process.name >
            
        </div>
        
        <br>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="formInputDescription">{{ process.description }}</textarea>
        </div>
        
        <br><br>
        
        
        <h4>Parameters</h4>
        <hr>


        <!-- this is a placeholder for the javascript to manage the parameters when swith over to JS based. -->
        <span id="parametersSection">

        
            <div class="parameter" v-for="parameter in process.parameters">
                
                <div class="row" style="margin-top: 0.5em">
                    <div class="col-md-5">
                        <input type="text" class="form-control" :value=parameter.name>
                    </div>

                    <div class="col-md-5">
                        <select class="form-control">
                            <option v-for="dataPoint in possibleParameterDataPoints" :value=possibleParameterDataPoints.id >{{ dataPoint.name }}</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button v-on:click="removeParameter(parameter.parameterId)" type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
                
            </div>
           
            
        </span>
                
        
        
       
        <br><br>
        
        <?= new ViewBootstrapButton(ButtonType::createDark(), "Add Parameter", "EditProcessPage.getInstance().addParameter()"); ?>

        <hr>
        <br><br>
        
        
        <h5>Output Data Point</h5>
        <div class="form-group">
            <select id="datapointId" class="form-control">
                <?php 
                    foreach ($this->m_modelDataPoints as $activeDataPoint)
                    {
                        print "<option value='{$activeDataPoint->getDataPointId()}'>{$activeDataPoint->getName()}</option>";
                    }
                ?>
            </select>
        </div>
        
        <br><br>
        
        <h5>Code</h5>
        <div class="form-group">
            <textarea style="height: 400px;" class="form-control" id="formInputCode">{{ process.code }}</textarea>
        </div>
        
        <br><br>
       
        
        <button type="submit" class="btn btn-primary">Save</button>
        
        <button v-on:click="onTestButtonClicked()" type="button" class="btn btn-warning">Test</button>
        
        <br><br>
    </form>
</div>


<script type="text/javascript">
    
EditProcessPage.getInstance();
</script>

<?php
    }
}
