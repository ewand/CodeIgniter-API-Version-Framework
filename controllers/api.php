<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Api
 * @property CI_Uri $uri
 * @property Api_v1 $api
 */
class Api extends CI_Controller {

    private $api = null;

    public function __construct($params = array()) {
        parent::__construct();
        $this->load->language("api");
    }

    public function index()
    {
        if ($this->load_version($this->uri->segment(2))) {
            $action = $this->uri->segment(3);
            if ($this->api->check_authentication()) {
                if ($action != "" && method_exists($this->api, $action)) {
                    $this->api->{$action}();
                } else {
                    $this->api->output_results(array(), $this->lang->line('api_action_not_exists'), 400);
                }
            } else {
                $this->api->output_results(array(), $this->lang->line('api_unauthorised'), 401);
            }
        } else {
            $this->api->output_results(array(), $this->lang->line('api_version_not_exists'), 400);
        }
    }

    private function load_version($version = "v2") {
        switch ($version) {
            case "v1":
                require ("api/api_v1.php");
                $this->api = new api_v1();
                return true;
                break;
            case "v2":
                require ("api/api_v2.php");
                $this->api = new api_v2();
                return true;
                break;
            default:
                require ("api/api.php");
                $this->api = new api_base();
                return false;
                break;
        }
    }
}