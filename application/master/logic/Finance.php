<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\core\model\InterestType;
use app\core\model\SubjectStat;
use app\core\model\SubjectType;
use app\core\model\Subject as Model;
use app\core\model\User;
use app\core\model\Cang;
use think\Db;
use tool\Common;

class Finance extends Base
{
    /**
     * @api {post} subject/getSubjectTypeList 得到产品类型列表
     * @apiVersion 1.0.0
     * @apiName getSubjectTypeList
     * @apiDescription 得到产品类型列表
     * @apiGroup Subject
     *
     * @apiSuccess {Object[]} subjectTypeList 产品类型列表
     * @apiSuccess {Number} subjectTypeList.subjectTypeID 产品类型ID
     * @apiSuccess {String} subjectTypeList.name 产品名称
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "subjectTypeList": [
    {
    "subjectTypeID":1,
    "name":"满标计息"
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */
    public function getSubjectTypeList() {
        $subjectTypeList = SubjectType::all();
        return Common::rm(1, '操作成功', [
            'subjectTypeList'=>$subjectTypeList
        ]);
    }

    /**
     * @api {post} subject/getInterestTypeList 得到计息类型列表
     * @apiVersion 1.0.0
     * @apiName getInterestTypeList
     * @apiDescription 得到计息类型列表
     * @apiGroup Subject
     *
     * @apiSuccess {Object[]} interestTypeList 计息类型列表
     * @apiSuccess {Number} interestTypeList.interestTypeID 计息类型ID
     * @apiSuccess {String} interestTypeList.name 计息类型名称
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "interestTypeList": [
    {
    "interestTypeID": 1,
    "name": "一次付本息"
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */
    public function getInterestTypeList() {
        $interestTypeList = InterestType::all();
        return Common::rm(1, '操作成功', [
            'interestTypeList'=>$interestTypeList
        ]);
    }

    /**
     * @api {post} subject/getStatusList 得到产品状态列表
     * @apiVersion 1.0.0
     * @apiName getStatusList
     * @apiDescription 得到产品状态列表
     * @apiGroup Subject
     *
     *
     * @apiSuccess {Object[]} statusList 状态列表
     * @apiSuccess {Number} statusList.status 状态ID
     * @apiSuccess {String} statusList.statusText 状态名称
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "statusList": [
    {
    "status":1,
    "statusText":"注册"
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */
    public function getStatusList() {
        $statusList = Model::STATUSS;
        return Common::rm(1, '操作成功', [
            'statusList'=>$statusList
        ]);
    }

    /**
     * @api {post} subject/getStatusLoanList 得到产品放款状态列表
     * @apiVersion 1.0.0
     * @apiName getStatusLoanList
     * @apiDescription 得到产品放款状态列表
     * @apiGroup Subject
     *
     * @apiSuccess {Object[]} statusLoanList 放款状态列表
     * @apiSuccess {Number} statusLoanList.statusLoan 放款状态ID
     * @apiSuccess {String} statusLoanList.statusLoanText 放款状态名称
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "statusLoanList": [
    {
    "statusLoan":1,
    "statusLoanText":"已放款"
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */
    public function getStatusLoanList() {
        $statusLoanList = Model::STATUS_LOANS;
        return Common::rm(1, '操作成功', [
            'statusLoanList'=>$statusLoanList
        ]);
    }

    /**
     * @api {post} subject/getFinanceList 条件得到产品列表
     * @apiVersion 1.0.0
     * @apiName getFinanceList
     * @apiDescription 条件得到产品列表
     * @apiGroup Finance
     *
     * @apiParam {String} [overTimeFrom] 到期时间范围开始
     * @apiParam {String} [overTimeTo] 到期时间范围结束
     * @apiParam {Number} [status] 产品状态，为0为全部状态
     * @apiParam {Number} [statusLoan] 放款状态，为0为全部状态
     * @apiParam {Number} [pageIndex=1] 页码，从1开始
     * @apiParam {Number} [pageItemCount=10] 每页条数
     * @apiParam {String} [keyword] 关键字
     * @apiParamExample {json} 发送报文:
    {
    "overTimeFrom": "2016-12-20",
    "overTimeTo": "2018-12-20",
    "status": 0,
    "statusLoan": 0,
    "pageIndex": 1
    }
     *
     * @apiSuccess {Object[]} subjectList 产品列表
     * @apiSuccess {Number} subjectList.subjectID 产品ID
     * @apiSuccess {Number} subjectList.term 投标期数
     * @apiSuccess {String} subjectList.title 标题
     * @apiSuccess {String} subjectList.price 总金额
     * @apiSuccess {String} subjectList.year 年化
     * @apiSuccess {String} subjectList.basePrice 起投金额
     * @apiSuccess {String} subjectList.addTime 产品添加时间
     * @apiSuccess {String} subjectList.updateTime 更新时间
     * @apiSuccess {String} subjectList.baseMaxPrice 最大投资额
     * @apiSuccess {String} subjectList.overTime 产品到期时间，新增时为必传字段，更新时无效
     * @apiSuccess {String} subjectList.fullTime 满标时间
     * @apiSuccess {String} subjectList.overTime 产品到期时间
     * @apiSuccess {String} subjectList.repayTime 还款时间
     * @apiSuccess {String} subjectList.reachTime 到账时间
     * @apiSuccess {String} subjectList.status 产品状态,
     * @apiSuccess {String} subjectList.statusText 产品状态描述
     * @apiSuccess {String} subjectList.statusLoan 放款状态,
     * @apiSuccess {String} subjectList.statusLoanText 放款状态描述
     * @apiSuccess {Object} subjectList.subjectType 项目类型
     * @apiSuccess {Number} subjectList.subjectType.subjectTypeID 新手理财ID
     * @apiSuccess {String} subjectList.subjectType.name 名称
     * @apiSuccess {Object} subjectList.interestType 计息类型
     * @apiSuccess {Number} subjectList.interestType.interestTypeID 计息类型ID
     * @apiSuccess {String} subjectList.interestType.name 名称
     * @apiSuccess {Object} subjectList.interestTimeType 计息时间类型
     * @apiSuccess {Number} subjectList.interestTimeType.interestTypeID 计息时间类型ID
     * @apiSuccess {String} subjectList.interestTimeType.name 名称
     * @apiSuccess {Number} subjectList.isIndexApp 是否为app首页
     * @apiSuccess {Number} subjectList.isIndexPc 是否为PC首页
     * @apiSuccess {Number} subjectList.loanID 借款ID
     * @apiSuccess {Object} subjectList.subjectStat 投资统计
     * @apiSuccess {String} subjectList.subjectStat.moneyTotalInvest 总共已投资
     * @apiSuccess {Number} subjectList.subjectStat.timesInvest 已投资次数
     * @apiSuccess {Number} count 查询到条目总数
     * @apiSuccess {Number} pageItemCount 每页条数
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "subjectList": [
    {
    "subjectID": 33,
    "term": 15,
    "title": "新手标4567",
    "price": "100000.00",
    "year": "12.00",
    "basePrice": "1000.00",
    "baseMaxPrice": "100000.00",
    "addTime": "2017-12-18 17:38:30",
    "updateTime": "2017-12-18 19:24:41",
    "isIndexApp": 1,
    "isIndexPc": 0,
    "status": 2,
    "alias": "20171218173830mlh3vY",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2018-01-02",
    "fullTime": "2015-01-01 22:33:33",
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 1,
    "name": "满标计息"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "发布审核中",
    "repayTime": "2018-01-02",
    "reachTime": "2018-01-03",
    "statusLoanText": "未到放款时间"
    }
    ],
    "count": 18,
    "pageItemCount": 10
    }
    }
     * @apiUse CreateUserError
     */
    public function getFinanceList() {
        $map = [];
        if( isset($this->app['addTimeTo']) &&
            isset($this->app['addTimeFrom'])) {
            $map['addTime'] = ['between time', [$this->app['addTimeFrom'],$this->app['addTimeTo']]];
        }
        /*if($this->app['repayTimeFrom'] && $this->app['repayTimeTo']) {
            $map['repayTime'] = ['between', [$this->app['repayTimeFrom'],$this->app['repayTimeTo']]];
        }
        if($this->app['repayTimeFrom'] && $this->app['repayTimeTo']) {
            $map['repayTime'] = ['between', [$this->app['repayTimeFrom'],$this->app['repayTimeTo']]];
        }*/
        if(isset($this->app['subjectTypeID']) && $this->app['subjectTypeID']) {
            $map['subjectTypeID'] = $this->app['subjectTypeID'];
        }
        if(isset($this->app['status']) && $this->app['status']) {
            $map['status'] = $this->app['status'];
        }

        if(isset($this->app['statusLoan']) && $this->app['statusLoan']) {
            $map['statusLoan'] = $this->app['statusLoan'];
        }

        if(isset($this->app['keyword']) && $this->app['keyword']) {
            $map['subjectTypeID|title'] = ['like', '%'.$this->app['keyword'].'%'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }
        $count = Model::where($map)->count();
        $list = Model::with('subjectType,interestType,interestTimeType,subjectStat')->where($map)->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list->append(['statusText','repayTime','reachTime','statusLoanText'])->hidden(['multiplePrice','operation','yearSystem','beginTime','endTime','subjectTypeID','interestTypeID','interestTimeTypeID',
            'subjectStat'=>[
                'subjectID','subjectStatID'
            ]
        ]);

        return Common::rm(1, '操作成功', [
            'subjectList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount']
        ]);
    }

    /**
     * @api {post} subject/getDetail 得到产品详细
     * @apiVersion 1.0.0
     * @apiName getDetail
     * @apiDescription 得到产品详细，返回参数释义请参照产品列表
     * @apiGroup Subject
     *
     * @apiParam {Number} subjectID 产品ID
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 12
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "detail": {
    "term": 15,
    "title": "新手标4567",
    "price": "100000.00",
    "year": "12.00",
    "basePrice": "1000.00",
    "baseMaxPrice": "100000.00",
    "addTime": "2017-12-18 17:38:30",
    "updateTime": "2017-12-20 15:25:24",
    "isIndexApp": 1,
    "isIndexPc": 0,
    "status": 3,
    "alias": "20171218173830mlh3vY",
    "loanID": 0,
    "overTime": "2018-01-02",
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 1,
    "name": "一次性还本付息"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "抢购中",
    "repayTime": "2018-01-02"
    }
    }
    }
     * @apiUse CreateUserError
     */
    public function getDetail() {
        $item = $this->getItem();
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item->hidden(['operation','fullTime','hongbao','multiplePrice','yearSystem','beginTime','endTime']);
        return Common::rm(1, '操作成功', [
            'detail'=>$item
        ]);
    }

    /**
     * @api {post} subject/update 新增/更新产品统一接口
     * @apiVersion 1.0.0
     * @apiName update
     * @apiDescription 新增/更新产品统一接口
     * @apiGroup Subject
     *
     * @apiParam {Number} subjectID 产品类型ID，更新时为必传字段，新增时保持subjectID必须为0
     * @apiParam {Number} subjectTypeID 产品类型ID，新增时为必传字段，更新时无效
     * @apiParam {Number} interestTypeID 计息类型ID，新增时为必传字段，更新时无效
     * @apiParam {Number} term 投标期数，新增时为必传字段，更新时无效
     * @apiParam {String} title 标题
     * @apiParam {String} price 总金额，新增时为必传字段，更新时无效
     * @apiParam {String} year 年化，新增时为必传字段，更新时无效
     * @apiParam {String} basePrice 起投金额
     * @apiParam {String} baseMaxPrice 最大投资额
     * @apiParam {String} overTime 产品到期时间，新增时为必传字段，更新时无效
     * @apiParam {Number} isIndexApp 是否为app首页
     * @apiParam {Number} isIndexPc 是否为PC首页
     * @apiParam {Number} loanID 借款ID，新增时为必传字段，更新时无效
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 2,
    "subjectTypeID": 12,
    "interestTypeID": 13,
    "term": 120,
    "year": "12.8",
    "price": "100000.00",
    "basePrice": "100.00",
    "baseMaxPrice": "100000.00",
    "overTime": "2014-10-10",
    "isIndexApp": 1,
    "isIndexPc": 1,
    "loanID": 12
    }
     *
     * @apiSuccess {Number} subjectID 更新产品ID
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "subjectID": 12
    }
    }
     * @apiUse CreateUserError
     */
    public function update()
    {
        if(!isset($this->app['subjectID']) ||!$this->app['subjectID']) {
            $model = Model::create([
                'subjectTypeID' => $this->app['subjectTypeID'],
                'interestTypeID' => $this->app['interestTypeID'],
                'interestTimeTypeID'=> Model::getDefaultInterestTimeType(),
                'term' => $this->app['term'],
                'title' => $this->app['title'],
                'price'=>$this->app['price'],
                'year' => $this->app['year'],
                /*'yearSystem' => $this->app['yearSystem'],*/
                'basePrice'=> $this->app['basePrice'],
                'baseMaxPrice' => $this->app['baseMaxPrice'],
                /*'multiplePrice' => isset($this->app['multiplePrice']) ? $this->app['multiplePrice'] : 0,*/
                /*'beginTime'=>Common::datetotime($this->app['beginTime']),
                'endTime' => Common::datetotime($this->app['endTime']),*/
                /*'repayBenTime' => Common::datetotime($this->app['repayTime']),
                'repayInterestTime' => $this->app['interestTypeID'] == 2 ? 0 : Common::datetotime($this->app['repayTime']),*/
                'overTime' => Common::datetotime($this->app['overTime']),
                'isIndexApp'=>$this->app['isIndexApp'],
                'isIndexPc'=>$this->app['isIndexPc'],
                'status'=>Model::STATUS_ONLINE_CHECK,
                'alias'=>Model::createAlias(),
                /*'loanID'=>$this->app['loanID'],*/
                'statusLoan'=>1
            ]);

            SubjectStat::create([
                'subjectID' => $model['subjectID']
            ]);
        }
        else {
            $model = Model::update([
                'title' => $this->app['title'],
                'basePrice'=> $this->app['basePrice'],
                'baseMaxPrice' => $this->app['baseMaxPrice'],
                'isIndexApp'=>$this->app['isIndexApp'],
                'isIndexPc'=>$this->app['isIndexPc']
            ], [
                'subjectID'=>$this->app['subjectID']
            ]);
            $model['subjectID'] = $this->app['subjectID'];
        }

        return Common::rm(1, '操作成功', [
            'subjectID'=>$model['subjectID']
        ]);
    }

    public function getList() {
        $map = [];
        if($this->app['addTimeFrom'] && $this->app['addTimeTo']) {
            $map['addTime'] = ['between', [$this->app['addTimeFrom'],$this->app['addTimeTo']]];
        }
        if($this->app['repayTimeFrom'] && $this->app['repayTimeTo']) {
            $map['repayTime'] = ['between', [$this->app['repayTimeFrom'],$this->app['repayTimeTo']]];
        }
        if($this->app['repayTimeFrom'] && $this->app['repayTimeTo']) {
            $map['repayTime'] = ['between', [$this->app['repayTimeFrom'],$this->app['repayTimeTo']]];
        }
        if($this->app['subjectTypeID']) {
            $map['subjectTypeID'] = $this->app['subjectTypeID'];
        }
        if($this->app['status']) {
            $map['status'] = $this->app['status'];
        }

        $list = Model::with('subjectType,interestType,interestTimeType,subjectStat')->where($map)->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list->append(['statusText','repayTime','reachTime'])->hidden(['subjectID','subjectTypeID','interestTypeID','interestTimeTypeID',
            'subjectStat'=>[
                'subjectID','subjectStatID'
            ]
        ]);

        return Common::rm(1, '操作成功', [
            'list'=>$list
        ]);
    }

    /**
     * @api {post} subject/actionOnline A - 发布通过审核上线
     * @apiVersion 1.0.0
     * @apiName actionOnline
     * @apiDescription 发布通过审核上线
     * @apiGroup Subject
     *
     * @apiParam {Number} subjectID 产品ID
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 12
    }
     *
     * @apiSuccess {Number} subjectID 更新产品ID
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "status": 1,
    "statusText": "正在审核"
    }
    }
     * @apiUse CreateUserError
     */
    public function actionOnline() {
        $item = $this->getItem();
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        if($item['status'] != 2) {
            return Common::rm(-3, '不是可以进行的状态，当前状态'.$item['statusText']);
        }
        $item['status'] = Model::STATUS_ONLINE;
        $item->save();

        return Common::rm(1, '操作成功', [
            'detail'=>$item->append(['statusText'])->visible(['status'])
        ]);
    }

    /**
     * @api {post} subject/actionDelete 删除条目
     * @apiVersion 1.0.0
     * @apiName actionDelete
     * @apiDescription 删除条目
     * @apiGroup Subject
     *
     * @apiParam {Number} subjectID 产品ID
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 12
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */
    public function actionDelete() {
        $model = Model::get($this->app['subjectID']);
        if($model['status'] != 2) {
            return Common::rm(-3, '当前产品状态不允许删除');
        }
        $model->delete();
        return Common::rm(1, '操作成功');
    }

    /**
     * @api {post} subject/actionForged 虚拟认购
     * @apiVersion 1.0.0
     * @apiName actionForged
     * @apiDescription 虚拟认购
     * @apiGroup Subject
     *
     * @apiParam {Number} subjectID 产品ID
     * @apiParam {String} moneySubject 投资金额
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 12,
    "moneySubject":"100000.00"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */
    public function actionForged() {

        //第零步，判断该产品是否符合投资要求
        $model = Model::get($this->app['subjectID']);

        if($model['status'] == Model::STATUS_FULL) {
            return Common::rm(-4, '该商品已经满标，不能投资了');
        }

        if($model['status'] == Model::STATUS_ONLINE_CHECK || $model['status'] == Model::STATUS_LOCK) {
            return Common::rm(-5, '该商品已经下架，不能投资了');
        }

        if($model['basePrice'] > $this->app['moneySubject']) {
            return Common::rm(-6, '最低需投资'.$model['basePrice']);
        }
        if($model['baseMaxPrice'] < $this->app['moneySubject']) {
            return Common::rm(-7, '投资最大不得超出'.$model['baseMaxPrice']);
        }
        if($model->subjectStat['moneyTotalInvest'] + $this->app['moneySubject'] > $model['price']) {
            return Common::rm(-8, '投入金额过多，超出标的总金额，最多可投'.($model['price'] - $model->subjectStat['moneyTotalInvest']));
        }


        //第一步，得到一个虚拟账户
        $userList = User::where([
            'isForged'=>1
        ])->field(['userID'])->select();
        if($userList->isEmpty()) {
            return Common::rm(-3, '不存在虚拟账户');
        }
        $userIDS = array_column($userList->toArray(), 'userID');
        $userIDIndex = mt_rand(0, count($userIDS) - 1);
        $userID = $userIDS[$userIDIndex];
        $user = User::get($userID);


        //第二步，保存一个仓
        $cang = Cang::create([
            'subjectID' => $this->app['subjectID'],
            'userID' => $user['userID'],
            'moneySubject' => $this->app['moneySubject'],
            'ben' => $this->app['moneySubject'],
            'status'=>Cang::STATUS_PAY,
            'isForged'=>1
        ]);
        $cang['alias'] = Cang::createAlias($cang['cangID']);
        $cang->save();

        //第三步，对产品进行统计
        SubjectStat::where([
            'subjectID'=>$this->app['subjectID']
        ])->setInc('moneyTotalInvest', $this->app['moneySubject'] * 100);
        SubjectStat::where([
            'subjectID'=>$this->app['subjectID']
        ])->setInc('timesInvest');


        //第四步，判断是否满标
        if($model->subjectStat['moneyTotalInvest'] + $this->app['moneySubject'] == $model['price']) {
            //如果满标了，设置满标
            Model::setSubjectFull($model);
        }

        return Common::rm(1, '操作成功', [
            'detail'=>$this->getItem()
        ]);
    }

    private function getItem($map = []) {
        $map['subjectID'] = $this->app['subjectID'];
        $item= Model::with('subjectType,interestType,interestTimeType,subjectStat')->where($map)->find();
        if(!$item) {
            return false;
        }
        $item->append(['statusText','repayTime'])->hidden(['subjectID','subjectTypeID','interestTypeID','interestTimeTypeID',
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


    public function getUserByWatchID()
    {
        $model = User::get([
            'watchID'=>$this->app['watchID']
        ]);
        if(!$model) {
            return Common::rm(-1, '不存在');
        }
        $model->append([
            'userID'
        ])->visible([
            'status',
            'height',
            'weight',
            'gender',
            'trueName'
        ]);
        return Common::rm(1, '操作成功', [
            'user'=>$model
        ]);
    }
}