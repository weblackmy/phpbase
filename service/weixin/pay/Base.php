<?php
namespace phpbase\service\weixin\pay;

/**
 * Class BasePay 微信支付基础类
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\pay
 */
class Base
{
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
        $this->config = $this->getConfig();
        $this->request = new Request($this->config);
        $this->request->setCurlOptions([
            'urlPrefix' => $this->config['payApi'],
        ]);
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->values['sign'];
    }

    /**
     * @return string
     */
    public function setSign()
    {
        return $this->values['sign'] = $this->makeSign();
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
     * 微信基础配置
     * @return array
     */
    protected function getConfig()
    {
        if (!$this->config) {
            if (file_exists(__DIR__ . '/config/pay.php')) {
                $this->config = require __DIR__ . '/config/pay.php';
            }
        }
        return $this->config;
    }
}