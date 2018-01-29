<?php
namespace phpbase\lib\util;

/**
 * Class Validate
 * @package phpbase\lib\util
 * @author qian lei
 */
class Validate
{
    /**
     * 检测是否是合法的大陆手机号
     * @param string $str
     * @param bool $checkPrefix 是否检测号码前缀
     * @return bool|int
     */
    public static function isMobile($str, $checkPrefix = false)
    {
        return !empty($str) ? preg_match('/^' . ($checkPrefix ? '(\+?86-?|0)?' : '') . '1[0-9]{10}$/', $str) : false;
    }

    /**
     * 检测是否是合法的大陆电话号码
     * @param string $str
     * @return bool|int
     */
    public static function isTel($str)
    {
        return !empty($str) ? preg_match('/(\d{4}-|\d{3}-)?(\d{8}|\d{7})/', $str) : false;
    }

    /**
     * 是否是合法邮箱
     * @param string $str
     * @return int|bool
     * @see http://www.regular-expressions.info/email.html
     */
    public static function isEmail($str)
    {
        return preg_match('/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/', $str);
    }
}