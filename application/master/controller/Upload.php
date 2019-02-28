<?php
namespace app\master\controller;

use sea\Upyun;
use tool\Common;

class Upload extends Base
{
    public function getUploadUrl($count = 0)
    {
        $upyun = new Upyun();
        $res = $upyun->getFormUrl($count);
        if(!$res) {
            return json(Common::rm(-2, '获取上传配置失败'));
        }
        return json(Common::rm(1, '', $res));
    }
}
