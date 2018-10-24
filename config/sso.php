<?php
return array(
    'product' => array(
        'server' => array(
            // 登陆跳转页
            'url_login' => 'http://sso.com/?m=sso&c=server&a=index',
            // 通过token获取用户信息
            'url_check' => 'http://sso.com/?m=sso&c=server&a=login_captcha',
        ),
        'cookie_expire' => time()+86400,

    ),
    'develop' => array(
        'server' => array(
            // 登陆跳转页
            'url_login' => 'http://sso.com/?m=sso&c=server&a=index',
            // 通过token获取用户信息
            'url_check' => 'http://sso.com/?m=sso&c=server&a=get_user_info',
        ),
        'cookie_expire' => time()+86400,
    )

);