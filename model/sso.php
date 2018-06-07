<?php
class model_sso extends model_base{
    public function login($name, $password){
        $db = new freak_pdo('read');
        $ret = $db->row("select * from `sso` where `name`=:name and `password`=:password", array('name'=>$name, 'password'=>$password));
        return $ret;
    }

    public function gen_token($uid){
        $db = new freak_pdo('write');
        $token = ((microtime(true) * 10000 ) . mt_rand(10,30)) << 4;
        $id = $db->query("insert into `token` (`uid`, `token`) VALUES (:uid, :token)", array('uid'=>$uid, 'token'=>$token));
        return $token;
    }

    public function check_token($token){
        $db = new freak_pdo();
        $ret = $db->row("select * from `token` where `token`=:token", array('token' => $token));
        return $ret;
    }
}