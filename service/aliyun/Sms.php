<?php
namespace phpbase\service\aliyun;

use phpbase\lib\log\Log;
use phpbase\lib\util\Arrays;
use phpbase\service\SConfig;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
 * Class Sms
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\service\aliyun
 */
class Sms
{
    private static $instance;

    private static $config;

    /**
     * @var string 短信签名
     */
    private $signName;

    /**
     * @var string 模板ID
     */
    private $templateCode;

    //私有化构造方法
    private function __construct(){}

    //私有化克隆方法
    private function __clone(){}

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function init()
    {
        try {
            self::$config = SConfig::getAliCloud();
            AlibabaCloud::accessKeyClient(self::$config['accessKeyId'], self::$config['accessSecret'])
                ->regionId(self::$config['regionId'])
                ->asDefaultClient();
        } catch (ClientException $e) {

        }
        return self::getInstance();
    }

    /**
     * 设置短信签名
     * @param string $signName
     * @return self
     */
    public function setSignName($signName)
    {
        $this->signName = $signName;
        return $this;
    }

    /**
     * @param string $templateCode
     * @return $this
     */
    public function setTemplateCode($templateCode)
    {
        $this->templateCode = $templateCode;
        return $this;
    }

    /**
     * @param string $mobile
     * @param array $params
     */
    public function sendSms($mobile, $params)
    {
        try {
            $request = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                 ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => self::$config['regionId'],
                        'PhoneNumbers' => $mobile,
                        'SignName' => $this->signName,
                        'TemplateCode' => $this->templateCode,
                        'TemplateParam' => json_encode($params),
                    ],
                ])
                ->request();
            $result = $request->toArray();
            if (Arrays::get($result, 'Code') == 'OK') {
                return true;
            }
            Log::info('phpbase', [
                'msg' => 'send sms failed',
                'mobile' => $mobile,
                'templateCode' => $this->templateCode,
                'result' => $result,
            ]);
        } catch (ClientException $e) {
            Log::info('phpbase', $e->getMessage());
        } catch (ServerException $e) {
            Log::info('phpbase', $e->getMessage());
        }
        return false;
    }
}
