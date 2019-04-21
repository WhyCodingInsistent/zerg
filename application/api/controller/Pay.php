<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/20
 * Time: 11:47
 */

namespace app\api\controller;


use app\api\validate\IDMustBePositiveInt;

class Pay extends BaseController
{
    public function pay($id) {
        (new IDMustBePositiveInt())->goCheck();
        return (new \app\api\service\Pay($id))->pay();
    }
}