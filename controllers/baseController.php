<?php
/**
 *
 *
 * author liuhui9<liuhui9@staff.sina.com.cn>
 * @version 2018/5/3
 * @copyright copyright(2018) weibo.com all rights reserved
 */
!defined(FREAK_ACCESS) or exit('NOT ACCESS');

class baseController{
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