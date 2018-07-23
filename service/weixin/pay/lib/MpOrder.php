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
            'nonce_str' => md5(microtime(true)),
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
}