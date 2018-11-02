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
                'host' => '',
                'port' => '',
                'password' => '',
            ),
            'write' => array(
                'host' => '',
                'port' => '',
                'password' => '',
            ),
        ),
        'weibo' => array(
            'read' => array(
                'host' => '',
                'port' => '',
                'password' => '',
            ),
            'write' => array(
                'host' => '',
                'port' => '',
                'password' => '',
            ),
        ),
    )
);