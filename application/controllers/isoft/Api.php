<?php

/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 23.09.17
 * Time: 16:14
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/Rest.php';

class Api extends CI_Controller
{
    var $rest;

    function __construct()
    {
        parent::__construct();
        $this->load->model('misoft');
        $this->load->model('mauth');
        $this->rest = new Rest;
    }

    public function __call($method, $args)
    {
        $this->rest->$method($args[0]);
    }

    function addCustomer()
    {
        $headers = $this->input->request_headers();
        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                $user = null;
                if ($token->id) {
                    $dataPost = $this->input->post();
                    $user = $this->mauth->getUserById($token->id);
                    $id = $this->misoft->addCustomer($dataPost);
                    $customer = $this->misoft->getCustomerById($id);
                    $this->misoft->log($user->login, 'addCustomer', json_encode($customer));
                    if ($id != false) {
                        $this->set_response($id, Rest::HTTP_OK);
                    }
                    return;
                }
            }
            $response = [
                'status' => Rest::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized',
            ];
            $this->set_response($response, Rest::HTTP_UNAUTHORIZED);
            return;
        }
        $response = [
            'status' => Rest::HTTP_FORBIDDEN,
            'message' => 'Forbidden',
        ];
        $this->set_response($response, Rest::HTTP_FORBIDDEN);
    }

    function addTransaction()
    {
        $headers = $this->input->request_headers();
        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                $user = null;
                if ($token->id) {
                    $dataPost = $this->input->post();
                    $user = $this->mauth->getUserById($token->id);
                    $id = $this->misoft->addTransaction($dataPost);
                    $transaction = $this->misoft->getTransactionById($id);
                    $this->misoft->log($user->login, 'addTransaction', json_encode($transaction));
                    if ($id != false) {
                        $this->set_response($transaction, Rest::HTTP_OK);
                    }
                    return;
                }
            }
            $response = [
                'status' => Rest::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized',
            ];
            $this->set_response($response, Rest::HTTP_UNAUTHORIZED);
            return;
        }
        $response = [
            'status' => Rest::HTTP_FORBIDDEN,
            'message' => 'Forbidden',
        ];
        $this->set_response($response, Rest::HTTP_FORBIDDEN);
    }

    function getAllTransactions()
    {
        $headers = $this->input->request_headers();
        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                $user = null;
                if ($token->id) {
                    $user = $this->mauth->getUserById($token->id);
                    $todo = $this->misoft->getAllTransactions();
                    $this->misoft->log($user->login, 'getAllTransactions', json_encode($todo));
                    $this->rest->set_response($todo, Rest::HTTP_OK);
                }
                return;
            }
        }
        $response = [
            'status' => Rest::HTTP_FORBIDDEN,
            'message' => 'Forbidden',
        ];
        $this->rest->set_response($response, Rest::HTTP_FORBIDDEN);
    }

    function getTransaction()
    {
        $headers = $this->input->request_headers();
        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                $user = null;
                if ($token->id) {
                    $dataGet = $this->input->get();
                    $user = $this->mauth->getUserById($token->id);
                    $data = [
                        'customerId' => $dataGet['customerId'],
                        'transactionId' => $dataGet['transactionId']
                    ];
                    $todo = $this->misoft->getTransaction($data);
                    $this->misoft->log($user->login, 'getTransaction', json_encode($todo));
                    $this->rest->set_response($todo, Rest::HTTP_OK);
                }
                return;
            }
        }
        $response = [
            'status' => Rest::HTTP_FORBIDDEN,
            'message' => 'Forbidden',
        ];
        $this->rest->set_response($response, Rest::HTTP_FORBIDDEN);
    }

    function getTransactionBy()
    {
        /*TODO
         * Request:​customerId, amount, date, offset, limit
           Response:​an array of transactions
        */
    }

    function updateTransaction()
    {
        $headers = $this->input->request_headers();
        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                $user = null;
                if ($token->id) {
                    $put_data = (object)[];
                    $this->http_method_data($put_data);
                    $dataGet = (array)$put_data;
                    $user = $this->mauth->getUserById($token->id);
                    $data = [
                        'amount' => $dataGet['amount'],
                        'id' => $dataGet['transactionId']
                    ];
                    if ($this->misoft->updateTransaction($data)) {

                        $transaction = $this->misoft->getTransactionById($dataGet['transactionId']);
                        $this->misoft->log($user->login, 'updateTransaction', json_encode($transaction));
                        $this->rest->set_response($transaction, Rest::HTTP_OK);
                        return;
                    }
                    $this->rest->set_response('Couldn\'t update the transaction...', Rest::HTTP_INTERNAL_SERVER_ERROR);
                    return;
                }
            }
        }
        $response = [
            'status' => Rest::HTTP_FORBIDDEN,
            'message' => 'Forbidden',
        ];
        $this->rest->set_response($response, Rest::HTTP_FORBIDDEN);
    }

    function deleteTransaction()
    {
        $headers = $this->input->request_headers();
        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                $user = null;
                if ($token->id) {
                    $put_data = (object)[];
                    $this->http_method_data($put_data);
                    $dataGet = (array)$put_data;
                    $user = $this->mauth->getUserById($token->id);
                    if ($this->misoft->deleteTransaction($dataGet['transactionId'])) {
                        $this->misoft->log($user->login, 'deleteTransaction', json_encode($dataGet['transactionId']));
                        $this->rest->set_response('success', Rest::HTTP_OK);
                        return;
                    }
                    $this->rest->set_response('fail', Rest::HTTP_INTERNAL_SERVER_ERROR);
                    return;
                }
            }
        }
        $response = [
            'status' => Rest::HTTP_FORBIDDEN,
            'message' => 'Forbidden',
        ];
        $this->rest->set_response($response, Rest::HTTP_FORBIDDEN);
    }

    function http_method_data(\stdClass &$a_data)
    {
        $input = file_get_contents('php://input');
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        if ($matches) {
            $boundary = $matches[1];
            $a_blocks = preg_split("/-+$boundary/", $input);
            array_pop($a_blocks);
        } else {
            parse_str($input, $a_blocks);
        }

        foreach ($a_blocks as $id => $block) {
            if (empty($block))
                continue;


            if (strpos($block, 'application/octet-stream') !== FALSE) {
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            } else {
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
            }
            if ($matches) {
                $a_data->{$matches[1]} = $matches[2];
            } else {
                $a_data->{$id} = $block;
            }
        }
    }
}
