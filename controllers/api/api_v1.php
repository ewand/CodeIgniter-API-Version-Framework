<?php defined('BASEPATH') OR exit('No direct script access allowed');
require("api.php");
class api_v1 extends api_base {

    public function __construct($params = array()) {
        parent::__construct($params);
        $this->_detect_output();
    }
    /**
     * Apikeys used for version 1, this can be changed in subsequent versions
     * @return bool
     */
    public function check_authentication() {
        $key = (isset($this->_input_parameters["apikey"])) ? $this->_input_parameters["apikey"] : "";
        if ($key == "TEST") {
			$this->_user = "Api_test_user";
			return true;
        }
        return false;
    }
	
    public function method1() {
        $this->output_results(array("message" => "version 1 output"));
    }

    protected function _detect_output() {
        if (isset($this->_input_parameters["output_type"])) {
            switch ($this->_input_parameters["output_type"]) {
                case "JSON":
                    $this->_output_type = "JSON";
                    break;
                case "XML":
                    $this->_output_type = "XML";
                    break;
            }
        }
    }
}