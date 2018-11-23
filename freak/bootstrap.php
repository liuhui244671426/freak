<?php
/**
 * 本文件是核心文件, web 和 cli 模式均需要加载本文件
 * web 不需要手动加载,系统已经处理
 * cli 必须手动加载
 * */

$_SERVER['ENV_CONFIG'] = 'develop';//配置文件需要的环境
if(version_compare(PHP_VERSION, '5.6.0') < 0){ exit('PHP版本需要大于5.6.0'); }

error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('PRC');

//--------CONST--------------
define('PATH_ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_ROOT.DS.'views');
define('PATH_CONFIG', PATH_ROOT.DS.'config');
define('PATH_DAEMON', PATH_ROOT.DS.'daemon');
define('PATH_PUBLIC', PATH_ROOT.DS.'public');
defined('FREAK_ACCESS') or exit('Access Denied');
//--------CONST--------------

//--------register-----------
spl_autoload_register('f_auto_load');
set_error_handler('f_error_handler', E_ALL|E_STRICT);
set_exception_handler('f_last_error');
register_shutdown_function('f_last_error');
//--------register-----------

//web interface
if(PHP_SAPI != 'cli'){
    //--------session-----------
    if($_COOKIE[session_name()] == '') { session_id(microtime(true)*10000); }
    $session = new lib_session();
    session_set_save_handler($session, true);
    session_start();
    setcookie(session_name(), session_id(), time()+86400);//expire time和redis ttl 一致
    //--------session-----------


    ###########router rule##############
    #rule : module->controller->action
    #     : m=index?c=index&a=index
    ####################################
    $m = filter_input(INPUT_GET, 'm');
    $c = filter_input(INPUT_GET, 'c');
    $a = filter_input(INPUT_GET, 'a');
    $module = $m?$m:'index';
    $controller = $c?$c:'index';
    $action = $a?$a:'index';

    $exec_class = 'fpm'.'_'.$module.'_'.$controller;
    try{
        $obj = new $exec_class();
        $obj->$action();
    } catch (Throwable $e){
        freak_log::write($e->getTraceAsString());
        freak_log::write($e->getMessage());
        exit();
    }
}
return true;

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
    if($errno == E_STRICT)$msg = "STRICT";//严格
    if($errno == E_DEPRECATED)$msg = "DEPRECATED"; //过时遗弃
    freak_log::write("$msg: $errstr in $errfile on line $errline");
    return true;
}
function f_last_error(){
    $e = error_get_last();
    if($e) freak_log::write("ERROR message: {$e['message']},type: {$e['type']}, in file: {$e['file']} on line:{$e['line']}");
    return true;
}
function f_exception($e){
    freak_log::write("EXCEPTION: {$e->getTraceAsString()}");
    return true;
}