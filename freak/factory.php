<?php
defined('FREAK_ACCESS') or exit('Access Denied');
class freak_factory{
    protected static $connect = [];
    public static function getPDO($alias, $mode) {
        if(self::$connect['pdo'][$alias]) {return self::$connect['pdo'][$alias][$mode];}
        $config = freak_config::get('pdo', $alias)[$mode];
        $obj = new freak_pdo($config['host'], $config['port'], $config['dbname'], $config['user'], $config['password']);
        self::$connect['pdo'][$alias][$mode] = $obj;
        return $obj;
    }

    public static function getRedis($alias, $mode) {
        if(self::$connect['redis'][$alias]) {return self::$connect['redis'][$alias][$mode];}
        $config = freak_config::get('redis', $alias)[$mode];
        $obj = new freak_redis($config['host'], $config['port'], $config['password']);
        self::$connect['redis'][$alias][$mode] = $obj;
        return $obj;
    }
}