<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/20
 * Time: 14:50
 */

namespace app\api\lib\enum;


class Pay
{
    // 待支付
    const UNPAID = 1;

    // 已支付
    const PAID = 2;

    // 已发货
    const DELIVERED = 3;

    // 已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;

    // 已处理PAID_BUT_OUT_OF
    const HANDLED_OUT_OF = 5;
}