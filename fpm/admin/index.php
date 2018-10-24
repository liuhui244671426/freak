<?php

class fpm_admin_index extends fpm_base{
    public function init(){
        freak_view::$layout = 'admin/layout';
        $user_info = lib_ssoClient::is_login();
    }

    public function index(){
        freak_view::layout_render('admin/index/index', array());
        return;
    }
    public function show_one(){
        $id = lib_filter::get('id');
        $db = new freak_pdo('read');
        $ret = $db->query("select * from `content` where id=:id", array('id' => $id));
        freak_view::layout_render('admin/index/show', array('text' => $ret[0]));
        return;
    }
    public function show_list(){
        $page = lib_filter::get('page', 0);
        $start = $page * 10;
        $db = new freak_pdo('read');
        $ret = $db->query("select * from `content` order by id desc limit :start, 10", array('start' => $start));
        freak_view::layout_render('admin/index/show_list', array('text' => $ret));
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

        freak_view::layout_render('admin/index/editor', [
            'include_js' => freak_output::js($js),
            'include_css' => freak_output::css($css),
        ]);
    }
    public function editor_post(){
        $db = new freak_pdo('write');
        $db->insert('content', array('content' => $_POST['editordata']));
    }
}