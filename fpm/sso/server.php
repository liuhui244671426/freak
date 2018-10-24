<?php
class fpm_sso_server extends fpm_base{
    public function init(){}

    public function index(){
        $callback = lib_filter::strGet('callback');
        freak_view::render("sso/login", array('callback'=>$callback));
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
        if(lib_filter::strPost('login_captcha') != $_SESSION['login_captcha']){
            header("Location:".$callback);
            return;
        }

        $model = new model_sso();
        $ret = $model->login($name, $password);
        if($ret){

            $token = $model->gen_token($ret['id']);
            //回调地址,携带 token
            header("Location:".$callback.'&token='.$token);
            return;
        }
    }
    // 通过 token 获取用户信息
    public function get_user_info(){
        $model = new model_sso();
        $token_info = $model->check_token(lib_filter::strGet('token'));
        if($token_info){
            $uid_info = $model->get_user_info($token_info['uid']); //获取用户信息
            freak_output::json(array('code' => 10000, 'msg' => 'Success', 'data' => array('uid' => $uid_info['id'], 'name' => $uid_info['name'])));
        } else {
            freak_output::json(array('code' => 20000, 'msg' => 'Failed', 'data' => array()));
        }
        return;
    }

    public function login_captcha(){
        $lib = new lib_captcha();
        $code = $lib->getCode();
        $_SESSION['login_captcha'] = $code;
        $lib->getGifImage();
        return;
    }
}