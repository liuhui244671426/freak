<?php
class lib_ssoClient{
    public static function is_login($callback=''){
        $token = lib_request::get('string', 'token');
        if($token){
            $bool = self::check_token($token);
            if($bool && empty($_COOKIE['token'])){
                setcookie('token', $token, configCore::get('sso', 'cookie_expire'));
            }
            return $bool;
        } else {
            if(empty($_COOKIE['token'])){
                $url = configCore::get('sso', 'server');
                $callback = ($callback == '')   ?   self::current_url() :   $callback;
                header("Location: {$url['url_login']}&callback=".urlencode($callback));
                exit;
            } else {
                return true;
            }
        }
    }

    public static function check_token($token){
        $curl = curlLib::init();
        $url = configCore::get('sso', 'server')['url_check'];
        $ret = $curl->url("{$url}&token=".$token)->data();
        if($ret == 'ok'){
            return true;
        }
        return false;
    }

    public static function current_url(){
        return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
}