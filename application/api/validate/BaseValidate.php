<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 15:25
 */
namespace app\api\validate;
use app\lib\exception\ParamsException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck() {
        $params = Request::instance()->param();
        if(!$this->check($params)) {
            $exception = new ParamsException([
                'message' => $this->error
            ]);
            throw $exception;
        } else {
            return true;
        }
    }

    protected function isPositiveInt($value, $rule='', $data='', $field='') {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return false;
    }

    public function getParamsByRules($params) {
        if(array_key_exists('uid',$params) && array_key_exists('uid',$params)) {
            //抛出自定义恶意行为的异常
        } else {
            $newParamsArray = [];
            foreach ($this->rule as $key => $value) {
                if(array_key_exists($key, $params)) {
                    $newParamsArray[$key] = $params[$key];
                }
            }
        }
        return $newParamsArray;
    }

}