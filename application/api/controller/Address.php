<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/18
 * Time: 14:37
 */

namespace app\api\controller;
use app\api\model\User;
use app\api\service\UserToken;
use app\lib\exception\SuccessMessage;
use think\Request;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'userAddress']
    ];

    public function userAddress() {
        $uid = UserToken::getValueByKey('uid');
        $user = User::get($uid);
        if(!$user) {
            //抛出自定义异常用户不存在
        }
        $params = Request::instance()->param();
        $validate = new \app\api\validate\Address();
        $params = $validate->getParamsByRules($params);
        $validate->goCheck($params);
        $userAddress = $user->address;
        if(!$userAddress) {
            //如果用户地址不存在则新增
            $user->address()->save($params);
        } else {
            //更新用户地址
            $user->address->save($params);
        }

        return new SuccessMessage();
    }
}