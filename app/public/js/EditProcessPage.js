/// <reference path="../node_modules/vue/types/index.d.ts" />
var Parameter = /** @class */ (function () {
    function Parameter(id, name) {
        this.m_variableName = name;
        this.m_dataPointId = id;
    }
    return Parameter;
}());
var DataPoint = /** @class */ (function () {
    function DataPoint() {
    }
    return DataPoint;
}());
var TestResultStatus;
(function (TestResultStatus) {
    TestResultStatus["success"] = "success";
    TestResultStatus["requestError"] = "request_error";
    TestResultStatus["codeError"] = "code_error";
    TestResultStatus["error"] = "fail";
})(TestResultStatus || (TestResultStatus = {}));
var EditProcessPage = /** @class */ (function () {
    function EditProcessPage() {
        var self = this;
        this.m_vueData = {
            "process": {
                "id": "",
                "name": "bob",
                "description": "",
                "code": "",
                "parameters": []
            },
            "possibleParameterDataPoints": []
        };
        var vueJsConfig = {
            el: '#vueJsApp',
            data: this.m_vueData,
            methods: {
                removeParameter: function (parameterId) { self.removeParameter(parameterId); },
                onTestButtonClicked: function () { self.onTestButtonClicked(); }
            }
        };
        this.sendGetProcessDetailsRequest();
        this.sendGetParametersRequest();
        vueJsConfig.data.process.name = "bobby";
        this.m_vueJsApp = new Vue(vueJsConfig);
        Vue.config.devtools = true;
    }
    EditProcessPage.getInstance = function () {
        if (EditProcessPage.s_instance === undefined) {
            EditProcessPage.s_instance = new EditProcessPage();
        }
        return EditProcessPage.s_instance;
    };
    /**
     * Send a request to get details about a process
     */
    EditProcessPage.prototype.sendGetProcessDetailsRequest = function () {
        var processId = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
        var url = "/api/processes/" + processId;
        var self = this;
        var onErrorCallback = function () {
            console.log("There was an error getting the process details.");
        };
        var onloadCallback = function () {
            switch (this.status) {
                case 200:
                    {
                        var data = JSON.parse(this.response);
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
    };
    /**
     * Send a request to get the parameters in the specified parameter group.
     */
    EditProcessPage.prototype.sendGetParametersRequest = function () {
        var processId = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
        var url = "/api/processes/" + processId + "/parameters";
        var self = this;
        var onErrorCallback = function () {
            console.log("There was an error getting the process details.");
        };
        var onloadCallback = function () {
            switch (this.status) {
                case 200:
                    {
                        var responseData = JSON.parse(this.response);
                        self.m_vueData.process.parameters = [];
                        for (var _i = 0, _a = responseData.active_parameters; _i < _a.length; _i++) {
                            var activeParameter = _a[_i];
                            var vueParameter = {
                                'id': activeParameter.id,
                                'dataPointId': activeParameter.data_point_id,
                                'name': activeParameter.name,
                                'parameterGroupId': activeParameter.parameter_group_id,
                                'parameterId': activeParameter.parameter_id,
                                'testValue': activeParameter.test_value
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
    };
    /**
     * Fire off an ajax request to the API to get the data points that we can use for this process.
     * @return void
     */
    EditProcessPage.prototype.sendGetDataPointsRequest = function (modelId) {
        var url = "/api/data-points/for-model/" + modelId;
        var self = this;
        var onErrorCallback = function () {
            console.log("There was an error getting the process details.");
        };
        var onloadCallback = function () {
            switch (this.status) {
                case 200:
                    {
                        var responseData = JSON.parse(this.response);
                        self.m_vueData.possibleParameterDataPoints = [];
                        for (var _i = 0, _a = responseData.active_data_points; _i < _a.length; _i++) {
                            var activeDataPoint = _a[_i];
                            var vueDataPoint = {
                                id: activeDataPoint.data_point_id,
                                name: activeDataPoint.name,
                                testValue: activeDataPoint.sample_value,
                            };
                            self.m_vueData.possibleParameterDataPoints.push(vueDataPoint);
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
    };
    /**
     * Handle the user pressing the button to test the process.
     * @return void - sends of request.
     */
    EditProcessPage.prototype.onTestButtonClicked = function () {
        console.log("Test running!");
        // run the test!
        var hiddenInput = document.getElementById('formHiddenProcessId');
        var processId = hiddenInput.value;
        var codeTextArea = document.getElementById('formInputCode');
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
        request.onload = function () {
            if (this.status === 200) {
                // Success!
                var data = JSON.parse(this.response); // @todo separeate success interface
                self.updateTestResultBadge(true);
                self.showTestOutput(data.test_result.output);
            }
            else {
                var errorObject = JSON.parse(this.response);
                // We reached our target server, but it returned an error
                // @TODO - proper error handling.
                // try to run JSON.parse(this.response); but also handle if non-json response.
                self.updateTestResultBadge(false);
                self.showErrorOutput(errorObject.error);
            }
        };
        request.onerror = function () {
            // @TODO - proper error handling.
            console.error("There was an issue.");
        };
        request.send(JSON.stringify(data));
    };
    EditProcessPage.prototype.clearTestResultOutputArea = function () {
        var testResultDiv = document.getElementById("testResultArea");
        if (testResultDiv !== null && testResultDiv !== undefined) {
            document.removeChild(testResultDiv);
        }
    };
    /**
     * Get the area for placing test results.
     */
    EditProcessPage.prototype.getTestResultOutputArea = function () {
        var testResultDiv = document.getElementById("testResultArea");
        if (testResultDiv === null || testResultDiv === undefined) {
            var testResultDiv = document.createElement("div");
            testResultDiv.setAttribute("id", "testResultArea");
            document.body.appendChild(testResultDiv);
        }
        return testResultDiv;
    };
    EditProcessPage.prototype.updateTestResultBadge = function (passed) {
        var testResultTitle = document.getElementById("testResultTitle");
        var testResultTitleBadge = document.getElementById("testResultStatusBadge");
        if (testResultTitle === null || testResultTitle === undefined) {
            testResultTitle = document.createElement("h2");
            testResultTitleBadge = document.createElement("span");
            testResultTitle.setAttribute("id", "testResultTitle");
            testResultTitleBadge.setAttribute("id", "testResultStatusBadge");
            testResultTitle.innerText = "Test result: ";
            testResultTitle.appendChild(testResultTitleBadge);
            this.getTestResultOutputArea().appendChild(testResultTitle);
        }
        if (passed) {
            testResultTitleBadge.innerText = "Passed";
            testResultTitleBadge.setAttribute("class", "badge bg-success");
        }
        else {
            testResultTitleBadge.innerText = "Failed";
            testResultTitleBadge.setAttribute("class", "badge bg-danger");
        }
    };
    EditProcessPage.prototype.showTestOutput = function (output) {
        var preElement = document.getElementById("outputPre");
        if (preElement === null || preElement === undefined) {
            preElement = document.createElement("pre");
            preElement.setAttribute("id", "outputPre");
            preElement.setAttribute("style", "whitespace: pre; background-color: black; color: white;");
            this.getTestResultOutputArea().appendChild(preElement);
        }
        preElement.innerText = JSON.stringify(output, null, 4);
    };
    EditProcessPage.prototype.showErrorOutput = function (error) {
        var preElement = document.getElementById("outputPre");
        if (preElement === null || preElement === undefined) {
            preElement = document.createElement("pre");
            preElement.setAttribute("id", "outputPre");
            preElement.setAttribute("style", "whitespace: pre; background-color: black; color: white;");
            document.body.appendChild(preElement);
        }
        console.log(error);
        preElement.innerText = error.message;
        if (error.exception !== null && error.exception !== undefined) {
            preElement.innerText = preElement.innerText + "\n" + JSON.stringify(error.exception, null, 4);
        }
    };
    /**
     * Handle the user clicking a the delete/remove button beside a parameter to remove it.
     */
    EditProcessPage.prototype.removeParameter = function (parameterId) {
        for (var index in this.m_vueData.process.parameters) {
            var indexNumber = parseInt(index, 10);
            var parameter = this.m_vueData.process.parameters[index];
            if (parameter.parameterId === parameterId) {
                this.m_vueData.process.parameters.splice(indexNumber, 1);
            }
        }
    };
    /**
     * Handle the user requesting the addition of a parameter to the page.
     */
    EditProcessPage.prototype.addParameter = function () {
        var newParameter;
        newParameter = {
            "id": Utils.generateUUID(),
            "parameterId": Utils.generateUUID(),
            "dataPointId": null,
            "parameterGroupId": null,
            "name": "",
            "testValue": null
        };
        this.m_vueData.process.parameters.push(newParameter);
    };
    return EditProcessPage;
}());
//# sourceMappingURL=EditProcessPage.js.map