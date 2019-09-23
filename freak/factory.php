<?php
class freak_factory{
    public static function getPDO($alias, $mode) {
        $config = freak_config::get('pdo', $alias)[$mode];
        return new freak_pdo($config['host'], $config['port'], $config['dbnane'], $config['user'], $config['password']);
    }

    public static function getRedis($alias, $mode) {
        $config = freak_config::get('redis', $alias)[$mode];
        return new freak_redis($config['host'], $config['port'], $config['password']);
    }
}