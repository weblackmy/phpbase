<?php
namespace phpbase\lib\weixin\mp\lib;

use phpbase\lib\weixin\mp\Base;
/**
 * Class Account 账号管理
 * @author qian lei <weblackmy@gmail.com>
 * @package phpbase\lib\weixin\mp
 */
class Account extends Base
{
    /**
     * 生成带参数的二维码
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542
     */
    public function qrCode()
    {
        $result = $this->request->qrCodeTicketCreate('QR_SCENE', 100);
        if ($result === false) {
            var_dump($this->request->getErrCode());
            exit;
        }
        var_dump($result);
    }

    /**
     * 创建二维码ticket
     * @return string
     */
    protected function qrCodeTicketCreate()
    {
        
    }
}