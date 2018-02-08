<?php
namespace phpbase\lib\qiniu;

use Qiniu\Auth;
/**
 * Class Base
 * @package phpbase\service\qiniu
 * @author qian lei
 * 七牛云SDK封装
 */
class Base
{
    /**
     * @var string https://portal.qiniu.com/user/key
     */
    private $accessKey;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var Auth 鉴权对象
     */
    protected $auth;

    /**
     * @var string 上传空间
     */
    protected $bucket;

    /**
     * Base constructor.
     * @param string $accessKey
     * @param string $secretKey
     */
    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->initAuth();
    }

    /**
     * init Auth
     */
    protected function initAuth()
    {
        $this->auth = new Auth($this->accessKey, $this->secretKey);
    }

    /**
     * @param string $bucket
     */
    protected function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * 获取上传凭证
     * @param string $key 上传后保存到七牛的文件名
     * @param int $expires 上传凭证有效期
     * @param null $policy
     * @return string
     */
    protected function getUploadToken($key = null, $expires = 3600, $policy = null)
    {
        if (null === $policy) {
            //https://developer.qiniu.com/kodo/manual/1235/vars#magicvar
            $policy = [
                'returnBody' => '{"bucket":"$(bucket)","key":"$(key)","fsize":$(fsize),"hash":"$(etag)"}'
            ];
        }
        return $this->auth->uploadToken($this->bucket, $key, $expires, $policy, true);
    }
}