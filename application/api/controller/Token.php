<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 19:43
 */

namespace app\api\controller;


use app\api\service\UserToken;
use think\Request;

class Token extends BaseController
{
    public function getToken() {
        $code = Request::instance()->post('code');
        $token = (new UserToken($code))->get();
        //将获取到的token传给用户端
        return $token;
    }

}