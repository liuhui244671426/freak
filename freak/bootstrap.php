<?php
$_SERVER['ENV_CONFIG'] = 'develop';//配置文件
if(version_compare(PHP_VERSION, '5.6.0') < 0){ exit('PHP版本需要大于5.6.0'); }

error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('PRC');
//--------CONST-----------
define('PATH_ROOT', dirname(dirname(__FILE__)));
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
register_shutdown_function('f_last_error');
//--------register-----------

//--------session-----------
if(PHP_SAPI != 'cli'){
    if($_COOKIE[session_name()] == '') { session_id(microtime(true)*10000); }
    $session = new lib_session();
    session_set_save_handler($session, true);
    session_start();
    setcookie(session_name(), session_id(), time()+86400);//expire time和redis ttl 一致
}
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
    freak_log::write("$msg: $errstr in $errfile on line $errline");
    return true;
}
function f_last_error(){
    $e = error_get_last();
    if($e) freak_log::write("ERROR message: {$e['message']},type: {$e['type']}, in file: {$e['file']} on line:{$e['line']}");
    return true;
}
###########router rule##############
#rule : module->controller->action
#     : m=index?c=index&a=index
####################################
function run(){
    if(PHP_SAPI == 'cli'){return true;}
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
    return true;
}
run();
return;