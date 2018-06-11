<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_workers_test extends daemon_workers_base {
    public function init(){
    }
    public function run(){
        //sleep(10);
        $log = new freak_log();
        $model = new model_index();
        $log->write(__METHOD__ . $this->getPid());
        $log->write($model->get_name());
        return;
    }
}
new daemon_workers_test();