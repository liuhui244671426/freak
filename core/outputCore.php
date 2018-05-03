<?php
/**
 *
 *
 */
class outputCore{
    public static function view_render($file, $data=array()){
        @extract($data);
        include PATH_VIEW.DS.$file.".html";
        return;
    }
    public static function json_render($data){
        header('Content-type: application/json');
        echo json_encode($data);
    }
}