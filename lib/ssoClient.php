<?php
defined('FREAK_ACCESS') or exit('Access Denied');
class lib_ssoClient{
    public static function is_login($callback=''){
        $token = lib_filter::strGet('token');
        if($token){
            $bool = self::check_token($token);
            if($bool && empty($_COOKIE['token'])){
                setcookie('token', $token, freak_config::get('sso', 'cookie_expire'));
            }
            return $bool;
        } else {
            if(empty($_COOKIE['token'])){
                $url = freak_config::get('sso', 'server');
                $callback = ($callback == '')   ?   lib_helper::current_url() :   $callback;
                header("Location: {$url['url_login']}&callback=".urlencode($callback));
                exit;
            } else {
                return true;
            }
        }
    }

    public static function check_token($token){
        $curl = lib_curl::init();
        $url = freak_config::get('sso', 'server')['url_check'];
        $ret = $curl->url("{$url}&token=".$token)->data();
        if($ret == 'ok'){
            return true;
        }
        return false;
    }


}