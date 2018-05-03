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

//version: 0.0.1
function f_auto_load($class){
    $path = '';
    if(strpos($class, 'Controller') !== false) {
        $path =  PATH_ROOT.DS."controllers".DS.$class.".php";
    } elseif(strpos($class, 'Model') !== false) {
        $path =  PATH_ROOT.DS."model".DS.$class.".php";
    } elseif(strpos($class, 'Core') !== false) {
        $path = PATH_ROOT.DS."core".DS.$class.".php";
    } elseif(strpos($class, 'Data') !== false) {
        $path = PATH_ROOT.DS."data".DS.$class.".php";
    } elseif(strpos($class, 'Lib') !== false) {
        $path = PATH_ROOT.DS."lib".DS.$class.".php";
    } elseif(strpos($class, 'Worker') !== false){
        $path = PATH_DAEMON.DS.'workers'.DS.$class.'.php';
    } else {
        //
    }
    //print_r($path);
    if(file_exists($path)){
        include $path;
    } else {
        throw new Exception("path loaded failed, ".$path);
    }
}