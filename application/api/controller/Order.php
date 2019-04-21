<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/19
 * Time: 12:45
 */

namespace app\api\controller;
use app\api\service\UserToken;
use app\api\validate\OrderParams;

class Order extends BaseController
{
    public function placeOrder() {
        (new OrderParams())->goCheck();
        $orderService = new \app\api\service\Order();
        $uid = UserToken::getValueByKey('uid');
        $oProduct = input('post.products/a'); // /a接受数组参数
        return $status = $orderService->place($uid, $oProduct);
    }
}