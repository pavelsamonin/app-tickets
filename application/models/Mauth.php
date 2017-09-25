<?php

/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 23.09.17
 * Time: 16:59
 */
class Mauth extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {
    }

    function login($username, $password)
    {
        $condition = [
            'login' => $username,
            'password' => md5($password),
        ];
        $user = $this->db
            ->get_where('users', $condition)
            ->row();
        return $user;
    }
    function getUserById($id){
        $user = $this->db
            ->get_where('users', ['id' => $id])
            ->row();
        return $user;
    }
}