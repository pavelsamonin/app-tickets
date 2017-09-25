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

    function log($user, $action, $data)
    {
        $this->db->insert('logs', [
            'user' => $user,
            'action' => $action,
            'params' => $data
        ]);
    }

    function addCustomer($data)
    {
        $insert_id = null;
        if ($this->db->insert('customer', [
            'name' => $data['name'],
            'cnp' => $data['cnp']
        ]))
            $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function getCustomerById($id)
    {
        return $this->db
            ->get_where('customer', ['id' => $id])
            ->result();
    }

    function getAllCustomers()
    {
        return $this->db
            ->get('customer')
            ->result();
    }

    function getAllTransactions()
    {
        return $this->db
            ->get('transaction')
            ->result();
    }

    function getTransaction($data)
    {
        $where = [
            'customerId' => $data['customerId'],
            'id' => $data['transactionId']
        ];
        return $this->db
            ->get_where('transaction', $where)
            ->result();
    }

    function getTransactionById($id)
    {
        return $this->db
            ->get_where('transaction', ['id' => $id])
            ->result();
    }

    function getTransactionByFilter($data, $filter)
    {
        $result = $this->db
            ->get_where('transaction', [$data['column'] => $data['value']]);
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
        $insert_id = null;
        if ($this->db->insert('transaction', [
            'customerId' => $data['customerId'],
            'amount' => $data['amount']
        ]))
            $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function updateTransaction($data)
    {
        if ($this->db->update('transaction',
            [
                'amount' => $data['amount']
            ],
            ['id' => $data['id']]
        ))
            return true;
    }

    function deleteTransaction($id)
    {
        if ($this->db->delete('transaction', ['id' => $id]))
            return true;
    }

}