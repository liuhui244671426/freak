<?php
/**
 */
defined('FREAK_ACCESS') or exit('Access Denied');

class core_config{
    public static function get($file, $filed=''){
        $ini = include PATH_CONFIG.DS.$file.'.php';
        $ini = $ini[ENV_CONFIG];//读取不同环境的配置文件
        return ($filed)?$ini[$filed]:$ini;
    }
}