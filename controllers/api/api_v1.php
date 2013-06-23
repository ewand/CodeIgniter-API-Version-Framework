<?php defined('BASEPATH') OR exit('No direct script access allowed');
require("api.php");
class api_v1 extends api_base {

    /**
     * Apikeys used for version 1, this can be changed in subsequent versions
     * @return bool
     */
    public function check_authentication() {
        $key = $this->_input["apikey"];
        if ($key != "") {
            // Check if api key exists in the database.
            $this->_ci->db->select("account")
                ->where("key =", $key)
                ->from("api_keys");
            $query = $this->_ci->db->get();
            // Reject user if there is no key found or more than 1.
            if ($query->num_rows() == 1) {
                $this->_user = $query->row("account");
                return true;
            }
        }
        return false;
    }
	
    public function method1() {
        $this->output_results(array("message", "version 1 test"));
    }
}