<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\laojiaqian\model\User;
use app\laojiaqian\model\UserDueDetail;
use think\Cache;
use think\Db;
use tool\Common;

class Laojiaqian extends Base
{
    public function getCangList() {
        $map = [
            'user_id'=>['gt', 0]
        ];
        if( isset($this->app['addTimeTo']) &&
            isset($this->app['addTimeFrom'])) {
            $map['add_time'] = ['between time', [$this->app['addTimeFrom'],Common::timetodate(Common::datetotime($this->app['addTimeTo']) + 86400, 0)]];
        }

        if(isset($this->app['status']) && $this->app['status']) {
            $map['status'] = $this->app['status'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        $app = $this->app;
        $list = UserDueDetail::with([
            'user'=>function($query) use ($app) {
                $map = [];
                if(isset($app['keyword']) &&  $app['keyword']) {
                    $map['mobile|real_name'] = ['like', '%'.$app['keyword'].'%'];
                }
                if(isset($app['clientType']) &&  $app['clientType']) {
                    if($app['clientType'] == 1) {
                        $map['device_type'] = 1;
                    }
                    else if($app['clientType'] == 2) {
                        $map['device_type'] = 2;
                    }
                    else if($app['clientType'] == 3){
                        $map['device_type'] = 3;
                    }
                    else if($app['clientType'] == 4){
                        $map['device_type'] = 4;
                    }
                }
                if(!empty($map)) {
                    $query->where($map);
                }
            },
            'project'=>function($query) use ($app) {
                $map = [];
                if(isset($app['typeID']) &&  $app['typeID']) {
                    $map['type'] = $app['type'];
                }
                if(!empty($map)) {
                    $query->where($map);
                }
            }
            //])->where($map)->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('id desc')->select();
        ])->where($map)->order('id desc')->select();

        /*if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }*/
        $_list = [];
        $stat_currentTotal = 0;
        $stat_total = UserDueDetail::where([
            'user_id'=>['gt', 0],
            'status'=>1
        ])->sum('due_capital');
        $stat_totalAll = UserDueDetail::where([
            'user_id'=>['gt', 0]
        ])->sum('due_capital');
        $stat_futouList = UserDueDetail::where([
            'user_id'=>['gt', 0]
        ])->field('due_capital, user_id, status')
            ->group('user_id')
            ->having('count(user_id)>1 and status = 2')
            ->select();
        $stat_futouUserIDS = array_column(collection($stat_futouList)->toArray(), 'user_id');
        $stat_futouUserYouxiaoIDS = [];

        foreach ($stat_futouUserIDS as $item) {
            $stat_futouList = UserDueDetail::where([
                'user_id'=>$item
            ])->field('due_capital, user_id, status, add_time')->select();
            $has = false;
            foreach ($stat_futouList as $_k=>$_item) {
                foreach ($stat_futouList as $__k=>$__item) {
                    if(abs(Common::datetotime($_item['add_time']) - Common::datetotime($__item['add_time'])) > 14 * 86400) {
                        $stat_futouUserYouxiaoIDS[] = $item;
                        $has = true;
                        break;
                    }
                }
                if($has) {
                    break;
                }
            }
        }

        //复投用户量
        $stat_futouUserTotal = count($stat_futouUserYouxiaoIDS);


        //总用户量
        $stat_userTotal = UserDueDetail::where([
            'user_id'=>['gt', 0]
        ])->group('user_id')
            ->count();

        foreach ($list as $k=>$item) {
            if($item['user'] && $item['project']) {
                $_list[] = $item;
                $stat_currentTotal += $item['due_capital'];
            }
        }
        Cache::set('stat_laojiaqian', [
            'stat_total'=>number_format($stat_total),
            'stat_totalAll'=>number_format($stat_totalAll),
            'stat_userTotal'=>$stat_userTotal
        ]);
        return Common::rm(1, '操作成功', [
            'cangList'=>$_list,
            'stat_currentTotal'=>number_format($stat_currentTotal),
            'stat_total'=>number_format($stat_total),
            'stat_totalAll'=>number_format($stat_totalAll),
            'stat_futouUserTotal'=>$stat_futouUserTotal,
            'stat_userTotal'=>$stat_userTotal,
            'count'=>count($_list),
            'pageItemCount'=>$this->app['pageItemCount']
        ]);
    }
}