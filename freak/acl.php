<?php
//权限
//参考 composer require casbin/casbin
class freak_acl{
    private $status = 0;
    const ADD = 1;
    const DEL = 2;
    const SET = 4;
    const GET = 8;


    //设置
    public function set($pid){
        return $this->status = $this->status|$pid;
    }
    //添加
    public function add($pid){
        return $this->set($pid);
    }
    //删除
    public function del($pid){
        return $this->status = $this->status^$pid;
    }
    //权限
    public function is($pid){
        return $pid==($this->status&$pid);
    }

    public function getStatus(){
        return $this->status;
    }
}