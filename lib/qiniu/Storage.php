<?php
namespace phpbase\lib\qiniu;

use phpbase\lib\util\Common;
use phpbase\lib\util\Msg;
use Qiniu\Http\Error;
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
     * @param string $file 要上传的文件路径
     * @return bool|array
     */
    public function uploadFile($bucket, $file)
    {
        $this->setBucket($bucket);
        try {
            if (!is_file($file) || !file_exists($file)) {
                throw new \Exception("{$file}不存在");
            }
            list($ret, $error) = (new UploadManager())->putFile($this->getUploadToken(), pathinfo($file, PATHINFO_BASENAME), $file);
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
}