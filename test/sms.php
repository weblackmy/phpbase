<?php
require ('../autoload.php');
/**
 * User: qianlei
 * Date: 03/08/2017
 */
use phpbase\service\aliyun\Sms;
use phpbase\service\SConfig;


SConfig::set('aliCloud', [
    'accessKeyId' => '',
    'accessSecret' => '',
    'regionId' => '',
]);
Sms::init()
    ->setSignName('')
    ->setTemplateCode('')
    ->sendSms('15850676621', ['code' => '123456']);
