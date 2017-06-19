<?php
namespace phpbase\lib\Weixin;
/**
 * 微信对接基础类
 * User: qianlei
 * Date: 17/06/2017
 */
class Base
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * Base constructor.
     * @param array $config ['appId', 'token', 'encodingAESKey']
     */
    public function __construct($config = [])
    {
        if (empty($config)) {
            $config = $this->getConfig();
        }
        $this->config = $config;
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
}