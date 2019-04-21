<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 12:49
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInt|between:1,20'
    ];
    protected $message = [
        'count' => 'count必须是1到20的正整数'
    ];
}