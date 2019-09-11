<?php
defined('FREAK_ACCESS') or exit('Access Denied');
class freak_redis{
    private static $rds = null;
    public static $read = 'read';
    public static $write = 'write';
    public function __construct($mode='read',$alias='freak'){
        $config = freak_config::get('redis', $alias);
        $config = $config[$mode];
        if(is_null(self::$rds)){
            self::$rds = new Redis();
            self::$rds->pconnect($config['host'], $config['port']);
            if($config['password']){
                self::$rds->auth($config['password']);
            }
            return self::$rds;
        } else{
            return self::$rds;
        }
    }

    public function __call($method, $arg){
        if (!method_exists(self::$rds, $method)) {
            throw new Exception("Class RedisCli not have method ($method) ");
        }
        return call_user_func_array(array(self::$rds, $method), $arg);
    }

}