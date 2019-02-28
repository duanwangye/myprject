<?php
/**
 * 下单行为类.
 * User: qissen
 * Date: 2018/2/19
 * Time: 13:59
 */


namespace app\mobile_v_2_1\behavior;

use app\core\model\Cash;
use app\core\model\Cang;
use app\core\model\UserAccount;
use app\core\service\Finance;
use think\Db;
use think\Exception;
use think\Log;

class UserHonor_Share
{
    //下单成功
    public function run(&$params)
    {
        Log::write('UserHonor', 'behavior');
        return true;
        // 行为逻辑
        $cang = $params['cang'];
        $user = $params['user'];

        $money = '10';
        $fromUserAccount = '17316900863';

        Db::startTrans();
        try{
            //第一步
            $finance = new Finance();
            $trade = new \Trade();
            $trade->setPayment($fromUserAccount);
            $trade->setReceive($user['mobile']);
            $trade->setMoney($money);
            $trade->setRem('现金鼓励');
            $result = $finance->order($msg, $trade);
            if(!$result) {
                Db::rollback();
                return true;
            }
            //第二步
            $cash = Cash::create([
                'mode'=>Cash::MODE_CANG,
                'modeID'=>$cang['cangID'],
                'outerNumber'=>$result,
                'userID'=>$user['userID'],
                'fromUser'=>$fromUserAccount,
                'money'=>$money
            ]);

            //第三步
            $user->userAccount = $user->userAccount->MODE_cashsend_addMoney($cash, $user);

            Db::commit();
        }
        catch (Exception $e) {
            Log::write($e->getMessage().'|'.$e->getLine().'|'.$e->getFile(), 'behavior');
            Db::rollback();

        }
        return true;
    }
}