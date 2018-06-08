<?php
/**
 *
 *
 */
defined('FREAK_ACCESS') or exit('Access Denied');
class freak_output{
    public static function view_render($file, $data=array()){
        @extract($data);
        include PATH_VIEW.DS.$file.".html";
        return;
    }
    public static function view_layout_render($file , $data=array(), $layout='layout'){
        $data['include_file'] = PATH_VIEW.DS.$file.".html";
        @extract($data);
        include PATH_VIEW.DS.$layout.'.html';
    }
    public static function json_render($data){
        header('Content-type: application/json');
        echo json_encode($data);
    }
    //页面需要运行的js
    public static function js_render($path){
        $out = '';
        if(is_array($path)){
            foreach ($path as $k => $v) {
                $out .= '<script src="'.$v.'"></script>';
            }
        } else {
            $out .= '<script src="'.$path.'"></script>';
        }
        return $out;
    }
}