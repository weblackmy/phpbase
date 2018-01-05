<?php
namespace phpbase\lib\util;

/**
 * Class String
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\util
 */
class Date
{
    /**
     * @param string $date
     * @return int
     */
    public static function yesterday($date = '')
    {
        if (!$date) $date = date('Y-m-d');
        return strtotime($date) - 86400;
    }

    /**
     * unix时间戳转换
     * @param int $ts
     * @param string $format
     * @return string
     */
    public static function unixTimeFormat($ts, $format = 'Y-m-d H:i:s')
    {
        return $ts ? date($format, $ts) : '';
    }
}