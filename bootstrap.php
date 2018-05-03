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
    $dir = '';
    if(strpos($class, 'Controller') !== false) {
        $dir = 'controllers';
    } elseif(strpos($class, 'Model') !== false) {
        $dir = 'model';
    } elseif(strpos($class, 'Core') !== false) {
        $dir = 'core';
    } elseif(strpos($class, 'Data') !== false) {
        $dir = 'data';
    } elseif(strpos($class, 'Lib') !== false) {
        $dir = 'lib';
    } elseif(strpos($class, 'Worker') !== false){
        $dir = 'daemon'.DS.'workers';
    } else {
        //
    }
    $path = PATH_ROOT.DS.$dir.DS.$class.'.php';
    //print_r($path);
    if(file_exists($path)){
        include $path;
    } else {
        throw new Exception("path loaded failed, ".$path);
    }
}