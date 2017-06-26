<?php
namespace phpbase\lib\weixin\mp\lib;

use phpbase\lib\weixin\mp\Base;
use phpbase\lib\weixin\lib\Xml;
use phpbase\lib\log\Log;
/**
 * Class Message 消息管理
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\mp
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
            $xml = Xml::decode($xmlData);
            //解析MsgType
            $method = 'callbackMsgType'.ucfirst($xml['MsgType']);
            if ($xml['MsgType'] == 'event') {
                $method .= ucfirst($xml['Event']);
            }
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
     * 事件消息(关注公众号)
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeEventSubscribe($data)
    {
        return $this->replyNewsMessage($data, [
            [
                'title' => 'this is title',
                'description' => 'this is description',
                'picUrl' => 'http://www.chinagwyw.org/uploadfile/2016/1214/20161214101256437.jpg',
                'url' => 'http://www.baidu.com'
            ],
            [
                'title' => 'this is title 2',
                'description' => 'this is description 2',
                'picUrl' => '',
                'url' => 'http://www.baidu.com'
            ]
        ]);
    }

    /**
     * 事件消息(关注公众号)
     * @param array $data
     * @return string
     */
    protected function callbackMsgTypeEventUnsubscribe($data)
    {
        return $this->replyTextMessage($data, 'bye bye');
    }

    /**
     * 接收微信消息后,被动回复文本消息
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
     * 接收微信消息后,被动回复图文消息
     * @param array $data
     * @param array $news 图文消息配置
     * @return string
     */
    protected function replyNewsMessage($data, $news)
    {
        $maxNews = 8;
        $params = [
            'MsgType' => 'news',
            'ArticleCount' => count($news) <= $maxNews ? count($news) : $maxNews,
        ];
        //微信支持最多8条
        foreach (array_slice($news, 0, 8) as $item) {
            $params['Articles'][] = [
                'Title' => $item['title'],
                'Description' => $item['description'],
                'PicUrl' => $item['picUrl'],
                'Url' => $item['url'],
            ];
        }
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
        return Xml::encode($params);
    }

    /********************************************* 客服消息(主动发送TODO) ************************************************/
}