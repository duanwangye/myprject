<?php

/**
 * Created by PhpStorm.
 * 服务提供者
 * User: qissen
 * Date: 2017/6/7
 * Time: 7:36
 * 注意调用顺序，checkClientType，checkData必须先调用，才可以验证其他
 */

namespace app\core\service;
use app\core\model\Bank;
use app\core\model\Cang;
use app\core\model\CangRepay;
use app\core\model\Channel;
use app\core\model\HongbaoPlan;
use app\core\model\Subject;
use app\core\model\User;
use app\core\model\UserAccount;
use app\core\model\UserBank;
use app\core\model\UserDrawcash;
use app\core\model\UserFinance;
use app\core\model\UserRecharge;
use think\Db;
use think\Log;
use tool\Common;

class Tongbu
{
    private $db_config_from = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => 'rm-bp1m6abztwo27gum6.mysql.rds.aliyuncs.com',
        // 数据库名
        'database'    => 'goldapi',
        // 数据库用户名
        'username'    => 'sqlyog',
        // 数据库密码
        'password'    => 'Qissen111111',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 's_',
    ];


    public function DY_user($mobile) {
        $isTongbu = false;
        $from_list = Db::connect($this->db_config_from)->query('select * from s_user where card_no_auth=1  and username=\''.$mobile.'\'');
        foreach ($from_list as $k=>$item) {
            if($item['tongbu'] == 1) {
                return false;
            }
            $isTongbu = true;
            /*if($password) {
                if($item['password'] != md5($password)) {
                    return -2;
                }
            }*/
            Log::info('执行了一次同步');
            $hasTongbu = true;
            //用户
            $user = User::get([
                'mobile'=>$item['username']
            ]);
            if(!$user) {
                $user = new User();
            }
            $user['BACKUPID'] = $item['id'];//BACKUPID
            $user['password'] = (new User())->createPassword(THINK_START_TIME);//重新设置新密码
            $user['mobile'] = $item['username'];//手机号
            $user['trueName'] = $item['real_name'];//真实姓名
            $user['isAuthTrueName'] = $item['card_no_auth'];//实名认证
            $user['isAuthBank'] = $item['card_no_auth'];//实名认证
            $user['passport'] = $item['card_no'];//身份证
            $user['email'] = $item['email'];//银行卡认证
            $user['osType'] = $item['device_type'];//银行卡认证
            $user['isNewInvest'] = 0;
            if($item['device_type'] == 4) {
                $user['osType'] = 3;
            }
            if($item['device_type'] == 3) {
                $user['osType'] = 4;
            }
            if($item['level'] == 1) {
                $user['isNewInvest'] = 1;
            }
            $channel = Channel::get([
                'code'=>$item['channel']
            ]);
            if($channel) {
                $user['channelID'] = $channel['channelID'];//渠道名称
                if($item['channel_web_id'] == 40) {
                    $user['channelID'] = 59;
                }
            }
            else {
                $item['channelID'] = 0;
            }
            if($item['add_time']) {
                $user['addTime'] = Common::datetotime($item['add_time']);//添加时间
            }
            else {
                $user['addTime'] = 0;//添加时间
            }
            $user->save();
            (new HongbaoPlan())->sendUserOnRegister($user);

            //账户
            $from_account = Db::connect($this->db_config_from)->query('select * from s_user_account where user_id='.$item['id']);

            $account = UserAccount::get([
                'userID'=>$user['userID']
            ]);
            if(!$account) {
                $account = new UserAccount();
            }


            if(isset($from_account[0])) {
                $account['BACKUPID'] = $from_account[0]['id'];
                $account['userID'] = $user['userID'];
                $account['money'] = $from_account[0]['account_able'];
                $account['moneyAcc'] = $from_account[0]['total_invest_interest'];
                $account['moneyFrozen'] = $from_account[0]['account_freeze'];
                $account['waitBen'] = $from_account[0]['wait_capital'];
                $account['waitInterest'] = $from_account[0]['wait_interest'];


                $account['hasInvestBenTotal'] = 0;
                $account['hasInvestMoneyTotal'] = 0;
                $account['hasRepayBenTotal'] = $from_account[0]['total_invest_capital'];
                $account['hasRepayInterestTotal'] = $from_account[0]['total_invest_interest'];
                $account->save();
            }
            else {
                $account['userID'] = $from_account[0]['user_id'];
                $account->save();
            }


            //新增银行卡
            $from_bank = Db::connect($this->db_config_from)->query('select * from s_user_bank where user_id='.$item['id']);
            foreach ($from_bank as $_k=>$_item) {
                $bank = Bank::where([
                    'bankAccountCode'=>$_item['bank_code']
                ])->find();

                //用户银行卡
                $userBank = UserBank::get([
                    'BACKUPID'=>$_item['id']
                ]);
                if(!$userBank) {
                    $userBank = new UserBank();
                }
                $userBank['BACKUPID'] = $_item['id'];//BACKUPID
                $userBank['userID'] = $user['userID'];//手机号
                $userBank['bankNameFull'] = $_item['bank_name'];//
                $userBank['bankID'] = isset($bank['bankID']) ? $bank['bankID'] : 1;//实名认证
                $userBank['bankAccount'] = $_item['acct_name'];
                $userBank['bankNumber'] = $_item['bank_card_no'];
                $userBank['trueName'] = $_item['acct_name'];
                $userBank['mobile'] = $_item['mobile'];
                $userBank['addTime'] = Common::datetotime($_item['add_time']);
                $userBank['status'] = 1;
                $userBank['isDefault'] = 1;
                $userBank->save();
            }

            //新增交易
            $from_cangList = Db::connect($this->db_config_from)->query('select * from s_user_due_detail where user_id='.$item['id']);
            foreach ($from_cangList as $_k=>$_item) {

                $subject = Subject::get([
                    'BACKUPID'=>$_item['project_id']
                ]);

                $user = User::get([
                    'BACKUPID'=>$_item['user_id']
                ]);
                $status = 0;
                if($_item['status'] == 1) {
                    $status = Cang::STATUS_INTEREST;
                }
                else if($_item['status'] == 2) {
                    $status = Cang::STATUS_FINISH;
                }
                else if($_item['type'] == 3) {
                    $status = Cang::STATUS_REPAY;
                }

                //$from_recharge = Db::connect($this->db_config_from)->query('select * from s_recharge_log where recharge_no=\''.$_item['recharge_no'].'\''.' and user_id='.$_item['user_id'].' and project_id='.$_item['project_id']);
                //if(!$from_recharge) {
                //    continue;
                //}
                //$from_recharge = $from_recharge[0];

                $cang = Cang::get([
                    'BACKUPID'=>$_item['id']
                ]);

                if(!$cang) {
                    $cang = new Cang();
                }

                $cang['BACKUPID'] = $_item['id'];
                $cang['subjectID'] = isset($subject['subjectID']) ? $subject['subjectID'] : 0;
                $cang['userID'] = $user['userID'];
                $cang['moneySubject'] = $_item['due_capital'];
                $cang['status'] = $status;
                $cang['addTime'] = Common::datetotime($_item['add_time']);
                $cang['payTime'] = Common::datetotime($_item['add_time']);
                $cang['updateTime'] = Common::datetotime($_item['add_time']);
                $cang['interestBeginTime'] = Common::datetotime($_item['start_time']);
                $cang['interestEndTime'] = Common::datetotime($_item['due_time']);
                $cang['repayTime'] = Common::datetotime($_item['due_time']);
                $cang['year'] = $subject['year'];
                $cang['ben'] = $_item['due_capital'];
                $cang['yearSystem'] = $subject['yearSystem'];
                $cang['interest'] = $_item['due_interest'];
                $cang['investDay'] = $_item['duration_day'];
                $cang['interestTimeTypeID'] = isset($subject['interestTimeTypeID']) ? $subject['interestTimeTypeID']: 0;
                if($_item['ghost_phone']) {
                    $cang['isForged'] = 1;
                    $cang['userID'] = 4;
                }
                $cang['osType'] = $_item['device_type'];
                if($_item['device_type'] == 3) {
                    $cang['osType'] = 4;
                }
                else if($_item['device_type'] == 4) {
                    $cang['osType'] = 3;
                }
                $cang['money'] = $_item['due_capital'];
                $cang->save();

                CangRepay::where([
                    'cangID'=>$cang['cangID']
                ])->delete();



                //生成本
                $cangRepay = new CangRepay();
                $cangRepay['money'] = $_item['due_capital'];
                $cangRepay['repayTime'] = Common::datetotime($_item['due_time']);
                $cangRepay['reachTime'] = Common::datetotime($_item['due_time']) + 86400;
                $cangRepay['cangID'] = $cang['cangID'];
                $cangRepay['subjectID'] = $subject['subjectID'];
                $cangRepay['userID'] = $user['userID'];
                $cangRepay['status'] = CangRepay::STATUS_UNREPAY;
                if($_item['status'] == 2) {
                    $cangRepay['status'] = CangRepay::STATUS_REPAY;
                }
                $cangRepay['repayTypeID'] = 1;
                $cangRepay->save();


                //生成息
                $cangRepay = new CangRepay();
                $cangRepay['money'] = $_item['due_interest'];
                $cangRepay['repayTime'] = Common::datetotime($_item['due_time']);
                $cangRepay['reachTime'] = Common::datetotime($_item['due_time']) + 86400;
                $cangRepay['cangID'] = $cang['cangID'];
                $cangRepay['subjectID'] = $subject['subjectID'];
                $cangRepay['userID'] = $user['userID'];
                $cangRepay['status'] = CangRepay::STATUS_UNREPAY;
                if($_item['status'] == 2) {
                    $cangRepay['status'] = CangRepay::STATUS_REPAY;
                }
                $cangRepay['repayTypeID'] = 2;
                $cangRepay->save();



            }


            //生成充值记录
            $from_list = Db::connect($this->db_config_from)->query('select * from s_user_wallet_records where recharge_no<>\'\' and value > 0 and type=1 and status<>3 and user_id='.$item['id']);
            foreach ($from_list as $__k=>$__item) {

                //用户
                $user = User::get([
                    'BACKUPID'=>$__item['user_id']
                ]);
                if(!$user || $__item['user_id'] == 0) {
                    continue;
                }

                //银行
                $userBank = UserBank::get([
                    'BACKUPID'=>$__item['user_bank_id']
                ]);
                if(!$userBank) {
                    continue;
                }

                //余额
                $userRecharge = UserRecharge::get([
                    'BACKUPID'=>$__item['id']
                ]);
                if(!$userRecharge) {
                    $userRecharge = new UserRecharge();
                }
                $status = 0;
                if($__item['status'] == 1) {
                    $status = UserRecharge::STATUS_PAY;
                }
                else if($__item['status'] == 2) {
                    $status = UserRecharge::STATUS_UNPAY;
                }
                else if($__item['status'] == 3) {
                    $status = UserRecharge::STATUS_ERROR;
                }
                $userRecharge['BACKUPID'] = $__item['id'];
                $userRecharge['userID'] = $user['userID'];
                $userRecharge['money'] = $__item['value'];
                $userRecharge['status'] = $status;
                $userRecharge['outerNumber'] = $__item['recharge_no'].'-'.$__item['trade_no'];
                $userRecharge['type'] = 'TYPE_BANK';
                $userRecharge['bankID'] = $userBank['bankID'];
                $userRecharge['bankAccount'] = $userBank['bankAccount'];
                $userRecharge['bankNumber'] = $userBank['bankNumber'];
                $userRecharge['bankName'] = $userBank['bankNameFull'];
                $userRecharge['addTime'] = Common::datetotime($__item['add_time']);
                $userRecharge['trueName'] = $userBank['trueName'];
                $userRecharge['mobile'] = $userBank['mobile'];
                $userRecharge['userBankID'] = $userBank['userBankID'];
                $userRecharge->save();

                UserFinance::create([
                    'mode'=>UserFinance::MODE_RECHARGE,
                    'modeID'=>$userRecharge['userRechargeID'],
                    'money'=>$__item['value'],
                    'addTime'=>Common::datetotime($__item['add_time']),
                    'userID'=>$user['userID'],
                    'status'=>UserFinance::STATUS_OK,
                    'updateTime'=>Common::datetotime($__item['add_time'])
                ]);
            }



            //生成提现记录
            $from_list = Db::connect($this->db_config_from)->query('select * from s_user_wallet_records where recharge_no<>\'\' and value < 0 and type=2 and status<>3 and user_id='.$item['id']);
            foreach ($from_list as $__k=>$__item) {

                //用户
                $user = User::get([
                    'BACKUPID'=>$__item['user_id']
                ]);
                if(!$user || $__item['user_id'] == 0) {
                    continue;
                }

                //银行
                $userBank = UserBank::get([
                    'BACKUPID'=>$__item['user_bank_id']
                ]);
                if(!$userBank) {
                    continue;
                }

                //余额
                $userDrawcash = UserDrawcash::get([
                    'BACKUPID'=>$__item['id']
                ]);
                if(!$userDrawcash) {
                    $userDrawcash = new UserDrawcash();
                }
                $status = 0;
                if($__item['status'] == 1) {
                    $status = UserDrawcash::STATUS_OK;
                }
                else if($__item['status'] == 2) {
                    $status = UserDrawcash::STATUS_ING;
                }
                else if($item['status'] == 3) {
                    $status = UserDrawcash::STATUS_ERROR;
                }
                $userDrawcash['BACKUPID'] = $__item['id'];
                $userDrawcash['userID'] = $user['userID'];
                $userDrawcash['money'] = $__item['value'];
                $userDrawcash['status'] = $status;
                $userDrawcash['outerNumber'] = $__item['recharge_no'].'-'.$__item['trade_no'];
                $userDrawcash['type'] = 'TYPE_BANK';
                $userDrawcash['bankID'] = $userBank['bankID'];
                $userDrawcash['bankAccount'] = $userBank['bankAccount'];
                $userDrawcash['bankNumber'] = $userBank['bankNumber'];
                $userDrawcash['bankName'] = $userBank['bankNameFull'];
                $userDrawcash['applyTime'] = Common::datetotime($__item['add_time']);
                $userDrawcash['trueName'] = $userBank['trueName'];
                $userDrawcash['mobile'] = $userBank['mobile'];
                $userDrawcash['userBankID'] = $userBank['userBankID'];
                $userDrawcash->save();

                UserFinance::create([
                    'mode'=>UserFinance::MODE_DRAWCASH,
                    'modeID'=>$userDrawcash['userDrawcashID'],
                    'money'=>$__item['value'],
                    'addTime'=>Common::datetotime($__item['add_time']),
                    'userID'=>$user['userID'],
                    'status'=>UserFinance::STATUS_OK,
                    'updateTime'=>Common::datetotime($__item['add_time'])
                ]);
            }

            Db::connect($this->db_config_from)->execute('UPDATE s_user SET tongbu=1 WHERE username=\''.$mobile.'\'');
        }

        return $isTongbu;
    }

    public function DY_password($mobile, $password = '') {
        $from_list = Db::connect($this->db_config_from)->query('select * from s_user where card_no_auth=1  and username=\''.$mobile.'\'');
        foreach ($from_list as $k=>$item) {
            if($item['tongbu_password'] == 1) {
                return false;
            }
            if ($item['password'] == md5($password)) {
                //tongbu_password
                Db::connect($this->db_config_from)->execute('UPDATE s_user SET tongbu_password=1 WHERE username=\''.$mobile.'\'');
                return true;
            }
        }
        return false;
    }

}