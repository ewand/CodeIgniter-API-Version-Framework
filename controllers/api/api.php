<?php defined('BASEPATH') OR exit('No direct script access allowed');
class api_base {

    // Instance of CodeIgniter
    protected $_ci;

    // Input type GET|POST|JSON|XML, determined by HTTP request content type and content
    protected $_input_type;

    // Input parameters, both GET parameters and POST values depending on input type
    protected $_input_parameters = array();

    // Output type JSON|XML, use either type specified in request, use input type or default to JSON
    protected $_output_type = "JSON";

    // User with authentication
    protected $_user;

    // HTTP request type GET|POST|PUT|DELETE
    protected $_action_type;

    // Get instance of CI, determine input type and values
    public function __construct($params = array()) {
        $this->_ci = get_instance();
        $this->_action_type = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : "GET";
        $this->detect_input();
        $this->expand_input();
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
        if($this->_ci->input->get() != "") {
            $this->_input_parameters = array_merge($this->_input_parameters, $this->_ci->input->get());
        }
        switch ($this->_input_type) {
            case "HTML":
                if ($this->_ci->input->post() != "") {
                    $this->_input_parameters = array_merge($this->_input_parameters, $this->_ci->input->post());
                }
                break;
            case "JSON":
                $json = json_decode(file_get_contents("php://input"), true);
                if (gettype($json) == "array") {
                    $this->_input_parameters = array_merge($this->_input_parameters, $json);
                } else if (gettype($json) == "string") {
                    $this->_input_parameters = array_merge($this->_input_parameters, array("data" => $json));
                }
                break;
            case "XML":
                $xml_string = file_get_contents("php://input");
                if (gettype($xml_string) == "string") {
                    $xml_object = simplexml_load_string($xml_string);
                    $xml_array = $this->_objects_to_array($xml_object);
                    $this->_input_parameters = array_merge($this->_input_parameters, $xml_array);
                }
                break;
        }
    }

    function _objects_to_array($obj, $arr_skip_indexes = array()) {
        $arr = array();
        if (is_object($obj)) {
            $obj = get_object_vars($obj);
        }
        if (is_array($obj)) {
            foreach ($obj as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = $this->_objects_to_array($value, $arr_skip_indexes);
                }
                if (in_array($index, $arr_skip_indexes)) {
                    continue;
                }
                $arr[$index] = $value;
            }
        }
        return $arr;
    }

    public function detect_input() {
        switch ($this->_ci->input->server('CONTENT_TYPE')) {
            case "application/json":
                $this->_input_type = "JSON";
                $this->_output_type = "JSON";
                break;
            case "text/xml":
                $this->_input_type = "XML";
                $this->_output_type = "XML";
                break;
            default:
                $this->_input_type = "HTML";
                $this->_output_type = "JSON";
        }
    }

    /**
     * Genertate output bases on any errors and output type
     * @param array $results            Any results to be returned
     * @param string $error             Any error message
     * @param string $http_error_code   HTML Error Code
     * @param string $error_code        Exception Error Code
     */
    public function output_results(array $results = array("status" => "OK"), $error = "", $http_error_code = "200", $error_code = "0") {
        $data = $this->_parse_error($error, $http_error_code, $error_code, $results);
        switch ($this->_output_type) {
            case "JSON":
                $this->_json_output($data);
                break;
            case "XML":
                $this->_xml_output($data);
                break;
            default:
                $this->_json_output($data);
                break;
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