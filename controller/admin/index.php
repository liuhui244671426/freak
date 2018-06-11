<?php

class controller_admin_index extends controller_base{
    public function init(){
        lib_ssoClient::is_login();
    }

    public function index(){
        freak_output::view_layout_render('admin/index/index', array(), 'admin/layout');
    }
    public function welcome(){}

    public function editor(){
        $pre = freak_config::get('common', 'static');
        $css = [
            $pre.'css/plugins/summernote/summernote',
            $pre.'css/plugins/summernote/summernote-bs3',
        ];
        $js = [
            $pre.'js/plugins/summernote/summernote.min',
            $pre.'js/plugins/summernote/summernote-zh-CN',
            '/public/assets/js/summernote'
        ];

        freak_output::view_layout_render('admin/index/editor', [
            'include_js' => freak_output::js_render($js),
            'include_css' => freak_output::css_render($css),
        ], 'admin/layout');
    }
    public function editor_post(){
        print_r($_POST);
    }
}