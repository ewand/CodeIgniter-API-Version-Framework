CodeIgniter API Version Framework
=================================

Built on CodeIgniter 2.1.3

Base API Framework for CodeIgniter with support for XML and JSON, and has versioning support. Developed this code for a larger project, designed to be the starting point for anyone wanting to implement thier own API in CodeIgniter.

Currently only GET request type and parameters are supported, and only JSON output.

There is one test method that can be accessed, at the moment it requires an API key to perform this action. Test urls below.

#Test Links:
/api
[HTTP STATUS: 400]
{"status":"error","error_code":"0","error_message":"API Version does not exist or not supplied"}

/api/v1/
[HTTP STATUS: 403]
{"status":"error","error_code":"0","error_message":"Unauthorized"}

/api/v1/method1?apikey=TEST
{"message":"version 1 output"}

/api/v2/method1?apikey=TEST
{"message":"version 2 output"}

/api/v1/method2?apikey=TEST
[HTTP STATUS: 400]
{"status":"error","error_code":"0","error_message":"Action does not exist or not supplied"}

#Installation:
Copy the following files:
controllers/api.php     ->      application/controllers/api.php
controllers/api/*       ->      application/controllers/api/

Modify the following files:
config/autoload.php
line 99: added 'api' to the array or languages to autoload
config/routes.php
line 41: added new route for api/:any to point to api controller