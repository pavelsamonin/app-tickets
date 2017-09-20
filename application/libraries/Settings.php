<?php

class Settings
{
    const TABLE = DB_SETTINGS;
    private $data;
    private $CI;

    function __construct()
    {
        $this->CI = &get_instance();

        $q = $this->CI->s->from(self::TABLE)->select()->many();
        foreach ($q as $row_value) {
            $this->data[$row_value['key']] = ($row_value['value']);
        }
    }

    function __get($key)
    {
        return $this->data[$key];
    }

    /**  As of PHP 5.1.0  */
    function __isset($key)
    {
        return isset($this->data[$key]);
    }
}

?>