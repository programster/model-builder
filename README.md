# Model Builder
An unfinished project for creating calculation models that could be executed in a highly parallelized manner. The [full documentation](http://model-builder-docs.programster.org/#/) is available online.

## Process Testing
When testing a process:

1. The code and parameters are sent up to the server.
1. PHP validates the parameter values against the parameter schemas and sends back a parameter validaton error if this fails.
1. PHP runs the code with the parameter values (either from test values provided, or defaults on the data points). If PHP has issues reading the code, a parsing error is returned.
1. If the code successfully runs, the output is checked against the output data point schema. If this failes an "output validation error" is returned.
1. If the output validates against the schema correctly, then a successful test response is sent back.

[swaggest/json-schema](https://github.com/swaggest/php-json-schema) is the package being used to perform schema validation the server.
