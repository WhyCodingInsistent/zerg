<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 12:18
 */

namespace app\api\controller;


class Category extends BaseController
{
    public function getAllCategories() {
        $result = \app\api\model\Category::all([],'topicImg');
        return json($result);
    }
}