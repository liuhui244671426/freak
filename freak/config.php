<?php
/**
 */
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_config{
    public static function get($file, $filed=''){
        $ini = include PATH_CONFIG.DS.$file.'.php';

        //注: ENV_CONFIG 变量需配置到 nginx || fpm || apache 中
        $env_config = $_SERVER['ENV_CONFIG'] ? $_SERVER['ENV_CONFIG'] : 'develop';//'develop' || 'product'
        $ini = $ini[$env_config];//读取不同环境的配置文件
        return ($filed)?$ini[$filed]:$ini;
    }
}