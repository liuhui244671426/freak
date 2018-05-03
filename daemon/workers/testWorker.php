<?php
/**
 *
 *
 * author liuhui9<liuhui9@staff.sina.com.cn>
 * @version 2018/5/3
 * @copyright copyright(2018) weibo.com all rights reserved
 */

require_once dirname(dirname(dirname(__FILE__))).'/bootstrap.php';

class testWorker extends baseWorker {
    public function run(){
        sleep(10);
        $log = new logCore();
        $log->write(date('Y-m-d H:i:s'));
        return;
    }
}
new testWorker();