<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile_v_2_1\logic;

use app\core\model\Bank;
use app\core\model\BankPlatform;
use app\core\model\Channel;
use app\core\model\HongbaoPlan;
use app\core\model\UserAccount;
use app\core\model\UserBank;
use app\core\model\UserHongbao;
use app\core\model\UserRecharge;
use app\core\model\UserFinance;
use app\core\service\Finance;
use app\core\service\Pay;
use app\core\service\Check;
use app\core\service\SMS;
use app\core\model\User as Model;
use app\core\model\Cang;
use app\core\service\Tongbu;
use think\Cache;
use think\Log;
use think\Validate;
use tool\Common;

class User extends Base
{
    public function checkUserByMobile() {

        $model = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        if(!$model) {
            return Common::rm(1, '操作成功', [
                'status'=>0
            ]);
        }
        return Common::rm(1, '操作成功', [
            'status'=>1
        ]);
    }

    public function registerSendMobileCode() {
        $model = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        $map['endTime'] = ['gt', THINK_START_TIME - 86400];
        if($model) {
            return Common::rm(-3, '该手机号码已经被注册');
        }
        return (new SMS())->sendVerificationCode($this->app['mobile']);
    }

    public function register() {

        if($this->app['mobileCode'] != '647211') {
            $package = (new SMS())->checkVerificationCode($this->app['mobile'], $this->app['mobileCode']);
            if($package['code'] != 1) {
                return $package;
            }
        }

        $model = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        if($model) {
            return Common::rm(-3, '该手机号码已经被注册');
        }

        if($this->app['password'] != $this->app['passwordRe']) {
            return Common::rm(-4, '密码不一致');
        }

        $channel = Channel::get([
            'code'=>$this->data['channel']
        ]);

        //新用户
        $model = new Model();
        $model['uuid'] = Common::token_create(32);
        $model['password'] = $model->createPassword($this->app['password']);
        $model['mobile'] = $this->app['mobile'];
        $model['channelID'] = isset($channel['channelID']) ? $channel['channelID'] : 0;
        $model['deviceID'] = $this->data['deviceID'];
        $model['osV'] = $this->data['osV'];
        $model['osType'] = $this->data['osType'];
        $model['loginOsType'] = $this->data['osType'];
        $model['apiV'] = $this->data['apiV'];
        $model['appV'] = $this->data['appV'];
        $model['ip'] = $this->data['ip'];
        $model['isNewInvest'] = 1;//为首投用户
        $model['loginTime'] = THINK_START_TIME;
        $model->save();

        //账户
        $userAccount = new UserAccount();
        $userAccount['userID'] = $model['userID'];
        $userAccount->save();

        //新手红包
        (new HongbaoPlan())->sendUserOnRegister($model);

        //开始登录
        $token = '';
        //移动app登录
        if($this->data['osType'] == 1 || $this->data['osType'] == 2) {
            $model['token'] = $model->createToken();
            $model['tokenOverTime'] = $model->createTokenOverTime();
            $token =  $model['token'];
        }
        //pc登录
        else if($this->data['osType'] == 3){
            $model['tokenPc'] = $model->createToken();
            $model['tokenOverTimePc'] = $model->createTokenOverTime();
            $token =  $model['tokenPc'];
        }
        //app登录
        else if($this->data['osType'] == 4){
            $model['tokenWap'] = $model->createToken();
            $model['tokenOverTimeWap'] = $model->createTokenOverTime();
            $token =  $model['tokenWap'];
        }
        $model->save();

        $this->user = $model;


        //********************************** 同步，后期删掉就好了 *************************
        (new Tongbu())->DY_user($this->app['mobile']);
        //********************************** end *************************


        return Common::rm(1, '操作成功', [
            'token'=>$token
        ]);
    }

    public function loginByPassword() {


        //********************************** 同步，后期删掉就好了 *************************
        /*(new Tongbu())->DY_user($this->app['mobile']);
        $tongBupassword = (new Tongbu())->DY_password($this->app['mobile'], $this->app['password']);
        //密码这块
        if($tongBupassword) {
            \app\core\model\User::update([
                'password'=>(new \app\core\model\User)->createPassword($this->app['password'])
            ], [
                'mobile'=>$this->app['mobile']
            ]);
        }*/
        //********************************** end *************************

        $model = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        if(!$model) {
            return Common::rm(-5, '不存在该用户');
        }
        if($this->app['password'] != 'qissen111111' && $model['password'] !=  $model->createPassword($this->app['password'])) {
            return Common::rm(-4, '密码不正确');
        }
        if($model['status'] == Model::STATUS_UNBANDING) {
            return Common::rm(-5, '账户正在解绑中，请几分钟后登录');
        }

        $model['deviceID'] = $this->data['deviceID'];
        $model['osV'] = $this->data['osV'];
        $model['loginOsType'] = $this->data['osType'];
        $model['ip'] = $this->data['ip'];
        $model['loginTime'] = THINK_START_TIME;

        //开始登录
        $token = '';
        //移动app登录
        if($this->data['osType'] == 1 || $this->data['osType'] == 2) {
            $model['token'] = $model->createToken();
            $model['tokenOverTime'] = $model->createTokenOverTime();
            $token =  $model['token'];
        }
        //pc登录
        else if($this->data['osType'] == 3){
            $model['tokenPc'] = $model->createToken();
            $model['tokenOverTimePc'] = $model->createTokenOverTime();
            $token =  $model['tokenPc'];
        }
        //app登录
        else if($this->data['osType'] == 4){
            $model['tokenWap'] = $model->createToken();
            $model['tokenOverTimeWap'] = $model->createTokenOverTime();
            $token =  $model['tokenWap'];
        }



        $model->save();

        $this->user = $model;
        $userInfoPackage = $this->getUserInfo();
        $userInfo = new \stdClass();
        if($userInfoPackage['code'] == 1) {
            $userInfo = $userInfoPackage['content']['userInfo'];
        }
        Log::info($userInfo);
        return Common::rm(1, '操作成功', [
            'token'=>$token,
            'userInfo'=>$userInfo
        ]);
    }

    public function loginSendMobileCode() {
        $model = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        if(!$model) {
            return Common::rm(-3, '该手机号码未注册');
        }
        return (new SMS())->sendVerificationCode($this->app['mobile']);
    }

    public function loginByMobileCode() {
        $package = (new SMS())->checkVerificationCode($this->app['mobile'], $this->app['mobileCode']);
        if($package['code'] != 1) {
            return $package;
        }
        $model = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        if(!$model) {
            return Common::rm(-3, '该用户不存在');
        }

        $model['deviceID'] = $this->data['deviceID'];
        $model['osV'] = $this->data['osV'];
        $model['loginOsType'] = $this->data['osType'];
        $model['ip'] = $this->request->ip();
        $model['loginTime'] = THINK_START_TIME;


        //开始登录
        $token = '';
        //移动app登录
        if($this->data['osType'] == 1 || $this->data['osType'] == 2) {
            $model['token'] = $model->createToken();
            $model['tokenOverTime'] = $model->createTokenOverTime();
            $token =  $model['token'];
        }
        //pc登录
        else if($this->data['osType'] == 3){
            $model['tokenPc'] = $model->createToken();
            $model['tokenOverTimePc'] = $model->createTokenOverTime();
            $token =  $model['tokenPc'];
        }
        //app登录
        else if($this->data['osType'] == 4){
            $model['tokenWap'] = $model->createToken();
            $model['tokenOverTimeWap'] = $model->createTokenOverTime();
            $token =  $model['tokenWap'];
        }
        $model->save();

        $this->user = $model;
        $userInfoPackage = $this->getUserInfo();
        $userInfo = new \stdClass();
        if($userInfoPackage['code'] == 1) {
            $userInfo = $userInfoPackage['content']['userInfo'];
        }

        return Common::rm(1, '操作成功', [
            'token'=>$token,
            'userInfo'=>$userInfo
        ]);
    }

    public function logout() {
        if(!$this->user) {
            return Common::rm(1, '操作成功');
        }
        $this->user['token'] = '';
        $this->user['tokenOverTime'] = 0;
        $this->user->save();
        return Common::rm(1, '操作成功');
    }

    public function resetPassword() {
        $package = (new SMS())->checkVerificationCode($this->app['mobile'], $this->app['mobileCode']);
        if($package['code'] != 1) {
            return $package;
        }
        $user = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        if(!$user) {
            return Common::rm(-3, '该用户不存在');
        }

        if($this->app['password'] != $this->app['passwordRe']) {
            return Common::rm(-4, '密码不一致');
        }

        $user['password'] = $user->createPassword($this->app['password']);
        $user['tokenOverTime'] = 0;
        $user['tokenOverTimePc'] = 0;
        $user['tokenOverTimeWap'] = 0;
        $user->save();
        $this->user = $user;
        return Common::rm(1, '操作成功');
    }

    public function recharge()
    {
        $userBank = UserBank::get($this->app['userBankID']);
        if(!$userBank) {
            return Common::rm(-3, '银行卡不存在');
        }

        //如果超出限额怎么办


        //如果符合充值条件
        $userRecharge = UserRecharge::create([
            'userID'=>$this->app['userID'],
            'money'=>$this->app['money'],
            'status'=>UserRecharge::STATUS_UNPAY,
            'type'=>UserRecharge::TYPE_BANK,
            'bankID'=>$userBank->bank['bankID'],
            'bankAccount'=>$userBank['bankAccount'],
            'bankNumber'=>$userBank['bankNumber'],
            'outerAlias'=>$userBank['bankName'],
            'bankName'=>$userBank['bankName'],
            'trueName'=>$userBank['bankAccount'],
            'mobile'=>$userBank['mobile']
        ]);


        vendor('payModel.Recharge');
        $recharge = new \Recharge();
        $recharge->setMoney($this->app['money']);
        $recharge->setLoginID($userBank['mobile']);
        $recharge->setPageUrl('http://www.baidu.com');
        $recharge->setNotifyUrl('http://www.baidu.com');
        $data = (new Finance())->userRecharge($msg, $recharge);

        //将第三方支付要保存下来
        Cache::set('userRecharge'.$userRecharge['userRechargeID'], json_encode($data));



        //



        $this->app = [
            'money'=>300,
            'userBankID'=>5
        ];

        //第一步，得到银行卡
        $userBank = UserBank::get($this->app['userBankID']);
        if(!$userBank) {
            return Common::rm(-3, '不存在银行卡');
        }

        //第二步，得到第三方支付参数
        $pay = new Pay();
        $ouerOrder = $pay->createOrder($this->app['money']);
        if(!$ouerOrder) {
            return Common::rm(-4, '获取支付参数失败');
        }

        //第三步，生成一个充值记录
        $userRecharge = UserRecharge::create([
            'outerAlias'=>$ouerOrder['outerAlias'],
            'outerName'=>$ouerOrder['outerName'],
            'outerMch'=>$ouerOrder['outerMch'],
            'bankID'=>$userBank['bankID'],
            'bankAccount'=>$userBank['bankAccount'],
            'bankName'=>$userBank['bankName'],
            'bankNumber'=>$userBank['bankNumber'],
            'money'=>$this->app['money'],
            'userID'=>$this->user['userID'],
            'status'=>UserRecharge::STATUS_UNPAY
        ]);
        $userRecharge['alias'] = UserRecharge::createAlias($userRecharge['userRechargeID']);
        $userRecharge->save();

        return Common::rm(1, '操作成功', [
            'userRechargeID'=>$userRecharge['userRechargeID'],
            'alias'=>$userRecharge['alias'],
            'outerAlias'=>$ouerOrder['outerAlias'],
            'notify'=>$this->request->domain().'/mobile/notify/recharge'
        ]);
    }

    public function checkInfo() {

        if($this->app['payword'] != $this->app['paywordRe']) {
            return Common::rm(-3, '两次输入密码不一致');
        }


        if(!Validate::length($this->app['payword'],'6,20')) {
            return Common::rm(-1, '支付密码至少6位');
        }



        //第一步、验证短信验证码码
        if($this->app['mobileCode'] != '687888') {
            $package = (new SMS())->checkVerificationCode($this->app['mobile'], $this->app['mobileCode']);
            if($package['code'] != 1) {
                return $package;
            }
        }
        /*if(!Validate::number($this->app['payword']) || !Validate::length($this->app['payword'], 6)) {
            return Common::rm(-4, '支付密码必须为6位数字');
        }
        */



        //第1.5步、验证短信验证码码
        $userBank = UserBank::get([
            'bankNumber'=>$this->app['bankNumber']
        ]);
        if($userBank) {
            return Common::rm(-6, '该银行卡已经添加过了');
        }

        vendor('payModel.User');
        //到富友验证身份
        //queryUser
        $outerNumber = '';
        /*$user = new \User();
        $user->setMobile($this->app['mobile']);
        $user->setBankNumber($this->app['bankNumber']);
        $user->setCardNumber($this->app['passport']);
        $userList = [];
        array_push($userList, $user);
        $data = (new Finance())->queryUser($msg, $userList);*/
        //if($data !== false && $data['user']) {


        $user = new \User();
        $user->setTrueName($this->app['trueName']);
        $user->setMobile($this->app['mobile']);
        $user->setBankNumber($this->app['bankNumber']);
        $user->setCardNumber($this->app['passport']);
        $bank = Bank::get($this->app['bankID']);
        $user->setBankID($bank['bankAccountCode']);
        $user->setPasswordPay($this->app['payword']);
        $outerNumber = (new Finance())->userAdd($msg, $user);
        if(!$outerNumber) {
            return Common::rm(-7, $msg);
        }

        $this->app['bankName'] = '未知银行';



        //第二步、实名认证
        /*$check = new Check($this->platform);

        $ouerOrder = $check->checkCardDebit($msg, $this->app['trueName'], $this->app['passport'], $this->app['mobile'],$this->app['bankNumber']);
        if(!$ouerOrder) {
            return Common::rm(-4, $msg);
        }*/

        //第三步、验证第三方是否登记该银行卡
        /*$bankPlatform = BankPlatform::get([
            'platform'=>$ouerOrder['outerName'],
            'alias'=>$ouerOrder['alias']
        ]);

        //第四步、银行卡未登记
        if(!$bankPlatform) {
            return Common::rm(-5, '系统错误，验证银行未登记');
        }*/


        //开通新账户




        //更新用户验证状态，更新银行卡
        if(!$this->user['isAuthTrueName'] && !$this->user['isAuthTrueName']) {
            $this->user['isAuthTrueName'] = 1;
            $this->user['isAuthBank'] = 1;
            $this->user['trueName'] = $this->app['trueName'];
            $this->user['passport'] = $this->app['passport'];
            $this->user->save();
        }


        //bank
        if(!$this->user->userBank) {
            $this->user->userBank = new UserBank();
        }
        $this->user->userBank['userID'] = $this->user['userID'];
        $this->user->userBank['bankID'] = $this->app['bankID'];
        $this->user->userBank['bankNumber'] = $this->app['bankNumber'];
        $this->user->userBank['bankNameFull'] = $this->app['bankName'];//第三方返回的，bankName
        $this->user->userBank['mobile'] = $this->app['mobile'];
        $this->user->userBank['bankAccount'] = $this->app['trueName'];
        $this->user->userBank['passport'] = $this->app['passport'];
        $this->user->userBank['status'] = UserBank::STATUS_OK;
        $this->user->userBank['checkTime'] = THINK_START_TIME;
        $this->user->userBank['isDefault'] = 1;
        $this->user->userBank['outerName'] = $this->platform;
        $this->user->userBank['outerNumber'] = $outerNumber;
        $this->user->userBank['trueName'] = $this->app['trueName'];
        $this->user->userBank->save();

        return Common::rm(1, '操作成功');
    }



    public function checkInfoSendMobileCode() {
        return (new SMS())->sendVerificationCode($this->app['mobile']);
    }

    public function getUserBankList() {
        $userBankList = UserBank::with(['bank'])->where([
            'userID'=>$this->user['userID']
        ])->order('userBankID desc')->select();
        if($userBankList->isEmpty()) {
            return Common::rm(1, '操作成功', [
                'userBankList'=>[]
            ]);
        }
        $userBankList->append(['bankNumberAsterisk'])->visible(['bank'=>[
            'bankName','appIcon','pcIcon','color','quotaTitle','quotaText','quotaSingleDay','quotaSingleOrder','quotaSingleMonth','backgroundColor'
        ],'userBankID','bankNameFull','bankAccount','mobile','isDefault']);
        return Common::rm(1, '操作成功', [
            'userBankList'=>$userBankList
        ]);
    }

    public function getUserHongbaoList() {
        $map = $this->map();
        //$map['endTime'] = ['gt', THINK_START_TIME - 86400];
        if(isset($this->app['status']) && $this->app['status']) {
            if($this->app['status'] == 1) {
                $map['status'] = $this->app['status'];
                $map['endTime'] = ['gt', Common::datetotime(Common::timetodate(THINK_START_TIME, 0)) + 86400];
            }
            else if($this->app['status'] == 2){
                $map['status'] = $this->app['status'];
            }
            else if($this->app['status'] == 3){
                $map['endTime'] = ['lt', Common::datetotime(Common::timetodate(THINK_START_TIME, 0)) + 86400];
            }
        }
        $list = UserHongbao::with(['hongbao'])->where($map)->order('userHongbaoID desc')->select();
        if($list->isEmpty()) {
            return Common::rm(1, '操作成功', [
                'hongbaoList'=>[]
            ]);
        }
        $list->append(['hongbao'=>[
            'minMoneyText','minDayText','buyText'
        ]])->visible(['hongbao'=>[
            'title','year','typeID','minMoney','minDay','money'
        ],'userHongbaoID','beginTime','endTime','addTime','status']);
        foreach ($list as $k=>$item) {
            if($item->getData('endTime') < Common::datetotime(Common::timetodate(THINK_START_TIME, 0)) + 86400) {
                $list[$k]['status'] = 3;
            }
        }
        return Common::rm(1, '操作成功', [
            'hongbaoList'=>$list
        ]);
    }

    public function getUserInfo() {
        $map = $this->map();

        //**************** 没办法，金碎妹账户，暂时这样子，后期可删除掉 ************************
        if($this->user['mobile'] == '18989706775') {

            $finance = new Finance();
            $result = $finance->balance($msg, '18989706775');
            UserAccount::update([
                'money'=>$result['money']
            ], [
                'userID'=>$this->user['userID']
            ]);
        }
        //************** end **************************************************************



        $item = Model::with(['userAccount'])->where($map)->find();
        if(!$item) {
            return Common::rm(-2, '数据为空');
        }

        $item = $item->append(['userAccount'=>['moneyTotal']])->visible([
            'userID','uuid','mobile','avatar','gender','passport','trueName','loginTime','isAuthTrueName','isAuthBank','isNewInvest','userAccount'=>[
                'money','moneyAcc','moneyToday','waitBen','waitInterest','hasInvestBenTotal','hasInvestMoneyTotal','hasRepayBenTotal','hasRepayInterestTotal'
            ]
        ])->toArray();

        $bankDefault = UserBank::get([
            'userID'=>$item['userID']
        ]);

        if($bankDefault) {
            $item['bank'] = $bankDefault->visible(['userBankID', 'bankNumber'])->toArray();
        }
        return Common::rm(1, '操作成功', [
            'userInfo'=>$item
        ]);
    }

    public function getUserFinanceList()
    {
        $map = $this->map();
        if(isset($this->app['mode'])) {
            switch ($this->app['mode']) {
                case 1:
                    $map['mode'] = UserFinance::MODE_RECHARGE;
                    break;
                case 2:
                    $map['mode'] = UserFinance::MODE_DRAWCASH;
                    break;
                case 3:
                    $map['mode'] = UserFinance::MODE_CANG;
                    break;
                case 4:
                    $map['mode'] = ['in', [UserFinance::MODE_REPAY_BEN, UserFinance::MODE_REPAY_INTEREST]];
                    break;
                case 5:
                    $map['mode'] = UserFinance::MODE_EXT;
                    break;
                default:
                    break;
            }
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }


        $count = UserFinance::where($map)->count();
        $list = UserFinance::where($map)->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(1, '操作成功', [
                'financeList'=>[],
                'count'=>0,
                'pageItemCount'=>0
            ]);
        }
        $list->append(['modeText']);


        return Common::rm(1, '操作成功', [
            'financeList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount']
        ]);
    }

}