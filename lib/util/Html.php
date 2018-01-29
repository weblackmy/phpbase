<?php
namespace phpbase\lib\util;

/**
 * Class Html
 * @package phpbase\lib\util
 * @author qian lei
 */
class Html
{
    /**
     * 解析<img>标签
     * @param string $str
     * @param $attributes bool
     * @return int|array
     */
    public static function getTagImg($str, $attributes = false)
    {
        $cnt = preg_match_all('/<img\s.*?>/i', $str, $m1);
        if (false === $attributes) {
            return $cnt;
        }
        $data = [];
        for ($i=0; $i < $cnt; $i++) {
            $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2);
            for($j = 0; $j < $c2; $j++) {
                $data[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];
            }
        }
        return $data;
    }
}
