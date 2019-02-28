<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\service;

use app\core\model\Cang;
use app\core\model\CangRepay;
use app\core\model\Subject;
use app\core\model\SubjectStat;
use app\core\model\UserAccount;
use app\core\model\UserDrawcash;
use app\core\model\UserHongbao;
use app\core\model\UserRecharge;
use app\core\model\User as UserModel;
use think\Config;
use think\Db;
use think\Exception;
use think\Log;
use tool\Common;

class Finance
{
    private $platform;

    public function __construct()
    {
        vendor('fuyou.Gold');
        $this->platform = new \Gold();
    }

    //添加新用户
    public function userAdd(&$msg, \User $user) {
        vendor('payModel.User');
        return $this->platform->add($msg, $user);
    }

    //充值
    public function userRecharge(&$msg, \Recharge $recharge) {
        vendor('payModel.Recharge');
        return $this->platform->recharge($msg, $recharge);
    }

    //充值回调
    public function userRecharge_notify(&$msg, $outerNumber, $money, $mobile) {
        $userRecharge = UserRecharge::get([
            'outerNumber'=>$outerNumber,
            'status'=>UserRecharge::STATUS_UNPAY
        ]);
        if(!$userRecharge) {
            $msg = '该记录已失效';
            return false;
        }

        if($userRecharge->getData('money') != $money) {
            $msg = '金额不对';
            return false;
        }

        $user = UserModel::get($userRecharge['userID']);
        if(!$user) {
            $msg = '该充值用户已经丢失';
            return false;
        }

        $userAccount = new UserAccount();
        //到账时间有点问题
        $userAccount->MODE_recharge_addMoney($userRecharge, $user, THINK_START_TIME);
        $msg = '充值成功';
        return true;
    }

    //提现
    public function userDrawcash(&$msg, \Drawcash $drawcash) {
        vendor('payModel.Drawcash');
        return $this->platform->drawcash($msg, $drawcash);
    }

    //提现回调
    public function userDrawcash_notify(&$msg, $outerNumber, $money, $mobile) {
        $userDrawcash = UserDrawcash::get([
            'outerNumber'=>$outerNumber,
            'status'=>UserDrawcash::STATUS_SUBMIT
        ]);
        if(!$userDrawcash) {
            $msg = '不存在该提现记录';
            return false;
        }

        if($userDrawcash->getData('money') != $money) {
            $msg = '金额不对';
            return false;
        }

        $user = UserModel::get($userDrawcash['userID']);
        if(!$user) {
            $msg = '该提现用户已经丢失';
            return false;
        }

        $userAccount = new UserAccount();
        //到账时间有点问题
        $userAccount->MODE_drawcash_decMoney_submit($msg, $userDrawcash, $user, THINK_START_TIME);
        $msg = '操作成功';
        return true;
    }

    //注销用户
    public function userCancel(&$msg, $mobile, $pageUrl) {
        return $this->platform->userCancel($msg, $mobile, $pageUrl);
    }

    //下单
    public function order(&$msg, \Trade $trade) {
        vendor('payModel.Trade');
        return $this->platform->order($msg, $trade);
    }

    public function order_notify(&$msg, $outerNumber) {

        //第一步，得到cang
        $cang = Cang::get([
            'outerNumber'=>$outerNumber,
            'status'=>UserRecharge::STATUS_UNPAY
        ]);
        if($cang) {
            $msg = '不存在订单';
            return true;
        }

        //第二步，得到红包相关
        $hongbaoMoney = 0;//现金券
        $hongbaoYear = 0;//加息年化
        $hongbaoIDS = explode(',', $cang['hongbao']);

        foreach ($hongbaoIDS as $k => $hongbaoID) {
            $userHongbao = UserHongbao::with(['hongbao'])->where([
                'userHongbaoID'=>$hongbaoID,
                'status'=>UserHongbao::STATUS_UNUSED,
                'userID'=>$cang['userID']
            ])->find();
            if(!$userHongbao) {
                continue;
            }
            if($userHongbao->hongbao['typeID'] == 1) {
                $hongbaoMoney += $userHongbao->hongbao['money'];
            }
            else if($userHongbao->hongbao['typeID'] == 2) {
                $hongbaoYear += $userHongbao->hongbao['year'];
            }
        }

        Db::startTrans();
        try{

            //第三步，更新支付状态
            $cang['payTime'] = THINK_START_TIME;
            $cang['status'] = Cang::STATUS_PAY;
            $cang->save();


            //第四步，计算总年化，更新支付状态
            $year = $cang-['year'] + $cang-['yearExt'] + $cang['yearSystem'];//总的年化
            $interest = 0;
            if($cang['interestTimeTypeID'] == 2 || $cang['interestTimeTypeID'] == 3) {
                $interest = round($cang->subject['moneySubject'] * $year / 100 / 365 * $cang->subject['investDay'], 2);//预付利息
            }


            //第五步，更新回款清单
            //更新本金回款清单
            $cangRepaySave = [
                'money'=>$cang['ben'],
                'repayTime'=>Common::datetotime($cang->subject['repayTime']),
                'reachTime'=>Common::datetotime($cang->subject['reachTime']),
                'userID' => $cang['userID'],
                'subjectID' => $cang['subjectID'],
                'cangID'=>$cang['cangID'],
                'status'=>CangRepay::STATUS_UNREPAY,
                'repayTypeID'=>1
            ];

            CangRepay::create($cangRepaySave);
            //更新利息回款清单
            $cangRepaySave['repayTypeID'] = 2;
            $cangRepaySave['money'] = $interest;
            CangRepay::create($cangRepaySave);


            //第六步，更新账户金额及流水
            $user = User::get($cang['userID']);
            $user->userAccount = $user->userAccount->MODE_cang_decMoney($cang, $user);

            //第十一步，更新标的统计
            SubjectStat::where([
                'subjectID'=>$cang['subjectID']
            ])->setInc('moneyTotalInvest', $cang['moneySubject'] * 100);
            SubjectStat::where([
                'subjectID'=>$this->app['subjectID']
            ])->setInc('timesInvest');

        }
        catch (Exception $e) {
            // 回滚事务
            Log::error($e->getMessage().'|'.$e->getLine().'|'.$e->getFile());
            Db::rollback();
            throw new AppException(-9, $e->getMessage());
        }


        //第十二步，判断是否满标
        if($cang->subject->subjectStat['moneyTotalInvest'] == $cang->subject['price']) {
            //如果满标了，设置满标
            Subject::setSubjectFull($cang->subject);
        }
        $msg = '操作成功';
        return true;
    }


    //放款
    public function fang(&$msg, \Trade $trade) {
        vendor('payModel.Trade');
        return $this->platform->order($msg, $trade);
    }

    public function fang_notify(&$msg, $outerNumber) {}


    //还款
    public function userRepay(&$msg, \Trade $trade) {
        vendor('payModel.Trade');
        return $this->platform->repay($msg, $trade);
    }

    //查询用户
    public function queryUser(&$msg, $userList) {
        return $this->platform->queryUser($msg, $userList);
    }


    //查询余额
    public function balance(&$msg, $mobile) {
        return $this->platform->balance($msg, $mobile);
    }
}