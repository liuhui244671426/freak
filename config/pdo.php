<?php
defined('FREAK_ACCESS') or exit('Access Denied');
return array(
    'product' => array(
        'read' => array(),
        'write' => array(),
    ),
    'develop' => array(
        'read' => array(
            'dbname' => 'freak',
            'host' => '10.222.66.180',
            'port' => '3306',
            'user' => 'root',
            'password' => '123456',
        ),
        'write' => array(
            'dbname' => 'freak',
            'host' => '10.222.66.180',
            'port' => '3306',
            'user' => 'root',
            'password' => '123456',
        ),
    )
);