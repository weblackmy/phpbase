<?php
require ('../autoload.php');
/**
 * User: qianlei
 * Date: 19/06/2017
 */
use phpbase\lib\redis\Redis;

Redis::setOpts([
    'host' => '127.0.0.1',
    'port' => 6379,
    'database' => 0,
    'auth' => 'icolumn123',
]);

//var_dump(Redis::set('test:abc', 1));
//var_dump(Redis::get('test:abc'));
//var_dump(Redis::info('CPU'));
