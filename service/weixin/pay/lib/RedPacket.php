<?php
namespace phpbase\lib\weixin\pay\lib;

use phpbase\service\weixin\mp\Base;
use phpbase\service\weixin\lib\Xml;
/**
 * 微信支付红包
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\pay
 */
class RedPacket extends Base
{
    /**
     * 发送普通红包
     */
    public function sendNormal()
    {
        $this->values = [
            'nonce_str' => md5(time()),
            'mch_billno' => '20170626001',
            'mch_id' => '1392405902',
            'wxappid' => 'wx33fa9038d6a907de',
            'send_name' => 'shuowenwangluo',
            're_openid' => 'oa_HfwpaDesxBW_lv8RSfvYZ8T_Y',
            'total_amount' => 100,
            'total_num' => 1,
            'wishing' => '测试红包,感谢参与',
            'client_ip' => '115.159.42.133',
            'act_name' => '测试活动',
            'remark' => '测试活动remark'
        ];
        $this->setSign();
        return $this->request->sendRedPacketNormal(Xml::encode($this->values), $this->getCert());
    }
}