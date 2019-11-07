<?php
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_config{

    public static function get($file, $filed=''){
        $generator_ = self::generator($file);
        $arr_ini = iterator_to_array($generator_, true);
        return ($filed)?$arr_ini[$filed]:$arr_ini;
    }

    protected static function generator($file){
        $env = defined('RUN_ENV') ? RUN_ENV :   'develop';
        $path = PATH_CONFIG.DS.$file.'.'.$env.'.php';
        if(!file_exists($path)){
            throw new Exception("get config : {$file}.{$env}.php not exists");
        }
        //wiki https://www.php.net/manual/en/language.generators.syntax.php
        yield from include $path;
    }
}