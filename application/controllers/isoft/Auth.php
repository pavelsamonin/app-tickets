<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 16:04
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/isoft/Rest.php';
class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('misoft');
        $this->load->model('mauth');
        $this->load->library('jwt');
        var_dump(222);die;
        $this->rest = new Rest;
    }

    public function __call($method, $args)
    {
        $this->rest->$method($args[0]);
    }

    function index()
    {
        $post = $this->input->post();
        $headers = $this->input->request_headers();
        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                $user = null;
                if ($token->id) {
                    $user = $this->mauth->getUserById($token->id);
                    $this->misoft->log($user->login, 'authorize', json_encode($user));
                    $this->set_response('authorized', Rest::HTTP_OK);
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
        if(!$post)
            return false;
        if($this->token_post($post))
            redirect('/main/showTransactions');
    }

    function checkUser($data){

    }

    public function token_post()
    {
        $dataPost = $this->input->post();
        $user = $this->mauth->login($dataPost['username'], $dataPost['password']);
        if ($user != null) {
            $tokenData = array();
            $tokenData['id'] = $user->id;
            $response['token'] = Authorization::generateToken($tokenData);
            $this->rest->set_response($response, Rest::HTTP_OK);
            return;
        }
        $response = [
            'status' => Rest::HTTP_UNAUTHORIZED,
            'message' => 'Unauthorized',
        ];
        $this->rest->set_response($response, Rest::HTTP_UNAUTHORIZED);
    }
}