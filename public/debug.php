<?php
/**
 * freak.framework
 * @author: liuhui
 */
$is_debug = true;

header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
error_reporting(E_ALL);
if(version_compare(PHP_VERSION, '5.6.0') < 0){ exit('PHP版本需要大于5.6.0'); }
if($is_debug) { xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY); }

include dirname(dirname(__FILE__)).'/bootstrap.php';

run();
if($is_debug){
    $xhprof_data = xhprof_disable();
    $xhprof_root = "/Users/liuhui/vagrant/htdocs/xhprof";
    include_once $xhprof_root."/xhprof_lib/utils/xhprof_lib.php";
    include_once $xhprof_root."/xhprof_lib/utils/xhprof_runs.php";
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_freak");
    $u = 'http://xhprof.com/?run=' . $run_id . '&source=xhprof_freak';
    echo "<a href='{$u}' target='_blank'>xhprof</a>";
}

return;