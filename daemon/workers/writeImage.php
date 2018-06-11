<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_workers_writeImage extends daemon_workers_base {
    public function init(){
    }
    public function run(){
        $path = PATH_PUBLIC.DS.'uploads'.DS;

        $hd = opendir($path);
        $files = [];
        while(($file = readdir($hd)) !== false){
            if($file == '.' || $file == '..') continue;
            if($file == 'index.html') continue;
            $files[] = $file;
        }
        closedir($hd);

        foreach ($files as $file) {
            $img = new lib_image($path.$file);
            $img->thumb(100,100);
            $img->save($path.str_replace('.jpg', '_thumb.jpg', $file));
        }

        return;
    }
}
new daemon_workers_writeImage();