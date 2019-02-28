<?php
namespace app\notify\controller;
use app\core\model\Bank;
use app\core\model\User;
use app\core\model\UserCancelLog;
use app\core\model\UserDrawcash;
use app\core\model\UserRecharge;
use app\core\service\Finance;
use think\Controller;
use think\Log;
use think\Request;
use tool\Common;

class Fuyou extends Controller
{
    //充值接口通知，2分钟之后调用
    public function recharge() {

        /*$param = Request::instance()->param();
        Log::write('notify begin', 'fuyou');
        Log::write($param, 'fuyou');


        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            Log::write('验证失败', 'fuyou');
        }

        $finance = new Finance();
        //传入第三方订单号
        $result = $finance->userRecharge_notify($msg, $param['mchnt_txn_ssn'], $param['amt'], $param['login_id']);//消息提示，流水号，金额，手机号
        Log::write($msg, 'fuyou');



        if(!$result) {
            $str = '<plain>'.
                '<resp_code>'.'1111'.'</resp_code>'.
                '<mchnt_cd>'.$gold->getMchID().'</mchnt_cd>'.
                '<mchnt_txn_ssn>'.$param['mchnt_txn_ssn'].'</mchnt_txn_ssn>'.
                '</plain>';
            Log::write($str, 'fuyou');
            $sign = $gold->rsaSign($str);

            Log::write('end', 'fuyou');
            return  '<ap>'.
                '<plain>'.
                $str.
                '<signature>'.$sign.'</signature>'.
                '</ap>';
        }

        $str = '<plain>'.
            '<resp_code>'.'0000'.'</resp_code>'.
            '<mchnt_cd>'.$gold->getMchID().'</mchnt_cd>'.
            '<mchnt_txn_ssn>'.$param['mchnt_txn_ssn'].'</mchnt_txn_ssn>'.
            '</plain>';
        Log::write($str, 'fuyou');
        $sign = $gold->rsaSign($str);

        Log::write('end', 'fuyou');
        return  '<ap>'.
            '<plain>'.
            $str.
            '<signature>'.$sign.'</signature>'.
            '</ap>';*/
    }

    //充值实时同步
    public function rechargePage() {
        $param = Request::instance()->param();
        $this->assign('from', isset($param['from']) ? $param['from'] : '');


        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            Log::write('充值验证失败', 'fuyou');
            $this->assign('icon', 2);
            return $this->fetch();
        }

        if($param['resp_code'] != '0000') {
            $this->assign('icon', 2);
            return $this->fetch();
        }


        $userRecharge = UserRecharge::get([
            'outerNumber'=>$param['mchnt_txn_ssn']
        ]);

        if(!$userRecharge) {
            $this->assign('icon', 2);
            return $this->fetch();
        }
        $bank = Bank::get($userRecharge['bankID']);
        $this->assign('bankNumber', substr($userRecharge['bankNumber'], (strlen($userRecharge['bankNumber'])-4)));
        $this->assign('bankName', $bank['bankName']);
        $this->assign('money', $userRecharge['money']);
        $this->assign('icon', 1);
        return $this->fetch();
    }

    //充值实时异步
    public function rechargeNotify() {
        $param = Request::instance()->param();
        Log::write('notify begin', 'fuyou');
        Log::write($param, 'fuyou');
        Log::write('end', 'fuyou');

        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            Log::write('充值验证失败', 'fuyou');
        }

        if($param['resp_code'] != '0000') {
            exit;
        }

        $finance = new Finance();
        $finance->userRecharge_notify($msg, $param['mchnt_txn_ssn'], $param['amt'], $param['login_id']);
        Log::write($msg, 'fuyou');
    }



    //提现接口通知，2分钟之后调用
    public function drawcash() {
        //Log::info(request());
    }

    //提现实时同步
    public function drawcashPage() {
        /*$this->assign('bankNumber', '123123123123123123123123');
        $this->assign('bankName', '中国旗舰银行');
        $this->assign('money', '200.00');
        $this->assign('reachTime', '2012年2月12 下周二');
        $this->assign('icon', 1);
        $this->assign('from', isset($param['from']) ? $param['from'] : '');
        return $this->fetch();*/


        $param = Request::instance()->param();
        $this->assign('from', isset($param['from']) ? $param['from'] : '');
        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            Log::write('提现验证失败', 'fuyou');
            $this->assign('icon', 2);
            return $this->fetch();
        }

        //更新userDrawcash可能和异步重复，但是不影响业务
        //$finance = new Finance();
        //$finance->userDrawcash_notify($msg, $param['mchnt_txn_ssn'], $param['amt'], $param['login_id']);
        //Log::write($msg, 'fuyou');

        if($param['resp_code'] != '0000') {
            $this->assign('icon', 2);
            return $this->fetch();
        }

        $userDrawcash = UserDrawcash::get([
            'outerNumber'=>$param['mchnt_txn_ssn']
        ]);

        if(!$userDrawcash) {
            $this->assign('icon', 2);
            return $this->fetch();
        }

        $weekIndex= date("w",strtotime(Common::timetodate(THINK_START_TIME, 0)));
        $reachTime = '';
        if($weekIndex == 5) {
            $reachTime = '预计下周一'.Common::timetodate(THINK_START_TIME, 7).'前到账';
        }
        else {
            $reachTime = '预计明日'.Common::timetodate(THINK_START_TIME, 7).'前到账';
        }

        $bank = Bank::get($userDrawcash['bankID']);
        $this->assign('bankNumber', substr($userDrawcash['bankNumber'], (strlen($userDrawcash['bankNumber'])-4)));
        $this->assign('bankName', $bank['bankName']);
        $this->assign('money', $userDrawcash['money']);
        $this->assign('reachTime', $reachTime);
        $this->assign('icon', 1);
        return $this->fetch();
    }

    //提现实时异步
    public function drawcashNotify() {
        $param = Request::instance()->param();
        Log::write('notify begin', 'fuyou');
        Log::write($param, 'fuyou');
        Log::write('end', 'fuyou');

        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            Log::write('提现验证失败', 'fuyou');
        }

        $finance = new Finance();
        $finance->userDrawcash_notify($msg, $param['mchnt_txn_ssn'], $param['amt'], $param['login_id']);
        Log::write($msg, 'fuyou');
    }



    //交易接口通知，2分钟之后调用
    public function trade() {
        /*Log::info(request());



        $param = Request::instance()->param();
        Log::write('notify begin', 'fuyou');
        Log::write($param, 'fuyou');


        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['amt'].'|'.$param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'], $param['signature']);
        if(!$check) {
            Log::write('验证失败', 'fuyou');
        }

        $finance = new Finance();
        //传入第三方订单号
        $result = $finance->userRecharge_notify($msg, $param['mchnt_txn_ssn']);
        Log::write($msg, 'fuyou');



        if(!$result) {
            $str = '<plain>'.
                '<resp_code>'.'1111'.'</resp_code>'.
                '<mchnt_cd>'.$gold->getMchID().'</mchnt_cd>'.
                '<mchnt_txn_ssn>'.$param['mchnt_txn_ssn'].'</mchnt_txn_ssn>'.
                '</plain>';
            Log::write($str, 'fuyou');
            $sign = $gold->rsaSign($str);

            Log::write('end', 'fuyou');
            return  '<ap>'.
                '<plain>'.
                $str.
                '<signature>'.$sign.'</signature>'.
                '</ap>';
        }

        $str = '<plain>'.
            '<resp_code>'.'0000'.'</resp_code>'.
            '<mchnt_cd>'.$gold->getMchID().'</mchnt_cd>'.
            '<mchnt_txn_ssn>'.$param['mchnt_txn_ssn'].'</mchnt_txn_ssn>'.
            '</plain>';
        Log::write($str, 'fuyou');
        $sign = $gold->rsaSign($str);

        Log::write('end', 'fuyou');
        return  '<ap>'.
            '<plain>'.
            $str.
            '<signature>'.$sign.'</signature>'.
            '</ap>';*/
    }

    //退票接口通知，2分钟之后调用
    public function refundTicket() {
        Log::info(request());
    }

    //修改用户信息接口通知，2分钟之后调用
    public function updateUser() {
        Log::info(request());
    }




    //注销用户实时同步
    public function cancelUser() {
        $param = Request::instance()->param();
        $this->assign('from', isset($param['from']) ? $param['from'] : '');
        Log::write('canceluser notify begin', 'fuyou');
        Log::write($param, 'fuyou');
        Log::write('end', 'fuyou');

        vendor('fuyou.Gold');
        $gold = new \Gold();

        $check = $gold->rsaVerify($param['login_id'].'|'.$param['mchnt_cd'].'|'.$param['mchnt_txn_ssn'].'|'.$param['resp_code'].'|'.$param['resp_desc'], $param['signature']);
        if(!$check) {
            $this->assign('icon', 2);
            return $this->fetch();
        }

        if($param['resp_code'] != '0000') {
            $this->assign('msg', $param['resp_desc']);
            $this->assign('icon', 2);
            return $this->fetch();
        }

        $userCancelLog = UserCancelLog::get([
            'outerNumber'=>$param['mchnt_txn_ssn']
        ]);

        if(!$userCancelLog) {
            $this->assign('icon', 2);
            return $this->fetch();
        }

        $userCancelLog['status'] = UserCancelLog::STATUS_SUCCESS;
        $userCancelLog['resultTime'] = THINK_START_TIME;
        $userCancelLog->save();

        User::update([
            'status'=>User::STATUS_UNBANDING,
            'token'=>'',
            'tokenOverTime'=>0
        ], [
            'userID'=>$userCancelLog['userID']
        ]);

        $this->assign('icon', 1);
        return $this->fetch();
    }
}
