<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 17:05
 */
namespace app\api\model;
use think\Model;

class BannerItem extends Model
{
    public function img() {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}