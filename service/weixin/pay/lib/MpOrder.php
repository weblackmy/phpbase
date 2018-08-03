<?php
namespace phpbase\service\weixin\pay\lib;

use phpbase\lib\util\Msg;
use phpbase\service\weixin\pay\Base;
use phpbase\service\weixin\lib\Xml;
/**
 * 小程序支付
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\pay
 */
class MpOrder extends Base
{
    /**
     * 统一下单
     */
    public function unifiedOrder($param)
    {
        $this->values = [
            'appid' => $this->config['appId'],
            'mch_id' => $this->config['mchId'],
            'nonce_str' => $this->getNonceStr(),
            'body' => $param['goods'],
            'out_trade_no' => $param['tradeNo'],
            'total_fee' => $param['money'],
            'spbill_create_ip' => $param['ip'],
            'notify_url' => $param['notifyUrl'],
            'trade_type' => 'JSAPI',
            'openid' => $param['openId']
        ];
        $this->setSign();
        return $this->request->sendUnifiedOrder(Xml::encode($this->values));
    }

    /**
     * 支付所需参数
     */
    public function payParams($param)
    {
        $this->values = [
            'appId' => $this->config['appId'],
            'timeStamp' => (string)time(),
            'nonceStr' => $this->getNonceStr(),
            'package' => 'prepay_id=' . $param['prepayId'],
            'signType' => 'MD5',
        ];
        $this->setSign();
        return [
            'timeStamp' => $this->values['timeStamp'],
            'nonceStr' => $this->values['nonceStr'],
            'package' => $this->values['package'],
            'signType' => $this->values['signType'],
            'paySign' => $this->getSign(),
        ];
    }

    /**
     * 查询订单
     */
    public function orderQuery($param)
    {
        $this->values = [
            'appid' => $this->config['appId'],
            'mch_id' => $this->config['mchId'],
            'transaction_id' => $param['transactionId'],
            'out_trade_no' => $param['tradeNo'],
            'nonce_str' => $this->getNonceStr(),
        ];
        $this->setSign();
        return $this->request->sendOrderQuery(Xml::encode($this->values));
    }

    /**
     * 支付结果通知
     * @param string $xmlData
     * @param array $callback 回调函数
     * @return function
     */
    public function payNotify($xmlData, $callback)
    {
        try {
            $this->values = Xml::decode($xmlData);
            $this->CheckSign();
        } catch (\Exception $e) {
            Msg::setMsg($e->getMessage());
            return false;
        }
        return call_user_func($callback, $this->values);
    }

    /**
     * 回复通知
     * @param array $param
     * @param bool $needSign 是否需要签名输出
     * @return string
     */
    public function replyNotify($param, $needSign = true)
    {
        $this->values = [
            'return_code' => $param['returnCode'],
            'return_msg' => $param['returnMsg'],
        ];

        if ($needSign == true && $this->getReturn_code() == "SUCCESS") {
            $this->SetSign();
        }
        return Xml::encode($this->values);
    }
}