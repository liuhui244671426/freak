<?php
class ssoClientLib{
    public static function is_login($callback=''){
        $token = requestLib::get('string', 'token');
        if($token){
            $bool = self::check_token($token);
            if($bool){
                setcookie('token', $token);
            }
            return $bool;
        }else {
            if(empty($_COOKIE['token'])){
                $url = configCore::get('sso', 'server');
                header("Location: {$url['url_login']}&callback={$callback}");
                exit;
            }
            else {
                return true;
            }
        }
    }

    public static function check_token($token){
        $curl = curlLib::init();
        $url = configCore::get('sso', 'server');
        $ret = $curl->url("{$url['url_check']}&token=".$token);
        if($ret == 'ok'){
            return true;
        }
        return false;
    }

    public static function current_url(){
        return $_SERVER['SERVER_PROTOCOL']."://".$_SERVER['REMOTE_HOST'].$_SERVER['REQUEST_URI'];
    }
}