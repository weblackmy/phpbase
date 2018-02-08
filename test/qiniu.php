<?php
require ('../autoload.php');
use phpbase\service\SConfig;
use phpbase\service\SQiniu;

SConfig::set('qiniu', [
    'accessKey' => '',
    'secretKey' => '',
]);
SQiniu::uploadFile('bucket','/data/web/temp/qiniu.txt.zip');