<?php
class controller_sso_server extends controller_base{
    public function init(){}

    public function index(){
        $callback = lib_filter::strGet('callback');
        freak_output::view_render("sso/login", array('callback'=>$callback));
        return;
    }

    public function login(){
        ##############################
        # 1.校验账号密码
        # 2.密码成功,生成 token
        # 3.通过回跳,把 token 带个 client
        ##############################

        $name = lib_filter::strPost('name');
        $password = lib_filter::strPost('password');
        $callback = lib_filter::strPost('callback');

        $model = new model_sso();
        $ret = $model->login($name, $password);
        if($ret){

            $token = $model->gen_token($ret['id']);
            //回调地址,携带 token
            header("Location:".$callback.'&token='.$token);
            return;
        }
    }
    // 检测 token 是否有效
    public function check(){
        $model = new model_sso();
        $ret = $model->check_token(lib_filter::strGet('token'));
        if($ret){
            echo 'success';
        } else {
            echo 'failed';
        }
        return;
    }

}