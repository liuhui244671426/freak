<?php
class controller_sso_server extends controller_base{
    public function init(){}

    public function index(){
        $callback = lib_request::get('string', 'callback');
        core_output::view_render("login", array('callback'=>$callback));
        return;
    }

    public function login(){
        ##############################
        # 1.校验账号密码
        # 2.密码成功,生成 token
        # 3.通过回跳,把 token 带个 client
        ##############################

        $name = lib_request::get('string', 'name');
        $password = lib_request::get('string', 'password');

        $db = new core_pdo('write');
        $ret = $db->row("select * from `sso` where `name`=:name and `password`=:password", array('name'=>$name, 'password'=>$password));
        if($ret){
            $token = ((microtime(true) * 10000 ) . mt_rand(10,30)) << 4;
            $db->query("insert into `token` (`uid`, `token`) VALUES (:uid, :token)", array('uid'=>$ret['id'], 'token'=>$token));

            $callback = lib_request::get('string', 'callback');
            $callback = $callback.'&token='.$token;

            header("Location:".$callback);
            return;
        }
    }
    // 检测 token 是否有效
    public function check(){
        $db = new core_pdo();
        $ret = $db->row("select * from `token` where `token`=:token", array('token' => requestLib::get('string', 'token')));
        if($ret){
            echo 'ok';
        } else {
            echo 'error';
        }
        return;
    }

}