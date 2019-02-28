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

class UserRecharge extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const TYPE_BANK = 'TYPE_BANK';
    const TYPE_WXCHANGE = 'TYPE_WXCHANGE';
    const TYPES = [
        self::TYPE_BANK=>'银行卡充值',
        self::TYPE_WXCHANGE=>'微信零钱充值'
    ];

    const STATUS_PAY = 1;
    const STATUS_UNPAY = 2;
    const STATUS_ERROR = 3;
    const STATUSS = [
        self::STATUS_PAY=>'已充值',
        self::STATUS_UNPAY=>'没有支付',
        self::STATUS_ERROR=>'充值失败',
    ];

    public function user() {
        return $this->belongsTo('user', 'userID');
    }

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function getResultTimeAttr($value)
    {
        return Common::timetodate($value, 4);
    }

    public function getOuterReachTimeAttr($value)
    {
        return Common::timetodate($value, 4);
    }

    public function getTypeNameAttr($value, $data) {
        return self::TYPES[$data['type']];
    }

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function createAlias($id)
    {
        return 'RC' . Common::timetodate(THINK_START_TIME, 10) .  sprintf("%08d", $id);
    }


}