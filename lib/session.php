<?php
defined('FREAK_ACCESS') or exit('Access Denied');
class lib_session implements SessionHandlerInterface{
    /*public function __construct(){
        ini_set("session.save_handler", "redis");
        $config = core_config::get('redis','write');
        if($config['password']){
            ini_set('session.save_path', "tcp://{$config['host']}:{$config['port']}?auth={$config['password']}");
        } else {
            ini_set('session.save_path', "tcp://{$config['host']}:{$config['port']}");
        }
        session_start();
    }*/

    protected static $obj;

    public function __construct($resource='redis'){
        if($resource=='redis'){
            self::$obj = new freak_redis('write');
        }
    }

    public function open($save_path, $sid){
        return true;
    }
    public function close(){
        return true;
    }
    /**
     * @url http://php.net/manual/zh/sessionhandlerinterface.read.php
     * Returns an encoded string of the read data. If nothing was read,
     * it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * */
    public function read($sid){
        $ret= self::$obj->get($sid);
        $ret = $ret?$ret:'';
        return $ret;
    }

    public function write($sid,$data){
        $ret = self::$obj->set($sid,$data, 86400);//n day
        return $ret;
    }

    public function destroy($sid){
        return self::$obj->delete($sid);
    }

    public function gc($maxtime){
        return true;
    }
}