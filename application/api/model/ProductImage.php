<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/18
 * Time: 14:08
 */

namespace app\api\model;


use think\Model;

class ProductImage extends Model
{
    public function img() {
        return $this->belongsTo('Image','img_id','id');
    }
}