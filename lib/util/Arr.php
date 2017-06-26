<?php
namespace phpbase\lib\util;

/**
 * Class Arr
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\util
 */
class Arr
{
    /**
     * 返回数组键值是否有效
     * @param array $array
     * @param string $key
     * @return bool
     */
    public static function keyValid($array, $key)
    {
        return isset($array[$key]) && $array[$key];
    }
}