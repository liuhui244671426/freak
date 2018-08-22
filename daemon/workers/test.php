<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_workers_test extends daemon_workers_base {
    public function init(){
    }
    public function run(){
        //sleep(10);
        $i = 0;
        $t = '';
        while($i <= 50){
            $t .= "=";
            echo $t."\r";
            $i++;
            sleep(1);
        }
        echo "\r\n";
        echo "finish\r\n";
        return;
    }
}
new daemon_workers_test();