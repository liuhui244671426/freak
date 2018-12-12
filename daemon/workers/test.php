<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';
ini_set('memory_limit', '1024M');

class daemon_workers_test extends daemon_workers_base {
    public function init(){
    }
    public function run(){
        //sleep(10);
        /*$i = 0;
        $t = '';
        while($i <= 50){
            $t .= "=";
            echo $t."\r";
            $i++;
            sleep(1);
        }
        echo "\r\n";
        echo "finish\r\n";*/

        /*$bf = new lib_bloomFilter(99999999, 2);
        $str_add1 = "test1";
        $str_add2 = "test2";
        $str_notadd3 = "test3";
        print_r($bf->hashcode($str_add1));
        print_r($bf->getFalsePositiveProbability());
        return;
        $bf->add($str_add1);
        $bf->add($str_add2);
        $bf->add($str_notadd3);
        var_dump($bf->exist($str_add1));
        var_dump($bf->exist($str_add2));
        var_dump($bf->exist($str_notadd3));*/


        /*$bm = new lib_bitMap(10);
        //$bm->setBit(2);
        var_dump($bm->getInt());
        var_dump($bm->getBinary());*/

        $bm2 = new lib_bitMap2(PATH_PUBLIC.DS.'bitmap2.bin');
        $bm2->setBit(99);


        return;
    }
}
new daemon_workers_test();