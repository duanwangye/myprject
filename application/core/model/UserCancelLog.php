<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Config;
use think\Log;
use think\Model;
use tool\Common;

class UserCancelLog extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = false;
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;


    const STATUS_SUCCESS = 1;
    const STATUS_UN = 2;
    const STATUS_ERROR = 3;
    const STATUSS = [
        self::STATUS_SUCCESS=>'已成功',
        self::STATUS_UN=>'未完成',
        self::STATUS_ERROR=>'失败'
    ];
}