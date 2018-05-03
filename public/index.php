<?php
/**
 * freak.framework
 *
 */
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
error_reporting(E_ALL);

define('VERSION', "0.0.1");
define('FREAK_ACCESS', true);
define('PATH_PUBLIC', __DIR__);
define('PATH_ROOT', dirname(PATH_PUBLIC));
define('DS', DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_ROOT.DS.'views');
define('PATH_CONFIG', PATH_ROOT.DS.'config');
define('PATH_DAEMON', PATH_ROOT.DS.'daemon');

//xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);

$controller = $_GET['c']."Controller";
$method = $_GET['m'];
spl_autoload_register('f_auto_load');

try{
    $obj = new $controller();
    $obj->$method();
} catch (Exception $e){
    exit("system error");
}

//$xhprof_data = xhprof_disable();
//$xhprof_root = "/Users//vagrant/htdocs/xhprof";
//include_once $xhprof_root."/xhprof_lib/utils/xhprof_lib.php";
//include_once $xhprof_root."/xhprof_lib/utils/xhprof_runs.php";
//$xhprof_runs = new XHProfRuns_Default();
//$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_freak");
//$u = 'http://xhprof.com/?run=' . $run_id . '&source=xhprof_freak';
//echo "<a href='{$u}' target='_blank'>xhprof</a>";
return;
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