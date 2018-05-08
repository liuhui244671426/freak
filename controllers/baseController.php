<?php
defined(FREAK_ACCESS) or exit('Access Denied');

abstract class baseController{
    public function __construct(){$this->init();}
    public function __destruct(){}
    public function init(){}

    //__call
    //__callStatic
    //__get
    //__set
    //__isset
    //__unset
    //__sleep
    //__wakeup
    //__toString
    //__set_state
    //__clone
    //__autoload
}