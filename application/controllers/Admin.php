<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Admin
 */
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->authorize()) {
            redirect('404');
        }
    }

    /**
     * Basic auth
     *
     * @return bool
     */
    private function authorize()
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
            header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
            header("HTTP/1.0 401 Unauthorized");
            print "Oops! It require login to proceed further. Please enter your login detail\n";
            die();
        } else {
            $lp = $this->config->item('admin_login');

            if (trim($_SERVER['PHP_AUTH_PW']) !== '' && $_SERVER['PHP_AUTH_PW'] === $lp[$_SERVER['PHP_AUTH_USER']]) {
                return true;
            } else {
                header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
                header("HTTP/1.0 401 Unauthorized");
                print "Oops! It require login to proceed further. Please enter your login detail\n";
                die();
            }
        }
    }

}

