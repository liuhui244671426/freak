<?php

class lib_session extends  SessionHandler{
    public function __construct(){
        ini_set("session.save_handler", "redis");
        $config = core_config::get('redis','write');
        if($config['password']){
            ini_set('session.save_path', "tcp://{$config['host']}:{$config['port']}?auth={$config['password']}");
        } else {
            ini_set('session.save_path', "tcp://{$config['host']}:{$config['port']}");
        }
        session_start();
    }

    /*protected static $obj;

    public function __construct($resource='redis'){
        if($resource=='redis'){
            self::$obj = new core_redis('write');
        }
    }

    public function open($save_path, $sid){
        return true;
    }
    public function close(){
        return true;
    }

    public function read($sid){
        $ret= self::$obj->get($sid);
        return $ret;
    }

    public function write($sid,$data){
        $maxtime = 60*60*24*1;//n day
        $ret = self::$obj->set($sid,$data,$maxtime);
        return $ret;
    }

    public function destroy($sid){
        return self::$obj->delete($sid);
    }

    public function gc($maxtime){
        return true;
    }*/
}