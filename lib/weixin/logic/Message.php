<?php
namespace phpbase\lib\weixin\logic;

use phpbase\lib\weixin\Base;
/**
 * Class Message 消息管理
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\curl
 */
class Message extends Base
{
    /**
     * 验证消息真实性
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421135319
     * @param string $signature 微信加密签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机数
     * @return bool
     */
    public function checkSignature($signature, $timestamp, $nonce)
    {
        $array = [$this->config['token'], $timestamp, $nonce];
        sort($array, SORT_STRING);
        return sha1(implode($array)) == $signature;
    }
}