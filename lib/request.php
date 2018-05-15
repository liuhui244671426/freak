<?php
/**
 * 遗弃,不建议使用
 *
 */
defined('FREAK_ACCESS') or exit('Access Denied');
//class lib_request{
//
//    public static function get($type, $key,$default=''){
//        $params = self::getAll();
//        $params = $params[$key]?$params[$key]:$default;
//        return self::checkParam($type, $params);
//    }
//
//    public static function getAll(){
//        return $_POST?$_POST:$_GET;
//    }
//    //参数检测
//    public static function checkParam($type, $params){
//        switch($type){
//            case 'string':{
//                return strval($params);break;
//            }
//            case 'int': {
//                return intval($params);break;
//            }
//            case 'float':{
//                return floatval($params);break;
//            }
//            case 'array':{
//                array_walk($params, function(&$v, $k){
//                    strval($v);
//                });
//                return $params;
//                break;
//            }
//            default:
//                break;
//        }
//    }
//}