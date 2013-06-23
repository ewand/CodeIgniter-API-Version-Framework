<?php defined('BASEPATH') OR exit('No direct script access allowed');
class api_base {

    protected $_ci;
    protected $_input;
    protected $_user;
    protected $_action_type;

    public function __construct($params = array()) {
        $this->_ci = get_instance();
        $this->_ci->load->language("api");
        $this->_action_type = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : "GET";
        $this->_input = $this->expand_input();
    }

    /**
     * Needs to be overridden by following versions
     * @return bool User is authorised
     */
    public function check_authentication() {
        $this->_user = "";
        return false;
    }

    /**
     * Request data can be in GET or POST parameters, JSON or XML.
     * @return array
     */
    public function expand_input() {
        switch ($this->_action_type) {
            case "GET":
                $data = $this->_ci->input->get();
                break;
            case "PUT":
                $data = array();
                break;
            case "POST":
                $data = array();
                break;
            case "DELETE":
                $data = array();
                break;
            default:
                $data = array();
                exit;
        }
        return $data;
    }

    /**
     * Genertate output bases on any errors and output type
     * @param array $results            Any results to be returned
     * @param string $error             Any error message
     * @param string $http_error_code   HTML Error Code
     * @param string $error_code        Exception Error Code
     * @param string $output_type        Output type JSON|XML
     */
    public function output_results(array $results = array("status" => "OK"), $error = "", $http_error_code = "200", $error_code = "0", $output_type = "JSON") {
        $data = $this->_parse_error($error, $http_error_code, $error_code, $results);
        if ($output_type == "JSON") {
            $this->_json_output($data);
        } else {
            $this->_xml_output($data);
        }
    }

    /**
     * Check if there is any errors and formulate response accordingly
     * Set a html response code in the output if there is an error
     * @param string $error             Error message
     * @param string $http_error_code   HTML Error code
     * @param string $error_code        Exception Error code
     * @param array $results            Results to be returned if there is no error
     * @return array                    Array containing error detail
     */
    protected function _parse_error($error, $http_error_code, $error_code, array $results) {
        if ($error != "" || $http_error_code != "200") {
            $http_error_code = ($http_error_code == "200") ? 400 : $http_error_code;
            $this->_ci->output->set_status_header($http_error_code);
            return array(
                "status" => $this->_ci->lang->line('api_status_error'),
                "error_code" => $error_code,
                "error_message" => $error
            );
        } else {
            return $results;
        }
    }

    /**
     * Turn result array into a json encapsulated html response
     * @param array $data Results to be returned
     */
    protected function _json_output(array $data) {
        $this->_ci->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    /**
     * Turn result array into a XML encapsulated html response
     * <output> will be the root node for the xml
     * @param array $data Results to be returned
     */
    protected function _xml_output(array $data) {
        $xml = $this->array_to_xml($data, new SimpleXMLElement('<output/>'));
        $output = $xml->asXML();
        $this->_ci->output
            ->set_content_type('text/xml')
            ->set_output($output);
    }

    /**
     * Function used to convert array to xml, needs to be recursive
     * @param array $arr Array to get converted to xml
     * @param SimpleXMLElement $xml XML item that acts as the parent for the returned xml nodes
     * @return SimpleXMLElement Returned xml nodes in multilevel structure
     */
    protected function array_to_xml(array $arr, SimpleXMLElement $xml)
    {
        foreach ($arr as $k => $v) {
            is_array($v)
                ? $this->array_to_xml($v, $xml->addChild($k))
                : $xml->addChild($k, $v);
        }
        return $xml;
    }
}