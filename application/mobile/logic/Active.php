<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile\logic;

use app\core\model\Active as Model;

use tool\Common;

class Active extends Base
{

    public function getActiveList() {

        $map = [];
        if($this->data['osType'] == 3) {
            $map['isOnLinePc'] = 1;
        }
        else {
            $map['isOnLineApp'] = 1;
        }
        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }
        $count = Model::where($map)->count();
        $list = Model::where($map)->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list = $list->hidden(['listOrder','isOnLinePc','isOnLineApp','updateTime'])->toArray();
        foreach ($list as $k=>$item) {
            $list[$k]['share'] = [
                'headImgUrl'=>'https://static.qimai.cn/static/img/newaso100@2x.png',
                'title'=>$item['title'],
                'desc'=>$item['title'],
                'link'=>'http://www.qq.com'
            ];
        }

        return Common::rm(1, '操作成功', [
            'activeList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount']
        ]);
    }

}