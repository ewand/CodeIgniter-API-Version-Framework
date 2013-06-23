CodeIgniter API Version Framework
=================================

Base API Framework for CodeIgniter with support for XML and JSON, and has versioning support.

Currently only GET request type and parameters are supported, and only JSON output.

There is one test method that can 

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