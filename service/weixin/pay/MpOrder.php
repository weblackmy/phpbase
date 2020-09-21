<?php
namespace phpbase\service\weixin\pay;

use phpbase\lib\curl\Curl;
use phpbase\lib\util\Arrays;
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
     * 参考 https://pay.weixin.qq.com/wiki/doc/api/H5.php?chapter=9_20&index=1
     * @param array $params
     * @param string $type MWEB => H5支付
     * @return array|bool
     */
    public function unifiedOrder($params, $type)
    {
        $this->values = [];
        $this->values['appid'] = $this->config['appId'];
        $this->values['mch_id'] = $this->config['mchId'];
        $this->values['nonce_str'] = $this->getNonceStr();
        //当trade_type等于JS_API时, 此参数必传, 表示用户在商户appid下的唯一标识
        $this->values['openid'] = Arrays::get($params, 'openId', '');
        // 商品信息
        $this->values['body'] = Arrays::get($params, 'description');
        $this->values['out_trade_no'] = Arrays::get($params, 'orderSn');
        $this->values['total_fee'] = Arrays::get($params, 'money');
        $this->values['spbill_create_ip'] = Arrays::get($params, 'ip');
        $this->values['notify_url'] = Arrays::get($params, 'notifyUrl');
        // 交易方式
        if ($type == 'h5') {
            $this->values['trade_type'] = 'MWEB';
            $this->values['scene_info'] = json_encode([
                'h5_info' => [
                    'type' => 'WAP',
                    'wap_url' => Arrays::get($params, 'wapUrl'),
                    'wap_name' => Arrays::get($params, 'wapName')
                ]
            ]);
        }
        $this->setSign();
        return $this->getResponse($this->curl->post(self::unifiedOrderApi, Xml::encode($this->values), [
            'postType' => Curl::POST_TYPE_XML_TEXT,
        ]));
    }

    /**
     * 支付所需参数
     */
//    public function payParams($params)
//    {
//        $this->values = [
//            'appId' => $this->config['appId'],
//            'timeStamp' => (string)time(),
//            'nonceStr' => $this->getNonceStr(),
//            'package' => 'prepay_id=' . $params['prepayId'],
//            'signType' => 'MD5',
//        ];
//        $this->setSign();
//        return [
//            'timeStamp' => $this->values['timeStamp'],
//            'nonceStr' => $this->values['nonceStr'],
//            'package' => $this->values['package'],
//            'signType' => $this->values['signType'],
//            'paySign' => $this->getSign(),
//        ];
//    }


    /**
     * 支付结果通知
     * @param string $xmlData
     * @param array $callback 回调函数
     * @return function
     */
//    public function payNotify($xmlData, $callback)
//    {
//        try {
//            $this->values = Xml::decode($xmlData);
//            $this->CheckSign();
//        } catch (\Exception $e) {
//            Msg::setMsg($e->getMessage());
//            return false;
//        }
//        return call_user_func($callback, $this->values);
//    }

    /**
     * 回复通知
     * @param array $param
     * @param bool $needSign 是否需要签名输出
     * @return string
     */
//    public function replyNotify($param, $needSign = true)
//    {
//        $this->values = [
//            'return_code' => $param['returnCode'],
//            'return_msg' => $param['returnMsg'],
//        ];
//
//        if ($needSign == true && $this->getReturn_code() == "SUCCESS") {
//            $this->SetSign();
//        }
//        return Xml::encode($this->values);
//    }
}
