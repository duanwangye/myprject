<?php
namespace app\mobile\controller;

use app\core\model\User;
use app\core\model\UserAccount;
use app\core\model\UserDrawcash;
use app\core\model\UserRecharge;
use app\core\service\Pay;
use think\Log;
use think\Request;

class Notify
{
    //充值回调page
    public function Page_recharge() {
        $param = Request::instance()->param();
        Log::write('notify begin', 'pay');
        Log::write($param, 'pay');
        Log::write('end', 'pay');

        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            echo '验证失败';exit;
        }
        $userRecharge = UserRecharge::get([
            'outerNumber'=>$param['mchnt_txn_ssn']
        ]);
        if(!$userRecharge) {
            echo '不存在该充值记录';exit;
        }

        $user = User::get($userRecharge['userID']);
        if(!$user) {
            echo '该充值用户已经丢失';exit;
        }

        $userAccount = new UserAccount();
        //到账时间有点问题
        $userAccount->MODE_recharge_addMoney($userRecharge, $user, THINK_START_TIME);
        echo '验证通过';exit;
    }

    //充值回调Notify
    public function Notify_recharge() {
        $param = Request::instance()->param();
        Log::write($param, 'pay');

        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_dt'].'|'.$param['mchnt_txn_ssn'].'|'.$param['mobile_no'].'|'.$param['remark'], $param['signature']);
        if(!$check) {
            return;
        }

        return view(__FUNCTION__, [
            'money'=>''
        ]);
    }





    //提现
    public function Page_drawcash() {
        $param = Request::instance()->param();
        Log::write('notify begin', 'pay');
        Log::write($param, 'pay');
        Log::write('end', 'pay');

        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            echo '验证失败';exit;
        }
        $userDrawcash = UserDrawcash::get([
            'outerNumber'=>$param['mchnt_txn_ssn'],
            'userDrawcashID'=>$param['userDrawcashID']
        ]);
        if(!$userDrawcash) {
            echo '不存在该充值记录';exit;
        }

        $user = User::get($userDrawcash['userID']);
        if(!$user) {
            echo '该充值用户已经丢失';exit;
        }

        $userAccount = new UserAccount();
        //到账时间有点问题
        $userAccount->MODE_drawcash_decMoney_submit($msg, $userDrawcash, $user, THINK_START_TIME);
        echo $msg;exit;
    }

    //提现回调Notify
    public function Notify_drawcash() {
        $param = Request::instance()->param();
        Log::write($param, 'pay');

        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_dt'].'|'.$param['mchnt_txn_ssn'].'|'.$param['mobile_no'].'|'.$param['remark'], $param['signature']);
        if(!$check) {
            return;
        }

        return view(__FUNCTION__, [
            'money'=>''
        ]);
    }

    //提现到账notify
    public function Notify_drawcash_reach() {
        $param = Request::instance()->param();
        Log::write($param, 'pay');

        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_dt'].'|'.$param['mchnt_txn_ssn'].'|'.$param['mobile_no'].'|'.$param['remark'], $param['signature']);
        if(!$check) {
            return;
        }

        return view(__FUNCTION__, [
            'money'=>''
        ]);
    }





}
