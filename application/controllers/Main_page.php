<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_page extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->lang->load(LANGUAGE_DEFAULT_FILE);
        $this->load->model(APPLICATION_CASES_MODEL,'cases_model');
        $this->load->model('user');
        //$this->load->model('top');
    }

	public function index()
	{
        $user = $this->user->get();
        $giveaway = null;
        $joined = null;

        $this->load->library('settings');
        if ($this->settings->GIVEAWAY == 1) {
            $this->load->model('Giveaway_model');
        }


		$this->load->view('main_page',['user' => $user]);
	}

	public function faq(){
		$this->load->view('faq',['user' => $this->user->get()]);
	}

    public function giveaway(){
        redirect($this->settings->GIVEAWAY_LINK);
    }


    public function tos()
    {
        $this->load->view('tos', ['user' => $this->user->get()]);
    }

    public function closed()
    {
        $this->load->view('closed');
    }

    public function login(){
        $params = $_GET;
        $this->user->go_to_auth($params);
    }

	public function logout(){
        $this->user->logout();
        redirect(site_url());
    }
}
