<?php
/**
 *
 */

class Index_indexController extends baseController{
    public function init(){}

    public function index(){

//        $data = array('a' => 'a', 'b' => 123);
//        outputCore::view_render('index', $data);

        $m = new indexModel();
        echo $m->get_name();

//        $l = new logCore();
//        $l->write('write log');

        $pdo = new pdoCore('read');
        $ret = $pdo->row("select * from GnAdm where id=:id", array('id'=>1));
        print_r($ret);

        $curl = new curlLib();
        $res = $curl->url('http://www.baidu.com');
        print_r($res);
//        $b = requestLib::get('array', 'b', '');
//        var_dump($b);

//        $r = new redisCore('read');
//        echo $r->get('aaa');
    }
}