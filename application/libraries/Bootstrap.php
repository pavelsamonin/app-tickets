<?php

class Bootstrap
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->loadSparrow();
    }

    /**
     * Load sparrow library
     *
     * @throws Exception
     * @return void
     */
    public function loadSparrow()
    {
        $sparrow_path = APPPATH . 'libraries/Sparrow.php';
        if (
            !file_exists($db_config_path = APPPATH . 'config/database.php')
        ) {
            throw new Exception('The configuration file database.php does not exist.');
        }

        if(!file_exists($sparrow_path)){
            throw new Exception('The library file Sparrow.php does not exist.');
        }

        include_once($sparrow_path);
        include_once($db_config_path);
        $this->CI->s = new Sparrow();
        $this->CI->s->setDb([
            'type' => 'mysqli',
            'hostname' => $db['default']['pconnect'] === true ? ('p:' . $db['default']['hostname']) : ($db['default']['hostname']),
            'database' => $db['default']['database'],
            'username' => $db['default']['username'],
            'password' => $db['default']['password'],
        ]);
        $this->CI->s->sql('SET NAMES utf8')->execute();
    }
}
?>