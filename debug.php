<?php
/**
 * freak.framework
 * @author: liuhui
 */
define('FREAK_ACCESS', true);
header("Content-type:text/html;charset=utf-8");

//-----------------------------------------
xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
//-----------------------------------------

include dirname(__FILE__).'/freak/bootstrap.php';

//-----------------------------------------
$xhprof_data = xhprof_disable();
$xhprof_root = "/Users/liuhui/vagrant/htdocs/xhprof";
include_once $xhprof_root."/xhprof_lib/utils/xhprof_lib.php";
include_once $xhprof_root."/xhprof_lib/utils/xhprof_runs.php";
$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_freak");
$u = 'http://xhprof.com/?run=' . $run_id . '&source=xhprof_freak';
echo "<a href='{$u}' target='_blank'>xhprof</a>";
//-----------------------------------------