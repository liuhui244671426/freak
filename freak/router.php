<?php
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_router
{

    public function url()
    {
        ###########router rule##############
        #rule : module->controller->action
        #     : m=index?c=index&a=index
        ####################################
        $m = filter_input(INPUT_GET, 'm');
        $c = filter_input(INPUT_GET, 'c');
        $a = filter_input(INPUT_GET, 'a');
        $module = $this->default_name($m);
        $controller = $this->default_name($c);
        $action = $this->default_name($a);
        $this->run($module, $controller, $action);
    }

    public function simple()
    {
        ###########router rule##############
        #rule : module->controller->action
        #     : /index/index/index
        #     : /index 优先 map 模式
        ####################################
        $map = freak_config::get('router', 'map')[ $this->parse_path() ];
        if ($map) $path = $map; else $path = $this->parse_path();
        list($_, $m, $c, $a) = explode('/', $path);
        $module = $this->default_name($m);
        $controller = $this->default_name($c);
        $action = $this->default_name($a);
        $this->run($module, $controller, $action);
    }

    protected function default_name($n)
    {
        return $n ? $n : 'index';
    }

    protected function run($module, $controller, $action)
    {
        try {
            $exec_class = 'fpm_' . $module . '_' . $controller;
            $re = new ReflectionMethod($exec_class, $action);
            $re->invoke(new $exec_class());
        } catch (Throwable $e) {
            freak_log::write($e->getTraceAsString());
            freak_log::write($e->getMessage());
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
            exit('File not found.');
        }
    }

    protected function parse_path()
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'];
    }
}