<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/20
 * Time: 11:47
 */

namespace app\api\service;


use app\api\model\Order;
use think\Loader;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;

    private $orderNO;

    private $uid;

    public function __construct($id)
    {
        $this->orderID = $id;
        $this->uid = UserToken::getValueByKey('uid');
    }

    public function pay() {
        //检测订单号是否存在
        //检测订单是否是该用户创建
        //检测库存量
        if($this->checkOrderID()) {
            $status = (new \app\api\service\Order())->checkOrderByID($this->orderID);
            if($status['pass'] == false) {
                return $status;
            }
            //调用微信sdk发送预订单
            return makeWxProOrder($status['orderTotalPrice']);
        }
    }

    public function makeWxProOrder($totalPrice) {
        $openID = UserToken::getValueByKey('open_id');
        if(!$openID) {
            //抛出用户异常
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openID);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        //向微信服务器发送

        return $this->getPaySignature($wxOrderData);

    }

    //向微信请求订单号并生成签名
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        // 失败时不会返回result_code
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
//            throw new Exception('获取预支付订单失败');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder){
        // 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id是不同的
        Order::where('id', '=', $this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    // 签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();//生成签名
        $rawValues = $jsApiPayData->GetValues();//转化为数组
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }



    private function checkOrderID() {
        $order = Order::where('id', '=', $this->orderID)->find();
        if(!$order) {
            //抛出异常 订单不存在
        }
        if($order->user_id != $this->uid) {
            //抛出异常 用户订单不匹配
        }
        if($order->status != 1) {
            //抛出异常 订单已经支付过了
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}