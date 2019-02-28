<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Config;
use think\Db;
use think\Exception;
use think\Log;
use think\Model;
use tool\Common;

class Master extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    public function createPassword($password) {
        return md5('aisdfa90asopdf0as8d0f8a0s9d8f0asdfjasdfaqw'.$password);
    }

    public function createToken() {
        return md5(THINK_START_TIME.Common::token_create(10));
    }

    public function createTokenOverTime() {
        return (int)THINK_START_TIME + 7 * 86400;
    }

}