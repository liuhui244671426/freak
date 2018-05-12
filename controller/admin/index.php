<?php

class controller_admin_index extends controller_base{
    public function init(){}

    public function index(){
        $bool = lib_ssoClient::is_login();
        if($bool){
            echo 'success';
        } else {
            echo 'failed';
        }
    }
}