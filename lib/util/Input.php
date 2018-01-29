<?php

namespace phpbase\lib\util;

/**
 * Class Input
 * @package phpbase\lib\util
 * @author qian lei
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
        return Common::typeConvert(isset($_GET[$key]) ? $_GET[$key] : $defaultValue, $type);
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
        return Common::typeConvert(isset($_POST[$key]) ? $_POST[$key] : $defaultValue, $type);
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
        return Common::typeConvert(isset($_REQUEST[$key]) ? $_REQUEST[$key] : $defaultValue, $type);
    }
}