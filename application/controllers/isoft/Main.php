<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 18.09.17
 * Time: 00:14
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller
{
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

        $title = 'Login Page';
        $result['head'] = 'Login Page';
        $data = $this->load->view('isoft/login', array('data' => $result), TRUE);
        $this->load->view('isoft/header', array('title' => $title));
        $this->load->view('isoft/main', array('content' => $data));
        $this->load->view('isoft/footer');

    }

    function showCustomers(){
        $title = 'Show Customers';
        $result['customers'] = $this->misoft->getAllCustomers();
        $result['head'] = 'Customers table';
        $data = $this->load->view('isoft/customers', array('data' => $result), TRUE);
        $data .= $this->load->view('isoft/add_customer');
        $this->load->view('isoft/header', array('title' => $title));
        $this->load->view('isoft/main', array('content' => $data));
        $this->load->view('isoft/footer');
    }

    function addCustomer($data)
    {
        $this->misoft->addCustomer($data);
    }

    function showTransactions($user)
    {
        $title = 'Show Transactions';
        $result['transactions'] = $this->misoft->getAllTransactions();
        $result['head'] = 'Transactions table';
        $result['user'] = $user;
        $data = $this->load->view('isoft/transactions', array('data' => $result), TRUE);
        $data .= $this->load->view('isoft/add_transaction');
        $this->load->view('isoft/header', array('title' => $title));
        $this->load->view('isoft/main', array('content' => $data));
        $this->load->view('isoft/footer');
    }

    function addTransaction($data)
    {
        $this->misoft->addTransaction($data);
    }
}
