<?php

class fpm_admin_index extends fpm_base{
    public function init(){
        lib_ssoClient::is_login();
    }

    public function index(){
        freak_output::view_layout_render('admin/index/index', array(), 'admin/layout');
        return;
    }
    public function show_one(){
        $id = lib_filter::get('id');
        $db = new freak_pdo('read');
        $ret = $db->query("select * from `content` where id=:id", array('id' => $id));
        freak_output::view_layout_render('admin/index/show', array('text' => $ret[0]), 'admin/layout');
        return;
    }
    public function show_list(){
        $page = lib_filter::get('page', 0);
        $start = $page * 10;
        $db = new freak_pdo('read');
        $ret = $db->query("select * from `content` order by id desc limit :start, 10", array('start' => $start));
        freak_output::view_layout_render('admin/index/show_list', array('text' => $ret), 'admin/layout');
        return;
    }
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
        //print_r($_POST);
        $db = new freak_pdo('write');
        $db->insert('content', array('content' => $_POST['editordata']));
    }
}