<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use app\core\model\UserFinance;
use think\Config;
use think\Db;
use think\Exception;
use think\Log;
use think\Model;
use tool\Common;

class UserAccount extends Model
{
    protected $pk = 'userAccountID';
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'updateTime';
    protected $autoWriteTimestamp = true;

    public function setMoneyAttr($value)
    {
        return $value * 100;
    }

    public function getMoneyTotalAttr($value, $data)
    {
        return Common::price2(($data['waitBen'] + $data['waitInterest'] + $data['money']) / 100);
    }

    public function getMoneyAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setMoneyFrozenAttr($value)
    {
        return $value * 100;
    }

    public function getMoneyFrozenAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setMoneyAccAttr($value)
    {
        return $value * 100;
    }

    public function getMoneyAccAttr($value)
    {
        return Common::price2($value / 100);
    }

    /*public function setMoneyYesterdayAttr($value)
    {
        return $value * 100;
    }

    public function getMoneyTodayAccAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setMoneyTodayAttr($value)
    {
        return $value * 100;
    }

    public function getMoneyTodayAttr($value)
    {
        return Common::price2($value / 100);
    }*/

    public function setHasInvestBenTotalAttr($value)
    {
        return $value * 100;
    }

    public function getHasInvestBenTotalAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setHasInvestMoneyTotalAttr($value)
    {
        return $value * 100;
    }

    public function getHasInvestMoneyTotalAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setHasRepayBenTotalAttr($value)
    {
        return $value * 100;
    }

    public function gethasRepayBenTotalAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setHasRepayInterestTotalAttr($value)
    {
        return $value * 100;
    }

    public function getHasRepayInterestTotalAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setWaitBenAttr($value)
    {
        return $value * 100;
    }

    public function getWaitBenAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setWaitInterestAttr($value)
    {
        return $value * 100;
    }

    public function getWaitInterestAttr($value)
    {
        return Common::price2($value / 100);
    }

    public function setIntroduceMoneyAttr($value)
    {
        return $value * 100;
    }

    public function getIntroduceMoneyAttr($value)
    {
        return Common::price2($value / 100);
    }


    public function updateProfit($cang  = false) {
        $todayTime = Common::datetotime(Common::timetodate(THINK_START_TIME, 0));
        if($this['todayTime'] != $todayTime) {
            //更新昨天收益
            //$this['moneyYesterday'] = $this['moneyToday'];

            //更新今日收益
            $cangList = Cang::where([
                'isForged'=>0,
                'status'=>['in', [Cang::STATUS_INTEREST]],
                'userID'=>$this['userID']
            ])->select();
            $money = 0;
            $moneyAcc = 0;
            if(!$cangList->isEmpty()) {
                foreach ($cangList as $k=>$item) {
                    $moneyOneDay = round($item['interest'] / $item['investDay'], 6);
                    $money += $moneyOneDay;
                    if(!$this['todayTime']) {
                        $moneyAcc = $moneyAcc + $moneyOneDay + (($todayTime - Common::datetotime(Common::timetodate($item->getData('interestBeginTime')))) / 86400) * $moneyOneDay;
                        //Log::info('day:'.(($todayTime - Common::datetotime(Common::timetodate($item->getData('interestBeginTime')))) / 86400));
                    }
                }
            }
            $money = round($money, 4);

            $this['moneyToday'] = $money;
            $this['moneyAcc'] = round($this['moneyAcc'] + $moneyAcc, 4);
            $this['todayTime'] = $todayTime;
            $this->save();
            //$this->userAccount['moneyAcc'] =
        }
        else {
            if($cang) {
                $this['moneyAcc'] = $this['moneyAcc'] + round($cang['interest'] / $cang['investDay'], 2);
                $this['moneyToday'] = $this['moneyToday'] + round($cang['interest'] / $cang['investDay'], 4);
                $this->save();
            }
        }
        return $this;
    }


    /**
     * 充值时,更新money，并生成账务流水，该方法只做充值时余额增加时使用
     * @param $recharge 充值对象
     * @param $user 用户对象
     * @param $reachTime 第三方生成流水时间
     */
    public function MODE_recharge_addMoney(UserRecharge $recharge, User $user, $reachTime)
    {
        //1、更新账户资产
        $userAccount = self::get([
            'userID' => $user['userID']
        ]);
        $moneyPre = $userAccount['money'];
        $moneyNow = $userAccount['money'] + $recharge['money'];
        $userAccount['money'] = $userAccount['money'] + $recharge['money'];
        $userAccount->save();


        //2、更新用户流水
        UserFinance::create([
            'mode' => UserFinance::MODE_RECHARGE,
            'modeID' => $recharge['userRechargeID'],
            'money' => $recharge['money'],
            'userID' => $user['userID'],
            'status' => UserFinance::STATUS_OK,
            'moneyPre' => $moneyPre,
            'moneyNow' => $moneyNow
        ]);

        //3、更新充值记录
        UserRecharge::update([
            'status' => UserRecharge::STATUS_PAY,
            'outerReachTime' => $reachTime
        ], [
            'userRechargeID' => $recharge['userRechargeID']
        ]);

        return $userAccount;
    }

    /**
     * 发起提现到零钱的请求
     * @param $userDrawcash
     * @param $user - 发起申请人
     * @param $submitTime - 提交时间
     */
    public function MODE_drawcash_decMoney_submit(&$msg, UserDrawcash $userDrawcash, User $user, $submitTime)
    {
        //1、更新账户资产，余额减少，冻结金额增多
        $msg = '操作成功';
        $userAccount = self::get([
            'userID' => $user['userID']
        ]);

        $moneyPre = $userAccount['money'];
        $moneyNow = $userAccount['money'] - $userDrawcash['money'];
        if ($moneyNow < 0) {
            $msg = '余额不足';
            return false;
        }
        $userAccount['money'] = $userAccount['money'] - $userDrawcash['money'];
        $userAccount['moneyFrozen'] = $userAccount['moneyFrozen'] + $userDrawcash['money'];
        $userAccount->save();

        //2、生成一个提现流水，处于冻结状态，什么时候收到异步通知，我们在改变该流水
        UserFinance::create([
            'mode' => UserFinance::MODE_DRAWCASH,
            'modeID' => $userDrawcash['userDrawcashID'],
            'money' => 0 - $userDrawcash['money'],
            'userID' => $user['userID'],
            'status' => UserFinance::STATUS_FROZEN,
            'moneyPre' => $moneyPre,
            'moneyNow' => $moneyNow
        ]);


        //3、更新提现记录
        UserDrawcash::update([
            'status' => UserDrawcash::STATUS_ING,
            'submitTime' => $submitTime
        ], [
            'userDrawcashID' => $userDrawcash['userDrawcashID']
        ]);
        return $userAccount;
    }

    /**
     * 持仓时,更新money，并生成账务流水，该方法只做持仓时余额减少时使用
     * @param $money 更新金钱
     * @param $rechargeID 充值ID
     * @param $userID 用户ID
     * @param $outer 第三方支付等
     */
    public function MODE_cang_decMoney($cang, $user, $interest)
    {

        //1、更新账户资产
        $userAccount = self::get([
            'userID' => $user['userID']
        ]);
        $moneyPre = $userAccount['money'];
        $moneyNow = $userAccount['money'] - $cang['money'];
        $userAccount['waitBen'] = $userAccount['waitBen'] + $cang['ben'];
        $userAccount['waitInterest'] = $userAccount['waitInterest'] + $interest;
        $userAccount['hasInvestBenTotal'] = $userAccount['hasInvestBenTotal'] + $cang['ben'];
        $userAccount['money'] = $moneyNow;
        $userAccount->save();


        //2、更新用户流水
        UserFinance::create([
            'mode' => UserFinance::MODE_CANG,
            'modeID' => $cang['cangID'],
            'money' => 0 - $cang['money'],
            'userID' => $user['userID'],
            'status' => UserFinance::STATUS_OK,
            'moneyPre' => $moneyPre,
            'moneyNow' => $moneyNow
        ]);

        return $userAccount;
    }

    /**
     * 返息,更新money，并生成账务流水，该方法只做持返息余额增加时使用
     * @param $money 更新金钱
     * @param $cangID 持仓ID
     * @param $userID 用户ID
     */
    public function MODE_repayInterest_addMoney($cangRepay, $user)
    {
        //1、更新账户资产
        $userAccount = self::get([
            'userID' => $user['userID']
        ]);
        $moneyPre = $userAccount['money'];
        $moneyNow = $userAccount['money'] + $cangRepay['money'];
        $userAccount['money'] = $moneyNow;
        $userAccount['waitInterest'] = $userAccount['waitInterest'] - $cangRepay['money'];
        $userAccount['moneyAcc'] = $userAccount['moneyAcc'] + $cangRepay['money'];
        $userAccount->save();

        //2、更新用户流水
        UserFinance::create([
            'mode' => UserFinance::MODE_REPAY_INTEREST,
            'modeID' => $cangRepay['cangRepayID'],
            'money' => $cangRepay['money'],
            'userID' => $user['userID'],
            'status' => UserFinance::STATUS_OK,
            'moneyPre' => $moneyPre,
            'moneyNow' => $moneyNow
        ]);
        return $userAccount;
    }

    /**
     * 返本,更新money，并生成账务流水，该方法只做返本时余额增加时使用
     * @param $money 更新金钱
     * @param $cangID 持仓ID
     * @param $userID 用户ID
     */
    public function MODE_repayBen_addMoney($cangRepay, $user)
    {
        //1、更新账户资产
        $userAccount = self::get([
            'userID' => $user['userID']
        ]);
        $moneyPre = $userAccount['money'];
        $moneyNow = $userAccount['money'] + $cangRepay['money'];
        $userAccount['money'] = $moneyNow;
        $userAccount['waitBen'] = $userAccount['waitBen'] - $cangRepay['money'];
        $userAccount->save();

        //2、更新用户流水
        UserFinance::create([
            'mode' => UserFinance::MODE_REPAY_BEN,
            'modeID' => $cangRepay['cangRepayID'],
            'money' => $cangRepay['money'],
            'userID' => $user['userID'],
            'status' => UserFinance::STATUS_OK,
            'moneyPre' => $moneyPre,
            'moneyNow' => $moneyNow
        ]);
        return $userAccount;
    }

    /**
     * 返本,送现金
     * @param $cash 现金
     * @param $user 用户
     * @param $userID 用户ID
     */
    public function MODE_cashsend_addMoney(Cash $cash, User $user) {
        //1、更新账户资产
        $userAccount = self::get([
            'userID' => $user['userID']
        ]);
        $moneyPre = $userAccount['money'];
        $moneyNow = $userAccount['money'] + $cash['money'];
        $userAccount['money'] = $userAccount['money'] + $cash['money'];
        $userAccount->save();


        //2、更新用户流水
        UserFinance::create([
            'mode' => UserFinance::MODE_CASHSEND,
            'modeID' => $cash['cashID'],
            'money' => $cash['money'],
            'userID' => $user['userID'],
            'status' => UserFinance::STATUS_OK,
            'moneyPre' => $moneyPre,
            'moneyNow' => $moneyNow
        ]);
        return $userAccount;
    }


}