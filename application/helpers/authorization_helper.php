<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 23.09.17
 * Time: 17:48
 */
require_once APPPATH . 'libraries/Jwt.php';
class Authorization
{
    public static function validateToken($token)
    {
        $jwt = new JWT;
        $CI =& get_instance();
        $key = $CI->config->item('jwt_key');
        $algorithm = $CI->config->item('jwt_algorithm');
        return $jwt->decode($token, $key, array($algorithm));
    }
    public static function generateToken($data)
    {
        $jwt = new JWT;
        $CI =& get_instance();
        $key = $CI->config->item('jwt_key');
        $algorithm = $CI->config->item('jwt_algorithm');
        return $jwt->encode($data, $key, $algorithm);
    }
    public static function tokenIsExist($headers)
    {
        return (array_key_exists('Authorization', $headers)
            && !empty($headers['Authorization']));
    }
}