<?php
namespace phpbase\lib\util;

/**
 * Class String
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\util
 */
defined('MB_EXT_EXIST') || define('MB_EXT_EXIST', function_exists('mb_substr'));
class String
{
    /**
     * 字符串编码转换函数
     * @param $str
     * @param string $inCharset
     * @param string $outCharset
     * @return string
     */
    public static function iconv($str, $inCharset, $outCharset)
    {
        $inCharset = strtoupper($inCharset);
        $outCharset = strtoupper($outCharset);
        if (empty($str) || $inCharset == $outCharset) {
            return $str;
        }

        if (MB_EXT_EXIST) {
            $out = mb_convert_encoding($str, $outCharset, $inCharset);
        } else {
            $out = iconv($inCharset, $outCharset . '//IGNORE', $str);
        }
        return $out;
    }
}