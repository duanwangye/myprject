<?php
/**
 * 持仓到期自动结算
 * User: qissen
 * Date: 2017/11/26
 * Time: 11:26a
 */
namespace app\crontab\command;

use app\core\model\Subject as Model;
use app\core\model\SubjectStat;
use app\core\model\User;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Log;
use tool\Common;

class Subject extends Command
{
    protected function configure()
    {
        $this->setName('Subject')->setDescription('Subject开始');
    }

    protected function execute(Input $input, Output $output)
    {
        //在这里我们进行结算
        Log::info('Subject:'.Common::timetodate(THINK_START_TIME));


        $this->setStatusOnline();
        $this->setStatusOverTime();
        $this->setRandCreateCang();


        $output->writeln("Subject开始");
    }




    /**
     * 设置上线
     */
    private function setStatusOnline() {
        Model::where([
            'releaseTime'=>['lt', THINK_START_TIME],
            'status'=>Model::STATUS_ONLINE_CHECK
        ])->chunk(100, function($list) {
            foreach ($list as $item) {
                $item->status = Model::STATUS_ONLINE;
                $item->listOrder = THINK_START_TIME;
                $item->save();



                //将临近的app去掉首页
                if($item['isIndexApp']) {
                    $model = Model::where([
                        'subjectTypeID'=>$item['subjectTypeID'],
                        'BACKUPID'=>0,
                        'status'=>Model::STATUS_ONLINE,
                        'subjectID'=>['neq', $item['subjectID']]
                    ])->order('addTime desc')->find();
                    if(!$model) {
                        continue;
                    }
                    $model['isIndexPc'] = 0;
                    $model['isIndexApp'] = 0;
                    $model->save();
                }




                /********************* 如果进行只能上线一个标请将我打开 ***********************
                //将同类型最近一个产品设置为满标
                $model = Model::where([
                    'subjectTypeID'=>$item['subjectTypeID'],
                    'BACKUPID'=>0,
                    'status'=>Model::STATUS_ONLINE,
                    'subjectID'=>['neq', $item['subjectID']]
                ])->order('addTime desc')->find();
                if(!$model) {
                    continue;
                }
                //第0步，得到money
                $money = $model['price'] - $model->subjectStat['moneyTotalInvest'];
                if($money <= 0) {
                    continue;
                }


                //第一步，得到一个虚拟账户
                $userList = User::where([
                    'isForged'=>1
                ])->field(['userID'])->select();
                if($userList->isEmpty()) {
                    continue;
                }
                $userIDS = array_column($userList->toArray(), 'userID');
                $userIDIndex = mt_rand(0, count($userIDS) - 1);
                $userID = $userIDS[$userIDIndex];
                $user = User::get($userID);


                //第二步，保存一个仓
                $cang = \app\core\model\Cang::create([
                    'subjectID' => $model['subjectID'],
                    'userID' => $user['userID'],
                    'moneySubject' => $money,
                    'ben' => $money,
                    'status'=>\app\core\model\Cang::STATUS_PAY,
                    'isForged'=>1
                ]);
                $cang['alias'] = \app\core\model\Cang::createAlias($cang['cangID']);
                $cang->save();

                //第三步，对产品进行统计
                SubjectStat::where([
                    'subjectID'=>$model['subjectID']
                ])->setInc('moneyTotalInvest', $money * 100);
                SubjectStat::where([
                    'subjectID'=>$model['subjectID']
                ])->setInc('timesInvest');

                //第四步，将该标去掉首页符号
                $model['isIndexPc'] = 0;
                $model['isIndexApp'] = 0;
                $model->save();

                //第四步，设置为满标
                Model::setSubjectFull($model);

                ********************************** end *************************************/
            }
        });
    }


    private function setStatusOverTime() {
       Model::where([
            'overTime'=>['lt', THINK_START_TIME],
            'status'=>Model::STATUS_FULL
        ])->chunk(100, function($list) {
            foreach ($list as $item) {
                $item->status = Model::STATUS_OVERTIME;
                $item->save();
            }
        });
    }


    //随机虚拟认购
    private function setRandCreateCang() {
        Model::where([
            'status'=>Model::STATUS_ONLINE,
            'BACKUPID'=>0
        ])->chunk(100, function($list) {
            foreach ($list as $model) {

                //第0步，得到money
                $moneyStill = $model['price'] - $model->subjectStat['moneyTotalInvest'];
                if($moneyStill <= 0) {
                    continue;
                }
                //$hour = date("H",time());
                $cang = \app\core\model\Cang::where([
                    'subjectID'=>$model['subjectID']
                ])->order('addTime desc')->find();
                if($cang && $cang->getData('addTime') + mt_rand(8000,12000) > THINK_START_TIME) {
                    continue;
                }
                $moneyS = [1000, 1000, 1000, 2000, 5000, 10000];
                $index = mt_rand(0,5);
                $money = $moneyS[$index];//一次投2000
                if($moneyStill < $money) {
                    $money = $moneyStill;
                }

                //第一步，得到一个虚拟账户
                $userList = User::where([
                    'isForged'=>1
                ])->field(['userID'])->select();
                if($userList->isEmpty()) {
                    continue;
                }
                $userIDS = array_column($userList->toArray(), 'userID');
                $userIDIndex = mt_rand(0, count($userIDS) - 1);
                $userID = $userIDS[$userIDIndex];
                $user = User::get($userID);


                //第二步，保存一个仓
                $cang = \app\core\model\Cang::create([
                    'subjectID' => $model['subjectID'],
                    'userID' => $user['userID'],
                    'moneySubject' => $money,
                    'ben' => $money,
                    'status'=>\app\core\model\Cang::STATUS_PAY,
                    'isForged'=>1
                ]);
                $cang['alias'] = \app\core\model\Cang::createAlias($cang['cangID']);
                $cang->save();

                //第三步，对产品进行统计
                SubjectStat::where([
                    'subjectID'=>$model['subjectID']
                ])->setInc('moneyTotalInvest', $money * 100);
                SubjectStat::where([
                    'subjectID'=>$model['subjectID']
                ])->setInc('timesInvest');


                //第四步，判断是否满标
                if($model->subjectStat['moneyTotalInvest'] + $money == $model['price']) {
                    //如果满标了，设置满标
                    Model::setSubjectFull($model);
                }
            }
        });
    }
}