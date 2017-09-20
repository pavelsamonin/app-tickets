<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 18.09.17
 * Time: 00:14
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "iSoft dir Works!";
    }
}
