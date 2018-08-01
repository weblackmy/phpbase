<?php
namespace phpbase\service\weixin\pay;

use phpbase\service\SConfig;

/**
 * Class BasePay 微信支付基础类
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\pay
 */
class Base
{
    /**
     * @var string
     */
    const payPai = 'https://api.mch.weixin.qq.com';

    /**
     * @var array 微信支付相关配置
     */
    protected $config;

    /**
     * @var array 微信支付参数
     */
    protected $values = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->config = SConfig::getWeixin();
        $this->request = new Request($this->config);
        $this->request->setCurlOptions([
            'urlPrefix' => self::payPai,
        ]);
    }

    /**
     * @return string
     */
    public function setSign()
    {
        return $this->values['sign'] = $this->makeSign();
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->values['sign'];
    }

    /**
     * 设置错误码 FAIL 或者 SUCCESS
     * @param string $return_code
     */
    public function setReturn_code($return_code)
    {
        $this->values['return_code'] = $return_code;
    }

    /**
     * 获取错误码 FAIL 或者 SUCCESS
     * @return string $return_code
     */
    public function getReturn_code()
    {
        return $this->values['return_code'];
    }

    /**
     * 设置错误信息
     * @param string $return_msg
     */
    public function setReturn_msg($return_msg)
    {
        $this->values['return_msg'] = $return_msg;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getReturn_msg()
    {
        return $this->values['return_msg'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return bool
     **/
    public function isSignSet()
    {
        return array_key_exists('sign', $this->values);
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
        $string = $string . "&key=".$this->config['key'];
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
        if(!$this->isSignSet()){
            throw new \Exception('签名错误');
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
}