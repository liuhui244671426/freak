<?php

/**
 * @file
 *
 * CSRF
 */

class lib_csrf
{
    public static function getHiddenInputString()	{
        $uuid = microtime(true)*10000;
        return sprintf('<input type="hidden" name="_csrf_token_uuid" value="%s"/><input type="hidden" name="_csrf_token" value="%s"/>', $uuid, self::generate_token($uuid));
    }

    public static function generate_token($uuid)
    {
        $token = sha1( $uuid.'20180511'.helperLib::getip());
        return $token;
    }

    public static function check_token($uuid, $token){
        $n_token = sha1( $uuid.'20180511'.helperLib::getip());
        if($n_token == $token){
            return true;
        } else {
            return false;
        }
    }
}