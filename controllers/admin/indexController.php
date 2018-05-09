<?php

class Admin_indexController extends baseController{
    public function init(){}

    public function index(){
        $bool = ssoClientLib::is_login();
        if($bool){
            echo 'success';
        } else {
            echo 'failed';
        }
    }
}