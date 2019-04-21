<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 12:18
 */

namespace app\api\controller;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;

class Product extends BaseController
{
    //获取最近新品
    public function getRecentProduct($count = 15) {
        (new Count())->goCheck($count);
        $result = ProductModel::getRecentProduct($count);
        return json($result);
    }

    //获取指定类的商品
    public function getProductByCategoryID($id) {
        $result = ProductModel::getProductByCategoryID($id);
        return json($result);
    }

    public function getOne($id) {
        $product = ProductModel::getOne($id);
        return json($product);
    }

}