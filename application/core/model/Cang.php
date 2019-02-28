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

class   Cang extends Model
{
    protected $pk = 'cangID';
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const STATUS_PAY = 1;
    const STATUS_UNPAY = 2;
    const STATUS_INTEREST = 3;
    const STATUS_REPAY = 4;
    const STATUS_FINISH = 10;
    const STATUSS = [
        self::STATUS_PAY=>'持有中',
        self::STATUS_UNPAY=>'未支付',
        self::STATUS_INTEREST=>'计息中',
        self::STATUS_REPAY=>'回款中',
        self::STATUS_FINISH=>'已回款'
    ];

    public function subject() {
        return $this->belongsTo('Subject', 'subjectID');
    }

    public function cangRepay() {
        return $this->hasMany('CangRepay', 'cangID');
    }

    public function user() {
        return $this->belongsTo('User', 'userID');
    }

    public function setMoneySubjectAttr($value) {
        return $value * 100;
    }

    public function getMoneySubjectAttr($value) {
        return Common::price2($value / 100);
    }

    public function setBenAttr($value) {
        return $value * 100;
    }

    public function getBenAttr($value) {
        return Common::price2($value / 100);
    }

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function setInterestAttr($value) {
        return $value * 100;
    }

    public function getInterestAttr($value) {
        return Common::price2($value / 100);
    }

    public function getInterestBeginTimeAttr($value)
    {
        return Common::timetodate($value, 0);
    }

    public function getPayTimeAttr($value)
    {
        return Common::timetodate($value);
    }

    public function getInterestEndTimeAttr($value)
    {
        return Common::timetodate($value + 86400, 0);
    }

    public function getRepayTimeTextAttr($value)
    {
        return '到期日15点前还款';
    }

    /*public function getStatusAttr($value) {
        if($value == self::STATUS_PAY) {
            if(THINK_START_TIME < $this->getData['interestBeginTime']) {
                return self::STATUS_PAY;
            }
            else if(THINK_START_TIME >= $this->getData['interestBeginTime'] &&  THINK_START_TIME < $this->getData['interestEndTime']){
                return self::STATUS_INTEREST;
            }
            else {
                return self::STATUS_REPAY;
            }
        }
        return $value;
    }*/

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function getIsForgedTextAttr($value, $data) {
        if($data['isForged'] === 1) {
            return '虚拟账号';
        }
        return '自然人';
    }

    //设置计息状态
    public function setStatusInterest() {
        if($this->status != self::STATUS_PAY) {
            return true;
        }
        $this->status = self::STATUS_INTEREST;
        $this->save();
        return true;
    }

    public static function createAlias($id)
    {
        return 'CP' . Common::timetodate(THINK_START_TIME, 10) .  sprintf("%08d", $id);
    }
}
