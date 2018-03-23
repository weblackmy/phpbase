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
     * 文件列表
     * @param string $bucket
     * @param string $prefix
     * @param string $marker
     * @param integer $limit
     * @return bool|array
     */
    public static function listFiles($bucket, $prefix, $marker, $limit)
    {
        return self::getStorage()->listFiles($bucket, $prefix, $marker, $limit);
    }

    /**
     * 上传文件
     * @param string $bucket
     * @param string $file
     * @param bool $blob
     * @return bool|array
     */
    public static function uploadFile($bucket, $file, $blob = false)
    {
        return self::getStorage()->uploadFile($bucket, $file, $blob);
    }

    /**
     * 删除文件
     * @param string $bucket
     * @param string $key
     * @return bool|array
     */
    public static function deleteFile($bucket, $key)
    {
        return self::getStorage()->deleteFile($bucket, $key);
    }

    /**
     * 文件下载链接
     * @param string $bucket
     * @param string $key
     * @return bool|string
     */
    public static function downloadUrl($bucket, $key)
    {
        return self::getStorage()->downloadUrl($bucket, $key);
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