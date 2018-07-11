<?php
namespace phpbase\service\weixin\mp;

use phpbase\lib\redis\Redis;
use phpbase\lib\util\Arrays;
use phpbase\service\SConfig;

/**
 * Class Base 微信公众号基础类
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\mp
 */
class Base
{
    /**
     * @var string
     */
    const weixinApi = 'https://api.weixin.qq.com';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $accessTokenCacheKey;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Base constructor.
     * @param bool $initAccessToken
     * @throws \Exception
     */
    public function __construct($initAccessToken = true)
    {
        $this->config = SConfig::getWxMp();
        $this->accessTokenCacheKey = Arrays::get($this->config, 'accessTokenCacheKey', 'wxAccessToken');
        $this->request = new Request($this->config);
        $this->request->setCurlOptions([
            'urlPrefix' => self::weixinApi,
            'jsonResult' => true,
        ]);
        if ($initAccessToken) {
            $this->getAccessToken();// TODO try cache
            $this->request->setAccessToken($this->accessToken);
        }
    }

    /**
     * 加密消息
     */
    protected function encryptMsg()
    {}

    /**
     * 解密消息
     */
    protected function decryptMsg()
    {}

    /**
     * @return string
     * @throws \Exception
     */
    protected function getAccessToken()
    {
        $accessToken = Redis::get($this->accessTokenCacheKey);
        if (!$accessToken) {
            if (($result = $this->request->getAccessToken()) === false) {
                throw new \Exception($this->request->getErrMsg(), $this->request->getErrCode());
            }
            Redis::set($this->accessTokenCacheKey, $result['access_token'], $result['expires_in']);
            $accessToken = $result['access_token'];
        }
        return $accessToken;
    }
}