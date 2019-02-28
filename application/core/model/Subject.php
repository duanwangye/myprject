<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;


use think\Config;
use think\Model;
use tool\Common;


class Subject extends Model
{
    protected $pk = 'subjectID';
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const STATUS_LOCK = 1;
    const STATUS_ONLINE_CHECK = 2;
    const STATUS_ONLINE = 3;
    const STATUS_FULL = 4;
    const STATUS_OVERTIME = 5;
    const STATUS_REPAY = 6;

    const STATUSS = [
        self::STATUS_LOCK=>'锁定',
        self::STATUS_ONLINE_CHECK=>'等待上架',
        self::STATUS_ONLINE=>'抢购中',
        self::STATUS_FULL=>'已卖光',
        self::STATUS_OVERTIME=>'已到期',
        self::STATUS_REPAY=>'已还款'
    ];

    const STATUS_LOAN_NULL = 1;
    const STATUS_LOAN_FANG_WAIT = 2;
    const STATUS_LOAN_FANG = 3;
    const STATUS_LOAN_FINISH = 4;
    const STATUS_LOANS = [
        self::STATUS_LOAN_NULL=>'未到放款时间',
        self::STATUS_LOAN_FANG_WAIT=>'待放款',
        self::STATUS_LOAN_FANG=>'收款中',
        self::STATUS_LOAN_FINISH=>'已收款'
    ];

    public function subjectType()
    {
        return $this->belongsTo('SubjectType', 'subjectTypeID');
    }

    public function interestType()
    {
        return $this->belongsTo('InterestType', 'interestTypeID');
    }

    public function interestTimeType()
    {
        return $this->belongsTo('InterestTimeType', 'interestTimeTypeID');
    }

    public function subjectStat()
    {
        return $this->hasOne('SubjectStat', 'subjectID');
    }

    public function cangList()
    {
        return $this->hasMany('Cang', 'subjectID');
    }

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function getStatusLoanTextAttr($value, $data) {
        return self::STATUS_LOANS[$data['statusLoan']];
    }

    public function getUnitAttr($value, $data) {
        return '天';
    }

    public function getYearAttr($value) {
        return sprintf("%.1f",$value);
    }

    public function getYearSystemAttr($value) {
        return sprintf("%.1f",$value);
    }

    public function getYearExtAttr($value) {
        return sprintf("%.1f",$value);
    }

    public static function getStatusList() {
        return self::STATUSS;
    }

    public static function createAlias() {
        return Common::timetodate(THINK_START_TIME, 10).Common::token_create(6);
    }

    public function getReleaseTimeAttr($value) {
        return Common::timetodate($value, 6);
    }

    public function getBeginTimeAttr($value) {
        return Common::timetodate($value, 6);
    }

    public function getEndTimeAttr($value) {
        return Common::timetodate($value, 6);
    }

    public function getRepayInterestTimeAttr($value) {
        return Common::timetodate($value, 6);
    }

    public function getOverTimeAttr($value) {
        return Common::timetodate($value, 0);
    }

    public function getFullTimeAttr($value) {
        if(!$value) {
            return '';
        }
        return Common::timetodate($value);
    }

    public function getInterestBeginTimeAttr($value, $data) {
        return '购买日';
    }

    public function getSubjectTypeIconAttr($value, $data) {
        $icon = '';
        if($data['subjectTypeID'] == 1) {
            $icon = 'http://slb.dahengdian.com/jiaqiancaifu/2017/12/30/A3hShPxTCV.png';
        }
        else if($data['subjectTypeID'] == 2) {
            $icon = 'http://slb.dahengdian.com/jiaqiancaifu/2018/01/03/loLuc5moxc.png';
            //http://slb.dahengdian.com/jiaqiancaifu/2018/01/11/orrP4nOlQn.png
        }
        else if($data['subjectTypeID'] == 3) {
            $icon = 'http://slb.dahengdian.com/jiaqiancaifu/2018/01/03/QioKU4FSfn.png';
        }
        else {
            $icon = 'http://slb.dahengdian.com/jiaqiancaifu/2017/12/30/A3hShPxTCV.png';
        }
        return $icon;
    }

    public function setPriceAttr($value) {
        return $value * 100;
    }

    public function getPriceAttr($value) {
        return (int)($value / 100);
    }

    public function setBasePriceAttr($value) {
        return $value * 100;
    }

    public function getBasePriceAttr($value) {
        return Common::price2($value / 100);
    }

    public function setBaseMaxPriceAttr($value) {
        return $value * 100;
    }

    public function getBaseMaxPriceAttr($value) {
        return Common::price2($value / 100);
    }

    public function getRepayTimeAttr($value, $data) {
        $repaytimeDelaySpan = self::getRepaytimeDelaySpan();
        return Common::timetodate($repaytimeDelaySpan + $data['overTime'], 0);
    }

    public function getReachTimeAttr($value, $data) {
        $reachTimeDelaySpan = self::getReachtimeDelaySpan();
        return Common::timetodate($reachTimeDelaySpan * 86400 + $data['overTime'], 0);
    }

    public function getInvestDayAttr($value, $data) {
        $overtimeIsinterest = self::getOvertimeIsinterest();

        $investDay = ($data['overTime'] - Common::datetotime(Common::timetodate(THINK_START_TIME, 0))) / 86400;
        if($overtimeIsinterest == 1) {
            $investDay = $investDay - 1;
        }
        if($data['interestTimeTypeID'] == 2) {
            $investDay = $investDay + 1;
        }
        return $investDay;
    }

    public static function getRepaytimeDelaySpan() {
        return Config::get('interest.repaytime_delay_span');
    }

    public static function getDefaultInterestTimeType() {
        return Config::get('interest.default_interest_time_type');
    }

    public static function getOvertimeIsinterest() {
        return Config::get('interest.overtime_isinterest');
    }

    public static function getReachtimeDelaySpan() {
        return Config::get('interest.reachtime_delay_span');
    }

    public static function getDailyReleaseSpan() {
        return Config::get('interest.default_daily_release_span');
    }

    public static function setSubjectFull($subject) {
        //满标计息
        if($subject['interestTimeTypeID'] == 1) {
            $interestBeginTime = Common::datetotime(Common::timetodate(THINK_START_TIME, 0));
            if(self::getOvertimeIsinterest() == 1) {
                $interestEndTime = $subject->getData('overTime');
            }
            else {
                $interestEndTime = $subject->getData('overTime') - 86400;
            }
            $investDay = $subject['investDay'];//投资天数

            //批量更新数据cang
            Cang::where([
                'subjectID'=>$subject['subjectID']
            ])->chunk(100, function($subjectList) use($subject, $interestBeginTime, $interestEndTime, $investDay) {
                foreach ($subjectList as $k=>$item) {
                    $interest = $item['moneySubject'] * $subject['year'] / 100 / 365 * $investDay;//预付利息
                    Cang::update([
                        'interestBeginTime'=>$interestBeginTime,
                        'interestEndTime'=>$interestEndTime,
                        'investDay'=>$investDay,
                        'interest'=>$interest,
                        'status'=>Cang::STATUS_INTEREST //计息状态开始
                    ],[
                        'cangID'=>$item['cangID']
                    ]);

                    CangRepay::update([
                        'money'=>$interest
                        //'status'=>Cang::STATUS_INTEREST //计息状态开始
                    ],[
                        'cangID'=>$item['cangID'],
                        'repayTypeID'=>2
                    ]);
                }
            });
        }

        //最后更新subject
        self::update([
            'status'=> self::STATUS_FULL,
            'statusLoan'=>self::STATUS_LOAN_FANG_WAIT,
            'fullTime'=>THINK_START_TIME
        ],[
           'subjectID'=> $subject['subjectID']
        ]);
    }

}