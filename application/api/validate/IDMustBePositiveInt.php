<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 15:40
 */
namespace app\api\validate;
class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInt'
    ];

    protected $message = [
        'id' => 'id必须是大于0的正整数',
        ];
}