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

class UserIntroduceCash extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = false;
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;
    const STATUS_OK = 1;
    const STATUS_ERROR = 1;
}