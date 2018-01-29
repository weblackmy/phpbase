<?php
namespace phpbase\lib\util;

/**
 * Class Msg
 * @package phpbase\lib\util
 * @author qian lei
 */
class Msg
{
    //提示消息
    private $msg;
    //单例对象
    private static $instance;
    //私有化构造方法
    private function __construct()
    {
    }
    //私有化克隆方法
    private function __clone()
    {
    }

    /**
     * 获取该单例对象
     *
     * @return Msg
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 设置消息
     *
     * @param $msg
     */
    public static function setMsg($msg)
    {
        self::getInstance()->msg = $msg;
    }

    /**
     * 获取提示信息
     *
     * @return string
     */
    public static function getMsg()
    {
        if (self::getInstance()->msg != null) {
            return self::getInstance()->msg;
        }
    }
}