<?php

namespace phpbase\lib\util;

/**
 * Class Input
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\util
 */
class Input
{
    /**
     * 获取$_GET数据
     * @param string $key
     * @param string $type 数据类型 (int,float,string)
     * @param string $defaultValue
     * @return mixed
     */
    public static function get($key, $type = 'string', $defaultValue = '')
    {
        return self::typeConvert(isset($_GET[$key]) ? $_GET[$key] : $defaultValue, $type);
    }

    /**
     * 获取$_POST数据
     * @param string $key
     * @param string $type 数据类型 (int,float,string)
     * @param string $defaultValue
     * @return mixed
     */
    public static function post($key, $type = 'string', $defaultValue = '')
    {
        return self::typeConvert(isset($_POST[$key]) ? $_POST[$key] : $defaultValue, $type);
    }

    /**
     * 获取$_REQUEST数据
     * @param string $key
     * @param string $type 数据类型 (int,float,string)
     * @param string $defaultValue
     * @return mixed
     */
    public static function request($key, $type = 'string', $defaultValue = '')
    {
        return self::typeConvert(isset($_REQUEST[$key]) ? $_REQUEST[$key] : $defaultValue, $type);
    }

    /**
     * 数据类型转换
     * @param mixed $v
     * @param string $toType
     * @return mixed
     */
    public static function typeConvert($v, $toType)
    {
        switch ($toType) {
            case 'int':
                $v = (int)$v;
                break;
            case 'float':
                $v = (float)$v;
                break;
            case 'string':
                $v = (string)trim($v);
                break;
            case 'bool':
                $v = (bool)$v;
                break;
        }
        return $v;
    }
}