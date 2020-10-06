<?php
namespace phpbase\service\weixin\pay;

use phpbase\lib\curl\Curl;
use phpbase\lib\log\Log;
use phpbase\lib\util\Arrays;
use phpbase\service\SConfig;
use phpbase\service\weixin\lib\Xml;

/**
 * Class BasePay 微信支付基础类
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\pay
 */
abstract class Base
{
    /**
     * @var string
     */
    const unifiedOrderApi = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    /**
     * @var array 微信支付相关配置
     */
    protected $config;

    /**
     * @var array 微信支付参数
     */
    protected $values = [];

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->config = SConfig::getWeixin();
        $this->curl = new Curl();
    }

    /**
     * @return string
     */
    public function setSign()
    {
        $this->values['sign_type'] = 'MD5';
        $this->values['sign'] = $this->makeSign();
        return $this;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->values['sign'];
    }

    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return string 产生的随机字符串
     */
    public function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function toUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v) {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        return trim($buff, "&");
    }

    /**
     * 生成签名
     * @return string 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    protected function makeSign()
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->config['appKey'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        return strtoupper($string);
    }

    /**
     * 检测签名
     * @return bool
     * @throws \Exception
     */
    protected function CheckSign()
    {
        if(!Arrays::get($this->values, 'sign')){
            throw new \Exception('签名为空');
        }
        $sign = $this->MakeSign();
        if($this->GetSign() == $sign){
            return true;
        }
        throw new \Exception('签名错误');
    }

    /**
     * 微信支付时ssl证书
     */
    protected function getCert()
    {
        return [
            'sslCert' => $this->config['sslCert'],
            'sslKey' => $this->config['sslKey'],
            'sslCa' => $this->config['sslCa'],
        ];
    }

    /**
     * 返回结果
     * @param mixed $response
     * @return bool|string|array
     */
    protected function getResponse($response)
    {
        Log::debug('phpbase', sprintf('wxpay get raw response %s', $response));
        try {
            $response = Xml::decode($response);
            if (Arrays::get($response, 'return_code') == 'SUCCESS') {
                return $response;
            }
            Log::error('phpbase', sprintf('wxpay response error %s', Arrays::get($response, 'err_code_des')));
        } catch (\Exception $e) {
            Log::error('phpbase', sprintf('wxpay decode response error %s', $e->getMessage()));
        }
        return false;
    }
}
