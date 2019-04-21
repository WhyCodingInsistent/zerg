<?php
namespace app\api\controller;

use app\api\validate\IDMustBePositiveInt;
use think\Request;

class Banner extends BaseController
{
    public function getBanner()
    {
        $id = Request::instance()->param('id');
        (new IDMustBePositiveInt())->goCheck($id);
        $result = \app\api\model\Banner::getBannerByID($id);
        return $result;
    }
}
