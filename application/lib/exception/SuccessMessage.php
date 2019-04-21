<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/18
 * Time: 15:06
 */

namespace app\lib\exception;

class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}