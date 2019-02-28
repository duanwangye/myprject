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

class UserDrawcash extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'applyTime';
    protected $autoWriteTimestamp = true;
    const REACH_IMM = 'REACH_IMM';
    const REACH_HOUR2 = 'REACH_HOUR2';
    const REACH_DAY2 = 'REACH_DAY2';
    const REACHS = [
        self::REACH_IMM=>'即时到帐',
        self::REACH_HOUR2=>'两小时内到帐',
        self::REACH_DAY2=>'两天内到帐'
    ];

    const TYPE_BANK = 'TYPE_BANK';
    const TYPE_WXCHANGE = 'TYPE_WXCHANGE';
    const TYPES = [
        self::TYPE_BANK=>'提现到银行',
        self::TYPE_WXCHANGE=>'提现到零钱'
    ];

    const STATUS_OK = 1;
    const STATUS_SUBMIT = 2;
    const STATUS_ING = 3;
    const STATUS_REFUSE = 4;
    const STATUS_ERROR = 5;
    const STATUSS = [
        self::STATUS_OK=>'已到账',
        self::STATUS_SUBMIT=>'提交中',
        self::STATUS_ING=>'提现中',
        self::STATUS_REFUSE=>'已拒绝',
        self::STATUS_ERROR=>'失败'
    ];

    const BANK_TYPE_WXMCH = 'WXMCH';
    const BANK_TYPE_WXSERVICE = 'WXSERVICE';


    public function getDrawcashIDAttr($value, $data) {
        return $data['id'];
    }

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function getApplyTimeAttr($value)
    {
        return Common::timetodate($value, 4);
    }

    public function getResultTimeAttr($value)
    {
        return Common::timetodate($value, 4);
    }

    public function getReachTimeAttr($value)
    {
        return Common::timetodate($value, 4);
    }

    public function getTypeNameAttr($value, $data) {
        return self::TYPES[$data['type']];
    }

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function getReachTextAttr($value, $data) {
        return self::REACHS[$data['reach']];
    }

    public function createAlias($id)
    {
        return 'DH' . Common::timetodate(THINK_START_TIME, 10) .  sprintf("%08d", $id);
    }
}