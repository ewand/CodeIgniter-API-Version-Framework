<?php defined('BASEPATH') OR exit('No direct script access allowed');
require("api.php");
class api_v1 extends api_base {

    /**
     * Apikeys used for version 1, this can be changed in subsequent versions
     * @return bool
     */
    public function check_authentication() {
        $key = $this->_input["apikey"];
        if ($key == "TEST") {
			$this->_user = "Api_test_user";
			return true;
        }
        return false;
    }
	
    public function method1() {
        $this->output_results(array("message" => "version 1 output"));
    }
}