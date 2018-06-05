<?php
namespace phpbase\lib\util;

/**
 * Class File
 * @package phpbase\lib\util
 * @author qian lei
 */
class File
{
    /**
     * @param string $filename
     * @return string
     */
    public static function getExt($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }
}