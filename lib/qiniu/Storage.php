<?php
namespace phpbase\lib\qiniu;

use phpbase\lib\util\Common;
use phpbase\lib\util\Msg;
use Qiniu\Http\Error;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
/**
 * Class Storage
 * @package phpbase\service\qiniu
 * @author qian lei
 */
class Storage extends Base
{
    /**
     * @param $bucket
     * @param string $prefix 要列取文件的公共前缀
     * @param string $marker 上次列举返回的位置标记，作为本次列举的起点信息
     * @param integer $limit 本次列举的条目数
     * @param string $delimiter
     * @return bool|array
     */
    public function listFiles($bucket, $prefix = '', $marker = '', $limit = 10, $delimiter = '/')
    {
        try {
            list($ret, $error) = (new BucketManager($this->auth))->listFiles($bucket, $prefix, $marker, $limit, $delimiter);
            if ($error) {
                /* @var Error $error*/
                throw new \Exception(
                    is_string($error)
                        ? $error
                        : (is_object($error) ? Common::toJson($error->getResponse()) : '')
                );
            }
            return $ret;
        } catch (\Exception $e) {
            Msg::setMsg($e->getMessage());
            return false;
        }
    }

    /**
     * @param $bucket
     * @param string $file 要上传的文件路径
     * @param bool $blob 上传方式
     * @return bool|array
     */
    public function uploadFile($bucket, $file, $blob = false)
    {
        $this->setBucket($bucket);
        try {
            if (!is_file($file) || !file_exists($file)) {
                throw new \Exception("{$file}不存在");
            }
            if ($blob) {
                list($ret, $error) = (new UploadManager())->put($this->getUploadToken(), null, $file);
            } else {
                list($ret, $error) = (new UploadManager())->putFile($this->getUploadToken(), pathinfo($file, PATHINFO_BASENAME), $file);
            }
            if ($error) {
                /* @var Error $error*/
                throw new \Exception(
                    is_string($error)
                        ? $error
                        : (is_object($error) ? Common::toJson($error->getResponse()) : '')
                );
            }
            return $ret;
        } catch (\Exception $e) {
            Msg::setMsg($e->getMessage());
            return false;
        }
    }

    /**
     * @param $bucket
     * @param string $filename 要下载的文件名称
     * @return bool|string
     */
    public function downloadUrl($bucket, $filename)
    {
        //http://<domain>/<key>
        $domains = (new BucketManager($this->auth))->domains($bucket);
        return 'http://'. array_pop($domains[0]) . '/' . $filename;
    }
}