<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\core\model\Loan as Model;
use tool\Common;

class Loan extends Base
{
    /**
     * @api {post} loan/update 新增/更新loan统一接口
     * @apiVersion 1.0.0
     * @apiName update
     * @apiDescription 新增/更新loan统一接口
     * @apiGroup Loan
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
    public function update()
    {
        if(!isset($this->app['loanID']) ||!$this->app['loanID']) {
            $model = Model::create([
                'mobile' => $this->app['mobile'],
                'name' => $this->app['name'],
                'money' => $this->app['money'],
                'year' => $this->app['year'],
                'certType'=> $this->app['certType'],
                'certContent' => $this->app['certContent'],
                'pledge' => $this->app['pledge'],
                'financingEntity' => $this->app['financingEntity'],
                'usage' => $this->app['usage'],
                'status' => Model::STATUS_OK,
                'imageUrl' => $this->app['imageUrl'],
                'contractImageUrl' => $this->app['contractImageUrl'],
                'info' => $this->app['info'],
                'pledgeType'=>$this->app['pledgeType'],
            ]);
        }
        else {
            $model = Model::update([
                'mobile' => $this->app['mobile'],
                'name' => $this->app['name'],
                'money' => $this->app['money'],
                'year' => $this->app['year'],
                'certType'=> $this->app['certType'],
                'certContent' => $this->app['certContent'],
                'pledge' => $this->app['pledge'],
                'financingEntity' => $this->app['financingEntity'],
                'usage' => $this->app['usage'],
                'imageUrl' => $this->app['imageUrl'],
                'contractImageUrl' => $this->app['contractImageUrl'],
                'info' => $this->app['info'],
                'pledgeType'=>$this->app['pledgeType']
            ], [
                'loanID'=>$this->app['loanID']
            ]);
            $model['loanID'] = $this->app['loanID'];
        }

        return Common::rm(1, '操作成功', [
            'loanID'=>$model['loanID']
        ]);
    }

    public function getLoanList() {
        $map = [];

        if( isset($this->app['addTimeTo']) && $this->app['addTimeTo'] &&
            isset($this->app['addTimeFrom'])  && $this->app['addTimeTo']) {
            $map['addTime'] = ['between time', [$this->app['addTimeFrom'],$this->app['addTimeTo']]];
        }

        if(isset($this->app['keyword']) && $this->app['keyword']) {
            $map['mobile|certContent|name|money'] = ['like', '%'.$this->app['keyword'].'%'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }


        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        $count = Model::where($map)->count();
        $list = Model::where($map)->order('addTime desc')->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(1, '操作成功', [
                'loanList'=>[],
                'count'=>0,
                'pageItemCount'=>$this->app['pageItemCount']
            ]);
        }

        return Common::rm(1, '操作成功', [
            'loanList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount'],
        ]);
    }

    public function getDetail() {
        $item = Model::get($this->app['loanID']);
        if(!$item) {
            return Common::rm(-32, '没有数据');
        }
        return Common::rm(1, '操作成功', [
            'detail'=>$item
        ]);
    }

    public function actionDelete() {
        $model = Model::get($this->app['loanID']);
        $model->delete();
        return Common::rm(1, '操作成功');
    }

}