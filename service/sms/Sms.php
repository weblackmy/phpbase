<?php
namespace phpbase\service\sms;

use phpbase\lib\util\Validate;
use phpbase\lib\log\Log;

/**
 * Class Sms
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\service\sms
 */
class Sms
{
    /**
     * @var array
     */
    private static $driverMap = [];

    /**
     * @var string
     */
    private $driverName;

    /**
     * @var string
     */
    public static $defaultDriver;

    /**
     * @var array 失败转发配置
     */
    public static $failureForwardConfig = [];

    /**
     * @var bool|string 是否记录日志,如果记录,则需要指定log路径
     */
    public static $log = false;

    /**
     * @var array
     */
    private $error = [];

    /**
     * 发送文本消息
     * @param string|array $mobile
     * @param string $title
     * @param string $content
     * @return bool
     */
    public function sendText($mobile, $title, $content)
    {
        //验证手机号码合法性
        if (false === $this->checkMobile($mobile)) {
            return false;
        }
        if (false === ($result = $this->loadDriver()->sendText($mobile, $title, $content))) {
            //TODO forward
        }
        $this->log('sms-send-text', [
            'mobile' => $mobile,
            'result' => $result,
            'error' => $result ? : $this->loadDriver()->getError()
        ]);
        return $result;
    }

    /**
     * @TODO 发送语音消息
     * @return bool
     */
    public function sendVoice()
    {
        return false;
    }

    /**
     * @param string $driver
     * @return self
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string|array $mobile
     * @return bool
     */
    protected function checkMobile($mobile)
    {
        foreach (is_array($mobile) ? $mobile : [$mobile] as $v) {
            if (!Validate::isMobile($v)) {
                $this->error[] = "mobile:[{$mobile}] is invalid";
            }
        }
        return (bool)$this->error;
    }

    /**
     * @return DriverInterface
     * @throws SmsException
     */
    protected function loadDriver()
    {
        $driver = ucfirst($this->driverName ? : self::$defaultDriver);
        if (!isset(self::$driverMap[$driver])) {
            $driverFile = __DIR__ . '/driver/' . $driver . '.php';
            if (!file_exists($driverFile)) {
                throw new SmsException("Driver {$driverFile} not found.");
            }
            $driverClass = "phpbase\\service\\sms\\driver\\" . $driver;
            self::$driverMap[$driver] = new $driverClass();
        }
        return self::$driverMap[$driver];
    }

    /**
     * @param string $type
     * @param array $data
     */
    protected function log($type, $data = [])
    {
        if (self::$log && is_dir(self::$log)) {
            Log::info($type, $data);
        }
    }
}