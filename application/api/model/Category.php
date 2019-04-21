<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/17
 * Time: 12:16
 */

namespace app\api\model;

use think\Model;

class Category extends Model
{
    public function topicImg() {
        return $this->belongsTo('Image','topic_img_id','id');
    }
}