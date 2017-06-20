<?php
namespace phpbase\lib\weixin;

/**
 * Class Base
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\curl
 */
class Base
{
    /**
     * @var array
     */
    protected $config = [];

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
        if ($initAccessToken) {
            $this->getAccessToken();
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
     */
    protected function getAccessToken()
    {
        $this->request->getAccessToken();
    }

    /**
     * 微信基础配置
     * @return array
     */
    private function getConfig()
    {
        if (!$this->config) {
            if (file_exists(__DIR__ . '/config/config.php')) {
                $this->config = require __DIR__ . '/config/config.php';
            }
        }
        return $this->config;
    }
}