<?php
define('VERSION', '3.1.1');
define('PATH_ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_ROOT . DS . 'views');
define('PATH_CONFIG', PATH_ROOT . DS . 'config');
define('PATH_DAEMON', PATH_ROOT . DS . 'daemon');
define('PATH_PUBLIC', PATH_ROOT . DS . 'public');
define('PATH_MODEL', PATH_ROOT . DS . 'model');
define('PATH_DATA', PATH_ROOT . DS . 'data');
define('CMD_PHP', '/usr/bin/php');
define('CMD_SH', '/usr/bin/sh');
define('MAX_PROC', 128);//每个任务最多并发进程数
define('ENV', 'develop');