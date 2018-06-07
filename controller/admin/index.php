<?php

class controller_admin_index extends controller_base{
    public function init(){
        $bool = lib_ssoClient::is_login();
        if(!$bool) {
            exit('404');
        }
    }

    public function index(){

    }

    public function welcome(){

    }
}