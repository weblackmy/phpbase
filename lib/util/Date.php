<?php

namespace phpbase\lib\util;

/**
 * Class Date
 * @package phpbase\lib\util
 * @author qian lei
 */
class Date
{
    public static $day = [
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
        'Sunday' => 7,
    ];

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

    /**
     * 判断日期是一周中的第几天
     * @param string $date
     * @return int
     */
    public static function getNumericDayOfWeek($date = '')
    {
        return date('N', $date ? strtotime($date) : time());
    }

    /**
     * 获取一周中的某一天, 参数可以是[1-7]任意数字, 或者是星期几英文
     * @param int|string $day
     * @param string $date
     * @return bool|string
     */
    public static function getDayOfWeek($day, $date = '')
    {
        $date = $date ? $date : date('Y-m-d');
        $n = self::getNumericDayOfWeek($date) - 1;
        if (is_string($day)) {
            if (!isset(self::$day[$day])) {
                return false;
            }
            $day = self::$day[$day];
        }
        $day--;
        $day = $day < 0 ? 0 : ($day > 6 ? 6 : $day);
        return date('Y-m-d', strtotime("-{$n} day", strtotime($date)) + 86400 * $day);
    }
}