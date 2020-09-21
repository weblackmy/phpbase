<?php
require ('../autoload.php');
use phpbase\lib\util\Arrays;
use phpbase\service\SConfig;
use phpbase\service\weixin\pay\MpOrder;

SConfig::set('weixin', [
    'appId' => 'wx33fa9038d6a907de',
    'appKey' => '5df05b7331e6edb8be14c0d16d7143ed',
    'appSecret' => '70a4674e72986a6c4fcbc1228d9c7b79',
    'mchId' => '1392405902',
]);


$model = new MpOrder();
$result = $model->unifiedOrder([
    'orderSn'=> '100001',
    'money' => 1,
    'ip' => '127.0.0.1',
    'notifyUrl' => 'http://m.chnbook.org/',
    'description' => '测试商品',
    'wapUrl' => 'http://m.chnbook.org/',
    'wapName' => '公务员教材中心',
], 'h5');
if ($result) {
    var_dump(Arrays::get($result,'mweb_url'));
}
