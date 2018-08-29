<?php
defined('FREAK_ACCESS') or exit('Access Denied');
class freak_output{
    public static function json($data){
        header('Content-type: application/json');
        echo json_encode($data);
    }
    //页面需要运行的js
    public static function js($path){
        $out = '';
        if(is_array($path)){
            foreach ($path as $k => $v) {
                $out .= '<script src="'.$v.'.js"></script>';
            }
        } else {
            $out .= '<script src="'.$path.'.js"></script>';
        }
        return $out;
    }

    public static function css($path){
        $out = '';
        if(is_array($path)){
            foreach ($path as $k => $v) {
                $out .= '<link href="'.$v.'.css" rel="stylesheet">';
            }
        } else {
            $out .= '<script src="'.$path.'.css"></script>';
        }
        return $out;
    }
}