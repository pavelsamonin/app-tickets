<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 18.09.17
 * Time: 00:14
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('misoft');
    }

    function index()
    {

//        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
//        $server = $url["host"];
//        $username = $url["user"];
//        $password = $url["pass"];
//        $db = substr($url["path"], 1);
//        $conn = new mysqli($server, $username, $password, $db);
//        echo $server."<br>";
//        echo $username."<br>";
//        echo $password."<br>";
//        echo $db."<br>";

        $title = 'Show Customers';

        $result['customers'] = $this->misoft->getAllCustomers();
        $result['head'] = 'Customers table';
        $data = $this->load->view('isoft/customers', array('data' => $result), TRUE);

        var_dump($data);die;
        $this->load->view('isoft/header', array('title' => $title), TRUE);
        $this->load->view('isoft/main', array('content' => $data), TRUE);
        $this->load->view('isoft/footer');
    }

    function addCustomer($data){

        $this->load->view('isoft/add_customer',$result);
    }

}
