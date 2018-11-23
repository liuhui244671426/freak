<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_dayu_upload extends daemon_workers_base {
    private $cookie_jar = '';
    private $header = array();
    public function init(){

        $this->header = array(
            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
            ':authority: ns.dayu.com',
            ':method: POST',
            ':path: /article/imageUpload?appid=website&fromMaterial=1&wmid=c3429aef676e4922b3f5faf19fd65b9f&wmname=%E7%81%B0%E7%BE%8A%E8%AF%B4%E8%BD%A6&sign=2520525ae31c50bf7cf96b56596d6866',
            ':scheme: https',
            'accept: */*',
            'accept-encoding: gzip, deflate, br',
            'accept-language: zh-CN,zh;q=0.9,en;q=0.8,zh-TW;q=0.7',
            'access-control-request-method: POST',
            'cache-control: no-cache',
            'pragma: no-cache',
            'origin: https://mp.dayu.com',
            'x-requested-with: XMLHttpRequest',
        );
    }

    public function run(){
        $accounts = freak_config::get('account', 'dayu');
        $db = new freak_pdo('write', 'weibo');
        $topics = $db->query("select * from topics where id=102");

        foreach ($accounts as $username=> $password) {
            $this->cookie_jar = PATH_ROOT.DS.'download'.DS.'cookie'.DS.$username.'.cookie';
            foreach ($topics as $topic) {
                $pics = json_decode($topic['pics'], 1);
                foreach ($pics as $pic) {
                    $ret = $this->uploadFile(PATH_ROOT.DS.'download'.DS.'images'.DS, $pic);
                    $ret = json_decode($ret, 1);
var_dump($ret);
                    freak_log::write("local name: {$pic} , dayu sign: {$ret['data']['imgInfo']['sign']}");
                }

            }
        }


        return;
    }
    private function index(){
        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $ret = $curl->url('https://mp.dayu.com/dashboard/material?spm=a2s0i.db_index.menu.8.4a213caa8Y7Em1')->data();
        print_r($ret);
        return $ret;
    }


    private function uploadFile($path,$name){
        $lastModifiedDate = gmdate('D M d Y H:i:s e', filemtime($path.$name)) . ' 0800 (中国标准时间)';
        $fileSize = filesize($path.$name);
        list($name_f, $name_e) = explode('.', $name);
        $type = "image/{$name_e}";
        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $ret = $curl->post(
            array(
                'id'=>'WU_FILE_0', 'name'=>$name, 'type' => $type,
                'lastModifiedDate' => $lastModifiedDate, 'size' => $fileSize
            )
        )->file(
            'upfile',
            $path.$name,
            $type,
            $name
        )->url('https://ns.dayu.com/article/imageUpload?appid=website&fromMaterial=1&wmid=c3429aef676e4922b3f5faf19fd65b9f&wmname=%E7%81%B0%E7%BE%8A%E8%AF%B4&sign=6e5cf5563b406197ce46cb7f856dec60');
        //print_r($curl->data());
        return $ret;
    }

//https://mp.dayu.com/dashboard/article/write?spm=a2s0i.db_stat_article.menu.3.c9403caaIpB0vX
//utoken: ee6d0e97d6e0f791ab05c2bf67ad0357


    //private function uploadFile2($path){
//        // Create a cURL handle
//        $ch = curl_init('http://freak.com/?m=test&c=test&a=upload');
//        // Create a CURLFile object
//        $cfile = new CURLFile($path,'image/jpeg','005NmpXfly1fwtfkqhrnwj30lk0qg0w8');
//        print_r($cfile);
//        // Assign POST data
//        $data = array('file' => $cfile);
//        curl_setopt($ch, CURLOPT_POST,1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        // Execute the handle
//        curl_exec($ch);
//
//        return;
    //}
}
new daemon_dayu_upload();
