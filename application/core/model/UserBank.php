<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Model;
use tool\Common;

class UserBank extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;


    const STATUS_OK = 1;
    const STATUS_FAIL = 2;


    public function bank() {
        return $this->belongsTo('Bank', 'bankID');
    }

    public function getBankNumberAsteriskAttr($value, $data) {
        return Common::bankNumberAsterisk($data['bankNumber']);
    }
}