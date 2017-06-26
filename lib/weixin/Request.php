<?php
namespace phpbase\lib\weixin;

use phpbase\lib\curl\Curl;
/**
 * Class Request
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\curl
 */
class Request
{
    /**
     * @var array 公账号基础配置
     */
    protected $config;

    /**
     * @var string
     */
    protected $accessToken;

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

    /**
     * 获取accessToken
     * @return bool|array
     */
    public function getAccessToken()
    {
        $params = [
            'grant_type' => 'client_credential',
            'appid' => $this->config['appId'],
            'secret' => $this->config['appSecret'],
        ];
        return $this->getResponse($this->curl->get('/token', $params));
    }

    /**
     * 初始获取accessToken后, 后续调用需要用到此token(因为Reqeust类中的getAccessToken不负责缓存token,所以后续调用方需要传值进来)
     * @param string $token
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }
    /***************************************************** 账号管理 ****************************************************/
    /**
     * 生成带参数的二维码--创建二维码ticket
     * @param string $actionName QR_SCENE => 临时, QR_LIMIT_SCENE => 永久, QR_LIMIT_STR_SCENE => 永久的字符串参数值
     * @param int|string $sceneVal 场景值
     * @param int $expireSeconds 仅当$actionName为QR_SCENE时有效, 最大不超过24*3600*30秒
     * @return array|bool|string
     */
    public function qrCodeTicketCreate($actionName, $sceneVal, $expireSeconds = 24*3600*30)
    {
        $params = [
            'action_name' => $actionName,
            'expire_seconds' => $expireSeconds,
            'action_info' => [
                $actionName == 'QR_LIMIT_STR_SCENE' ? 'scene_str' : 'scene_id' => $sceneVal,
            ],
        ];
        return $this->getResponse($this->curl->post('/qrcode/create?access_token='.$this->accessToken, $params));
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
        if ($result && is_array($result) && isset($result['errcode']) && $result['errcode'] != 0) {
            $this->errCode = $result['errcode'];
            $this->errMsg = $result['errmsg'];
            return false;
        }
        return $result;
    }
}