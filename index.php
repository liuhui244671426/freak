<?php
/**
 * freak.framework
 * @author: liuhui
 */
define('FREAK_ACCESS', true);
header("Content-type:text/html;charset=utf-8");
if(version_compare(PHP_VERSION, '5.6.0') < 0){ exit('PHP版本需要大于5.6.0'); }

include dirname(__FILE__).'/bootstrap.php';
run();
return;