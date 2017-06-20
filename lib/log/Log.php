<?php
namespace phpbase\lib\log;

/**
 * Class Log
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\curl
 */
class Log
{
    /**
     * @var string
     */
    private static $logDir;

    /**
     * @var string|array
     */
    private static $logData;

    /**
     * @var string
     */
    private static $logFileExt = '.txt';

    /**
     * @var bool
     */
    private static $autoFlush = true;

    /**
     * @var int
     */
    private static $fileMode = 0644;

    /**
     * @var bool
     */
    private static $mkdirRecursive = false;

    /**
     * @param string $dir
     * @throws \Exception
     */
    public static function setLogDir($dir)
    {
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \Exception("Dir '{$dir}' is doesn't exist or not writable");
        }
        self::$logDir = rtrim($dir, '/');
    }

    /**
     * @param string $ext
     */
    public static function setLogFileExt($ext)
    {
        self::$logFileExt = $ext;
    }

    /**
     * @param bool $autoFlush
     */
    public static function setAutoFlush($autoFlush)
    {
        self::$autoFlush = $autoFlush;
    }

    /**
     * @param bool $recursive
     */
    public static function setMkdirRecursive($recursive)
    {
       self::$mkdirRecursive = $recursive;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return bool|int
     */
    public static function info($key, $data)
    {
        return self::log($key, $data, 'info');
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return bool|int
     */
    public static function debug($key, $data)
    {
        return self::log($key, $data, 'debug');
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return bool|int
     */
    public static function notice($key, $data)
    {
        return self::log($key, $data, 'notice');
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return bool|int
     */
    public static function warn($key, $data)
    {
        return self::log($key, $data, 'warn');
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return bool|int
     */
    public static function error($key, $data)
    {
        return self::log($key, $data, 'error');
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return bool|int
     */
    public static function fatal($key, $data)
    {
        return self::log($key, $data, 'fatal');
    }

    /**
     * 写入文件
     * @return bool|int
     * @throws \Exception
     */
    public static function flush()
    {
        if (empty(self::$logData)) {
            return false;
        }

        $length = 0;
        $handle = [];
        foreach (self::$logData as $logFile => $logData) {
            $dir = dirname($logFile);
            if (!is_dir($dir)) {
                if (false === mkdir($dir, self::$fileMode, true)) {
                    continue;
                }
            }

            if (!isset($handle[$logFile])) {//open file
                if (false === ($handle[$logFile] = fopen($logFile, 'a+'))) {
                    continue;
                }
                chmod($logFile, self::$fileMode);
            }
            $length += (int)fwrite($handle[$logFile], implode("\n", $logData)."\n");
            fclose($handle[$logFile]);
        }
        self::init();
        return $length;
    }

    /**
     * init
     */
    private static function init()
    {
        self::$logData = [];
    }

    /**
     * @param string $key
     * @param mixed $data
     * @param string $logLevel
     * @return bool
     */
    private static function log($key, $data, $logLevel)
    {
        $key = self::$mkdirRecursive ? $key : str_replace('/', '-', $key);
        $logFile = self::$logDir . '/'. trim($key, '/') . self::$logFileExt;
        if (!isset(self::$logData[$logFile])) {
            self::$logData[$logFile] = [];
        }

        $log = self::formatStr(date('Y-m-d H:i:s'));
        $log .= self::formatStr(getmypid());
        $log .= self::formatStr($logFile);
        $log .= self::formatStr($logLevel);
        $log .= is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE);
        self::$logData[$logFile][] = $log;

        return self::$autoFlush ? self::flush() : true;
    }

    /**
     * @param string $str
     * @return string
     */
    private static function formatStr($str)
    {
        return '['. $str . ']';
    }
}