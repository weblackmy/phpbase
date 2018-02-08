<?php
namespace phpbase\service;
require __DIR__ . '/../vendor/qiniu/php-sdk/autoload.php';

use phpbase\lib\util\Arrays;
use phpbase\lib\qiniu\Storage;
/**
 * Class SQiniu
 * @package phpbase\service
 * @author qian lei
 */
class SQiniu
{
    /**
     * @var Storage
     */
    private static $storage;

    /**
     * 上传文件
     * @param string $bucket
     * @param string $file
     * @return bool
     */
    public static function uploadFile($bucket, $file)
    {
        return self::getStorage()->uploadFile($bucket, $file);
    }

    /**
     * storage对象
     * @return Storage
     */
    private static function getStorage()
    {
        if (!self::$storage) {
            self::$storage = new Storage(self::getAccessKey(), self::getSecretKey());
        }
        return self::$storage;
    }

    /**
     * @return string
     */
    private static function getAccessKey()
    {
        return Arrays::get(SConfig::getQiniu(), 'accessKey');
    }

    /**
     * @return string
     */
    private static function getSecretKey()
    {
        return Arrays::get(SConfig::getQiniu(), 'secretKey');
    }
}