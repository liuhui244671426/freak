<?php
/**
 *
 * 格式: 启动时间 脚本类型 脚本路径 进程数 版本号
 */
defined('FREAK_ACCESS') or exit('Access Denied');
return array(
    'product' => array(),
    'develop' => array(
        #"* * * * * php workers/test.php   2 1.0",
        "*/2 * * * * php workers/daka.php   1 1.0",
    )

);