<?php
/**
 *
 * 定时任务启动器, 需要配置到系统的crontab里, 每分钟执行一次
 */

if(PHP_SAPI == 'cli') define('FREAK_ACCESS', true);
require_once dirname(__FILE__).'/bootstrap.php';

class monitor {
    const MAX_PROC = 128 ;  //每个任务最多并发进程数
    const CMD_PHP = '/usr/bin/php';
    const CMD_SH = '/usr/bin/sh';

    //正在运行的任务列表,  key: 脚本类型-脚本名称-进程总数-进程编号-版本号,value:进程PID
    private  $proc_running = array();
    //计划运行的任务列表, key: 脚本类型-脚本名称-进程总数-进程编号-版本号,value:启动时间
    private  $proc_plan = array();

    public function boot() {
        //只允许一个Monitor运行
        if($this->checkSelfRunning()) {
            $this->log("Crontab Booter Has Runing, Exited");
            exit;
        }
        $this->log("Crontab Booter Begin.");
        //构建正在运行的任务列表
        $this->buildProcRunning();
        //构建计划运行的任务列表
        $this->buildProcPlan();
        //杀死不在计划列表里的进程
        $this->log("Kill Useless Task:");
        foreach($this->proc_running as $proc_name=>$pid) {
            if (!isset($this->proc_plan[$proc_name])) {
                $this->killProc($pid);
                $this->log("Kill $proc_name, pid=$pid .");
            }
        }
        //启动在计划列表里, 且时间符合要求, 且未运行的任务.
        $this->log("Starting Task:");
        $t_now = time();
        $t_now_month = date("n", $t_now); // 没有前导0
        $t_now_day = date("j", $t_now); // 没有前导0
        $t_now_hour = date("G", $t_now);
        $t_now_minute = (int) date("i", $t_now);
        $t_now_week = date("w", $t_now); // w 0~6, 0:sunday  6:saturday
        foreach($this->proc_plan as $proc_name=>$start_time) {
            list($type, $path, $proc_total, $proc_no, $version) = explode("-", $proc_name);
            list($t_minute, $t_hour, $t_day, $t_month, $t_week) = explode(" ", $start_time);
            //检查启动时间是否符合条件
            if (!($this->checkTime($t_now_week, $t_week, 7, 0) &&
                $this->checkTime($t_now_month, $t_month, 12, 1) &&
                $this->checkTime($t_now_day, $t_day, 31, 1) &&
                $this->checkTime($t_now_hour, $t_hour, 24) &&
                $this->checkTime($t_now_minute, $t_minute, 60)
            )) {
                $this->log("$proc_name, TIME SKIPPED.");
                continue;
            }
            //检查是否正在运行
            if (isset($this->proc_running[$proc_name])) {
                $this->log("$proc_name, RUNNING.");
                continue;
            }
            //启动
            $this->startProc($type, $path, $proc_total, $proc_no,  $version);
            $this->log("$proc_name, STARTED.");
        }
        $this->log("Crontab Booter End.");
    }


    //检测自身进程是否存在
    private  function checkSelfRunning() {
        $_cmd = "ps -ef | grep -v 'sudo' | grep -v 'grep' | grep '".PATH_DAEMON . DS . "monitor.php' |grep -v \"/bin/sh \\-c\" | wc -l";
//        echo $_cmd ;exit;
        $_pp = @popen($_cmd, 'r');
        $_num = trim(@fread($_pp, 512)) + 0;
        @pclose($_pp);
        if ($_num > 1) {
            return true;
        }
        return false;
    }

    private function checkSeflChange() {}

    private  function buildProcRunning() {
        $cmd = "ps -ef | grep -v 'sudo' | grep -v 'grep' |grep -E '".PATH_DAEMON.DS. "workers'|awk -F  ' ' '{print $2,$8,$9,$10,$11,$12}' ";
        //echo $cmd;exit;
        $pp = @popen($cmd, 'r');

        while(!feof($pp)) {
            $line = trim(fgets($pp));
            if(empty($line)) continue;
            list($pid, $type, $path, $proc_total, $proc_no, $version) = explode(" ", $line);


            if ($type == self::CMD_PHP) {
                $type = 'php';
            } else if ($type == self::CMD_SH) {
                $type = 'sh';
            } else {
                continue;
            }

            $path = str_replace(PATH_DAEMON . DS, "", $path);
            if ($path == 'monitor.php') continue;

            $proc_name = $this->buildProcName($type, $path, $proc_total, $proc_no, $version);

            $this->proc_running[$proc_name] = $pid;
        }
        @pclose($pp);
    }


    private  function buildProcPlan() {
        $config = freak_config::get('crontab');
        foreach($config as $item) {
            $item = trim($item);
            $item = preg_replace ( "/\s(?=\s)/","\\1", $item ); //去除重复空格
            list($t_minute, $t_hour, $t_day, $t_month, $t_week, $type, $path, $proc_total, $version) = explode(" ", $item);

            //最多限制128个进程
            $proc_total = $proc_total > self::MAX_PROC ? self::MAX_PROC: $proc_total;

            for($proc_no = 1; $proc_no <= $proc_total; $proc_no++) {
                $proc_name = $this->buildProcName($type, $path, $proc_total, $proc_no, $version);
                $this->proc_plan[$proc_name] = "$t_minute $t_hour $t_day $t_month $t_week";
            }
        }
    }

    private function buildProcName($type, $path, $proc_total, $proc_no, $version) {
        return "$type-$path-$proc_total-$proc_no-$version";
    }

    private function log($content) {
        echo "[". date("Y-m-d H:i:s") ."] ". $content . "\n";
    }

    /**
     * 检查某个时间单位是否匹配当前时间单位
     * @param	mixed	$current  当前时间单位
     * @param	mixed	$boot	待检查的时间单位
     * @param	int $TotalCounts	待检查的时间单位最大值
     * @param	int $start	待检查的时间单位单位开始值（默认为0）
     * @return type
     */
    private static function checkTime($current, $boot, $max, $start = 0) {
        if (strpos($boot, ',') !== FALSE) {
            $weekArray = explode(',', $boot);
            if (in_array($current, $weekArray))
                return TRUE;
            return FALSE;
        }
        $array = explode('/', $boot);
        $end = $start + $max - 1;
        if (isset($array[1])) {
            if ($array[1] > $max)
                return FALSE;
            $tmps = explode('-', $array[0]);
            if (isset($tmps[1])) {
                if ($tmps[0] < 0 || $end < $tmps[1])
                    return FALSE;
                $start = $tmps[0];
                $end = $tmps[1];
            } else {
                if ($tmps[0] != '*')
                    return FALSE;
            }
            if (0 == (($current - $start) % $array[1]))
                return TRUE;
            return FALSE;
        }
        $tmps = explode('-', $array[0]);
        if (isset($tmps[1])) {
            if ($tmps[0] < 0 || $end < $tmps[1])
                return FALSE;
            if ($current >= $tmps[0] && $current <= $tmps[1])
                return TRUE;
            return FALSE;
        } else {
            if ($tmps[0] == '*' || $tmps[0] == $current)
                return TRUE;
            return FALSE;
        }
    }

    private function startProc($type, $path, $proc_total, $proc_no, $version) {
        if ($type == "php") {
            $cmd =  self::CMD_PHP . " " . PATH_DAEMON . DS . $path . " " . $proc_total . " ". $proc_no . " ". $version ." > /dev/null &";
        } else if ($type == 'sh') {
            $cmd = self::CMD_SH . " " . PATH_DAEMON . DS . $path . " " . $proc_total . " ". $proc_no . " ". $version ." > /dev/null &";
        } else {
            return false;
        }
        //var_dump($cmd);
        $pp = @popen($cmd, 'r');
        @pclose($pp);
    }

    private function killProc($pid) {
        $pid = intval($pid);
        return posix_kill($pid, SIGKILL);//直接 Kill 进程
    }

    /**
     * 查看当前正在运行的程序
     * */
    public function getRunningProcess(){
        $_cmd = "ps -ef | grep -v 'sudo' | grep -v 'grep' | grep '".PATH_DAEMON . DS . "' |grep -v \"/bin/sh \\-c\"";
        //echo $_cmd ;exit;
        $_pp = @popen($_cmd, 'r');
        $_num = fread($_pp, 5120);
        @pclose($_pp);
        echo "runningList: \n";
        echo $_num;
        echo PHP_EOL;
    }
}
$monitor = new monitor();
//if($argv[1] == 'list'){
//    $monitor->getRunningProcess();
//    return;
//}
$monitor->boot();
