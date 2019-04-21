<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 19:45
 */
namespace app\api\service;
use app\api\model\User;
use think\Cache;
use think\Exception;
use think\Request;
use app\api\lib\enum;
class UserToken
{
    protected $appID;

    protected $appSecret;

    protected $loginUrl;

    public function __construct($code)
    {
        $this->appID = config('wx.appID');
        $this->appSecret = config('wx.appSecret');
        $this->loginUrl = sprintf(config('wx.loginUrl'), $this->appID, $this->appSecret, $code);
    }



    public function get() {
        $result = curl_get($this->loginUrl);
        $wxResult = json_decode($result);
        if(empty($wxResult)) {
            throw new \think\Exception('获取open_id异常');
        }
        if(array_key_exists('errcode', $wxResult)) {
            //抛出自定义异常
        }

        return $this->grantToken($wxResult);
    }

    private function grantToken($wxResult) {
        //在数据库里查询是否有该open_id,如果有则不处理，没有则添加此open_id
        //生成token
        //将key：token  value:wxResult, uid(代替open_id)存入缓存中

        $cachedValue = [];
         $user = User::checkOpenID($wxResult['open_id']);
        if(!$user) {
            $user = User::createOpenID($wxResult['open_id']);
            $uid = $user->id;
        } else {
            $uid = $user->id;
        }
        $token = generateToken();
        $cachedValue['wxresult'] = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = 16;
        $this->writeToCached($token, $cachedValue);
        return $token;
    }


    private function writeToCached($token, $cachedValue) {
        $value = json_encode($cachedValue);
        $result = cache($token, $value);
        if(!$result) {
            //抛出异常
        }
    }

    //从缓存中获取用户传过来的token对应的value

    public static function getValueByKey($key) {
        $token = Request::instance()->header('token');
        if(!$token) {
            //抛出自定义的token异常
        }
        $value = Cache::get($token);
        $value = json_decode($value);
        if(array_key_exists($value)) {
            return $value[$key];
        } else {
            throw new Exception('请求的key不存在');
        }
    }

    public static function checkPrimaryScope() {
        $scope = self::getValueByKey('scope');
        if($scope >= enum\ScopeEnum::USER) {
            return true;
        } else {
            //抛出用户无权限异常
        }
    }


    public static function checkSuperScope() {
        $scope = self::getValueByKey('scope');
        if($scope >= enum\ScopeEnum::SUPER) {
            return true;
        } else {
            //抛出用户无权限异常
        }
    }


}