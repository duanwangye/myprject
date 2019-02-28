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
use app\core\model\Sms;
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
        return true;
        Log::write('CashSend', 'behavior');
        //return true;

        // 行为逻辑
        $cang = $params['cang'];
        $user = $params['user'];


        if($cang['isForged'] == 1) {
            return true;
        }

        $money = 0;
        $moneySubject = (int)$cang['moneySubject'];
        if($cang->subject['subjectTypeID'] == 1) {
            return true;
        }
        else if($cang->subject['subjectTypeID'] == 2) {
            /*if($moneySubject >= 0) {
                $money = 1;
            }*/
            if($moneySubject >= 50000) {
                $money = 108;
            }
            if($moneySubject >= 100000) {
                $money = 228;
            }
            if($moneySubject >= 150000) {
                $money = 368;
            }
        }
        else if($cang->subject['subjectTypeID'] == 3) {

            if($moneySubject >= 50000) {
                $money = 268;
            }
            if($moneySubject >= 100000) {
                $money = 638;
            }
            if($moneySubject >= 150000) {
                $money = 1028;
            }
        }
        else if($cang->subject['subjectTypeID'] == 4) {

            if($moneySubject >= 50000) {
                $money = 418;
            }
            if($moneySubject >= 100000) {
                $money = 788;
            }
            if($moneySubject >= 150000) {
                $money = 1228;
            }
        }

        if($money == 0) {
            return true;
        }

        $fromUserAccount = '15825631526';
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
                'money'=>$money,
                'status'=>1
            ]);

            //第三步
            $user->userAccount = $user->userAccount->MODE_cashsend_addMoney($cash, $user);


            //第四步，发送短信
            $value = '三重活动'.$money.'元现金';
            $phone = '400-005-8155';
            Sms::create([
                'mobile'=>$user['mobile'],
                'message'=>'【佳乾财富】亲爱的用户，'.$value.'已到账，赶快登录app查收哦！祝您新的一年工作顺利，事事顺心！'.$phone,
                'note'=>'三重活动',
                'sendTime'=>THINK_START_TIME
            ]);



            Db::commit();
        }
        catch (Exception $e) {
            Log::write($e->getMessage().'|'.$e->getLine().'|'.$e->getFile(), 'behavior');
            Db::rollback();

        }
        return true;
    }
}