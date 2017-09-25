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
        $this->load->model('mlogin');
    }

    function index()
    {
        $post = $this->input->post();
        if(!$post)
            redirect(BASE_URL);
        if($user = $this->checkUser($post)){
            header("Location: /main/showTransactions");
            return;
        }
        redirect(BASE_URL);

    }

    function checkUser($data){
        $login = $data['login'];
        $password = md5($data['password']);
        if($user = $this->mlogin->login($login,$password))
            return $user;
        return false;
    }
}