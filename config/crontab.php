<?php
/**
 *
 * 格式: 启动时间 脚本类型 脚本路径 进程数 版本号
 */
defined('FREAK_ACCESS') or exit('Access Denied');
return [
    'product' => [],
    'develop' => [
        #"* * * * * php workers/test.php   2 1.0",
        #"00 06 * * * php weibo/login.php   1 1.0",
        #"*/5 07 * * * php weibo/daka.php   1 1.0",
        "00 10 * * * php workers/clearHostDesktop.php   1 1.0",
    ]

];
