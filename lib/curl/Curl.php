<?php
namespace phpbase\lib\curl;

/**
 * Class Curl
 * @package phpbase\lib\Curl
 */
class Curl
{
    const POST_TYPE_FORM_DATA = 1;
    const POST_TYPE_X_WWW_FORM_URLENCODED = 2;
    const POST_TYPE_JSON = 3;
    const POST_TYPE_XML_APPLICATION = 4;
    const POST_TYPE_XML_TEXT = 5;

    /**
     * @var int default timeout
     */
    protected $timeout = 10;

    /**
     * @var int
     */
    protected $postType = self::POST_TYPE_FORM_DATA;

    /**
     * @var int
     */
    protected $maxRetryTimes = 1;

    /**
     * @var int micro seconds
     */
    protected $retrySleepTime = 500000;

    /**
     * @var array
     */
    protected $returnInfo = [];

    /**
     * @var array
     */
    protected $errorInfo = [];
    
    /**
     * Curl constructor.
     */
	public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->returnInfo = [];
        $this->errorInfo = [];
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param array $options
     * @param int|null $timeout
     * @return mixed
     */
    public function get($url, $params = [], $options = [], $timeout = null)
    {
        return $this->exec($url, $params, $options, $timeout, 'GET');
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param array $options
     * @param int|null $timeout
     * @return mixed
     */
    public function post($url, $params = [],  $options = [], $timeout = null)
    {
        return $this->exec($url, $params, $options, $timeout, 'POST');
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param array $options
     * @param int|null $timeout
     * @return mixed
     */
    public function put($url, $params, $options, $timeout = null)
    {
        return $this->exec($url, $params, $options, $timeout, 'PUT');
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param array $options
     * @param int|null $timeout
     * @return mixed
     */
    public function delete($url, $params, $options, $timeout = null)
    {
        return $this->exec($url, $params, $options, $timeout, 'DELETE');
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param array $options
     * @param int|null $timeout
     * @param string $type
     * @return bool|string|array
     */
    protected function exec($url, $params, $options, $timeout, $type = 'GET')
    {
        $this->init();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //set timeout
        $timeout = !is_null($timeout) ? $timeout : $this->timeout;
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        //set https
        if (substr($url, 0, 8) == 'https://') {
            if (isset($options['caInfo'])) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   //只信任CA颁布的证书
                curl_setopt($ch, CURLOPT_CAINFO, $options['caInfo']); //CA根证书(用来验证的网站证书是否是CA颁布)
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //检查证书中是否设置域名,并且是否与提供的主机名匹配
            } else {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //信任任何证书
                //CURLOPT_SSL_VERIFYHOST no longer accepts the value 1, value 2 will be used instead
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //检查证书中是否设置域名
            }
        }
        //header
        $header = ['cache-control: no-cache'];
        if (isset($options['header'])) {
            $header[] = $options['header'];
        }
        if (strtoupper($type) == 'POST') {//如果是POST, 则设置POST类型的header
            $postType = isset($options['postType']) ? $options['postType'] : $this->postType;
            switch ($postType) {
                case self::POST_TYPE_X_WWW_FORM_URLENCODED:
                    $header[] = 'content-type: application/x-www-form-urlencoded';
                    $params = $params ? (is_array($params) ? http_build_query($params) : $params) : '';
                    break;
                case self::POST_TYPE_JSON:
                    $header[] = 'content-type: application/json';
                    $params = $params ? json_encode($params) : '';
                    break;
                case self::POST_TYPE_XML_APPLICATION:
                    $header[] = 'content-type: application/xml';
                    break;
                case self::POST_TYPE_XML_TEXT:
                    $header[] = 'content-type: text/xml';
                    break;
                default:
                    $header[] = 'content-type: multipart/form-data';
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //user agent
        if (isset($options['userAgent'])) {
            curl_setopt($ch, CURLOPT_USERAGENT, $options['userAgent']);
        }
        //cookie
        if (isset($options['cookie'])) {
            curl_setopt($ch, CURLOPT_COOKIE, $options['cookie']);
        }
        if (isset($options['cookieFile']) && is_file($options['cookieFile'])) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $options['cookieFile']);
        }
        if (isset($options['cookieJAR']) && is_file($options['cookieJAR'])) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $options['cookieJAR']);
        }

        switch(strtoupper($type)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case 'HEAD':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
                break;
            default:
                if ($params) {
                    $url .= (strpos($url, '?') === false ? '?' : '');
                    $url .= is_array($params) ? http_build_query($params) : $params;
                }
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                break;
        }
        curl_setopt($ch, CURLOPT_URL, $url);

        $maxRetryTimes = isset($options['maxRetryTimes']) ? $options['maxRetryTimes'] : $this->maxRetryTimes;
        $result = false;
        while ($maxRetryTimes > 0) {
            if (($result = curl_exec($ch)) === false) {
                usleep(isset($options['retrySleepTime']) ? $options['retrySleepTime'] : $this->retrySleepTime);
                --$maxRetryTimes;
                continue;
            }
            break;
        }

        $this->returnInfo = curl_getinfo($ch);
        if (($errorNo = curl_errno($ch)) !== 0) {
            $this->errorInfo = ['errorNo' => $errorNo, 'error' => curl_error($ch)];
        }

        curl_close($ch);
        return $result;
    }

    /**
     * curl error
     * @return array
     */
    public function getError()
    {
        return $this->errorInfo;
    }
}