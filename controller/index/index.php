<?php
/**
 *
 */

class controller_index_index extends controller_base{
    public function init(){}

    public function index(){

//        $data = array('a' => 'a', 'b' => 123);
//        outputCore::view_render('index', $data);
//        core_output::view_layout_render('index',array('a' => 13145));
        //return;

        $m = new model_index();
        echo $m->get_name();
//
//        $l = new core_log();
//        $l->write('write log');
//
//        $pdo = new core_pdo('read');
//        $ret = $pdo->row("select * from GnAdm where id=:id", array('id'=>1));
//        print_r($ret);
//
//        $curl = lib_curl::init();
//        $res = $curl->url('http://www.baidu.com');
//        print_r($res);
//
        $b = lib_filter::strGet('b');
        var_dump($b);

//        $r = new core_redis('read');
//        echo $r->get('aaa');

//        $captcha = new lib_captcha();
//        $captcha->getCode();
//        $captcha->getImage();

//        $key = 'liuhuhi';
//        $en = lib_helper::encrypt('woshiphp', $key);
//        echo $en. PHP_EOL;
//        echo lib_helper::decrypt($en, 123);

//        var_dump( lib_csrf::getHiddenInputString('liuhui12'));
//        var_dump( lib_csrf::check_token('liuhui2', 'nJ+tmquZ'));
    }
}