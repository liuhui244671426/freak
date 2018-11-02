<?php
defined('FREAK_ACCESS') or exit('Access Denied');
return array(
    'product' => array(
        'read' => array(),
        'write' => array(),
    ),
    'develop' => array(
        'freak' => array(
            'read' => array(
                'dbname' => 'freak',
                'host' => '10.222.96.146',
                'port' => '3306',
                'user' => 'root',
                'password' => '123456',
            ),
            'write' => array(
                'dbname' => 'freak',
                'host' => '10.222.96.146',
                'port' => '3306',
                'user' => 'root',
                'password' => '123456',
            ),
        ),
        'weibo' => array(
            'read' => array(
                'dbname' => 'liuhui',
                'host' => '10.210.237.104',
                'port' => '3306',
                'user' => 'root',
                'password' => '12345',
            ),
            'write' => array(
                'dbname' => 'liuhui',
                'host' => '10.210.237.104',
                'port' => '3306',
                'user' => 'root',
                'password' => '12345',
            ),
        ),

    )
);