<?php
if(PHP_SAPI != 'cli'){ exit('must command line(cli)'); }
require_once 'const.php';

class freak_build {

    public function __construct(){
        $this->welcome();
        $this->check_environment();
        $mode_map = [0 => 'init', 1 => 'fpm', 2 => 'daemon', 3 => 'clean non-freak'];
        fwrite(STDOUT, 'Please selection mode
    0 - Initialization Freak framework
    1 - Build web
    2 - Build daemon worker
    3 - Clean Non-freak file&directory
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
        if($input == 3){
            fwrite(STDOUT, 'confirm Y or N: ');
            $reconfirm = trim(fgets(STDIN));
            if($reconfirm == 'Y'){
                $this->clean_non_framework();
                echo "clean non-freak file&directory done!".PHP_EOL;
            }

        }
        return;
    }

    public function welcome(){
        echo 'Freak framework build tool'.PHP_EOL;
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
                $this->make_file(PATH_VIEW.DS.'index.html', $this->get_framework_init(''));
                $this->make_file(PATH_DATA.DS.'base.php', $this->get_framework_init('data'));
                $this->make_file(PATH_MODEL.DS.'base.php', $this->get_framework_init('model'));
                $this->make_file(PATH_ROOT.DS.'logs'.DS.date('Ymd').'.txt', $this->get_framework_init_config(''));
                $this->make_file(PATH_PUBLIC.DS.'index.html', $this->get_framework_init(''));
                $this->make_file(PATH_CONFIG.DS.'nginx.conf', $this->get_framework_init('nginx'));
                $this->make_file(PATH_CONFIG.DS.'common.develop.php', $this->get_framework_init_config(''));
                $this->make_file(PATH_CONFIG.DS.'crontab.develop.php', $this->get_framework_init_config('crontab'));
                $this->make_file(PATH_CONFIG.DS.'pdo.develop.php', $this->get_framework_init_config(''));
                $this->make_file(PATH_CONFIG.DS.'redis.develop.php', $this->get_framework_init_config(''));
                $this->make_file(PATH_CONFIG.DS.'router.develop.php', $this->get_framework_init_config('router'));
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
ini_set('memory_limit', '128M');

class daemon_{$m}_{$c} extends freak_daemon {

    public function init(){}

    public function run(){
        //todo something
        echo 'Hi, daemon';
    }
}

new daemon_{$m}_{$c}();";
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
        if($type == 'router') {
            return "<?php
defined('FREAK_ACCESS') or exit('Access Denied');
return [
    'mode' => 'simple', //url | simple | map
    'map' => [],
];";
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
define('FREAK_ACCESS', true);
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

        location / {
                try_files \$uri \$uri/ /index.php\$is_args\$args;
        }
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

    public function clean_non_framework(){
        $this->delTree(PATH_ROOT, ['freak', '.', '..', '.DS_Store', '.git', '.gitignore', '.idea', 'README.md']);
        return true;
    }

    public function delTree($dir, array $exclude) {
        $files = array_diff(scandir($dir), $exclude);
        if(empty($files)) return true;
        foreach ($files as $file) {
            (is_dir($dir.DS.$file)) ? $this->delTree($dir.DS.$file, $exclude) : unlink($dir.DS.$file);
        }
        return ($dir!=PATH_ROOT)?rmdir($dir):true;
    }

    public function check_environment(){
        if (version_compare(PHP_VERSION, '7.1.13') < 0) {exit('PHP version must >= 7.1.13');}
        $must_extension_map = [
            'gd', 'PDO', 'pdo_mysql', 'mbstring', 'redis', 'curl', 'mcrypt'
        ];
        foreach($must_extension_map as $k => $v){
            if (!extension_loaded($v)) {exit("PHP extension {$v} must exists");}
        }

    }
}
new freak_build();