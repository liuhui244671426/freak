<?php

/**
 * @file
 *
 * CSRF
 */
defined('FREAK_ACCESS') or exit('Access Denied');
class lib_csrf
{
    const KEY = 'Freak_frameworkv1.4.0_on-20180511';
    public static function getHiddenInputString($session_data)	{
        return sprintf('<input type="hidden" name="_csrf_token_data" value="%s"/><input type="hidden" name="_csrf_token" value="%s"/>', $session_data, self::generate_token($session_data));
    }

    public static function generate_token($data)
    {
        $token = sha1($data.self::KEY);
        return $token;
    }

    public static function check_token($session_data, $token){
        $n_token = sha1($session_data.self::KEY);
        if($n_token == $token){
            return true;
        } else {
            return false;
        }
    }
}