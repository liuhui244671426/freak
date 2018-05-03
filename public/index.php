<?php
/**
 * freak.framework
 *
 */
$is_debug = false;
include dirname(dirname(__FILE__)).'/bootstrap.php';
if($is_debug) {
    xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
}

$controller = $_GET['c']."Controller";
$method = $_GET['m'];
try{
    $obj = new $controller();
    $obj->$method();
} catch (Exception $e){
    exit("system error");
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
