<?php
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_config{
    public static $config = [];

    public static function get($file, $filed=''){
        $env = ENV ? ENV : 'develop';//'develop' || 'product'
        $path = PATH_CONFIG.DS.$file.'.'.$env.'.php';
        if(!file_exists($path)){
            throw new Exception("get config : {$file}.{$env}.php not exists");
        }
        $ini = include $path;
        $c = ($filed)?$ini[$filed]:$ini;
        return $c;
    }
}