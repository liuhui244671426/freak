<?php
error_reporting(E_ALL);
date_default_timezone_set('PRC');
//--------CONST-----------
define('PATH_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_ROOT.DS.'views');
define('PATH_CONFIG', PATH_ROOT.DS.'config');
define('PATH_DAEMON', PATH_ROOT.DS.'daemon');
define('PATH_PUBLIC', PATH_ROOT.DS.'public');
defined('FREAK_ACCESS') or exit('Access Denied');
//--------CONST-----------

//--------register-----------
spl_autoload_register('f_auto_load');
set_error_handler('f_error_handler');
//--------register-----------

//--------session-----------
$session = new lib_session();
session_set_save_handler($session, true);
session_start();
//--------session-----------

function f_auto_load($class){
    $path = PATH_ROOT.DS.str_replace('_',DS,$class).'.php';
    if(!file_exists($path)){
        throw new Exception("file : {$path}, NOT EXIST");
    }
    include $path;
    return true;
}
function f_error_handler($errno, $errstr, $errfile, $errline){
    if(0 === error_reporting() || 30711 === error_reporting()){return false;}
    $msg = "ERROR";
    if($errno == E_WARNING)$msg = "WARNING";
    if($errno == E_NOTICE)$msg = "NOTICE";
    if($errno == E_STRICT)$msg = "STRICT";
    if($errno == 8192)$msg = "DEPRECATED";
    core_log::write("$msg: $errstr in $errfile on line $errline");
    return true;
}
###########router rule##############
#rule : module->controller->action
#     : m=index?c=index&a=index
####################################
function run(){
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