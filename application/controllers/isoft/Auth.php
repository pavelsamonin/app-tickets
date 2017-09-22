<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 16:04
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('misoft');
    }

    function index($post)
    {
        if(!$post)
            return false;
        if($this->checkUser($post))
            redirect('/main/showTransactions');
    }

    function checkUser($data){

    }
}