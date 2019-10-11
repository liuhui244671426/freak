<?php
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_config{
    public static $config = [];

    public static function get($file, $filed=''){
        $env_config = $_SERVER['ENV_CONFIG'] ? $_SERVER['ENV_CONFIG'] : 'develop';//'develop' || 'product'
        $path = PATH_CONFIG.DS.$file.'.'.$env_config.'.php';
        if(!file_exists($path)){
            throw new Exception("get config : {$file}.{$env_config}.php not exists");
        }
        $ini = include $path;
        $c = ($filed)?$ini[$filed]:$ini;
        return $c;
    }
}