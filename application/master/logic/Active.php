<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\core\model\Banner;
use tool\Common;

class Active extends Base
{
    /**
     * @api {post} setting/bannerUpdate 新增/更新banner统一接口
     * @apiVersion 1.0.0
     * @apiName update
     * @apiDescription 新增/更新banner统一接口
     * @apiGroup Setting
     *
     * @apiParam {Number} bannerID 类型ID，更新时为必传字段，新增时保持bannerID必须为0
     * @apiParam {Number} {clientType=1,2} 客户端类型，1为app，2为pc
     * @apiParam {Number} {status=1,2} 状态，1为上线，2为下线
     * @apiParam {String} thumb 图片url
     * @apiParam {String} title 标题
     * @apiParam {String} link 点击图片之后跳转
     * @apiParamExample {json} 发送报文:
    {
    "bannerID": 2,
    "clientType": 1,
    "status": 2,
    "thumb": "http://www.baidu.com/image.jpg",
    "link": "http://www.baidu.com/"
    }
     *
     * @apiSuccess {Number} bannerID bannerID
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "bannerID": 12
    }
    }
     * @apiUse CreateUserError
     */
    public function bannerUpdate()
    {
        if(!isset($this->app['bannerID']) ||!$this->app['bannerID']) {
            $model = Banner::create([
                'title' => $this->app['title'],
                'clientType' => $this->app['clientType'],
                'status'=> 2,
                'thumb' => $this->app['thumb'],
                'link' => $this->app['link']
            ]);
        }
        else {
            $model = Banner::update([
                'title' => $this->app['title'],
                'clientType' => $this->app['clientType'],
                'status'=> 2,
                'thumb' => $this->app['thumb'],
                'link' => $this->app['link']
            ], [
                'bannerID'=>$this->app['bannerID']
            ]);
            $model['bannerID'] = $this->app['bannerID'];
        }

        return Common::rm(1, '操作成功', [
            'bannerID'=>$model['bannerID']
        ]);
    }

    public function getBannerList() {
        $map = [];
        if(isset($this->app['clientType']) && $this->app['clientType']) {
            $map['clientType'] = $this->app['clientType'];
        }
        if(isset($this->app['clientType']) && $this->app['status']) {
            $map['status'] = $this->app['status'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        $count = Banner::where($map)->count();
        $list = Banner::where($map)->order('addTime desc')->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list->append(['statusText','clientTypeText']);

        return Common::rm(1, '操作成功', [
            'bannerList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount'],
        ]);
    }

    public function bannerGetDetail() {
        $item = Banner::get($this->app['bannerID']);
        if(!$item) {
            return Common::rm(-32, '没有数据');
        }
        return Common::rm(1, '操作成功', [
            'detail'=>$item
        ]);
    }

    /**
     * @api {post} setting/bannerActionOnline banner上线
     * @apiVersion 1.0.0
     * @apiName bannerActionOnline
     * @apiDescription banner上线
     * @apiGroup Setting
     *
     * @apiParam {Number} bannerID bannerID
     * @apiParamExample {json} 发送报文:
    {
    "bannerID": 12
    }
     *
     * @apiSuccess {Object} detail banner部分
     * @apiSuccess {Number} detail.status 更新bannerID
     * @apiSuccess {String} detail.statusText 更新bannerID
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    detail:{
    "status": 1,
    "statusText": "上线"
    }
    }
    }
     * @apiUse CreateUserError
     */
    public function bannerActionOnline() {
        $item = Banner::get($this->app['bannerID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['status'] = Banner::STATUS_ONLINE;
        $item->save();

        return Common::rm(1, '操作成功', [
            'detail'=>$item->append(['statusText'])->visible(['status'])
        ]);
    }


    /**
     * @api {post} setting/bannerActionOffline banner下线
     * @apiVersion 1.0.0
     * @apiName bannerActionOffline
     * @apiDescription banner下线
     * @apiGroup Setting
     *
     * @apiParam {Number} bannerID bannerID
     * @apiParamExample {json} 发送报文:
    {
    "bannerID": 12
    }
     *
     * @apiSuccess {Object} detail banner部分
     * @apiSuccess {Number} detail.status 更新bannerID
     * @apiSuccess {String} detail.statusText 更新bannerID
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    detail:{
    "status": 2,
    "statusText": "下线"
    }
    }
    }
     * @apiUse CreateUserError
     */
    public function bannerActionOffline() {
        $item = Banner::get($this->app['bannerID']);
        if(!$item) {
            return Common::rm(-2, '没有数据');
        }
        $item['status'] = Banner::STATUS_OFFLINE;
        $item->save();

        return Common::rm(1, '操作成功', [
            'detail'=>$item->append(['statusText'])->visible(['status'])
        ]);
    }


    /**
     * @api {post} setting/bannerActionDelete 删除banner条目
     * @apiVersion 1.0.0
     * @apiName bannerActionDelete
     * @apiDescription 删除banner条目
     * @apiGroup Subject
     *
     * @apiParam {Number} bannerID bannerID
     * @apiParamExample {json} 发送报文:
    {
    "bannerID": 12
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */
    public function bannerActionDelete() {
        $model = Model::get($this->app['bannerID']);
        $model->delete();
        return Common::rm(1, '操作成功');
    }
}