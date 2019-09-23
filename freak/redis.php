<?php
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_redis
{
    private static $rds = null;

    public function __construct($host, $port, $password)
    {
        if (is_null(self::$rds)) {
            self::$rds = new Redis();
            self::$rds->pconnect($host, $port);
            if ($password) {
                self::$rds->auth($password);
            }
            return self::$rds;
        } else {
            return self::$rds;
        }
    }

    public function __call($method, $arg)
    {
        if (!method_exists(self::$rds, $method)) {
            throw new Exception("Class RedisCli not have method ($method) ");
        }
        return call_user_func_array(array(self::$rds, $method), $arg);
    }

}