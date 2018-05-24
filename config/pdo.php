<?php
defined('FREAK_ACCESS') or exit('Access Denied');
return array(
    'product' => array(
        'read' => array(),
        'write' => array(),
    ),
    'develop' => array(
        'read' => array(
            'dbname' => 'demo',
            'host' => '192.168.1.65',
            'port' => '3306',
            'user' => 'root',
            'password' => '123456',
        ),
        'write' => array(
            'dbname' => 'demo',
            'host' => '192.168.1.65',
            'port' => '3306',
            'user' => 'root',
            'password' => '123456',
        ),
    )
);