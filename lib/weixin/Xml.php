<?php
namespace phpbase\lib\weixin;

/**
 * Class Xml
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin
 */
class Xml
{
    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string|array $attr 根节点属性
     * @return string
     */
    public static function xmlEncode($data, $root = 'xml', $attr = '')
    {
        if (is_array($attr)) {
            $_attr = [];
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<{$root}{$attr}>";
        $xml .= self::dataToXml($data);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string
     */
    protected static function dataToXml($data)
    {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml .= "<$key>";
            if (is_array($val) || is_object($val)) {
                $xml .= self::dataToXml($val);
            } else {
                $xml .= '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$val).']]>';
            }
            list($key, ) = explode(' ', $key);
            $xml .= "</$key>";
        }
        return $xml;
    }
}