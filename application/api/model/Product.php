<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 20:22
 */

namespace app\api\model;

use think\Model;

class Product extends Model
{
    public function getMainImgUrlAttr($value, $data) {
        return $this->processUrl($value, $data);
    }
    public function images() {
        return $this->hasMany('ProductImage','product_id','id');
    }

    protected function processUrl($value, $data) {
        $imgUrl = '';
        if($data['from'] == 1) {
            $imgUrl = config('queue.imgUrl')  . $value;
        } else {
            $imgUrl = $value;
        }
        return $imgUrl;
    }

    public function property() {
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public static function getRecentProduct($count) {
        return self::order('create_time desc')
            ->limit($count)
            ->select();
    }

    public static function getProductByCategoryID($id) {
        return self::where('category_id', '=', $id)
            ->select();
    }

    public static function getOne($id) {
        $result = self::with('property')
            ->with([
                'images' => function($query) {
                    $query->with('img')
                        ->order('order');
                }
            ])
            ->select($id);
        return $result;
    }
}