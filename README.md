CodeIgniter API Server Framework with Versions and Multiple format
=================================

Built on CodeIgniter 2.1.3

Base API Framework for CodeIgniter with support for XML and JSON, and has versioning support. Developed this code for a larger project, designed to be the starting point for anyone wanting to implement thier own API in CodeIgniter.

Currently only GET request type and parameters are supported, and only JSON output.

There is one test method that can be accessed, at the moment it requires an API key to perform this action. Test urls below.

#Test Links:
/api<br />
[HTTP STATUS: 400]<br />
{"status":"error","error_code":"0","error_message":"API Version does not exist or not supplied"}

/api/v1/
[HTTP STATUS: 403]<br />
{"status":"error","error_code":"0","error_message":"Unauthorized"}

/api/v1/method1?apikey=TEST<br />
{"message":"version 1 output"}

/api/v2/method1?apikey=TEST<br />
{"message":"version 2 output"}

/api/v1/method2?apikey=TEST<br />
[HTTP STATUS: 400]<br />
{"status":"error","error_code":"0","error_message":"Action does not exist or not supplied"}

#Installation:
Copy the following files:<br />
controllers/api.php     ->      application/controllers/api.php<br />
controllers/api/*       ->      application/controllers/api/<br />
language/anglish/api_lang.php      ->      application/language/anglish/api_lang.php

Modify the following files:<br />
config/autoload.php<br />
line 99: added 'api' to the array or languages to autoload<br />
config/routes.php<br />
line 41: added new route for api/:any to point to api controller
