<?php
if(PHP_SAPI != 'cli'){ return false; }
define('PATH_ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('VERSION', '3.0.0');

class freak_build {

    public function __construct(){
        $this->welcome();

        $mode_map = [0 => 'init', 1 => 'fpm', 2 => 'daemon',];
        fwrite(STDOUT, 'Please selection mode
    0 - Initialization Freak framework
    1 - Build web
    2 - Build daemon worker
Please enter the number: ');
        $input = trim(fgets(STDIN));
        $mode = $mode_map[$input];
        if(!$mode) {
            echo "mode : {$mode} not exists".PHP_EOL;return;
        }

        if($input == 0){
            $this->do_build($mode, '', '');
        }
        if($input == 1 || $input == 2){
            fwrite(STDOUT, 'please input module name: ');
            $module = trim(fgets(STDIN));
            fwrite(STDOUT, 'please input  controller name: ');
            $controller = trim(fgets(STDIN));

            $this->do_build($mode, $module, $controller);
            echo "build {$mode} {$module} {$controller} done!".PHP_EOL;
        }

        return;
    }

    public function welcome(){
        echo 'Freak framework build tools'.PHP_EOL;
        echo 'version: '.VERSION.PHP_EOL.PHP_EOL;
    }

    public function do_build($mode, $module, $controller){
        switch ($mode){
            case 'fpm':
                $this->make_file(PATH_ROOT.DS.$mode.DS.$module.DS.$controller.'.php', $this->get_web_class_content($module, $controller));
                break;
            case 'daemon':
                $this->make_file(PATH_ROOT.DS.$mode.DS.$module.DS.$controller.'.php', $this->get_cli_class_content($module, $controller));
                break;
            case 'init':
                $this->make_file(PATH_ROOT.DS.'index.php', $this->get_framework_init('index'));
                $this->make_file(PATH_ROOT.DS.'debug.php', $this->get_framework_init('debug'));
                $this->make_file(PATH_ROOT.DS.'views'.DS.'index.html', $this->get_framework_init(''));
                $this->make_file(PATH_ROOT.DS.'public'.DS.'index.html', $this->get_framework_init(''));
                $this->make_file(PATH_ROOT.DS.'model'.DS.'base.php', $this->get_framework_init('model'));
                $this->make_file(PATH_ROOT.DS.'data'.DS.'base.php', $this->get_framework_init('data'));
                $this->make_file(PATH_ROOT.DS.'config'.DS.'nginx.conf', $this->get_framework_init('nginx'));
                $this->make_file(PATH_ROOT.DS.'config'.DS.'common.develop.php', $this->get_framework_init_config(''));
                $this->make_file(PATH_ROOT.DS.'config'.DS.'crontab.develop.php', $this->get_framework_init_config('crontab'));
                $this->make_file(PATH_ROOT.DS.'config'.DS.'pdo.develop.php', $this->get_framework_init_config(''));
                $this->make_file(PATH_ROOT.DS.'config'.DS.'redis.develop.php', $this->get_framework_init_config(''));
                $this->make_file(PATH_ROOT.DS.'logs'.DS.date('Ymd').'.txt', $this->get_framework_init_config(''));
                break;
            default:
                break;
        }
        return true;
    }
    public function get_web_class_content($m, $c){
        $tmp = "<?php

class fpm_{$m}_{$c} extends freak_fpm{

    public function init(){}
    // url query /?m={$m}&c={$c}&a=something
    public function something(){
        echo 'Hi, web';
    }
}";
        return $tmp;
    }
    public function get_cli_class_content($m, $c){
        $tmp = "<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';
ini_set('memory_limit', '246M');

class daemon_{$m}_{$c} extends freak_daemon {

    public function init(){}

    public function run(){
        //todo something
        echo 'Hi, daemon';
    }
}

new daemon_{$m}_{$c}();
    ";
        return $tmp;
    }
    public function get_framework_init_config($type){
        if($type == 'crontab') {
            return "<?php
/**
 *
 * 格式: 启动时间 脚本类型 脚本路径 进程数 版本号
 */
defined('FREAK_ACCESS') or exit('Access Denied');
return [
    #\"* * * * * php workers/test.php   2 1.0\",
];
";
        }
        return "<?php
defined('FREAK_ACCESS') or exit('Access Denied');
return [];";
    }
    public function get_framework_init($file){
        $v = VERSION;
        $r = PATH_ROOT;
        $files_map[''] = '';
        $files_map['index'] = "<?php
/**
 * freak.framework
 * @version: {$v}
 */
//--------Access-----------
define('FREAK_ACCESS', true);
//--------Access-----------
header('Content-type:text/html;charset=utf-8');
include dirname(__FILE__).'/freak/bootstrap.php';";
        $files_map['debug'] = "<?php
/**
 * freak.framework
 */
define('FREAK_ACCESS', true);
header('Content-type:text/html;charset=utf-8');

//-----------------------------------------
xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
//-----------------------------------------

include dirname(__FILE__).'/freak/bootstrap.php';

//-----------------------------------------
\$xhprof_data = xhprof_disable();
\$xhprof_root = '/Users/liuhui/vagrant/htdocs/xhprof';
include_once \$xhprof_root.'/xhprof_lib/utils/xhprof_lib.php';
include_once \$xhprof_root.'/xhprof_lib/utils/xhprof_runs.php';
\$xhprof_runs = new XHProfRuns_Default();
\$run_id = \$xhprof_runs->save_run(\$xhprof_data, 'xhprof_freak');
\$u = 'http://xhprof.com/?run=' . \$run_id . '&source=xhprof_freak';
echo \"<a href='{\$u}' target='_blank'>xhprof</a>\";
//-----------------------------------------";
        $files_map['model'] = "<?php
defined('FREAK_ACCESS') or exit('Access Denied');
abstract class model_base{}";
        $files_map['data'] = "<?php
defined('FREAK_ACCESS') or exit('Access Denied');
abstract class data_base{}";
        $files_map['nginx'] = "server {
        listen       80;
        server_name  freak.com;
        root {$r};
        sendfile off;
        access_log  /var/log/nginx/freak.com-access.log;
        error_log   /var/log/nginx/freak.com-error.log;
        index index.php;
        location ~ \\.php?\$ {
                fastcgi_pass 127.0.0.1:9000;
                fastcgi_index index.php;
                include fastcgi_params;
                fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                fastcgi_intercept_errors off;
                fastcgi_buffer_size 16k;
                fastcgi_buffers 4 16k;
        }
}";
        return $files_map[$file];
    }
    public function delete_dir_files($path){
        $op = opendir($path);
        while(false !== ($file = readdir($op))){
            if($file == '.' || $file == '..'){
                continue;
            }
            unlink($path.DS.$file);
        }
        closedir($op);
        return true;
    }
    public function make_file($path, $content){
        //var_dump($path, $content);
        $dir = dirname( $path );
        if(!is_dir($dir)){
            if(!mkdir($dir, 0777, true)){
                echo "ERROR mkdir {$dir} FAILED";
                return false;
            }
        }
        if(file_exists($path)){
            echo "ERROR file {$path} is EXISTS, please DELETE".PHP_EOL;
            return false;
        }

        $fp = fopen($path, 'a+');
        $fw = fwrite($fp, $content);
        if($fw === false){
            echo "ERROR write file {$path} is FAILED".PHP_EOL;
            return false;
        }
        fclose($fp);

        return true;
    }
}
new freak_build();