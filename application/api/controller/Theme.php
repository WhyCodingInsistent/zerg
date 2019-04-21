<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 20:44
 */

namespace app\api\controller;


use app\api\validate\IDsMustBePositiveInt;

class Theme extends BaseController
{
    public function getSimpleList($ids = '') {
        (new IDsMustBePositiveInt())->goCheck($ids);
        $ids = explode(',', $ids);
        $theme = model('Theme');
        $result = $theme->with('topicImg')
                        ->with('headImg')
                        ->select($ids);
        return json($result);
    }
}