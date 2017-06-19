<?php
namespace phpbase\lib\Curl;

class Curl
{
    /**
     * @var int
     */
    private $timeout = 10;

    /**
     * @var array
     */
    private $options = [];
    
    /**
     * Curl constructor.
     */
	public function __construct()
    {
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param int|null $timeout
     * @return mixed
     */
    public function get($url, $params, $timeout = null)
    {
        return $this->exec($url, $params, $timeout, 'get');
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param int|null $timeout
     * @return mixed
     */
    public function post($url, $params, $timeout = null)
    {
        return $this->exec($url, $params, $timeout, 'post');
    }

    /**
     * @param string $url
     * @param string|array $params
     * @param int|null $timeout
     * @param string $type
     * @return mixed
     */
    protected function exec($url, $params, $timeout, $type = 'get')
    {
        $ch = curl_init($url);

    }

    /**
     * 自定义参数
     * @param array $data
     * @return $this
     */
    public function setCustom($data)
    {
        foreach ($data as $k => $v) {
            $this->options[$k] = $v;
        }
        return $this;
    }

    /**
     * @param int $seconds 超时时间,默认3s
     * @return $this
     */
    public function setTimeout($seconds = 3)
    {
        $this->options[CURLOPT_TIMEOUT] = $seconds;
        $this->options[CURLOPT_CONNECTTIMEOUT] = $seconds;
        return $this;
    }
}