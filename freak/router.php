<?php
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_router{

    public function url(){
        ###########router rule##############
        #rule : module->controller->action
        #     : m=index?c=index&a=index
        ####################################
        $m = filter_input(INPUT_GET, 'm');
        $c = filter_input(INPUT_GET, 'c');
        $a = filter_input(INPUT_GET, 'a');
        $module     = $this->default_name($m);
        $controller = $this->default_name($c);
        $action     = $this->default_name($a);
        $exec_class = 'fpm_'.$module.'_'.$controller;
        $this->run($exec_class, $action);
    }

    public function simple(){
        ###########router rule##############
        #rule : module->controller->action
        #     : /index/index/index
        ####################################
        list($over, $m, $c, $a) = explode('/', $_SERVER['REQUEST_URI']);
        $module     = $this->default_name($m);
        $controller = $this->default_name($c);
        $action     = $this->default_name($a);
        $exec_class = 'fpm_'.$module.'_'.$controller;
        $this->run($exec_class, $action);
    }

    public function map(){
        ###########router rule##############
        #rule : module->controller->action
        #     : /index/index/index
        #     : /index
        ####################################
        $map = freak_config::get('router', 'map');
        $v_map = $map[$_SERVER['REQUEST_URI']];
        list($exec_class, $action) = explode(':', $v_map);
        $this->run($exec_class, $action);
    }
    protected function default_name($n){
        return $n   ?   $n  :   'index';
    }
    protected function run($exec_class, $action){
        try {
            $re = new ReflectionMethod($exec_class, $action);
            $re->invoke(new $exec_class());//$obj = new $exec_class();$obj->$action();
        } catch (Throwable $e) {
            freak_log::write($e->getTraceAsString());
            freak_log::write($e->getMessage());
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
            exit('File not found.');
        }
    }
}