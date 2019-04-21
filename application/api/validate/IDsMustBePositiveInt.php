<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 20:47
 */

namespace app\api\validate;


class IDsMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|idsMustBePositiveInt'
    ];

    protected $message = [
        'ids' => 'ids必须是以逗号分隔的正整数'
    ];

    protected function idsMustBePositiveInt($value) {
        if(!$ids = explode(',', $value))
        {
            return false;
        }
        foreach ($ids as $v) {
            if(!$res = $this->isPositiveInt($v)) {
                return false;
            }
        }
        return true;
    }
}