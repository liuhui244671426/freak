<?php
return array(
    'product' => array(
        'server' => array(
            // 登陆跳转页
            'url_login' => 'http://sso.com/?m=sso&c=server&a=index',
            // token 检测
            'url_check' => 'http://sso.com/?m=sso&c=server&a=check',
        ),
        'cookie_expire' => time()+86400,
        //'client' => array(
        //'callback' => ''
        //),
    ),
    'develop' => array(
        'server' => array(
            // 登陆跳转页
            'url_login' => 'http://sso.com/?m=sso&c=server&a=index',
            // token 检测
            'url_check' => 'http://sso.com/?m=sso&c=server&a=check',
        ),
        'cookie_expire' => time()+86400,
        //'client' => array(
        //'callback' => ''
        //),
    )

);