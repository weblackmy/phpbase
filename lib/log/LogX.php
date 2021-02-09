<?php
namespace phpbase\lib\log;

/**
 * Class Log
 * @package phpbase\lib\log
 * @author qian lei
 */
class LogX
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
    private static $logFileExt = '.log';

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
    private static $logDate = '';

    /**
     * @var bool
     */
    private static $debugMode = false;

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
     * @param string $date
     */
    public static function setLogDate($date)
    {
       self::$logDate = $date;
    }

    /**
     * @param bool $mode
     */
    public static function setDebugMode($mode)
    {
        self::$debugMode = $mode;
    }

    /**
     * @param mixed $data
     * @return bool|int
     */
    public static function info($data)
    {
        return self::log($data, 'info');
    }

    /**
     * @param mixed $data
     * @return bool|int
     */
    public static function debug($data)
    {
        return self::$debugMode ? self::log($data, 'debug') : true;
    }

    /**
     * @param mixed $data
     * @return bool|int
     */
    public static function notice($data)
    {
        return self::log($data, 'notice');
    }

    /**
     * @param mixed $data
     * @return bool|int
     */
    public static function warn($data)
    {
        return self::log($data, 'warn');
    }

    /**
     * @param mixed $data
     * @return bool|int
     */
    public static function error($data)
    {
        return self::log($data, 'error');
    }

    /**
     * @param mixed $data
     * @return bool|int
     */
    public static function fatal($data)
    {
        return self::log($data, 'fatal');
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
     * @param mixed $data
     * @param string $logLevel
     * @return bool
     */
    private static function log($data, $logLevel)
    {
        if (empty(self::$logDate)) {
            self::$logDate = date('Y-m-d');
        }

        $logFile = self::$logDir . '/' . self::$logDate . self::$logFileExt;
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