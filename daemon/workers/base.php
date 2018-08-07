<?php

if(PHP_SAPI != 'cli') exit('must cli mode');
abstract class daemon_workers_base {

    private $ip;
    private $pid;
    private $proc_total;
    private $proc_no;
    private $stop;

    public function __construct() {
        $this->proc_total = $_SERVER['argv'][1]?$_SERVER['argv'][1]:'';
        $this->proc_no = $_SERVER['argv'][2]?$_SERVER['argv'][2]:'';
        $this->stop = false;
        $this->pid = posix_getpid();
//        pcntl_signal(SIGTERM,	array(&$this, 'signalHandler'));
//        pcntl_signal(SIGINT,	array(&$this, 'signalHandler'));
//        pcntl_signal(SIGHUP,	array(&$this, 'signalHandler'));
//        pcntl_signal(SIGCHLD,	array(&$this, 'signalHandler'));
        $this->init();
        $this->run();
    }

    abstract public function run();
    abstract public function init();
    public function stop() {$this->stop = true;}

    public function isStop() { return $this->stop; } //pcntl_signal_dispatch();

    public function signalHandler($signo){
        switch ($signo) {
            case SIGUSR1:
                echo "SIGUSR1\n"; break;
            case SIGUSR2:
                echo "SIGUSR2\n"; break;
            case SIGTERM:
                echo "SIGTERM\n"; break;
            case SIGINT:
                echo "SIGINT\n"; break;
            case SIGHUP:
                echo "SIGHUP\n"; break;
            case SIGCHLD:
                echo "SIGCHLD\n"; break;
            default:
                echo "unknow";    break;
        }
    }


    protected function log($type, $content) {}

    //获取进程总数
    protected function getProcTotal() {return $this->proc_total;}

    //获取当前进程数,  从1开始
    protected function getProcNo() {return $this->proc_no;}

    //获取当前进程ID
    protected function getPid() {return $this->pid;}

    protected function getIp() {
        if($this->ip == null) {
            $str = "/sbin/ifconfig | grep 'inet addr' | awk '{ print $2 }' | awk -F ':' '{ print $2}' | head -1";
            $ip = exec($str);
            $this->ip = $ip;
        }
        return $this->ip;
    }

    //获取任务名称
    protected function getDaemonName() {return get_class($this);}
}