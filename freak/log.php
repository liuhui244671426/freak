<?php
/* *
	* Log 			A logger class which creates logs when an exception is thrown.
	* @author		Author: Vivek Wicky Aswal. (https://twitter.com/#!/VivekWickyAswal)
	* @git 			https://github.com/wickyaswal/PHP-MySQL-PDO-Database-Class
	* @version      0.1a
	*/
defined('FREAK_ACCESS') or exit('Access Denied');
class freak_log {

    # @string, Log directory name
    //private static $path = '';

    # @void, Default Constructor, Sets the timezone and path of the log files.
    public function __construct() {
        //$this->path  = dirname(__FILE__)  . $this->path;
        //self::$path = PATH_ROOT.DS.'logs'.DS;
    }

    /**
     *   @void
     *	Creates the log
     *
     *   @param string $message the message which is written into the log.
     *	@description:
     *	 1. Checks if directory exists, if not, create one and call this method again.
     *	 2. Checks if log already exists.
     *	 3. If not, new log gets created. Log is written into the logs folder.
     *	 4. Logname is current date(Year - Month - Day).
     *	 5. If log exists, edit method called.
     *	 6. Edit method modifies the current log.
     */
    public static function write($message) {
        $date = new DateTime();
        $path = PATH_ROOT.DS.'logs'.DS;
        $log = $path . $date->format('Ymd').".txt";
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

    /**
     *  @void
     *  Gets called if log exists.
     *  Modifies current log and adds the message to the log.
     *
     * @param string $log
     * @param DateTimeObject $date
     * @param string $message
     */
    private static function edit($log,$date,$message) {
        $logcontent = "Time : " . $date->format('H:i:s')." Msg : " . $message ."\r\n\r\n";
        file_put_contents($log, $logcontent,FILE_APPEND);
    }
}