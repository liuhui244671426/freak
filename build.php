<?php
if(PHP_SAPI != 'cli'){
    return false;
}
define('PATH_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

echo 'Freak 框架构建工具'.PHP_EOL.PHP_EOL;

$mode_map = [1 => 'fpm', 2 => 'daemon', 11 => 'clear',];
fwrite(STDOUT, '选择模式
1:创建网页接口
2:创建任务脚本
11:清理缓存
请输入数字: ');
$input = trim(fgets(STDIN));
$mode = $mode_map[$input];
if($mode == NULL) {
    echo "ERROR mode NOT EXISTS";return;
}

if($input == 11){
    foreach ([PATH_ROOT.DS.'logs', PATH_ROOT.DS.'download'.DS.'images'] as $path) {
        $res = clear_cache($path);
        if($res){
            echo "SUCCESS clean dir: {$path} ".PHP_EOL;
        }
    }
}
if($input == 1 || $input == 2){
    fwrite(STDOUT, '请输入 module: ');
    $module = trim(fgets(STDIN));
    fwrite(STDOUT, '请输入 controller: ');
    $controller = trim(fgets(STDIN));

    make_file($mode, $module, $controller);
}
return;


function build_web_class($m, $c){
    $tmp = "<?php

class fpm_{$m}_{$c} extends fpm_base{

    public function init(){}
    // url query /?m={$m}&c={$c}&a=something
    public function something(){
        echo 'Hi, web';
    }
}";
    return $tmp;
}

function build_cli_class($m, $c){
    $tmp = "<?php

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(dirname(dirname(__FILE__))).'/freak/bootstrap.php';

class daemon_{$m}_{$c} extends daemon_workers_base {

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

function clear_cache($path){
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

function make_file($mode, $module, $controller){
    $module_dir_path = PATH_ROOT.DS.$mode.DS.$module;
    $controller_file_path = $module_dir_path.DS.$controller.'.php';

    if(!is_dir($module_dir_path)){
        if(!mkdir($module_dir_path, 0777, true)){
            echo "ERROR mkdir {$module_dir_path} FAILED";
            return false;
        }
    }
    if(file_exists($controller_file_path)){
        echo "ERROR file {$controller_file_path} is EXISTS, please DELETE";
        return false;
    }

    $fp = fopen($controller_file_path, 'a+');
    $fw = false;
    if($mode == 'fpm'){
        $fw = fwrite($fp, build_web_class($module, $controller));
    }
    if($mode == 'daemon'){
        $fw = fwrite($fp, build_cli_class($module, $controller));
    }
    fclose($fp);

    if($fw === false){
        echo "ERROR write file {$controller_file_path} is FAILED".PHP_EOL;
        return false;
    }

    echo "SUCCESS write file {$controller_file_path}".PHP_EOL;
    return true;
}