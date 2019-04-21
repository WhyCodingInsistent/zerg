<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 20:30
 */

namespace app\api\model;


use think\Model;

class User extends Model
{
    public function address() {
        return $this->hasOne('UserAddress','user_id','id');
    }

    public static function checkOpenID($openID) {
        return self::where('openid', '=', $openID)->find();
    }

    public static function createOpenID($openID) {
        return self::create([
            'openid' => $openID
        ]);
    }
}