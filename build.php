<?php
if(PHP_SAPI != 'cli'){
    return false;
}
define('PATH_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

echo 'freak world'.PHP_EOL;
echo '========================'.PHP_EOL.PHP_EOL;
$mode_map = array(
    1 => 'controller',
    2 => 'daemon',
);
fwrite(STDOUT, '选择模式(1:controller  2:daemon), 请输入数字: ');
$mode = trim(fgets(STDIN));
$mode = $mode_map[$mode];
if($mode == NULL) {
    echo "ERROR mode NOT EXISTS";return;
}
fwrite(STDOUT, '请输入 module: ');
$module = trim(fgets(STDIN));
fwrite(STDOUT, '请输入 controller: ');
$controller = trim(fgets(STDIN));


$module_dir_path = PATH_ROOT.DS.$mode.DS.$module;
$controller_file_path = $module_dir_path.DS.$controller.'.php';

if(!is_dir($module_dir_path)){
    if(!mkdir($module_dir_path, 0777, true)){
        echo "ERROR mkdir {$module_dir_path} FAILED";return;
    }
}
if(file_exists($controller_file_path)){
    echo "ERROR file {$controller_file_path} is EXISTS, please DELETE";return;
}

$fp = fopen($controller_file_path, 'a+');
$fw = false;
if($mode == 'controller'){
    $fw = fwrite($fp, build_web_class($module, $controller));
}
if($mode == 'daemon'){
    $fw = fwrite($fp, build_cli_class($module, $controller));
}
fclose($fp);

if($fw === false){
    echo "ERROR write file {$controller_file_path} is FAILED";return;
}

echo "SUCCESS write file {$controller_file_path}";return;


function build_web_class($m, $c){
    $tmp = "<?php

class controller_{$m}_{$c} extends controller_base{

    public function init(){}
    // url query /m={$m}&c={$c}&a=something
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
