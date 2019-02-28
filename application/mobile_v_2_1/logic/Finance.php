<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile_v_2_1\logic;

use app\core\exception\AppException;
use app\core\model\Subject;
use app\core\model\SubjectStat;
use app\core\model\UserAccount;
use app\core\model\UserFinance as Model;
use app\core\service\Pay;
use think\Db;
use think\Exception;
use think\Log;
use tool\Common;

class Finance extends Base
{



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
    "app": {
    "status": 1
    },
    "sign": "4297f44b13955235245b2497399d7a93"
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
            $map['status'] = Model::STATUS_REPAY;
        }


        $list = Model::with(['subject','cangRepay'])->where($map)->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }

        $list->append(['statusText'])->visible(['cangID','moneySubject','payTime','status','investDay','interestBeginTime','interestEndTime','year','yearExt','interest','ben','repayBenTime','subject'=>[
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