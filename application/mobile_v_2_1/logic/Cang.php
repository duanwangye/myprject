<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile_v_2_1\logic;

use app\core\exception\AppException;
use app\core\model\Cang as Model;
use app\core\model\CangRepay;
use app\core\model\Hongbao;
use app\core\model\Subject;
use app\core\model\SubjectStat;
use app\core\model\UserAccount;
use app\core\model\UserBank;
use app\core\model\User;
use app\core\model\UserHongbao;
use app\core\service\Pay;
use app\core\service\Finance;
use think\Db;
use think\Exception;
use think\Hook;
use think\Log;
use tool\Common;

class Cang extends Base
{

    public function create()
    {

        //第一步，得到标的信息
        $subject = Subject::get($this->app['subjectID']);

        //第二步，得到红包相关
        $hongbaoMoney = 0;//现金券
        $hongbaoYear = 0;//加息年化
        if(isset($this->app['hongbaoIDS']) && count($this->app['hongbaoIDS']) > 0) {
            foreach ($this->app['hongbaoIDS'] as $k => $hongbaoID) {
                $userHongbao = UserHongbao::with(['hongbao'])->where([
                    'userHongbaoID'=>$hongbaoID,
                    'status'=>UserHongbao::STATUS_UNUSED,
                    'userID'=>$this->user['userID']
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
        }

        //第三步，得到实际要付的金额
        $money = $this->app['moneySubject'] - $hongbaoMoney;

        //第四步，判断是否符合投资资格
        //1、判断是否过认购期
        /*if(THINK_START_TIME >= $subject->getData('endTime')) {
            return Common::rm(-3, '该商品已过认购期，不能投资了');
        }*/

        //2、判断是否满标了
        if($subject['status'] == Subject::STATUS_FULL) {
            return Common::rm(-4, '该商品已经满标，不能投资了');
        }

        //3、判断是否下架了
        if($subject['status'] != Subject::STATUS_ONLINE) {
            return Common::rm(-5, '该商品已经下架，不能投资了');
        }

        //4、判断金额数字是否符合标的要求
        if($subject['basePrice'] > $this->app['moneySubject']) {
            return Common::rm(-6, '最低需投资'.$subject['basePrice']);
        }
        if($subject['baseMaxPrice'] < $this->app['moneySubject']) {
            return Common::rm(-7, '投资最大不得超出'.$subject['baseMaxPrice']);
        }
        if($subject->subjectStat['moneyTotalInvest'] + $this->app['moneySubject'] > $subject['price']) {
            return Common::rm(-8, '投入金额过多，超出标的总金额，最多可投'.($subject['price'] - $subject->subjectStat['moneyTotalInvest']));
        }

        //5、查看余额是否够
        if($this->user['userAccount']['money'] < $money) {
            return Common::rm(-9, '余额不足请先充值');
        }

        if(!$this->user['isNewInvest'] && $subject['subjectTypeID'] == 1) {
            return Common::rm(-10, '您已经投过新手标了，不能再投！');
        }




        //第五步，生成一个未支付的仓
        $interest = 0;
        $interestBeginTime = 0;//起息日期
        $interestEndTime = 0;//起息结束（算当天）
        $investDay = 0;//利息产生的天数
        $repayTime = 0;
        $year = $subject['year'] + $hongbaoYear + $subject['yearSystem'];//总的年化
        //如果是满标计息
        if($subject['interestTimeTypeID'] == 1) {
            //满标利息无法计算，只有等到满标才可以
            $repayTime = $subject['repayTime'];
        }
        //如果是T + 1，我们容易计算利息
        else if($subject['interestTimeTypeID'] == 2) {
            $interestBeginTime = Common::datetotime(Common::timetodate(THINK_START_TIME, 0)) - 86400;
            $interestEndTime = $subject->getData('overTime');
            $investDay = $subject['investDay'];//投资天数
            //$investDay = (int)(($interestEndTime - $interestBeginTime) / 86400);
            $interest = round($this->app['moneySubject'] * $year / 100 / 365 * $investDay, 2);//预付利息
            $repayTime = $subject['repayTime'];
        }
        //如果是T + 0，我们容易计算利息
        else if($subject['interestTimeTypeID'] == 3) {
            $interestBeginTime = Common::datetotime(Common::timetodate(THINK_START_TIME, 0));
            $interestEndTime = $subject->getData('overTime') - 86400;
            $investDay = $subject['investDay'];//投资天数
            //$investDay = (int)(($interestEndTime - $interestBeginTime) / 86400);
            $interest = round($this->app['moneySubject'] * $year / 100 / 365 * $investDay, 2);
            $repayTime = $subject['repayTime'];
        }

        //根据红包，重新计算利率


        $model = Model::create([
            'subjectID' => $subject['subjectID'],
            'userID' => $this->user['userID'],
            'moneySubject' => $this->app['moneySubject'],
            'interestBeginTime'=>$interestBeginTime,
            'interestEndTime'=>$interestEndTime,
            'year'=>$subject['year'],
            'yearExt'=>$hongbaoYear,
            'ben'=>$this->app['moneySubject'],
            'money'=>$money,
            'yearSystem'=>$subject['yearSystem'],
            'osType'=>$this->data['osType'],
            'ip'=>$this->data['ip'],
            'interest'=>$interest,
            'investDay'=>$investDay,
            'interestTimeTypeID'=> $subject['interestTimeTypeID'],
            'status'=>Model::STATUS_UNPAY,
            'hongbao'=>implode(',', $this->app['hongbaoIDS'])
        ]);
        $model['alias'] = Model::createAlias($model['cangID']);
        $model->save();


        Db::startTrans();
        try{



            //第七步，更新红包状态
            if(isset($this->app['hongbaoIDS']) && count($this->app['hongbaoIDS']) > 0) {
                foreach ($this->app['hongbaoIDS'] as $k => $hongbaoID) {
                    UserHongbao::update([
                        'status'=>UserHongbao::STATUS_USED
                    ], [
                        'userHongbaoID'=>$hongbaoID
                    ]);
                }
            }

            //第八步，更新支付状态
            $cangRepaySave = [
                'money'=>$this->app['moneySubject'],
                'repayTime'=>Common::datetotime($repayTime),
                'reachTime'=>Common::datetotime($subject['reachTime']),
                'userID' => $this->user['userID'],
                'subjectID' => $subject['subjectID'],
                'cangID'=>$model['cangID'],
                'status'=>1,
                'repayTypeID'=>1
            ];

            //第九步，更新回款清单
            //更新本金回款清单
            CangRepay::create($cangRepaySave);
            //更新利息回款清单
            $cangRepaySave['repayTypeID'] = 2;
            $cangRepaySave['money'] = $interest;
            CangRepay::create($cangRepaySave);


            //第十步，更新账户金额及流水
            $this->user->userAccount = $this->user->userAccount->MODE_cang_decMoney($model, $this->user, $interest);

            //第十一步，更新标的统计
            SubjectStat::where([
                'subjectID'=>$this->app['subjectID']
            ])->setInc('moneyTotalInvest', $this->app['moneySubject'] * 100);
            SubjectStat::where([
                'subjectID'=>$this->app['subjectID']
            ])->setInc('timesInvest');


            //第十二步，同步金账户
            vendor('payModel.Trade');
            $finance = new Finance();
            $trade = new \Trade();
            $userBank = UserBank::get([
                'userID'=>$this->user['userID']
            ]);
            $trade->setPayment($userBank['mobile']);
            $trade->setReceive('15825631526');
            $trade->setMoney($money);
            $result = $finance->order($msg, $trade);
            if(!$result) {
                Db::rollback();
                return Common::rm(-10, $msg);
            }

            //第六步，更新支付状态
            $model['payTime'] = THINK_START_TIME;
            $model['status'] = Model::STATUS_PAY;
            $model['outerNumber'] = $result;
            $model->save();


            if($this->user['isNewInvest'] && $subject['subjectTypeID'] == 1) {
                //第七步，更新用户首投
                User::update([
                    'isNewInvest'=>0
                ], [
                    'userID'=>$this->user['userID']
                ]);
            }

            //<><><><><><><><><><><><><><><><><> 下单成功钩子 <><><><><><><><><><><><><><><><><>//
            $hook = [
                'user'=>$this->user,
                'cang'=>$model
            ];
            Hook::listen('tag_cang_create_success', $hook);
            //<><><><><><><><><><><><><><><><><> 下单成功end <><><><><><><><><><><><><><><><><>//
            Db::commit();
        }
        catch (Exception $e) {
            // 回滚事务
            Log::error($e->getMessage().'|'.$e->getLine().'|'.$e->getFile());
            Db::rollback();
            throw new AppException(-9, $e->getMessage());
        }


        //第十二步，判断是否满标
        if($subject->subjectStat['moneyTotalInvest'] + $this->app['moneySubject'] == $subject['price']) {
            //如果满标了，设置满标
            Subject::setSubjectFull($subject);
        }

        return Common::rm(1, '操作成功', [
            'cangID'=>$model['cangID']
        ]);
    }

    public function createPayParams() {
        $this->app = [
            'cangID'=>1
        ];




        //第一步，获取该仓
        $model = Model::get($this->app['cangID']);
        if(!$model) {
            return Common::rm(-2, '不存在该仓');
        }


        //第二步，判断仓所属的标的是否过期
        $subject = Subject::get($model['subjectID']);
        if(!$subject) {
            return Common::rm(-12, '不存在该仓所属的标的');
        }
        //1、判断是否过认购期
        if(THINK_START_TIME >= $subject->getData('endTime')) {
            return Common::rm(-13, '该商品已过认购期，不能购买了');
        }

        //2、判断是否满标了
        if($subject['status'] == Subject::STATUS_FULL) {
            return Common::rm(-14, '该商品已经满标，不能购买了');
        }

        //3、判断是否下架了
        if($subject['status'] == Subject::STATUS_OFFLINE) {
            return Common::rm(-15, '该商品已经下架，不能购买了');
        }


        //第三步，获取支付参数
        $pay = new Pay();
        $ouerOrder = $pay->createOrder($model['money']);
        if(!$ouerOrder) {
            return Common::rm(-3, '获取支付参数失败');
        }

        $model['outerAlias'] = $ouerOrder['outerAlias'];
        $model['outerMch'] = $ouerOrder['outerMch'];
        $model['outerName'] = $ouerOrder['outerName'];
        $model['payTimes'] = $model['payTimes'] + 1;
        $model->save();

        return Common::rm(1, '操作成功', [
            'cangID'=>$this->app['cangID'],
            'outerAlias'=>$model['outerAlias'],
            /*'money'=>$model['money'],*/
            'notify'=>$this->request->domain().'/mobile/notify/cang'
        ]);
    }

    /**
     * @api {post} cang/getCangList 得到我的投资列表
     * @apiVersion 1.0.0
     * @apiName getCangList
     * @apiDescription 得到我的投资列表
     * @apiGroup Cang
     *
     * @apiParam {Number=[1,2]} status 1为持有中，2为已回款
     * @apiParamExample {json} 发送报文:
    {
    "status": 1
    }
     *
     * @apiSuccess {Object[]} cangList 我的投资列表
     * @apiSuccess {Number} cangList.cangID ID
     * @apiSuccess {Number} cangList.moneySubject 投资份额（非本金，非实际支付金额）
     * @apiSuccess {Number} cangList.status 状态，1-已持有，4-已计息，5-已回款
     * @apiSuccess {String} cangList.statusText 状态描述，参考状态
     * @apiSuccess {String} cangList.payTime 交易时间
     * @apiSuccess {String} cangList.interestBeginTime 起息日期
     * @apiSuccess {String} cangList.interestEndTime 停止计息日
     * @apiSuccess {String} cangList.year 年化
     * @apiSuccess {String} cangList.yearExt 追加年化
     * @apiSuccess {String} cangList.yearSystem 系统年化（废弃）
     * @apiSuccess {String} cangList.ben 实际投入本金
     * @apiSuccess {String} cangList.interest 利息
     * @apiSuccess {Number} cangList.investDay 投资天数
     * @apiSuccess {Object} cangList.subject 相关标的
     * @apiSuccess {String} cangList.subject.title 标的标题
     * @apiSuccess {Number} cangList.subject.subjectTypeID 标的类型
     * @apiSuccess {Number} cangList.subject.interestTypeID 计息类型
     * @apiSuccess {Number} cangList.subject.interestTimeTypeID 计息时间类型
     * @apiSuccess {String} cangList.subject.reachTime 标的标题
     * @apiSuccess {String} cangList.subject.title 标的标题
     * @apiSuccess {Object[]} cangList.cangRepay 预回款清单
     * @apiSuccess {String} cangList.cangRepay.money 回款金额
     * @apiSuccess {String} cangList.cangRepay.repayTime 回款类型
     * @apiSuccess {String} cangList.cangRepay.resultTime 实际到款时间
     * @apiSuccess {String} cangList.cangRepay.reachTime 到款时间
     * @apiSuccess {Number} cangList.cangRepay.status 清单状态，1-未回款，2-已回款，3-已到账（该状态可隐藏）
     * @apiSuccess {Number} cangList.cangRepay.repayTypeID 清单类型，1-本金，2-利息
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "cangList": [
    {
    "cangID": 33,
    "moneySubject": "2000.00",
    "status": 4,
    "payTime": "2017-12-12 20:53:50",
    "interestBeginTime": "2017-12-12",
    "interestEndTime": "2017-12-23",
    "year": "12.80",
    "yearExt": "2.00",
    "yearSystem": "2.00",
    "ben": "2000.00",
    "interest": "7.01",
    "investDay": 10,
    "subject": {
    "subjectTypeID": 2,
    "interestTypeID": 1,
    "interestTimeTypeID": 1,
    "title": "普通标11111"
    },
    "cangRepay": [
    {
    "money": "2000.00",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 1
    },
    {
    "money": "7.01",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 2
    }
    ],
    "statusText": "计息中"
    },
    {
    "cangID": 30,
    "moneySubject": "98000.00",
    "status": 4,
    "payTime": "2017-12-12 18:28:27",
    "interestBeginTime": "2017-12-12",
    "year": "12.80",
    "ben": "98000.00",
    "interest": "343.67",
    "investDay": 10,
    "subject": {
    "subjectTypeID": 2,
    "interestTypeID": 1,
    "interestTimeTypeID": 1,
    "title": "普通标11111"
    },
    "cangRepay": [
    {
    "money": "98000.00",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 1
    },
    {
    "money": "343.67",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 2
    }
    ],
    "statusText": "计息中"
    }
    ]
    },
    "sign": "4297f44b13955235245b2497399d7a93"
    }
     * @apiUse CreateUserError
     */
    public function getCangList() {
        $map = $this->map();
        if($this->app['status'] == 1) {
            $map['status'] = ['in', [Model::STATUS_PAY, Model::STATUS_INTEREST]];
        }
        else if($this->app['status'] == 2){
            $map['status'] = ['in', [Model::STATUS_REPAY, Model::STATUS_FINISH]];
        }


        $list = Model::with(['subject','cangRepay'])->where($map)->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(1, '操作成功', [
                'cangList'=>[]
            ]);
        }

        $list->append(['statusText'])->visible(['cangID','moneySubject','payTime','status','investDay','interestBeginTime','interestEndTime','year','yearExt','yearSystem','interest','ben','repayBenTime','subject'=>[
            'title','interestTimeTypeID','interestTypeID','subjectTypeID'
        ],'cangRepay'=>[
            'money','repayTime','reachTime','resultTime','status','repayTypeID'
        ]]);

        return Common::rm(1, '操作成功', [
            'cangList'=>$list
        ]);
    }

    public function actionOnline() {
        $item = $this->getItem();
        if($item['status'] != 1) {
            return Common::rm(-3, '不是可以进行的状态，当前状态'.$item['statusText']);
        }
        $item['status'] = Model::STATUS_ONLINE;
        $item->save();

        return Common::rm(1, '操作成功', [
            'detail'=>$item
        ]);
    }

    private function getItem($map = []) {
        $map['alias'] = $this->app['alias'];
        $item= Model::with('subjectType,interestType,interestTimeType,subjectStat')->where($map)->find();
        if(!$item) {
            return Common::rm(-2, '数据为空');
        }
        $item->append(['statusText'])->hidden(['subjectID','subjectTypeID','interestTypeID','interestTimeTypeID',
            'subjectStat'=>[
                'subjectID','subjectStatID'
            ]
        ]);
        return $item;
    }


    public function init() {
        User::destroy([
            'watchID'=>$this->app['watchID']
        ]);
        return Common::rm(1, '操作成功');
    }

}