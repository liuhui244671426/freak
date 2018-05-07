<?php
/**
 * freak.framework
 *
 */
$is_debug = false;

header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
error_reporting(E_ALL);

include dirname(dirname(__FILE__)).'/bootstrap.php';

if($is_debug) {
    xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
}

$module = explode('?', $_SERVER['REQUEST_URI'])[0];//取?前的目录
$module = ltrim($module, '/');
#router
#module->controller->action
#index?c=index&a=index
$module = $module   ?   $module :   'index';
$controller = $_GET['c']    ?   $_GET['c']  :   'index';
$action = $_GET['a']    ?   $_GET['a']  :   'index';

$exec_class = $module.'_'.$controller."Controller";
try{
    $obj = new $exec_class();
    $obj->$action();
} catch (Exception $e){
    exit($e->getTraceAsString());
}

if($is_debug){
    $xhprof_data = xhprof_disable();
    $xhprof_root = "/Users//vagrant/htdocs/xhprof";
    include_once $xhprof_root."/xhprof_lib/utils/xhprof_lib.php";
    include_once $xhprof_root."/xhprof_lib/utils/xhprof_runs.php";
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_freak");
    $u = 'http://xhprof.com/?run=' . $run_id . '&source=xhprof_freak';
    echo "<a href='{$u}' target='_blank'>xhprof</a>";
}

return;
