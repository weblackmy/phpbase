<?php
namespace phpbase\service\sms;

/**
 * Interface for Sms
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\service\sms
 */
interface DriverInterface
{
    /**
     * @param string|array $mobile
     * @param string $title
     * @param string $content
     * @return bool
     */
    public function sendText($mobile, $title, $content);

    /**
     * 调用三方接口时, 如果出错, 则通过此函数返回
     * @return string
     */
    public function getError();
}