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
    protected $config = [];

    /**
     * @var array
     */
    protected $error = [];

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * Request constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->curl = new Curl([
            'urlPrefix' => $this->config['weixinApi'],
            'jsonResult' => true,
        ]);
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
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
     * 对返回结果进行判断是否成功/失败
     * @param mixed $result
     * @return bool|string|array
     */
    protected function getResponse($result)
    {
        if ($result && is_array($result) && isset($result['errcode']) && $result['errcode'] != 0) {
            $this->error = $result;
            return false;
        }
        return $result;
    }
}