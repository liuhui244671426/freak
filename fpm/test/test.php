<?php

class fpm_test_test extends freak_fpm{

    public function init(){}
    // url query /?m=test&c=test&a=something
    public function something(){
        echo 'Hi, web';
    }
}