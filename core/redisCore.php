<?php
class redisCore{
    private static $obj = null;

    public function __construct($mode='read'){
        $config = configCore::get('redis', $mode);
        if(is_null(self::$obj)){
            self::$obj = new Redis();
            self::$obj->pconnect($config['host'], $config['port']);
            if($config['password']){
                self::$obj->auth($config['password']);
            }
            return self::$obj;
        } else{
            return self::$obj;
        }
    }

    public function __call($method, $arg){
        if (!method_exists(self::$obj, $method)) {
            throw new Exception("Class RedisCli not have method ($method) ");
        }
        return call_user_func_array(array(self::$obj, $method), $arg);
    }

}