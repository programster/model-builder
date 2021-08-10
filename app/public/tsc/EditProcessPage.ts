/// <reference path="../node_modules/vue/types/index.d.ts" />


class Parameter
{
    private m_variableName : string;
    private m_dataPointId : string;
    
    public constructor(id : string, name : string)
    {
        this.m_variableName = name;
        this.m_dataPointId = id;
    }
}


class DataPoint
{
    private m_id : string;
    private m_name : string;
}



enum TestResultStatus
{
    success = "success",
    requestError = "request_error",
    codeError = "code_error",
    error = "fail"
}

interface SuccessTestResult
{
    status: TestResultStatus
    function: string
    parameter_values: object
    output: object
}

interface InterfaceErrorObject
{
    message: string
    status: TestResultStatus|null
    exception : object|null
}


interface InterfaceErrorTestResponse 
{
    error: InterfaceErrorObject;
}

interface InterfaceSuccessTestResponse 
{
    test_result: SuccessTestResult;
}




interface InterfaceGetProcessResponse
{
    active_process: null|{
        process_id : string
        name : string
        description : string
        code : string
        output_data_point_id : string
        parameter_group_id : string
        hash : string
    }
    process : {
        id : string
        model_id : string
    }
    process_history : object
}

interface InterfaceActiveParameterRecord
{
    id : string
    data_point_id : string,
    name : string,
    parameter_group_id : string,
    parameter_id : string,
    test_value: string|null
}



interface InterfaceGetParametersForProcessResponse
{
    active_parameters : Array<InterfaceActiveParameterRecord>
}

// represents the object data we store in the form.
interface InterfaceVueParameter
{
    id : string // id of the active parameter table row
    parameterId : string
    dataPointId : string|null // null if creating new parameter and not assigned yet
    name : string // variable name (without any $)
    parameterGroupId : string|null // null if createing new parameter and not assigned yet
    testValue : string|null
}

interface InterfaceVueDataPoint
{
    id : string // id of the active parameter table row
    name : string    
    testValue : string|null
}

interface InterfaceVueDataParameter
{
    id : string
    name : string
}

interface InterfaceVueDataProcess
{
    id : string
    name : string
    description : string
    code : string
    parameters :  Array<InterfaceVueParameter>
}


interface InterfaceVueData 
{
    process : InterfaceVueDataProcess
    possibleParameterDataPoints : Array<InterfaceVueDataPoint>
}


interface InterfaceGetDataPointsForModelResponse
{
    active_data_points : Array<{
        created_at: number
        data_point_id : string
        description : string
        hash: string
        id: string
        name : string
        sample_value: string
        validation_schema : string
    }>
}


class EditProcessPage
{
    private m_vueJsApp : Vue;
    private m_vueData : InterfaceVueData;
    private m_parameters;
    private static s_instance : EditProcessPage;
    
    
    public static getInstance() : EditProcessPage
    {
        if (EditProcessPage.s_instance === undefined)
        {
            EditProcessPage.s_instance = new EditProcessPage();
        }
        
        return EditProcessPage.s_instance;
    }
    
    
    private constructor()
    {
        var self = this;
        
        this.m_vueData = {
            "process" : {
                "id" : "",
                "name" : "bob",
                "description" : "",
                "code" : "",
                "parameters" : []
            },
            "possibleParameterDataPoints" : []
        };
        
        var vueJsConfig = {
            el : '#vueJsApp',
            data: this.m_vueData,
            methods: {
                removeParameter : function(parameterId) { self.removeParameter(parameterId); },
                onTestButtonClicked: function () {self.onTestButtonClicked(); }
            }
        };
        
        this.sendGetProcessDetailsRequest();
        this.sendGetParametersRequest();
        vueJsConfig.data.process.name = "bobby";
    
        this.m_vueJsApp = new Vue(vueJsConfig);
        Vue.config.devtools = true;
    }
    
    
    /**
     * Send a request to get details about a process
     */
    private sendGetProcessDetailsRequest()
    {
        let processId = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
        let url = "/api/processes/" + processId;
        var self = this;

        let onErrorCallback = function() {
            console.log("There was an error getting the process details.");
        };

        let onloadCallback = function (this: XMLHttpRequest) {
            switch (this.status)
            {
                case 200:
                {
                    var data = <InterfaceGetProcessResponse>JSON.parse(this.response);
                    self.m_vueData.process.id = data.active_process.process_id;
                    self.m_vueData.process.description = data.active_process.description;
                    self.m_vueData.process.code = data.active_process.code;

                    // fire off the request to get the data points now we know the model.
                    self.sendGetDataPointsRequest(data.process.model_id);
                }
                break;
                
                default:
                {
                    onErrorCallback();
                }
            }
        };

        Utils.sendGetRequest(url, onloadCallback, onErrorCallback);
    }
    
    
    /**
     * Send a request to get the parameters in the specified parameter group.
     */
    private sendGetParametersRequest() : void
    {
        let processId = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
        let url = "/api/processes/" + processId + "/parameters";
        var self = this;
        
        let onErrorCallback = function() {
            console.log("There was an error getting the process details.");
        };

        let onloadCallback = function (this: XMLHttpRequest) {
            switch (this.status)
            {
                case 200:
                {
                    var responseData = <InterfaceGetParametersForProcessResponse>JSON.parse(this.response);
                    self.m_vueData.process.parameters = [];

                    for (let activeParameter of responseData.active_parameters)
                    {
                        let vueParameter = {
                            'id' : activeParameter.id,
                            'dataPointId': activeParameter.data_point_id,
                            'name' : activeParameter.name,
                            'parameterGroupId' : activeParameter.parameter_group_id,
                            'parameterId' : activeParameter.parameter_id,
                            'testValue' : activeParameter.test_value
                        };

                        self.m_vueData.process.parameters.push(vueParameter);
                    }
                }
                break;
                
                default:
                {
                    onErrorCallback();
                }
            }
        };
        
        Utils.sendGetRequest(url, onloadCallback, onErrorCallback);
    }
    
    
    /**
     * Fire off an ajax request to the API to get the data points that we can use for this process.
     * @return void
     */
    private sendGetDataPointsRequest(modelId) : void
    {
        let url = "/api/data-points/for-model/" + modelId;
        var self = this;

        let onErrorCallback = function() {
            console.log("There was an error getting the process details.");
        };
        
        let onloadCallback = function (this: XMLHttpRequest) {
            switch (this.status)
            {
                case 200:
                {                
                    var responseData = <InterfaceGetDataPointsForModelResponse>JSON.parse(this.response);
                    self.m_vueData.possibleParameterDataPoints = [];

                    for (let activeDataPoint of responseData.active_data_points)
                    {
                        let vueDataPoint : InterfaceVueDataPoint = {
                            id: activeDataPoint.data_point_id,
                            name : activeDataPoint.name,
                            testValue: activeDataPoint.sample_value,
                        };

                        self.m_vueData.possibleParameterDataPoints.push(vueDataPoint);
                    }
                }
                break
                
                default:
                {
                    onErrorCallback();
                }
            }
        };

        Utils.sendGetRequest(url, onloadCallback, onErrorCallback);
    }
    
    
    /**
     * Handle the user pressing the button to test the process.
     * @return void - sends of request.
     */
    public onTestButtonClicked() : void
    {
        console.log("Test running!");
        // run the test!
        
        var hiddenInput = <HTMLInputElement>document.getElementById('formHiddenProcessId');
        var processId = hiddenInput.value;
        
        var codeTextArea = <HTMLTextAreaElement>document.getElementById('formInputCode');
        
        // send of ajax request.
        var request = new XMLHttpRequest();
        var url = "/processes/" + processId + "/test";
        request.open('POST', url, true);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
        
        var data = {
            'code': codeTextArea.value
        };
        
        var self = this;
        
        request.onload = function() {
            if (this.status === 200) 
            {
                // Success!
                var data = <InterfaceSuccessTestResponse>JSON.parse(this.response); // @todo separeate success interface
                self.updateTestResultBadge(true);
                self.showTestOutput(data.test_result.output);
            } 
            else 
            {
                var errorObject = <InterfaceErrorTestResponse>JSON.parse(this.response);
                // We reached our target server, but it returned an error
                // @TODO - proper error handling.
                // try to run JSON.parse(this.response); but also handle if non-json response.
                self.updateTestResultBadge(false);
                self.showErrorOutput(errorObject.error);
            }
        };

        request.onerror = function() {
            // @TODO - proper error handling.
            console.error("There was an issue.");
        };
        
        request.send(JSON.stringify(data));
    }
    
    
    private clearTestResultOutputArea()
    {
        var testResultDiv = document.getElementById("testResultArea");
        
        if (testResultDiv !== null && testResultDiv !== undefined)
        {
            document.removeChild(testResultDiv);
        }
    }
    
    
    /**
     * Get the area for placing test results.
     */
    private getTestResultOutputArea(): HTMLDivElement
    {
        var testResultDiv = <HTMLDivElement>document.getElementById("testResultArea");
        
        if (testResultDiv === null || testResultDiv === undefined)
        {
            var testResultDiv = <HTMLDivElement>document.createElement("div");
            testResultDiv.setAttribute("id", "testResultArea");
            document.body.appendChild(testResultDiv);
        }
        
        return testResultDiv;
    }
    
    
    private updateTestResultBadge(passed : boolean)
    {
        var testResultTitle = document.getElementById("testResultTitle");
        var testResultTitleBadge = <HTMLSpanElement> document.getElementById("testResultStatusBadge");
        
        if (testResultTitle === null || testResultTitle === undefined)
        {
            testResultTitle = <HTMLHeadingElement> document.createElement("h2");
            testResultTitleBadge = <HTMLSpanElement> document.createElement("span");
            
            testResultTitle.setAttribute("id", "testResultTitle");
            testResultTitleBadge.setAttribute("id", "testResultStatusBadge");
            
            testResultTitle.innerText = "Test result: ";
            testResultTitle.appendChild(testResultTitleBadge);
            this.getTestResultOutputArea().appendChild(testResultTitle);
        }
        
        if (passed) 
        {
            testResultTitleBadge.innerText = "Passed";
            testResultTitleBadge.setAttribute("class", "badge bg-success");
        }
        else
        {
            testResultTitleBadge.innerText = "Failed";
            testResultTitleBadge.setAttribute("class", "badge bg-danger");
        }
    }
    
    
    public showTestOutput(output)
    {
        
        var preElement = document.getElementById("outputPre");
        
        if (preElement === null || preElement === undefined)
        {
            preElement = <HTMLPreElement>document.createElement("pre");
            preElement.setAttribute("id", "outputPre");
            preElement.setAttribute("style", "whitespace: pre; background-color: black; color: white;");
            this.getTestResultOutputArea().appendChild(preElement);
        }
        
        preElement.innerText = JSON.stringify(output, null, 4);
    }
    
    
    public showErrorOutput(error: InterfaceErrorObject)
    {
        var preElement = document.getElementById("outputPre");
        
        if (preElement === null || preElement === undefined)
        {
            preElement = <HTMLPreElement>document.createElement("pre");
            preElement.setAttribute("id", "outputPre");
            preElement.setAttribute("style", "whitespace: pre; background-color: black; color: white;");
            document.body.appendChild(preElement);
        }

        console.log(error);
        preElement.innerText = error.message;
        
        if (error.exception !== null && error.exception !== undefined)
        {
            preElement.innerText = preElement.innerText + "\n" + JSON.stringify(error.exception, null, 4);
        }
    }
    
    
    /**
     * Handle the user clicking a the delete/remove button beside a parameter to remove it.
     */
    public removeParameter(parameterId : string) : void
    {
        for (let index in this.m_vueData.process.parameters)
        {
            let indexNumber = parseInt(index, 10);
            let parameter: InterfaceVueParameter = this.m_vueData.process.parameters[index];
            
            if (parameter.parameterId === parameterId)
            {
                this.m_vueData.process.parameters.splice(indexNumber, 1);
            }
        }
    }
    
    
    /**
     * Handle the user requesting the addition of a parameter to the page.
     */
    public addParameter() : void
    {
        let newParameter : InterfaceVueParameter;
        
        newParameter = {
            "id": Utils.generateUUID(),  // id of the active parameter table row
            "parameterId" : Utils.generateUUID(),
            "dataPointId" : null,
            "parameterGroupId" : null,
            "name" : "", // variable name (without any $)
            "testValue" : null
        };
        
        this.m_vueData.process.parameters.push(newParameter);
    }    
}