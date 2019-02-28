<?php
/**
 * 下单行为类.
 * User: qissen
 * Date: 2018/2/19
 * Time: 13:59
 */


namespace app\mobile\behavior;

use app\core\model\Cash;
use app\core\model\Cang;
use app\core\model\User;
use app\core\model\UserAccount;
use app\core\service\Finance;
use think\Db;
use think\Exception;
use think\Log;

class CashSend
{
    //下单成功
    public function run(&$params)
    {
        Log::write('CashSend', 'behavior');
        // 行为逻辑
        $cang = $params['cang'];
        $user = $params['user'];
        //如果是虚拟用户，则不执行
        if($user['isForged']) {
            return true;
        }

        $this->cashSendOnGuideForIntroduce($user);
        $this->cashSendOnCommissionForIntroduce($user, $cang);
        return true;
    }


    //邀请注册返现（引导）
    public function cashSendOnGuideForIntroduce($user) {

        $day = 30;//过了30天不投，gg
        $money = 100;//累计100000才能发
        $span = 1;//过几天发
        $sendMoney = 100;//给多少钱啊，嗯
        $sendMoney_bei = 100;
        if(
            $user['isForged'] ||//如果为虚拟用户
            $user['isIntroduceCashSend'] ||//如果已经发送就不发
            $user->getData('addTime') + $day * 86400 < THINK_START_TIME ||//如果已经过期了，不发
            $user->userAccount['hasInvestBenTotal'] < $money ||//如果没投到一定量，不发
            !$user['introduce']//如果没有邀请人，不发
        ) {
            Log::info($user['isForged'].'_'.$user['isIntroduceCashSend'].'_'.($user->getData('addTime') + $day * 86400).'_'.$user->userAccount['hasInvestBenTotal'].'_'.$user['introduce']);
            return;
        }

        $userFrom = User::get($user['introduce']);
        if(!$userFrom) {
            return;
        }

        //生成被邀请人(我)一个返现
        Cash::create([
            'mode'=>Cash::MODE_INTRODUCE_BEI,
            'modeID'=>$user['userID'],//被邀请人（我）
            'outerNumber'=>'',
            'userID'=>$userFrom['userID'],
            'fromUser'=>'',
            'money'=>$sendMoney_bei,
            'estimateTime'=>THINK_START_TIME + $span * 86400
        ]);
        $userAccount = UserAccount::get([
            'userID'=>$user['userID']
        ]);
        $userAccount['introduceMoney'] = $userAccount['introduceMoney'] + $sendMoney;
        $userAccount->allowField(['introduceMoney'])->save();

        //生成邀请人一个返现
        Cash::create([
            'mode'=>Cash::MODE_INTRODUCE,
            'modeID'=>$userFrom['userID'],//邀请人
            'outerNumber'=>'',
            'userID'=>$user['userID'],
            'fromUser'=>'',
            'money'=>$sendMoney_bei,
            'estimateTime'=>THINK_START_TIME + $span * 86400
        ]);
        $userAccountFrom = UserAccount::get([
            'userID'=>$userFrom['userID']
        ]);
        $userAccountFrom['introduceMoney'] = $userAccountFrom['introduceMoney'] + $sendMoney_bei;
        $userAccountFrom->allowField(['introduceMoney'])->save();

        $user['isIntroduceCashSend'] = 1;
        $user->allowField(['isIntroduceCashSend'])->save();
        return;
    }



    //邀请注册返现（提成）
    public function cashSendOnCommissionForIntroduce($user, $cang) {
        if( !$user['introduce']) {
            return;
        }
        if($user->getData('addTime') + 365 * 86400 < THINK_START_TIME) {
            return;
        }

        $userFrom = User::get($user['introduce']);
        if(!$userFrom) {
            return;
        }

        $span = 1;
        $year = 1;
        if($cang['moneySubject'] >= 0 && $cang['moneySubject'] < 5000) {
            $year = 1;
        }
        else if($cang['moneySubject'] >= 5000 && $cang['moneySubject'] < 30000) {
            $year = 3;
        }
        else if($cang['moneySubject'] >= 30000 && $cang['moneySubject'] < 100000) {
            $year = 5;
        }
        else if($cang['moneySubject'] >= 100000 && $cang['moneySubject'] < 200000) {
            $year = 10;
        }
        else if($cang['moneySubject'] >= 200000) {
            $year = 15;
        }

        $money = $cang['interest'] * $year / 100;
        //生成邀请人一个返现
        Cash::create([
            'mode'=>Cash::MODE_INTRODUCE_COMMISSION,
            'modeID'=>$cang['cangID'],//邀请人
            'outerNumber'=>'',
            'userID'=>$userFrom['userID'],
            'fromUser'=>'',
            'money'=>$money,
            'estimateTime'=>THINK_START_TIME + $span * 86400
        ]);
        $userAccountFrom = $userFrom->userAccount;
        $userAccountFrom['introduceMoney'] = $userAccountFrom['introduceMoney'] + $money;
        $userAccountFrom->allowField(['introduceMoney'])->save();
        return;
    }
}