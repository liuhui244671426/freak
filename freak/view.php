<?php

defined('FREAK_ACCESS') or exit('Access Denied');
class freak_view{
    public static $layout = 'layout';


    public static function render($file, $data=array()){
        @extract($data);
        include PATH_VIEW.DS.$file.".html";
        return;
    }
    public static function layout_render($file , $data=array()){
        $data['include_file'] = PATH_VIEW.DS.$file.".html";
        @extract($data);
        include PATH_VIEW.DS.self::$layout.'.html';
    }
}