<?php
//定期清理宿主机的桌面截图
if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_workers_clearHostDesktop extends daemon_workers_base {

    public function init(){

    }

    public function run(){
        $path = '/Users/liuhui/Desktop';
        $op = opendir($path);

        while(false !== ($file = readdir($op))){
            if(preg_match('/屏幕快照/', $file)){
                if(unlink($path.DS.$file)){
                    freak_log::write('clearHostDesktop delete file : '.$file);
                }
            }
        }
        return;
    }



}
new daemon_workers_clearHostDesktop();