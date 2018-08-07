<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_workers_daka extends daemon_workers_base {
    private $cookie_jar = '';
    private $header = array();
    public function init(){
        $this->cookie_jar = PATH_ROOT.DS.'logs'.DS.'tmp.cookie';
        $this->header = array(
            'User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 weibo',
            'Referer:http://getup.sc.weibo.com/home',
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
        $product_id = $this->getProduct_id();

        if(empty($product_id)){
            unlink($this->cookie_jar);
            $ret = $this->login_weibo();
            //print_r($ret);
            return; //需要第二次启动
        }
        freak_log::write("$product_id 打卡");
        print_r($product_id);
        $ret = $this->daka($product_id);
        print_r($ret);
        freak_log::write(json_encode($ret));
        freak_log::write("打卡结束 End.");
        return;
    }

    private function login_weibo(){
        $curl = lib_curl::init();
        $post_data = array(
            'username'=>'',//微博账号
            'password'=> '',//微博密码
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

    private function getProduct_id(){
        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $ret = $curl->url('http://getup.sc.weibo.com/aj/page/home')->data();
        $ret = json_decode($ret, 1);
        print_r($curl->error());
        //print_r($ret);
        return $ret['data']['previous_product']['product_id'];
    }

    private function daka($product_id){
        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $ret = $curl->post('product_id', $product_id)->url('http://getup.sc.weibo.com/aj/signin/sign')->data();
        $ret = json_decode($ret, 1);
        //print_r($ret);
        return $ret;
    }

}
new daemon_workers_daka();