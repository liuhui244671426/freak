<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_weibo_fetchList extends daemon_workers_base {
    private $cookie_jar = '';
    private $header = array();
    public function init(){

        $this->header = array(
            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
            'Referer:https://m.weibo.cn',
            'Content-Type:application/x-www-form-urlencoded',
            'Pragma: no-cache',
            'Connection: keep-alive',
            'X-Requested-With:XMLHttpRequest',
            'Accept: application/json, text/plain, */*',
            'Accept-Encoding: gzip, deflate, br',
            'MWeibo-Pwa: 1',
        );
    }

    public function run(){
        $accounts = freak_config::get('account', 'weibo');
        $db = new freak_pdo('write', 'weibo1');
        $urls = array(
            'car' => 'https://m.weibo.cn/api/container/getIndex?containerid=102803_ctg1_5188_-_ctg1_5188&openApp=0',
            'recommend' => 'https://m.weibo.cn/api/container/getIndex?containerid=102803&openApp=0',
            'fresh' => 'https://m.weibo.cn/api/container/getIndex?containerid=102803_ctg1_7978_-_ctg1_7978&openApp=0',
        );
        foreach ($accounts as $username=> $password) {
            $this->cookie_jar = PATH_ROOT.DS.'download'.DS.'cookie'.DS.$username.'.cookie';
            $this->remindTime();
            $list = $this->fetchTheList($urls['fresh']);
            //print_r($list);
            if($list['ok']){//ok是1
                $cards = $list['data']['cards'];
                foreach($cards as $idx => $card){
                    $dataFormat = $this->pickCardData($card);
                    if(!is_array($dataFormat)){
                        //var_dump($dataFormat);
                    } else {
                        $row = $db->row("select * from `topics` where `mid`=:mid", array('mid'=>$dataFormat['mid']));
                        if(empty($row)){
                            $insert_arr = $dataFormat;
                            $insert_arr['create_time'] = date('Y-m-d H:i:s');
                            print_r($insert_arr);
                            $ret = $db->insert('topics', $insert_arr);
                        }
                    }
                }
            } else {
                var_dump($list);
            }
        }


        return;
    }

    private function fetchTheList($url){
        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $ret = $curl->url($url)->data();
        $ret = json_decode($ret, 1, 1024);
        return $ret;
    }

    private function fetchThePic($pic_url, $mid, $offset){
        $part = explode('/', $pic_url);
        $name = end($part);
        list($name_f, $name_e) = explode('.', $name);
        $new_name = "{$mid}_{$offset}.{$name_e}";
        $path = PATH_ROOT.DS.'download'.DS.'images'.DS.$new_name;
        freak_log::write('pic url: '.$pic_url);
        freak_log::write('pic name: '.$name);
        if(file_exists($part)){
            unlink($part);
        }

        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $ret = $curl->url("$pic_url")->save($path)->data();
        return $new_name;
    }
    private function fetchTheVideo($video_url, $mid){
        $parse_video_url = parse_url($video_url);
        $name = ltrim($parse_video_url['path'], '/');
        list($name_f, $name_e) = explode('.', $name);
        $new_name = "{$mid}_1.{$name_e}";
        freak_log::write('video url: '.$video_url);
        freak_log::write('video name: '.$new_name);

        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $ret = $curl->url($video_url)->save(PATH_ROOT.DS.'download'.DS.'videos'.DS.$new_name)->data();
        return $new_name;
    }

    private function pickCardData($card){
        if(!$card['mblog']){return -1;}
        $pics2 = array();
        if($card['mblog']['pics']){
            $i = 1;
            foreach ($card['mblog']['pics'] as $pic) {//多张图片
                $pics2[] = $this->fetchThePic($pic['large']['url'], $card['mblog']['mid'], $i);
                $i++;
            }
        }

        if($card['mblog']['page_info']['media_info']['stream_url']){//纯文本,无此字段,多媒体或多图片才有
            $media_name = $this->fetchTheVideo($card['mblog']['page_info']['media_info']['stream_url'], $card['mblog']['mid']);
            $out['media'] = $media_name;
        }
        $out['mid'] = $card['mblog']['mid'];
        $out['pics'] = json_encode($pics2);
        $out['text'] = strip_tags($card['mblog']['text']);//内容

        return $out;
    }

    private function remindTime(){
        $curl = lib_curl::init();
        $curl->set('CURLOPT_HTTPHEADER', $this->header);
        $curl->set('CURLOPT_COOKIEFILE', $this->cookie_jar);
        $t = time()*100;
        $ret = $curl->url('https://m.weibo.cn/api/remind/unread?t='.$t);
    }
}
new daemon_weibo_fetchList();
