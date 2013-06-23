<?php defined('BASEPATH') OR exit('No direct script access allowed');
require("api_v1.php");
class api_v2 extends api_v1 {

    public function method1() {
        $this->output_results(array("message", "version 2 test"));
    }
}