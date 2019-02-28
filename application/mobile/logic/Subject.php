<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile\logic;

use app\core\model\Loan;
use app\core\model\Cang;
use app\core\model\Subject as Model;

use tool\Common;

class Subject extends Base
{

    public function create()
    {
        //第一步，得到标的信息
        $subject = Subject::get($this->app['subjectID']);


        //第二步，新增cang
        //1、判断是否过认购期
        if(THINK_START_TIME >= $subject['endTime']) {
            return Common::rm(-3, '该商品已过认购期，不能购买了');
        }

        //2、判断是否满标了
        if($subject['status'] == Subject::STATUS_FULL) {
            return Common::rm(-4, '该商品已经满标，不能购买了');
        }

        //3、判断是否下架了
        if($subject['status'] == Subject::STATUS_OFFLINE) {
            return Common::rm(-5, '该商品已经下架，不能购买了');
        }

        $model = Model::create([
            'subjectID' => $subject['subjectID'],
            'userID' => $this->app['userID'],
            'moneyBuy' => $this->app['moneyBuy'],
            'money' => $this->app['moneyBuy'],
            'status'=>Model::STATUS_UNPAY
        ]);

        $model['alias'] = Model::createAlias($model['cangID']);
        $model->save();


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

    public function getSubjectList() {

        $map = [
            'status'=>['in', [Model::STATUS_ONLINE, Model::STATUS_FULL]],
            'isShow'=>1
        ];

        if(isset($this->app['status']) && $this->app['status']) {
            if($this->app['status'] == 1) {
                $map['status'] = Model::STATUS_ONLINE;
            }
            else if($this->app['status'] == 2) {
                $map['status'] = ['in', [Model::STATUS_FULL]];
            }
            else if($this->app['status'] == 3){
                $map['status'] = ['in', [Model::STATUS_REPAY]];
            }
        }



        if(isset($this->app['subjectTypeID']) && $this->app['subjectTypeID']) {
            $map['subjectTypeID'] = $this->app['subjectTypeID'];
        }

        if(isset($this->app['term']) && $this->app['term']) {
            $map['term'] = $this->app['term'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }


        $order = 'status,';
        if(isset($this->app['orderTerm']) && $this->app['orderTerm']) {
            if($this->app['orderTerm'] == 1) {
                $order .= 'term desc,';
            }
            else {
                $order .= 'term,';
            }
        }

        if(isset($this->app['orderYear']) && $this->app['orderYear']) {
            if($this->app['orderYear'] == 1) {
                $order .= 'year desc,';
            }
            else {
                $order .= 'year,';
            }
        }
        $order .= 'addTime desc';



        $this->app['pageItemCount'] = 10;

        $count = Model::where($map)->count();
        $list = Model::with('subjectType,interestType,interestTimeType,subjectStat')->where($map)->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order($order)->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list->append(['statusText','investDay', 'repayTime','reachTime','unit', 'interestBeginTime'])->hidden(['subjectTypeID','beginTime','endTime','multiplePrice','interestTypeID','interestTimeTypeID','isIndexApp','isIndexPc','operation','updateTime',
            'subjectStat'=>[
                'subjectID','subjectStatID'
            ]
        ])->toArray();

        foreach ($list as $k=>$item) {
            $list[$k]['contentUrl1'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=1' . '&######';
            $list[$k]['contentUrl2'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=2' . '&######';
            $list[$k]['contentUrl3'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=3' . '&######';
            $list[$k]['subjectType']['icon'] = isset($this->skin['subjectType_icon'][$list[$k]['subjectType']['subjectTypeID']]) ? $this->skin['subjectType_icon'][$list[$k]['subjectType']['subjectTypeID']] : '';
        }

        return Common::rm(1, '操作成功', [
            'subjectList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount']
        ]);
    }

    public function getSubjectDetail() {
        $item =Model::with('subjectType,interestType,interestTimeType,subjectStat')->where('subjectID', $this->app['subjectID'])->find();
        if(!$item) {
            return Common::rm(-2, '无数据');
        }
        $item->append(['statusText','investDay', 'repayTime','reachTime','unit', 'interestBeginTime'])->hidden(['subjectTypeID','beginTime','endTime','multiplePrice','interestTypeID','interestTimeTypeID','isIndexApp','isIndexPc','operation','updateTime',
            'subjectStat'=>[
                'subjectID','subjectStatID'
            ]
        ])->toArray();
        $item['contentUrl1'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=1' . '&######';
        $item['contentUrl2'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=2' . '&######';
        $item['contentUrl3'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=3' . '&######';
        return Common::rm(1, '操作成功', [
            'subjectDetail'=>$item
        ]);
    }


    public function getSubjectContent() {
        $list = Cang::with(['user'])->where([
            'subjectID'=>$this->app['subjectID']
        ])->order('addTime desc')->select();
        $cangList = [];
        if(!$list->isEmpty()) {
            $cangList = $list->toArray();
        }

        $subject = Model::get($this->app['subjectID']);
        $loan = null;
        if($subject) {
            $loan = Loan::get($subject['loanID']);
        }
        return Common::rm(1, '操作成功', [
            'cangList'=>$cangList,
            'loan'=>$loan
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

}