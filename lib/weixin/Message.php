<?php
namespace phpbase\lib\weixin;
/**
 * 消息管理
 * User: qianlei
 * Date: 17/06/2017
 */
class Message extends Base
{
    /**
     * 验证消息真实性
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421135319
     * @param string $signature 微信加密签名
     * @param int $timestamp 时间戳
     * @param int $nonce 随机数
     * @return bool
     */
    public function checkSignature($signature, $timestamp, $nonce)
    {
        $array = [$this->config['token'], $timestamp, $nonce];
        sort($array, SORT_STRING);
        return sha1(implode($array)) == $signature;
    }
}