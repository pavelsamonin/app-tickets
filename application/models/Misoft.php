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

    function addCustomer($data)
    {
        if($this->db->insert('customer', [
            'name' => $data['name'],
            'cnp' => $data['cnp']
        ]))
            return true;
    }

    function getAllCustomers()
    {
        return $this->db
            ->get('customer')
            ->result_array();
    }

    function getAllTransactions()
    {
        return $this->db
            ->get('transaction')
            ->result_array();
    }

    function getTransaction($data)
    {
        return $this->db
            ->get_where('transaction', array($data['column'] => $data['value']))
            ->result();
    }

    function getTransactionByFilter($data, $filter)
    {
        $result = $this->db
            ->get_where('transaction', array($data['column'] => $data['value']));
        if (@$filter['limit']) {
            if (@$filter['offset']) {
                $result = $result->limit(@$filter['limit'], @$filter['offset']);
            }
            $result = $result->limit(@$filter['limit']);
        }
        $result = $result->result();
        return $result;
    }

    function addTransaction($data)
    {
        if($this->db->insert('transaction', [
            'customerId' => $data['customerId'],
            'amount' => $data['amount'],
            'date' => $data['date']
        ]))
            return true;
    }

    function updateTransaction($data)
    {
        if($this->db->update('transaction',
            [
                'customerId' => $data['customerId'],
                'amount' => $data['amount'],
                'date' => $data['date']
            ],
            ['id' => $data['id']]
        ))
            return true;
    }

}