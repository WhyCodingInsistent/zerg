<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/19
 * Time: 12:50
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use think\Exception;
use think\Log;

class Order
{
    private $oProduct;

    private $dataBaseProduct;

    private $uid;

    public function place($uid, $oProduct) {
        $this->uid = $uid;
        $this->oProduct = $oProduct;
        $this->dataBaseProduct = $this->getDataBaseProduct($this->oProduct);
        $orderStatus = $this->getOrderStatus($this->oProduct, $this->dataBaseProduct);
        if($orderStatus['isPass' == false]) {
            $orderStatus['orderStatus'] = -1;
            return $orderStatus;
        }
        // 创建订单快照，创建订单
        $snap = $this->createSnap($orderStatus);
        $orderStatus[] = $this->createOrder($snap);
        return $orderStatus;
    }

    private function getOrderStatus($oProduct, $dataBaseProduct) {
        $orderStatus = [
            'isPass'            => false,
            'orderTotalPrice'   => 0,
            'orderTotalCount'   => 0,
            'eachProductStatus' => []
        ];
        foreach ($oProduct as $value) {
            $eachStatus = $this->getEachProductStatus($value['product_id'], $value['count'], $this->dataBaseProduct);
            if($eachStatus['isEnough'] == false) {
                $orderStatus['isPass'] = false;
            }
            $orderStatus['orderTotalPrice'] += $eachStatus['totalPrice'];
            $orderStatus['orderTotalCount'] += $eachStatus['count'];
            array_push($orderStatus['eachProductStatus'], $eachStatus);
        }
        return $orderStatus;
    }

    private function getEachProductStatus($oProductID, $oCount, $dataBaseProduct) {
        $eachProductStatus = [
            'id' => 0,
            'name' => '',
            'totalPrice' => 0,
            'isEnough' => false,
            'count' => 0,
            'main_img_url' => '',
            'price' => 0,
        ];
        foreach ($dataBaseProduct as $value) {
            if($value[$oProductID]) {
                $eachProductStatus['id'] = $oProductID;
                $eachProductStatus['name'] = $value['name'];
                $eachProductStatus['totalPrice'] = $oCount * $value['price'];
                $eachProductStatus['count'] = $oCount;
                $eachProductStatus['name'] = $value['name'];
                $eachProductStatus['price'] = $value['price'];
                $eachProductStatus['main_img_url'] = $value['main_img_url'];
                if($oCount <= $value['stock']) {
                    $eachProductStatus['isEnough'] = true;
                } else {
                    $eachProductStatus['isEnough'] = false;
                }
            } else {
                //抛出异常订单里的商品不存在
            }
        }
        return $eachProductStatus;
    }

    private function getDataBaseProduct($oProduct) {
        $productIDArr = [];
        foreach ($oProduct as $value) {
            $productIDArr[] = $value['product_id'];
        }
        $dataBaseProductArr = Product::all($productIDArr);
        return $dataBaseProductArr;
    }


    //生成订单
    private function createOrder($snap) {
        try {
            $orderNo = self::makeOrderNo();
            $order = new \app\api\model\Order();
            $order->order_no = $orderNo;
            $order->user_id = $this->uid;
            $order->total_price = $snap['order_price'];
            $order->total_count = $snap['total_count'];
            $order->snap_img = $snap['snap_img'];
            $order->snap_name = $snap['snap_name'];
            $order->snap_address = $snap['snap_address'];
            $order->snap_items = $snap['snap_items'];
            $order->save();
            $orderID = $order->id;
            $createTime = $order->create_time;

            // 新增OrderProduct表
            foreach ($this->oProduct as &$value) {
                $value['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProduct);
        } catch (Exception $e) {
            Log::record($e->getMessage());
            throw $e;
        }
        return [
            'orderID' => $orderID,
            'orderNO' => $orderNo,
            'create_time' => $createTime
        ];
    }

    // 创建订单快照
    private function createSnap($orderStatus) {
        $snap = [
            'total_price' => 0,
            'total_count' => 0,
            'snap_name' => '',
            'snap_img' => '',
            'snap_address' => '',
            'snap_items' => ''
        ];
        $snap['snap_img'] = $this->dataBaseProduct[0]['main_img_url'];
        $snap['snap_name'] = $this->dataBaseProduct[0]['name'];
        $snap['total_price'] = $orderStatus['orderTotalPrice'];
        $snap['total_count'] = $orderStatus['orderTotalCount'];
        $snap['snap_address'] = json_encode($this->getAddress());
        $snap['snap_items'] = json_encode($this->getEachProductSnap());
        return $snap;
    }

    private function getAddress() {
        return UserAddress::where('user_id', '=', $this->uid)->select();
    }

    private function getEachProductSnap($orderStatus) {
        $totalpStatus = [];
        $pStatus = [
            'id' => null,
            'name' => null,
            'main_img_url'=>null,
            'count' => 0,
            'totalPrice' => 0,
            'price' => 0
        ];
        foreach ($orderStatus as &$value) {
            $pStatus['id'] = $value['eachProductStatus']['id'];
            $pStatus['name'] = $value['eachProductStatus']['name'];
            $pStatus['main_img_url'] = $value['eachProductStatus']['main_img_url'];
            $pStatus['count'] = $value['eachProductStatus']['count'];
            $pStatus['totalPrice'] = $value['eachProductStatus']['totalPrice'];
            $pStatus['price'] = $value['eachProductStatus']['price'];
            $totalpStatus[] = $pStatus;
        }
        return $totalpStatus;
    }


    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    public function checkOrderByID($id) {
        $oProduct = (new OrderProduct())::where('order_id', '=', $id)
            ->select();
        return $status = $this->getOrderStatus($oProduct,  $this->getDataBaseProduct($oProduct));
    }


}