<?php
defined('FREAK_ACCESS') or exit('Access Denied');
class lib_ssoClient{
    public static function is_login($callback=''){
        $token = lib_filter::strGet('token');
        if($token){
            //首次回调回来,带了 token
            $user_info = self::get_user_info_by_token($token);//去 server 端检测 token 是否合法
            if(!empty($user_info) && empty($_COOKIE['token'])){
                setcookie('token', $token, freak_config::get('sso', 'cookie_expire'));//埋 token
            }
            return $user_info;
        } else {
            $token = $_COOKIE['token'];
            if(empty($token)){
                //去 server 的登陆页面
                self::jump_login($callback);
            } else {
                //已经埋了 token, 拿 token 换user information
                $user_info = self::get_user_info_by_token($token);
                if(empty($user_info)){
                    self::jump_login($callback);
                }
            }
        }
    }

    public static function get_user_info_by_token($token){
        $curl = lib_curl::init();
        $url = freak_config::get('sso', 'server')['url_check'];
        $ret = $curl->url("{$url}&token=".$token)->data();
        $ret = json_decode($ret, 1);

        if($ret['code'] == 10000){
            return $ret['data'];
        }
        return array();
    }

    private static function jump_login($callback){
        $url = freak_config::get('sso', 'server');
        $callback = ($callback == '')   ?   lib_helper::current_url() :   $callback;
        header("Location: {$url['url_login']}&callback=".urlencode($callback));
        exit;
    }
}