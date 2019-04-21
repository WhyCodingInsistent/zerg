<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 17:29
 */
namespace app\api\model;
use think\Model;

class Image extends Model
{
    public function getUrlAttr($value, $data) {
        return $this->prefixImgUrl($value, $data);
    }

    protected function prefixImgUrl($value, $data) {
        $imgUrl = '';
        if($data['from'] == 1) {
            $imgUrl = config('queue.imgUrl')  . $value;
        } else {
            $imgUrl = $value;
        }
        return $imgUrl;
    }
}