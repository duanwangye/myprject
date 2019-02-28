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

class UserHongbao extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const STATUS_UNUSED = 1;
    const STATUS_USED = 2;
    const STATUS_OUTTIME = 3;
    const STATUSS = [
        self::STATUS_UNUSED=>'未用过',
        self::STATUS_USED=>'已使用',
        self::STATUS_OUTTIME=>'已过期',
    ];


    public function hongbao() {
        return $this->belongsTo('Hongbao', 'hongbaoID');
    }

    public function getBeginTimeAttr($value) {
        return Common::timetodate($value, 0);
    }

    public function getEndTimeAttr($value) {
        return Common::timetodate($value, 0);
    }
}