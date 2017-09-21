<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 21.09.17
 * Time: 11:50
 */
class Misoft extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function addCustomer($data){

    }
    function getTransaction($data){
        return $this->db
            ->get('transaction')
            ->result();
    }
    function getTransactionByFilter($data,$filter){

    }
    function addTransaction($data){

    }
    function updateTransaction($data){

    }

}