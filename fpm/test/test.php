<?php

class fpm_test_test extends fpm_base{

    public function init(){}
    // url query /?m=test&c=test&a=something
    public function index(){
        freak_view::render('test/index');
        return;
    }

    public function upload(){
        freak_log::write('upload: '.json_encode($_FILES));
        $file = $_FILES;
        print_r($file);
        unlink(PATH_ROOT.DS.'logs'.DS.$file['file']['name']);
        move_uploaded_file($file['file']['tmp_name'], PATH_ROOT.DS.'logs'.DS.$file['file']['name']);
        return;
    }
}