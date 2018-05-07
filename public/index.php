<?php
/**
 * freak.framework
 * @author: liuhui
 */

header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
error_reporting(E_ALL);
if(version_compare(PHP_VERSION, '5.6.0') < 0){ exit('PHP版本需要大于5.6.0'); }

include dirname(dirname(__FILE__)).'/bootstrap.php';

###########router rule##############
#rule : module->controller->action
#     : m=index?c=index&a=index
####################################

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
return;