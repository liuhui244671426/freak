<?php
/* *
	* Log 			A logger class which creates logs when an exception is thrown.
	* @author		Author: Vivek Wicky Aswal. (https://twitter.com/#!/VivekWickyAswal)
	* @git 			https://github.com/wickyaswal/PHP-MySQL-PDO-Database-Class
	* @version      0.1a
	*/
defined('FREAK_ACCESS') or exit('Access Denied');
class freak_log {

    public static function write($message, $pre='') {
        $date = new DateTime();
        $path = PATH_ROOT.DS.'logs'.DS;
        $file = $path . $date->format('Ymd').$pre.".txt";
        if(is_dir($path)) {
            $log = "Time : " . $date->format('H:i:s')." Msg : " . $message . PHP_EOL;
            file_put_contents($file, $log, FILE_APPEND);
        } else {
            if(mkdir($path,0777) === true)
            {
                self::write($message);
            }
        }
    }
}