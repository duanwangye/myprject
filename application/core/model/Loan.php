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

class Loan extends Model
{
    protected $pk = 'loanID';
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;


    const STATUS_OK = 1;
    const STATUS_INVALID = 2;
    const STATUSS = [
        self::STATUS_OK=>'有效的',
        self::STATUS_INVALID=>'无效的'
    ];

    public function subjectList() {
        return $this->hasMany('Subject', 'loanID');
    }

    public function certTypeText($value, $data) {
        $text = '未知';
        if($data['certType'] == 1) {
            $text = '身份证';
        }
        else if($data['certType'] == 1){
            $text = '营业执照';
        }
        return $text;
    }

    public function pledgeTypeText($value, $data) {
        $text = '未知';
        if($data['pledgeType'] == 1) {
            $text = '车辆抵押';
        }
        return $text;
    }

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }
}
