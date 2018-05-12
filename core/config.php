<?php
/**
 */
defined('FREAK_ACCESS') or exit('Access Denied');

class core_config{
    public static function get($file, $filed=''){
        $ini = include PATH_CONFIG.DS.$file.'.php';
        return ($filed)?$ini[$filed]:$ini;
    }
}