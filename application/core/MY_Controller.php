<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    const HTTP_OK = 200;
    const HTTP_BAD = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_SERVER_ERROR = 500;

    public function __construct(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }

        parent::__construct();

        if($this->config->item('global_site_closed'))
        {
            if(!in_array($this->input->ip_address(),$this->config->item('allowed_ip_for_works'))){
                $this->load->view('closed');
            }
        }
    }


    public function response($data = array(), $http_code = NULL)
    {
        if (empty($data) && $http_code === NULL) {
            $http_code = 404;
        }
        $output = json_encode($data);
        header('HTTP/1.1: ' . $http_code);
        header('Status: ' . $http_code);
        if ($http_code !== 200)
            header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Content-Length: ' . strlen($output));
        exit($output);
    }

	public function __destruct() {
	//	$this->db->close();

	}
}