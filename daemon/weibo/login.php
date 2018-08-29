<?php
//微博登陆
if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_weibo_login extends daemon_workers_base {
    private $cookie_jar = '';
    private $header = array();
    public function init(){
        $this->cookie_jar = PATH_ROOT.DS.'logs'.DS.'tmp.cookie';
        $this->header = array(
            'User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 weibo',
            'Referer:https://getup.sc.weibo.com/home',
            'Origin:https://passport.weibo.cn',
            'Content-Type:application/x-www-form-urlencoded',
            'Pragma: no-cache',
            'Connection: keep-alive',
            'X-Requested-With:XMLHttpRequest',
            'Accept: application/json, text/plain, */*',
            'Accept-Encoding: gzip, deflate',
        );
    }

    public function run(){
        unlink($this->cookie_jar);
        $ret = $this->login_weibo();
        return;
    }

    private function login_weibo(){
        $curl = lib_curl::init();
        $post_data = array(
            'username'=>'liuhui244671426@sina.cn',//微博账号
            'password'=> 'Liuhui3014224',//微博密码
            'savestate'=> 1,
            'r'=> 'https://m.weibo.cn/',
            'ec'=> 0,
            'pagerefer'=> 'https://m.weibo.cn/',
            'entry'=> 'mweibo',
            'wentry'=>'',
            'loginfrom' => '',
            'client_id'=>'',
            'code'=>'',
            'qq'=>'',
            'mainpageflag'=> 1,
            'hff'=>'',
            'hfp'=>'',
        );

        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEJAR', $this->cookie_jar);//存 cookie

        $curl->set('CURLOPT_SSL_VERIFYPEER', false);// 信任任何证书
        $curl->set('CURLOPT_SSL_VERIFYHOST', false);// 检查证书中是否设置域名

        $ret = $curl->post($post_data)->url("https://passport.weibo.cn/sso/login")->data();
        $ret = json_decode($ret, 1);
        if($ret['retcode'] == 20000000){
            $ret2 = $curl->url($ret['data']['loginresulturl'])->data();
            return $ret['data'];//loginresulturl uid
        }
        return array();
    }
}
new daemon_weibo_login();