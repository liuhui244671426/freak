<?php
/**
 * 本文件是核心文件, web 和 cli 模式均需要加载本文件
 * web 不需要手动加载,系统已经处理
 * cli 必须手动加载
 * */
defined('FREAK_ACCESS') or exit('Access Denied');
require_once 'const.php';

class bootstrap
{
    public function run()
    {
        if (version_compare(PHP_VERSION, '7.1.13') < 0) {
            exit('PHP version must >= 7.1.13');
        }
        date_default_timezone_set('PRC');
        error_reporting(E_ALL & ~E_NOTICE);
        spl_autoload_register([$this, 'f_auto_load']);
        set_exception_handler([$this, 'f_last_error']);
        set_error_handler([$this, 'f_error_handler'], E_ALL & ~E_NOTICE);
        register_shutdown_function([$this, 'f_last_error']);

        if (PHP_SAPI != 'cli') (new freak_router())->{freak_config::get('router')['mode']}();
        return true;
    }

    public function f_auto_load($class)
    {
        $path = PATH_ROOT . DS . str_replace('_', DS, $class) . '.php';
        if (!file_exists($path)) {
            throw new Exception("auto load file : {$path}, NOT EXIST");
        }
        include $path;
        return true;
    }

    public function f_error_handler($errno, $errstr, $errfile, $errline)
    {
        if (0 === error_reporting() || 30711 === error_reporting()) {
            return false;
        }
        $msg = "ERROR";
        if ($errno == E_WARNING) $msg = "WARNING";
        if ($errno == E_NOTICE) $msg = "NOTICE";
        if ($errno == E_STRICT) $msg = "STRICT";//严格
        if ($errno == E_DEPRECATED) $msg = "DEPRECATED"; //过时遗弃
        freak_log::write("error handler $msg: $errstr in $errfile on line $errline");
        return true;
    }

    public function f_last_error()
    {
        $e = error_get_last();
        if ($e) freak_log::write("ERROR message: {$e['message']},type: {$e['type']}, in file: {$e['file']} on line:{$e['line']}");
        return true;
    }

    public function f_session($storage, $alias)
    {
        if ($_COOKIE[ session_name() ] == '') {
            session_id(microtime(true) * 10000);
        }
        $session = new freak_lib_session($storage, 'write', $alias);
        session_set_save_handler($session, true);
        session_start();
        setcookie(session_name(), session_id(), time() + 86400);//expire time和redis ttl 一致
    }
}

(new bootstrap())->run();
return true;