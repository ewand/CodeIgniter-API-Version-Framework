<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index() {
        //XML Test
        /*
         * $data_string = "<?xml version=\"1.0\"?><input><apikey>TEST</apikey></input>";
         * $ch = curl_init('http://'.$_SERVER['SERVER_NAME'].'/api/v1/method1');
         */

        //XML Text with GET apikey parameter
        /*
         * $data_string = "<?xml version=\"1.0\"?><input><person>Person 1</person></input>";
         * $ch = curl_init('http://'.$_SERVER['SERVER_NAME'].'/api/v1/method1?apikey=TEST');
         */

        //JSON Test
        $data = array("apikey" => "TEST", "output_type" => "XML");
        $data_string = json_encode($data);
        $ch = curl_init('http://'.$_SERVER['SERVER_NAME'].'/api/v1/method1');


        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '192.168.3.3:8888');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: text/xml',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        echo $result;
    }
}