<?php
namespace phpbase\lib\weixin\pay;

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
    /*************************************************** 微信支付-红包 **************************************************/
    /**
     * 发送普通红包
     * @param string $postXml 红包参数,需要转换为xml
     * @param array $curlOptions
     * @return string
     */
    public function sendRedPacketNormal($postXml, $curlOptions = [])
    {
        return $this->curl->post('/mmpaymkttransfers/sendredpack', $postXml, $curlOptions);
    }

    /**
     * 对返回结果进行判断是否成功/失败
     * @param mixed $result
     * @return bool|string|array
     */
    protected function getResponse($result)
    {
        
    }
}