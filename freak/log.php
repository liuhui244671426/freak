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
        $log = $path . $date->format('Ymd').$pre.".txt";
        if(is_dir($path)) {
            if(!file_exists($log)) {
                $fh  = fopen($log, 'a+') or die("Fatal Error !");
                $logcontent = "Time : " . $date->format('H:i:s')." Msg : " . $message ."\r\n";
                fwrite($fh, $logcontent);
                fclose($fh);
            }
            else {
                self::edit($log,$date, $message);
            }
        }
        else {
            if(mkdir($path,0777) === true)
            {
                self::write($message);
            }
        }
    }

    private static function edit($log,$date,$message) {
        $logcontent = "Time : " . $date->format('H:i:s')." Msg : " . $message ."\r\n\r\n";
        file_put_contents($log, $logcontent,FILE_APPEND);
    }
}