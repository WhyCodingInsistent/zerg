<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 16:02
 */
namespace app\lib\exception;
use think\Exception;

class BaseException extends Exception
{
    public $code = 400;

    public $message = 'invalid params';

    public $errorCode = 500;

    public function __construct($params = [])
    {
        if(!is_array($params)) {
            return;
        }
        if(array_key_exists('code',$params)) {
            $this->code = $params['code'];
        }
        if(array_key_exists('message', $params)) {
            $this->message = $params['message'];
        }
        if(array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }
    }
}