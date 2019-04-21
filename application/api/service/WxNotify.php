<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/20
 * Time: 14:38
 */

namespace app\api\service;
use app\api\lib\enum\Pay;
use app\api\model\Order;
use app\api\model\Product;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');


class WxNotify extends \WxPayNotify
{

    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];
            $order = Order::where('order_no','=',$orderNo)->find();
            $orderService = new \app\api\service\Order();
            $status = $orderService->checkOrderByID($order->order_id);
            if($status['pass'] == true) {
                $this->reduceStock($status);
                $this->changeStatus($order->order_id, true);
            } else {
                $this->changeStatus($order->order_id, false);
            }
        }
    }

    private function reduceStock($status) {
        foreach ($status['eachProductStatus'] as $value) {
            Product::where('id', '=', $value['id'])->setDec('stock', $value['count']);
        }
    }

    private function changeStatus($orderID, $isStock=false) {
        $status = $isStock? Pay::PAID : Pay::PAID_BUT_OUT_OF;
        Order::where('id', '=', $orderID)->update(['status' => $status]);
    }
}