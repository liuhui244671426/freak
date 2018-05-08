<?php

define('PATH_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_ROOT.DS.'views');
define('PATH_CONFIG', PATH_ROOT.DS.'config');
define('PATH_DAEMON', PATH_ROOT.DS.'daemon');
defined('FREAK_ACCESS') or exit('Access Denied');
date_default_timezone_set('PRC');
error_reporting(E_ALL);
spl_autoload_register('f_auto_load');


function f_auto_load($class){
    $dir = '';
    $map = array(
        'Controller' => 'controllers',
        'Core' => 'core',
        'Model' => 'model',
        'Data' => 'data',
        'Lib' => 'lib',
        'Worker' => 'daemon',
    );
    foreach ($map as $k => $v) {
        if(strpos($class, $k) !== false){
            $dir = $v;break;
        }
    }
    $path = PATH_ROOT.DS.$dir.DS.str_replace('_','/',$class).'.php';

    if(file_exists($path)){
        include $path;
    } else {
        throw new Exception("path loaded failed, ".$path);
    }
}

###########router rule##############
#rule : module->controller->action
#     : m=index?c=index&a=index
####################################
function run(){
    //$module = explode('?', $_SERVER['REQUEST_URI'])[0];//取?前的目录
    //$module = ltrim($module, '/');
    $module = $_GET['m']   ?   $_GET['m'] :   'index';
    $controller = $_GET['c']    ?   $_GET['c']  :   'index';
    $action = $_GET['a']    ?   $_GET['a']  :   'index';
    $exec_class = $module.'_'.$controller."Controller";

    try{
        $obj = new $exec_class();
        $obj->$action();
    } catch (Exception $e){
        exit($e->getTraceAsString());
    }
}