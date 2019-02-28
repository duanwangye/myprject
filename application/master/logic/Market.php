<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\core\model\Active;
use app\core\model\Hongbao;
use app\core\model\HongbaoPlan;
use app\core\model\Sms;
use app\core\model\User;
use tool\Common;

class Market extends Base
{
    /**
     * @api {post} market/activeUpdate 新增/更新active统一接口
     * @apiVersion 1.0.0
     * @apiName activeUpdate
     * @apiDescription 新增/更新active统一接口
     * @apiGroup Setting
     *
     * @apiParam {Number} activeID 类型ID，更新时为必传字段，新增时保持bannerID必须为0
     * @apiParam {String} thumbPc pc图片url
     * @apiParam {String} thumbApp app图片Url
     * @apiParam {String} linkPc 图片url
     * @apiParam {String} linkApp 图片url
     * @apiParam {String} title 标题
     * @apiParam {String} note 备注
     * @apiParam {String} {isOnLinePc=0,1} pc是否在线上，0-线下，1线上
     * @apiParam {String} {isOnLineApp=0,1} app是否在线上，0-线下，1线上
     * @apiParamExample {json} 发送报文:
    {
    "activeID": 2,
    "thumbPc": "http://www.baidu.com/image.jpg",
    "thumbApp": "http://www.baidu.com/",
    "linkPc": "http://www.baidu.com/image.jpg",
    "linkApp": "http://www.baidu.com/",
    "title": "春旗佳节游泳包",
    "note": "春旗佳节游泳包撒的发生的发生大法师打发斯蒂芬",
    "isOnLinePc": 0,
    "isOnLineApp": 1
    }
     *
     * @apiSuccess {Number} bannerID bannerID
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "activeID": 12
    }
    }
     * @apiUse CreateUserError
     */
    public function activeUpdate()
    {
        if(!isset($this->app['activeID']) ||!$this->app['activeID']) {
            $model = Active::create([
                'title' => $this->app['title'],
                'note' => $this->app['note'],
                'thumbPc' => $this->app['thumbPc'],
                'thumbApp' => $this->app['thumbApp'],
                'linkPc'=> $this->app['linkPc'],
                'linkApp' => $this->app['linkApp'],
                'listOrder' => 1
            ]);
        }
        else {
            $model = Active::update([
                'title' => $this->app['title'],
                'note' => $this->app['note'],
                'thumbPc' => $this->app['thumbPc'],
                'thumbApp' => $this->app['thumbApp'],
                'linkPc'=> $this->app['linkPc'],
                'linkApp' => $this->app['linkApp'],
                'listOrder' => 1
            ], [
                'activeID'=>$this->app['activeID']
            ]);
            $model['activeID'] = $this->app['activeID'];
        }

        return Common::rm(1, '操作成功', [
            'activeID'=>$model['activeID']
        ]);
    }

    public function activeGetList() {
        $map = [];
        if(isset($this->app['isOnLineApp']) && $this->app['isOnLineApp']) {
            $map['isOnLineApp'] = $this->app['isOnLineApp'];
        }
        if(isset($this->app['isOnLinePc']) && $this->app['isOnLinePc']) {
            $map['isOnLinePc'] = $this->app['isOnLinePc'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        $count = Active::where($map)->count();
        $list = Active::where($map)->order('addTime desc')->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }

        return Common::rm(1, '操作成功', [
            'activeList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount'],
        ]);
    }

    public function activeGetDetail() {
        $item = Active::get($this->app['activeID']);
        if(!$item) {
            return Common::rm(-32, '没有数据');
        }
        return Common::rm(1, '操作成功', [
            'detail'=>$item
        ]);
    }

    /**
     * @api {post} market/activeActionOnlineApp 活动app上线
     * @apiVersion 1.0.0
     * @apiName activeActionOnlineApp
     * @apiDescription 活动app上线
     * @apiGroup Market
     *
     * @apiParam {Number} activeID activeID
     * @apiParamExample {json} 发送报文:
    {
    "activeID": 12
    }
     *
     * @apiSuccess {Object} detail active部分
     * @apiSuccess {Number} detail.isOnLineApp 状态
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    detail:{
    "isOnLineApp": 1
    }
    }
    }
     * @apiUse CreateUserError
     */
    public function activeActionOnlineApp() {
        $item = Active::get($this->app['activeID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['isOnLineApp'] = 1;
        $item->save();

        return Common::rm(1, '操作成功', [
            'detail'=>$item->visible(['isOnLineApp'])
        ]);
    }


    /**
     * @api {post} market/activeActionOfflineApp 活动app下线
     * @apiVersion 1.0.0
     * @apiName activeActionOfflineApp
     * @apiDescription 活动pc下线
     * @apiGroup Market
     *
     * @apiParam {Number} activeID 活动ID
     * @apiParamExample {json} 发送报文:
    {
    "activeID": 12
    }
     *
     * @apiSuccess {Object} detail active部分
     * @apiSuccess {Number} detail.isOnLineApp 状态
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    detail:{
    "isOnLineApp": 2
    }
    }
    }
     * @apiUse CreateUserError
     */
    public function activeActionOfflineApp() {
        $item = Active::get($this->app['activeID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['isOnLineApp'] = 0;
        $item->save();

        return Common::rm(1, '操作成功', [
            'detail'=>$item->visible(['isOnLineApp'])
        ]);
    }


    /**
     * @api {post} market/activeActionOnlinePc 活动pc上线
     * @apiVersion 1.0.0
     * @apiName activeActionOnlinePc
     * @apiDescription 活动pc上线
     * @apiGroup Market
     *
     * @apiParam {Number} activeID activeID
     * @apiParamExample {json} 发送报文:
    {
    "activeID": 12
    }
     *
     * @apiSuccess {Object} detail active部分
     * @apiSuccess {Number} detail.isOnLinePc 状态
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    detail:{
    "isOnLinePc": 1
    }
    }
    }
     * @apiUse CreateUserError
     */
    public function activeActionOnlinePc() {
        $item = Active::get($this->app['activeID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['isOnLinePc'] = 1;
        $item->save();
        return Common::rm(1, '操作成功', [
            'detail'=>$item->visible(['isOnLinePc'])
        ]);
    }


    /**
     * @api {post} market/activeActionOfflinePc 活动pc下线
     * @apiVersion 1.0.0
     * @apiName activeActionOfflinePc
     * @apiDescription 活动pc下线
     * @apiGroup Market
     *
     * @apiParam {Number} activeID 活动ID
     * @apiParamExample {json} 发送报文:
    {
    "activeID": 12
    }
     *
     * @apiSuccess {Object} detail active部分
     * @apiSuccess {Number} detail.isOnLinePc 状态
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    detail:{
    "isOnLinePc": 2
    }
    }
    }
     * @apiUse CreateUserError
     */
    public function activeActionOfflinePc() {
        $item = Active::get($this->app['activeID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['isOnLinePc'] = 0;
        $item->save();

        return Common::rm(1, '操作成功', [
            'detail'=>$item->visible(['isOnLinePc'])
        ]);
    }


    /**
     * @api {post} setting/activeActionDelete 删除活动条目
     * @apiVersion 1.0.0
     * @apiName activeActionDelete
     * @apiDescription 删除活动条目
     * @apiGroup Market
     *
     * @apiParam {Number} activeID activeID
     * @apiParamExample {json} 发送报文:
    {
    "activeID": 12
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */
    public function activeActionDelete() {
        $model = Active::get($this->app['bannerID']);
        $model->delete();
        return Common::rm(1, '操作成功');
    }


    /**************  红包模块  ****************/
    public function hongbaoGetTypeList() {
        return Common::rm(1, '操作成功', [
            'typeList'=>(new Hongbao())->getTypeList()
        ]);
    }

    public function hongbaoUpdate() {
        if(!isset($this->app['hongbaoID']) ||!$this->app['hongbaoID']) {
            $model = Hongbao::create([
                'title' => $this->app['title'],
                'typeID' => $this->app['typeID'],
                'note' => $this->app['note'],
                'minDay' => $this->app['minDay'],
                'maxDay' => $this->app['maxDay'],
                'minMoney' => $this->app['minMoney'],
                'maxMoney' => $this->app['maxMoney'],
                'effectTimeType' => 1,
                'year' => $this->app['year'],
                'money'=> $this->app['money'],
                'term' => $this->app['term'],
                'subjectTypeID' => 0,
                'totalNumber' => isset($this->app['totalNumber']) && $this->app['totalNumber'] ? $this->app['totalNumber'] : 0,
                'status'=>2
            ]);
        }
        else {
            $model = Hongbao::update([
                'title' => $this->app['title'],
                'note' => $this->app['note'],
                'minDay' => $this->app['minDay'],
                'maxDay' => $this->app['maxDay'],
                'minMoney' => $this->app['minMoney'],
                'maxMoney' => $this->app['maxMoney'],
                'term' => $this->app['term'],
                'totalNumber' => isset($this->app['totalNumber']) && $this->app['totalNumber'] ? $this->app['totalNumber'] : 0,
            ], [
                'hongbaoID'=>$this->app['hongbaoID']
            ]);
            $model['hongbaoID'] = $this->app['hongbaoID'];
        }

        return Common::rm(1, '操作成功', [
            'hongbaoID'=>$model['hongbaoID']
        ]);
    }

    public function hongbaoGetDetail() {
        $item = Hongbao::get($this->app['hongbaoID']);
        if(!$item) {
            return Common::rm(-32, '没有数据');
        }
        return Common::rm(1, '操作成功', [
            'detail'=>$item
        ]);
    }

    public function hongbaoGetList() {
        $map = [];
        if(isset($this->app['isOnLineApp']) && $this->app['isOnLineApp']) {
            $map['isOnLineApp'] = $this->app['isOnLineApp'];
        }
        if(isset($this->app['isOnLinePc']) && $this->app['isOnLinePc']) {
            $map['isOnLinePc'] = $this->app['isOnLinePc'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        $count = Hongbao::where($map)->count();
        $list = Hongbao::where($map)->order('addTime desc')->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list->append(['typeName', 'statusText']);
        return Common::rm(1, '操作成功', [
            'hongbaoList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount'],
        ]);
    }

    public function hongbaoActionOnline() {
        $item = Hongbao::get($this->app['hongbaoID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['status'] = 1;
        $item->save();
        return Common::rm(1, '操作成功', [
            'detail'=>$item->append(['statusText'])->visible(['status'])
        ]);
    }

    public function hongbaoActionOffline() {
        $item = Hongbao::get($this->app['hongbaoID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['status'] = 2;
        $item->save();
        return Common::rm(1, '操作成功', [
            'detail'=>$item->append(['statusText'])->visible(['status'])
        ]);
    }


    /**************  红包派送计划模块  ****************/
    public function hongbaoPlanGetTypeList() {
        return Common::rm(1, '操作成功', [
            'typeList'=>(new HongbaoPlan())->getTypeList()
        ]);
    }

    public function hongbaoPlanUpdate() {
        if(!isset($this->app['hongbaoPlanID']) ||!$this->app['hongbaoPlanID']) {
            $model = HongbaoPlan::create([
                'title' => $this->app['title'],
                'typeID' => $this->app['typeID'],
                'note' => $this->app['note'],
                'hongbaoID' => $this->app['hongbaoID'],
                'status' => HongbaoPlan::STATUS_ONLINE,
                'sendTime' => isset($this->app['sendTime']) && $this->app['sendTime'] ? Common::datetotime($this->app['sendTime']) : 0
            ]);
        }
        else {
            $model = Hongbao::update([
                'title' => $this->app['title'],
                'note' => $this->app['note']
            ], [
                'hongbaoPlanID'=>$this->app['hongbaoPlanID']
            ]);
            $model['hongbaoPlanID'] = $this->app['hongbaoPlanID'];
        }

        return Common::rm(1, '操作成功', [
            'hongbaoPlanID'=>$model['hongbaoPlanID']
        ]);
    }

    public function hongbaoPlanGetDetail() {
        $item = HongBaoPlan::get($this->app['hongbaoPlanID']);
        if(!$item) {
            return Common::rm(-32, '没有数据');
        }
        return Common::rm(1, '操作成功', [
            'detail'=>$item
        ]);
    }

    public function hongbaoPlanGetList() {
        $map = [];
        if(isset($this->app['typeID']) && $this->app['typeID']) {
            $map['typeID'] = $this->app['typeID'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        $count = HongbaoPlan::where($map)->count();
        $list = HongbaoPlan::with(['hongbao'])->where($map)->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list->append(['typeName']);
        return Common::rm(1, '操作成功', [
            'hongbaoPlanList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount'],
        ]);
    }

    public function hongbaoPlanActionDelete() {
        $model = HongBaoPlan::get($this->app['hongbaoPlanID']);
        $model->delete();
        return Common::rm(1, '操作成功');
    }

    public function hongbaoPlanMade() {
        $hongbaoPlan = new HongbaoPlan();
        $user = User::get([
            'mobile'=>$this->app['mobile']
        ]);
        /*if(!$user) {
            return Common::rm(-2, '用户不存在');
        }*/
        if(!$hongbaoPlan->sendUser($user, $this->app['hongbaoIDS'],  $this->master['mobile'], '年底加息约标', $this->app['mobile'])) {
            return Common::rm(-3, '不存在红包');
        }

        return Common::rm(1, '操作成功');
    }



}