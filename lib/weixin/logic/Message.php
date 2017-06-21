<?php
namespace phpbase\lib\weixin\logic;

use phpbase\lib\weixin\Base;
use phpbase\lib\log\Log;
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

    /**
     * 接收/解析微信发来的消息
     * @param string $xmlData
     * @return bool|string
     */
    public function parseMessage($xmlData)
    {
        try {
            $xml = $this->parseXml($xmlData);
            //解析MsgType
            $method = 'callbackMsgType'.ucfirst($xml['MsgType']);
            if (!method_exists($this, $method)) {
                throw new \Exception('callback method not exist:'.$method);
            }
            return call_user_func_array([$this, $method], [$xml]);
        } catch (\Exception $e) {
            Log::error('weixin-parseMessage', $e->getMessage());
            return false;
        }
    }

    /**
     * 解析微信服务器的xml消息
     * @param string $xmlData
     * @return array
     * @throws \Exception
     */
    protected function parseXml($xmlData)
    {
        if (false === ($data = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA))) {
            throw new \Exception('xml parse error:'.$xmlData);
        }
        return (array)$data;
    }

    /**
     * 文本消息(子类继承实现业务逻辑)
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeText($data)
    {
        return $this->replyTextMessage($data, '你好'.date('Y-m-d H:i:s'));
    }

    /**
     * 图片消息
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeImage($data)
    {
        return true;
    }

    /**
     * 语音消息
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeVoice($data)
    {
        return true;
    }

    /**
     * 视频消息
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeVideo($data)
    {
        return true;
    }

    /**
     * 小视频消息
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeShortvideo($data)
    {
        return true;
    }

    /**
     * 地理位置消息
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeLocation($data)
    {
        return true;
    }

    /**
     * 链接消息消息
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeLink($data)
    {
        return true;
    }

    /**
     * 事件消息
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeEvent($data)
    {
        return true;
    }

    /**
     * 接收微信消息后,被动回复消息
     * @param array $data 用户发送的消息
     * @param string $content
     * @return string
     */
    protected function replyTextMessage($data, $content)
    {
        $params = [
            'MsgType' => 'text',
            'Content' => $content,
        ];
        return $this->replyMessage($data, $params);
    }

    /**
     * @param array $data 用户发送的消息
     * @param array $params
     * @return bool
     */
    protected function replyMessage($data, $params)
    {
        $params = array_merge($params, [
            'ToUserName' => $data['FromUserName'],
            'FromUserName' => $data['ToUserName'],
            'CreateTime' => time(),
        ]);
        return $this->xmlEncode($params);
    }

    /********************************************* 客服消息(主动发送TODO) ************************************************/

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string|array $attr 根节点属性
     * @return string
     */
    protected function xmlEncode($data, $root = 'xml', $attr = '')
    {
        if (is_array($attr)) {
            $_attr = [];
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<{$root}{$attr}>";
        $xml .= $this->dataToXml($data);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string
     */
    protected function dataToXml($data)
    {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml .= "<$key>";
            if (is_array($val) || is_object($val)) {
                $xml .= $this->dataToXml($val);
            } else {
                $xml .= '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$val).']]>';
            }
            list($key, ) = explode(' ', $key);
            $xml .= "</$key>";
        }
        return $xml;
    }
}