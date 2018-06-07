<?php

class controller_admin_index extends controller_base{
    public function init(){
        lib_ssoClient::is_login();
    }

    public function index(){
        freak_output::view_layout_render('admin/index/index', array(), 'admin/layout');
    }

    public function welcome(){

    }
}