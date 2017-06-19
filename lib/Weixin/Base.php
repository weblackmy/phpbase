<?php
namespace phpbase\lib\Weixin;
/**
 * 微信对接基础类
 * User: QianLei
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
     * @param array $config ['appid', 'token', 'encodingAESKey']
     */
    public function __construct($config = [])
    {
        if (empty($config)) {
            $config = [];
        }
        $this->config = $config;
    }

    /**
     * 微信基础配置
     * @return array
     */
    private function getConfig()
    {
        return [];
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