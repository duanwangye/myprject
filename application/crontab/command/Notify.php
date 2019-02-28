<?php
/**
 * 发送
 * User: qissen
 * Date: 2017/11/26
 * Time: 11:26a
 */
namespace app\crontab\command;

use app\core\model\Bank;
use app\core\model\CangRepay;
use app\core\model\Channel;
use app\core\model\Hongbao;
use app\core\model\Loan;
use app\core\model\Subject;
use app\core\model\SubjectStat;
use app\core\model\User;
use app\core\model\Cang as CangModel;
use app\core\model\UserAccount;
use app\core\model\UserBank;
use app\core\model\UserDrawcash;
use app\core\model\UserHongbao;
use app\core\model\UserRecharge;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Log;
use tool\Common;

class Notify extends Command
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

    private $db_config_to = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => 'rm-bp1cy713yknm2326x.mysql.rds.aliyuncs.com',
        // 数据库名
        'database'    => 'p2p',
        // 数据库用户名
        'username'    => 'sqlyog',
        // 数据库密码
        'password'    => 'Qissen111111',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'a_',
    ];

    protected function configure()
    {
        $this->setName('Notify')->setDescription('Notify开始');
    }

    protected function execute(Input $input, Output $output)
    {

        Log::info('Notify:'.Common::timetodate(THINK_START_TIME));
        //执行顺序不能变
        $this->DY_subject();
        //$this->DY_user();
        //$this->DY_bank();
        //$this->DY_recharge();
        //$this->DY_drawcash();
        //$this->DY_hongbao();
        //$this->loan();
        //$this->user();


        $output->writeln("Notify结束");
    }

    private function DY_channel() {
        //Log::info(Db::connect($this->db_config_from)->query('select * from s_constant'));
        $from_channelList = Db::connect($this->db_config_from)->query('select * from s_constant where parent_id=9');

        $to_data = [];

        foreach ($from_channelList as $k=>$item) {
            $to_data[$k]['name'] = $item['cons_key'];
            $to_data[$k]['code'] = $item['cons_value'];
            $to_data[$k]['addTime'] = Common::datetotime($item['add_time']);
            $to_data[$k]['type'] = 1;
        }

        (new Channel())->insertAll($to_data);

        //Db::connect($this->db_config_to);
    }

    private function DY_hongbao() {
        $perCount = 100;
        for($i = 0; $i < 5000; $i++) {

            $from_list = Db::connect($this->db_config_from)->query('select id,user_id,title,min_due,min_invest,amount,create_time,expire_time,status from s_user_redenvelope '.' LIMIT '.($i * $perCount).','.$perCount);
            foreach ($from_list as $k => $item) {
                $user = User::get([
                    'BACKUPID' => $item['user_id']
                ]);
                if(!$user) {
                    continue;
                }
                $hongbao = Hongbao::get([
                    'title' => $item['title'],
                    'minDay' => $item['min_due'],
                    'minMoney' => $item['min_invest'] * 100,
                    'money' => $item['amount'] * 100
                ]);
                if(!$hongbao) {
                    continue;
                }
                //用户
                $userHongbao = UserHongbao::get([
                    'BACKUPID' => $item['id']
                ]);
                if (!$userHongbao) {
                    $userHongbao = new UserHongbao();
                }
                $status = 0;
                if ($item['status'] == 0) {
                    $status = UserHongbao::STATUS_UNUSED;
                } else if ($item['status'] == 1) {
                    $status = UserHongbao::STATUS_USED;
                } else if ($item['status'] == 2) {
                    $status = UserHongbao::STATUS_OUTTIME;
                }
                $userHongbao['BACKUPID'] = $item['id'];
                $userHongbao['hongbaoID'] = $hongbao['hongbaoID'];
                $userHongbao['userID'] = $user['userID'];
                $userHongbao['getType'] = 1;
                $userHongbao['status'] = $status;
                $userHongbao['hongbaoPlanID'] = 5;
                $userHongbao['addTime'] = Common::datetotime($item['create_time']);
                $userHongbao['beginTime'] = Common::datetotime($item['create_time']);
                $userHongbao['endTime'] = Common::datetotime($item['expire_time']);
                $userHongbao->save();
            }
        }
    }

    private function DY_user() {
        $perCount = 100;
        for($i = 0; $i < 300; $i++) {
            $from_list = Db::connect($this->db_config_from)->query('select * from s_user where card_no_auth=1 LIMIT '.($i * $perCount).','.$perCount);
            foreach ($from_list as $k=>$item) {

                //用户
                $user = User::get([
                    'mobile'=>$item['username']
                ]);
                if(!$user) {
                    $user = new User();
                }
                $user['BACKUPID'] = $item['id'];//BACKUPID
                $user['mobile'] = $item['username'];//手机号
                $user['trueName'] = $item['real_name'];//真实姓名
                $user['isAuthTrueName'] = $item['card_no_auth'];//实名认证
                $user['isAuthBank'] = $item['card_no_auth'];//实名认证
                $user['passport'] = $item['card_no'];//身份证
                $user['email'] = $item['email'];//银行卡认证
                if($item['level'] == 2) {
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
                    $item['channel_web_id'] = 0;
                }
                if($item['add_time']) {
                    $user['addTime'] = Common::datetotime($item['add_time']);//添加时间
                }
                else {
                    $user['addTime'] = 0;//添加时间
                }
                $user->save();

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
                    //$to_data['moneyYesterday'] = $account[''];
                    //$to_data['moneyToday'] = $account[''];
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


            }
        }
    }

    private function DY_bank() {
        $from_list = Db::connect($this->db_config_from)->query('select * from s_user_bank');
        foreach ($from_list as $k=>$item) {

            //用户
            $user = User::get([
                'BACKUPID'=>$item['user_id']
            ]);
            if(!$user) {
                continue;
            }

            //银行
            $bank = Bank::get([
                'bankAccountCode'=>$item['bank_code']
            ]);

            //用户银行卡
            $userBank = UserBank::get([
                'BACKUPID'=>$item['id']
            ]);

            if(!$userBank) {
                $userBank = new UserBank();
            }
            $userBank['BACKUPID'] = $item['id'];//BACKUPID
            $userBank['userID'] = $user['userID'];//手机号
            $userBank['bankNameFull'] = $item['bank_name'];//
            $userBank['bankID'] = isset($bank['bankID']) ? $bank['bankID'] : 0;//实名认证
            $userBank['bankNameFull'] = $item['bank_name'];
            $userBank['bankAccount'] = $item['acct_name'];
            $userBank['bankNumber'] = $item['bank_card_no'];
            $userBank['trueName'] = $item['acct_name'];
            $userBank['mobile'] = $item['mobile'];
            $userBank['addTime'] = Common::datetotime($item['add_time']);
            $userBank['status'] = 1;
            $userBank['isDefault'] = 1;
            $userBank->save();
        }
    }

    private function DY_recharge() {
        $from_list = Db::connect($this->db_config_from)->query('select * from s_user_wallet_records where recharge_no<>\'\' and value > 0 and type=1');

        foreach ($from_list as $k=>$item) {

            //用户
            $user = User::get([
                'BACKUPID'=>$item['user_id']
            ]);
            if(!$user || $item['user_id'] == 0) {
                continue;
            }

            //银行
            $userBank = UserBank::get([
                'BACKUPID'=>$item['user_bank_id']
            ]);
            if(!$userBank) {
                continue;
            }

            //余额
            $userRecharge = UserRecharge::get([
                'BACKUPID'=>$item['id']
            ]);
            if(!$userRecharge) {
                $userRecharge = new UserRecharge();
            }
            $status = 0;
            if($item['status'] == 1) {
                $status = UserRecharge::STATUS_PAY;
            }
            else if($item['status'] == 2) {
                $status = UserRecharge::STATUS_UNPAY;
            }
            else if($item['status'] == 3) {
                $status = UserRecharge::STATUS_ERROR;
            }
            $userRecharge['BACKUPID'] = $item['id'];
            $userRecharge['userID'] = $user['userID'];
            $userRecharge['money'] = $item['value'];
            $userRecharge['status'] = $status;
            $userRecharge['outerNumber'] = $item['recharge_no'].'-'.$item['trade_no'];
            $userRecharge['type'] = 'TYPE_BANK';
            $userRecharge['bankID'] = $userBank['bankID'];
            $userRecharge['bankAccount'] = $userBank['bankAccount'];
            $userRecharge['bankNumber'] = $userBank['bankNumber'];
            $userRecharge['bankName'] = $userBank['bankNameFull'];
            $userRecharge['addTime'] = Common::datetotime($item['add_time']);
            $userRecharge['trueName'] = $userBank['trueName'];
            $userRecharge['mobile'] = $userBank['mobile'];
            $userRecharge['userBankID'] = $userBank['userBankID'];
            $userRecharge->save();
        }
    }

    private function DY_drawcash() {
        $from_list = Db::connect($this->db_config_from)->query('select * from s_user_wallet_records where recharge_no<>\'\' and value < 0 and type=2');
        foreach ($from_list as $k=>$item) {

            //用户
            $user = User::get([
                'BACKUPID'=>$item['user_id']
            ]);
            if(!$user || $item['user_id'] == 0) {
                continue;
            }

            //银行
            $userBank = UserBank::get([
                'BACKUPID'=>$item['user_bank_id']
            ]);
            if(!$userBank) {
                continue;
            }

            //余额
            $userDrawcash = UserDrawcash::get([
                'BACKUPID'=>$item['id']
            ]);
            if(!$userDrawcash) {
                $userDrawcash = new UserDrawcash();
            }
            $status = 0;
            if($item['status'] == 1) {
                $status = UserDrawcash::STATUS_OK;
            }
            else if($item['status'] == 2) {
                $status = UserDrawcash::STATUS_ING;
            }
            else if($item['status'] == 3) {
                $status = UserDrawcash::STATUS_ERROR;
            }
            $userDrawcash['BACKUPID'] = $item['id'];
            $userDrawcash['userID'] = $user['userID'];
            $userDrawcash['money'] = $item['value'];
            $userDrawcash['status'] = $status;
            $userDrawcash['outerNumber'] = $item['recharge_no'].'-'.$item['trade_no'];
            $userDrawcash['type'] = 'TYPE_BANK';
            $userDrawcash['bankID'] = $userBank['bankID'];
            $userDrawcash['bankAccount'] = $userBank['bankAccount'];
            $userDrawcash['bankNumber'] = $userBank['bankNumber'];
            $userDrawcash['bankName'] = $userBank['bankNameFull'];
            $userDrawcash['applyTime'] = Common::datetotime($item['add_time']);
            $userDrawcash['trueName'] = $userBank['trueName'];
            $userDrawcash['mobile'] = $userBank['mobile'];
            $userDrawcash['userBankID'] = $userBank['userBankID'];
            $userDrawcash->save();
        }
    }

    private function DY_recharge1() {
        $from_list = Db::connect($this->db_config_from)->query('select * from s_recharge_log');
        foreach ($from_list as $k=>$item) {

            //用户
            $user = User::get([
                'BACKUPID'=>$item['user_id']
            ]);
            if(!$user || $item['user_id'] == 0) {
                continue;
            }

            //银行
            $userBank = UserBank::get([
                'bankNumber'=>$item['card_no']
            ]);


            //余额
            $userRecharge = UserRecharge::get([
                'BACKUPID'=>$item['id']
            ]);
            if(!$userRecharge) {
                $userRecharge = new UserRecharge();
            }
            $userRecharge['BACKUPID'] = $item['id'];//BACKUPID
            $userRecharge['userID'] = $user['userID'];//手机号
            $userRecharge['money'] = $item['amount'];//
            $userRecharge['status'] = 1;
            $userRecharge['type'] = 'TYPE_BANK';
            $userRecharge['bankID'] = $userBank['bankID'];
            $userRecharge['bankAccount'] = $userBank['bankAccount'];
            $userRecharge['bankNumber'] = $userBank['bankNumber'];
            $userRecharge['bankName'] = $userBank['bankName'];
            $userRecharge['addTime'] = Common::datetotime($item['add_time']);
            $userRecharge['trueName'] = $userBank['trueName'];
            $userRecharge['mobile'] = $userBank['mobile'];
            $userRecharge['userBankID'] = $userBank['userBankID'];
            $userRecharge->save();
        }
    }

    private function DY_loan() {
        $from_contractList = Db::connect($this->db_config_from)->query('select * from s_contract');
        foreach ($from_contractList as $k=>$item) {



            $loan = Loan::get([
                'alias'=>$item['name']
            ]);

            if(!$loan) {
                $loan = new Loan();
            }

            $loan['alias'] = $item['name'];
            $loan['beginTime'] = $item['start_time'];
            $loan['endTime'] = $item['end_time'];
            $loan['money'] = $item['price'];
            $loan['year'] = $item['interest'];
            $loan['name'] = $item['financing'];
            $loan['addTime'] = $item['add_time'];
            $loan['certType'] = 1;
            $loan['certContent'] = $item['idcard'];
            $loan['status'] = 1;
            $loan['pledgeType'] = 1;
            $loan->save();
        }
    }

    private function DY_subject() {
        $from_subjectList = Db::connect($this->db_config_from)->query('select * from s_project where amount > 0');
        foreach ($from_subjectList as $k=>$item) {
            $subjectTypeID = 1;
            if($item['type'] == 4) {
                $subjectTypeID = 1;
            }
            else if($item['type'] == 5) {
                $subjectTypeID = 2;
            }
            else if($item['type'] == 6) {
                $subjectTypeID = 3;
            }
            else if($item['type'] == 7) {
                $subjectTypeID = 4;
            }

            $status = Subject::STATUS_ONLINE_CHECK;
            $statusLoan = Subject::STATUS_LOAN_NULL;
            if($item['status'] == 1) {
                $status = Subject::STATUS_ONLINE_CHECK;
            }
            else if($item['status'] == 2) {
                $status = Subject::STATUS_ONLINE;
            }
            else if($item['status'] == 3) {
                $status = Subject::STATUS_FULL;
                $statusLoan = Subject::STATUS_LOAN_FANG_WAIT;
            }
            else if($item['status'] == 4) {
                $status = Subject::STATUS_FULL;
                $statusLoan = Subject::STATUS_LOAN_FANG_WAIT;
            }
            else if($item['status'] == 5) {
                $status = Subject::STATUS_REPAY;
            }
            else if($item['status'] == 6) {
                $status = Subject::STATUS_FULL;
                $statusLoan = Subject::STATUS_LOAN_FANG;
            }
            else if($item['status'] == 7) {
                $status = Subject::STATUS_FULL;
                $statusLoan = Subject::STATUS_LOAN_FANG;
            }


            $subject = Subject::get([
                'BACKUPID'=>$item['id']
            ]);

            if(!$subject) {
                $subject = new Subject();
            }

            $loan = Loan::get([
                'alias'=>$item['contract_no']
            ]);
            $subject['BACKUPID'] = $item['id'];
            $subject['title'] = $item['title'];
            $subject['subjectTypeID'] = $subjectTypeID;
            $subject['interestTypeID'] = 1;
            $subject['interestTimeTypeID'] = 2;
            $subject['term'] = $item['duration'];
            $subject['price'] = $item['amount'];
            $subject['year'] = $item['user_interest'];
            $subject['yearSystem'] = isset($item['user_platform_subsidy']) ? $item['user_platform_subsidy'] : 0;
            $subject['basePrice'] = $item['money_min'];
            $subject['baseMaxPrice'] = $item['money_max'];
            $subject['multiplePrice'] = 0;
            $subject['releaseTime'] = Common::datetotime($item['start_time']);
            /*$subject['beginTime'] = Common::datetotime($item['start_time']);
            $subject['endTime'] = Common::datetotime($item['end_time']);*/
            $subject['isIndexApp'] = 0;
            $subject['isIndexPc'] = 0;
            $subject['status'] = $status;
            $subject['statusLoan'] = $statusLoan;
            $subject['loanID'] = $loan ? $loan['alias'] : 0;
            $subject['hongbao'] = '';
            $subject['overTime'] = Common::datetotime($item['end_time']);
            $subject['fullTime'] = isset($item['soldout_time']) ? $item['soldout_time'] : 0;
            $subject['stage'] = $item['stage'];
            //dump($subject);exit;
            //dump($subject);exit;
            $subject->save();

            //$from_account = Db::connect($this->db_config_from)->query('select * from s_user_account where user_id='.$item['id']);
            $subjectStat = SubjectStat::get([
                'subjectID'=>$subject['subjectID']
            ]);
            if(!$subjectStat) {
                $subjectStat = new SubjectStat();
            }
            $subjectStat['subjectID'] = $subject['subjectID'];
            $subjectStat['moneyTotalInvest'] = $item['amount'] - $item['able'];
            $subjectStat['timesInvest'] = 0;
            $subjectStat->save();
        }
    }

    private function DY_cang() {
        $from_cangList = Db::connect($this->db_config_from)->query('select * from s_user_due_detail');
        foreach ($from_cangList as $k=>$item) {

            $subject = Subject::get([
                'BACKUPID'=>$item['project_id']
            ]);

            $user = User::get([
                'BACKUPID'=>$item['user_id']
            ]);
            $status = 0;
            if($item['status'] == 1) {
                $status = CangModel::STATUS_INTEREST;
            }
            else if($item['status'] == 2) {
                $status = CangModel::STATUS_FINISH;
            }
            else if($item['type'] == 3) {
                $status = CangModel::STATUS_REPAY;
            }

            $from_recharge = Db::connect($this->db_config_from)->query('select * from s_recharge_log where recharge_no=\''.$item['recharge_no'].'\''.' and user_id='.$item['user_id'].' and project_id='.$item['project_id']);
            if(!$from_recharge) {
                continue;
            }
            $from_recharge = $from_recharge[0];

            $cang = CangModel::get([
                'BACKUPID'=>$item['id']
            ]);

            if(!$cang) {
                $cang = new CangModel();
            }

            $cang['BACKUPID'] = $item['id'];
            $cang['subjectID'] = $subject['subjectID'];
            $cang['userID'] = $user['userID'];
            $cang['moneySubject'] = $item['due_capital'];
            $cang['status'] = $status;
            $cang['addTime'] = $item['user_interest'];
            $cang['payTime'] = isset($item['user_platform_subsidy']) ? $item['user_platform_subsidy'] : 0;
            $cang['updateTime'] = $item['money_min'];
            $cang['interestBeginTime'] = Common::datetotime($item['start_time']);
            $cang['interestEndTime'] = Common::datetotime($item['due_time']);
            $cang['repayTime'] = Common::datetotime($item['end_time']);
            $cang['year'] = $subject['year'];
            $cang['ben'] = $item['due_capital'];
            $cang['yearSystem'] = $subject['yearSystem'];
            $cang['interest'] = $item['due_interest'];
            $cang['investDay'] = $item['duration_day'];
            $cang['interestTimeTypeID'] = $subject['interestTimeTypeID'];
            if($item['ghost_phone']) {
                $cang['isForged'] = 1;
                $cang['userID'] = 4;
            }
            $cang['osType'] = $item['device_type'];
            if($item['device_type'] == 3) {
                $cang['osType'] = 4;
            }
            else if($item['device_type'] == 4) {
                $cang['osType'] = 3;
            }
            $cang['money'] = $from_recharge['amount'];
            $cang->save();

            $cangRepay = CangRepay::where([
                'cangID'=>$cang['cangID']
            ])->select();
            if($cangRepay->isEmpty()) {
                $cangRepay = new CangRepay();
            }
            else {
                $cangRepay->destroy();
            }

            //生成本
            $cangRepay['money'] = $item['due_capital'];
            $cangRepay['repayTime'] = Common::datetotime($item['end_time']);
            $cangRepay['reachTime'] = Common::datetotime($item['end_time']) + 86400;
            $cangRepay['cangID'] = $cang['cangID'];
            $cangRepay['subjectID'] = $subject['subjectID'];
            $cangRepay['userID'] = $user['userID'];
            $cangRepay['status'] = CangRepay::STATUS_UNREPAY;
            if($item['status'] == 2) {
                $cangRepay['status'] = CangRepay::STATUS_REPAY;
            }
            $cangRepay['repayTypeID'] = 1;
            $cangRepay->save();

            //生成息
            $cangRepay['money'] = $item['due_interest'];
            $cangRepay['repayTime'] = Common::datetotime($item['end_time']);
            $cangRepay['reachTime'] = Common::datetotime($item['end_time']) + 86400;
            $cangRepay['cangID'] = $cang['cangID'];
            $cangRepay['subjectID'] = $subject['subjectID'];
            $cangRepay['userID'] = $user['userID'];
            $cangRepay['status'] = CangRepay::STATUS_UNREPAY;
            if($item['status'] == 2) {
                $cangRepay['status'] = CangRepay::STATUS_REPAY;
            }
            $cangRepay['repayTypeID'] = 2;
            $cangRepay->isUpdate(false)->save();

            //生成流水

        }
    }

}


