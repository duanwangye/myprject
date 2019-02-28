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

class CangRepay extends Model
{

    protected $pk = 'cangRepayID';
    protected $resultSetType = 'collection';
    const STATUS_UNREPAY = 1;
    const STATUS_REPAY = 2;
    const STATUS_REACH = 3;

    const STATUSS = [
        self::STATUS_UNREPAY=>'未回款',
        self::STATUS_REPAY=>'已回款',
        self::STATUS_REACH=>'已到款'
    ];

    public function cang() {
        return $this->belongsTo('Cang', 'cangID');
    }

    public function subject() {
        return $this->belongsTo('Subject', 'subjectID');
    }

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function getReachTimeAttr($value)
    {
        return Common::timetodate($value, 0);
    }

    public function getRepayTimeAttr($value)
    {
        return Common::timetodate($value, 0);
    }

    public function getResultTimeAttr($value)
    {
        return Common::timetodate($value, 0);
    }

    public function getStatusTextAttr($value, $data) {

        return self::STATUSS[$data['status']];
    }

    public function getRepayTypeTextAttr($value)
    {
        if($value == 1) {
            return '本金';
        }
        else if($value == 2){
            return '利息';
        }
        return '';
    }

    public function repay() {
        //第一步，更新cang
        $this->data['status'] = self::STATUS_REPAY;
        $this->save();

        //第二步，更新用户账户
        if($this->data['repayTypeID'] == 1) {
            UserAccount::MODE_repayBen_addMoney($this->data['money'], $this->data['cangID'], $this->data['userID']);
        }
        else {
            UserAccount::MODE_repayInterest_addMoney($this->data['money'], $this->data['cangID'], $this->data['userID']);
        }
    }
}
