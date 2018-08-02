<?php
namespace phpbase\service\weixin\pay;

use phpbase\service\weixin\lib\Xml;
use phpbase\lib\curl\Curl;
/**
 * Class Request
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\pay
 */
class Request
{
    /**
     * @var array 公账号基础配置
     */
    protected $config;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var int
     */
    protected $errCode;

    /**
     * @var string
     */
    protected $errMsg;

    /**
     * Request constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->curl = new Curl();
    }

    /**
     * @param array $options
     */
    public function setCurlOptions($options)
    {
        foreach ($options as $k => $v) {
            $this->curl->{$k} = $v;
        }
    }

    /**
     * @return int
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * @return string
     */
    public function getErrMsg()
    {
        return $this->errMsg;
    }

    /*************************************************** 微信支付 **************************************************/
    /**
     * 生成预支付交易单
     * @param string $postXml 订单参数
     * @return bool|array
     */
    public function sendUnifiedOrder($postXml)
    {
        return $this->getResponse($this->curl->post('/pay/unifiedorder', $postXml));
    }

    public function sendOrderQuery($postXml)
    {
        return $this->getResponse($this->curl->post('/pay/orderquery', $postXml));
    }

    /**
     * 发送普通红包
     * @param string $postXml 红包参数,需要转换为xml
     * @param array $curlOptions
     * @return string
     */
    public function sendRedPacketNormal($postXml, $curlOptions = [])
    {
        return $this->getResponse($this->curl->post('/mmpaymkttransfers/sendredpack', $postXml, $curlOptions));
    }

    /**
     * 返回结果
     * @param mixed $result
     * @return bool|string|array
     */
    protected function getResponse($result)
    {
        return Xml::decode($result);
    }
}