<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 19:48
 */
return [
    'appID'     => '',
    'appSecret' => '',
    'loginUrl'  => "https://api.weixin.qq.com/sns/jscode2session?" .
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];