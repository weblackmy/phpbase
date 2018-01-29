<?php

namespace phpbase\service;

use phpbase\lib\mail\Mail;

/**
 * Class Mail
 * @package phpbase\service
 * @author qian lei
 */
class SMail
{
    /**
     * 发送邮件.
     * @param $subject string 标题
     * @param string $content
     * @param $receivers string|array 接收方(多个请用数组)
     * @param null|string|array $attaches 附件(多个请用数组)
     * @return bool
     */
    public static function send($subject, $content, $receivers, $attaches = null)
    {
        $mail = new Mail(SConfig::getMail());
        try {
            return $mail->send($subject, $content, $receivers, $attaches);
        } catch (\Exception $e) {
            return false;
        }
    }
}