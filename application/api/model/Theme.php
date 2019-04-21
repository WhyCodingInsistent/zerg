<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 20:22
 */

namespace app\api\model;

use think\Model;

class Theme extends Model
{
    public function topicImg() {
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public function headImg() {
        return $this->belongsTo('Image','head_img_id','id');

    }

    public function products() {
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }
}