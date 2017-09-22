<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 15:10
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('misoft');
    }

    function index($post)
    {
        if(!$post)
            redirect('/isoft/main');
        if($user = $this->checkUser($post))
            redirect('/isoft/main/showTransactions/'.$user);
        redirect('/isoft/main');

    }

    function checkUser($data){
        $login = $data['login'];
        $password = md5($data['password']);
        if($user = $this->misoft->login($login,$password))
            return $user;
        return false;
    }
}