<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 15:58
 */
class Mlogin extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($username, $password) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('login', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $result = $query->result();
            return $result[0]->login;
        }
        return false;
    }
}
