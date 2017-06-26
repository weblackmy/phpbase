<?php
namespace phpbase\lib\weixin\mp;

use Desarrolla2\Cache\Adapter\File;
/**
 * Class Base 微信公众号基础类
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\mp
 */
class Base
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Base constructor.
     * @param bool $initAccessToken
     */
    public function __construct($initAccessToken = true)
    {
        $this->config = $this->getConfig();
        $this->request = new Request($this->config);
        $this->request->setCurlOptions([
            'urlPrefix' => $this->config['weixinApi'],
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
     * @param string $cacheKey
     * @return string
     * @throws \Exception
     */
    protected function getAccessToken($cacheKey = 'weixinAccessToken')
    {
        $cache = new File($this->config['cacheDir']);
        if (!$accessToken = $cache->get($cacheKey)) {
            if (($result = $this->request->getAccessToken()) === false) {
                throw new \Exception($this->request->getErrMsg(), $this->request->getErrCode());
            }
            $cache->set($cacheKey, $result['access_token'], $result['expires_in']);
            $accessToken = $result['access_token'];
        }
        return $accessToken;
    }

    /**
     * 微信基础配置
     * @return array
     */
    protected function getConfig()
    {
        if (!$this->config) {
            if (file_exists(__DIR__ . '/config/config.php')) {
                $this->config = require __DIR__ . '/config/config.php';
            }
        }
        return $this->config;
    }
}