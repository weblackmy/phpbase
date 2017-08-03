<?php
require ('../autoload.php');
/**
 * User: qianlei
 * Date: 03/08/2017
 */
use phpbase\service\sms\Sms;

//set default driver
Sms::$defaultDriver = 'ChuangLan';
(new Sms)->sendText('15850676621', '测试', '测试');