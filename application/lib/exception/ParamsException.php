<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 16:53
 */
namespace app\lib\exception;
class ParamsException extends BaseException
{
    public $code = 400;

    public $errorCode = 10000;

    public $message = "invalid parameters";
}