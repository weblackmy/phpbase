<?php

namespace phpbase\service;

use phpbase\lib\util\Arrays;
/**
 * Class SConfig
 * @package phpbase\service
 * @author qian lei
 */
class SConfig
{
    /**
     * @var array 
     */
    private static $config = [];

    /**
     * get config
     * @param  string|\Closure|array $key
     * @return mixed
     */
    public static function get($key)
    {
        return Arrays::get(self::$config, $key, null);
    }

    /**
     * set config
     * @param string $key
     * @param array $config
     */
    public static function set($key, array $config)
    {
        self::$config[$key] = $config;
    }

    /**
     * Mail模版参数
     * return array
     */
    public static function getMail()
    {
        return Arrays::get(self::$config, 'mail', [
            'host' => 'smtp.exmail.qq.com',
            'port' => '465',
            'encryption' => 'ssl',
            'username' => 'xxx',
            'password' => 'xxx',
        ]);
    }

    /**
     * 七牛云模板参数
     * @return array
     */
    public static function getQiniu()
    {
        return Arrays::get(self::$config, 'qiniu', [
            'accessKey' => '',
            'secretKey' => '',
        ]);
    }

    /**
     * 微信模板参数
     * @return array
     */
    public static function getWxMp()
    {
        return Arrays::get(self::$config, 'wxMp', [
            'appId' => '',
            'appSecret' => '',
            'token' => '',
            'encodingAESKey' => '',
        ]);
    }

    /**
     * 微信支付模板参数
     * @return mixed
     */
    public static function getWxPay()
    {
        return Arrays::get(self::$config, 'wxPay', [
            'payApi' => '',
            'appId' => '',//绑定支付的APPID
            'mchId' => '',//商户号
            'key' => '',//商户支付密钥
            'sslCert' => '',
            'sslKey' => '',
            'sslCa' => '',
        ]);
    }
}