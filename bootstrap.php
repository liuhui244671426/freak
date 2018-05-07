<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
error_reporting(E_ALL);

define('VERSION', "0.0.1");
define('FREAK_ACCESS', true);
define('PATH_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_ROOT.DS.'views');
define('PATH_CONFIG', PATH_ROOT.DS.'config');
define('PATH_DAEMON', PATH_ROOT.DS.'daemon');

spl_autoload_register('f_auto_load');

//version: 0.0.2
function f_auto_load($class){
    $dir = '';
    $map = array(
        'Controller' => 'controllers',
        'Model' => 'model',
        'Core' => 'core',
        'Data' => 'data',
        'Lib' => 'lib',
        'Worker' => 'daemon'.DS.'workers',
    );
    foreach ($map as $k => $v) {
        if(strpos($class, $k) !== false){
            $dir = $v;
        }
    }
    $path = PATH_ROOT.DS.$dir.DS.$class.'.php';
    if(file_exists($path)){
        include $path;
    } else {
        throw new Exception("path loaded failed, ".$path);
    }
}