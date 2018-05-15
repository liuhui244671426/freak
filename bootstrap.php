<?php
define('PATH_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_ROOT.DS.'views');
define('PATH_CONFIG', PATH_ROOT.DS.'config');
define('PATH_DAEMON', PATH_ROOT.DS.'daemon');
define('PATH_PUBLIC', PATH_ROOT.DS.'public');
defined('FREAK_ACCESS') or exit('Access Denied');
date_default_timezone_set('PRC');
error_reporting(E_ALL);
spl_autoload_register('f_auto_load');
set_error_handler('f_error_handler');

function f_auto_load($class){
    $path = PATH_ROOT.DS.str_replace('_',DS,$class).'.php';

    if(file_exists($path)){
        include $path;
    } else {
        throw new Exception("path loaded failed, ".$path);
    }
}
function f_error_handler($errno, $errstr, $errfile, $errline){
    if(0 === error_reporting() || 30711 === error_reporting()){return false;}
    $msg = "ERROR";
    if($errno == E_WARNING)$msg = "WARNING";
    if($errno == E_NOTICE)$msg = "NOTICE";
    if($errno == E_STRICT)$msg = "STRICT";
    if($errno == 8192)$msg = "DEPRECATED";

    core_log::write("$msg: $errstr in $errfile on line $errline");
}
###########router rule##############
#rule : module->controller->action
#     : m=index?c=index&a=index
####################################
function run(){
    //$module = explode('?', $_SERVER['REQUEST_URI'])[0];//取?前的目录
    //$module = ltrim($module, '/');
    $m = filter_input(INPUT_GET, 'm');
    $c = filter_input(INPUT_GET, 'c');
    $a = filter_input(INPUT_GET, 'a');
    $module = $m?$m:'index';
    $controller = $c?$c:'index';
    $action = $a?$a:'index';

    $exec_class = 'controller'.'_'.$module.'_'.$controller;
    try{
        $obj = new $exec_class();
        $obj->$action();
    } catch (Exception $e){
        exit($e->getTraceAsString());
    }
}