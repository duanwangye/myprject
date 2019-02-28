<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\core\exception\AppException;
use app\core\model\Cang as Model;
use app\core\model\CangRepay;
use app\core\model\User;
use app\core\model\Subject;
use app\core\model\Sms;
use app\core\service\Stat;
use think\Db;
use think\Exception;
use think\Log;
use tool\Common;

class Index extends Base
{
    public function getCangList() {
        $map = [
            'isForged'=>['eq', 0],
            /*'status'=>2*/
        ];
        if( isset($this->app['addTimeTo']) &&
            isset($this->app['addTimeFrom'])) {
            $map['addTime'] = ['between', [Common::datetotime($this->app['addTimeFrom']),Common::datetotime($this->app['addTimeTo']) + 86400]];
        }

        if(isset($this->app['status']) && $this->app['status']) {
            $map['status'] = $this->app['status'];
        }

        if(isset($this->app['typeID']) && $this->app['typeID']) {
            $map['subject.subjectTypeID'] = $this->app['typeID'];
        }

        if(isset($this->app['osType']) && $this->app['osType']) {
            $map['user.osType'] = $this->app['osType'];
        }

        if(isset($this->app['channelID']) && $this->app['channelID']) {
            $map['user.channelID'] = $this->app['channelID'];
        }

        if(isset($this->app['keyword']) && $this->app['keyword']) {
            $map['mobile|trueName|title'] = ['like', '%'.$this->app['keyword'].'%'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }


        $list = Db::view('cang','cangID,subjectID,moneySubject,status,addTime,year,investDay,interest,money,userID,isForged,hongbao,yearExt')
            ->view('user','userID,trueName,mobile,channelID,isNewInvest,osType','cang.userID=user.userID')
            ->view('subject','subjectID,title','cang.subjectID=subject.subjectID')
            ->view('channel','channelID,name as channelName','user.channelID=channel.channelID', 'left')
            ->view('master','trueName as customer,masterID,mobile as customerMobile','master.masterID=cang.masterID', 'left')
            //->view('user_hongbao','userHongbaoID,sum(money),sum(interest)','cang.hongbao in (user_hongbao.userHongbaoID)', 'left')
            ->where($map)
            ->order('cangID desc')
            ->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])
            ->select();

        foreach ($list as $k=>$item) {
            $list[$k]['addTime'] = Common::timetodate($list[$k]['addTime']);
            $list[$k]['money'] = Common::price2($list[$k]['money'] / 100);
            $list[$k]['moneySubject'] = Common::price2($list[$k]['moneySubject'] / 100);
            $list[$k]['interest'] = Common::price2($list[$k]['interest'] / 100);
            if($list[$k]['osType'] == 1) {
                $list[$k]['osType'] = 'ios';
            }
            else if($list[$k]['osType'] == 2) {
                $list[$k]['osType'] = 'android';
            }
            else if($list[$k]['osType'] == 3) {
                $list[$k]['osType'] = 'pc';
            }
            else if($list[$k]['osType'] == 4) {
                $list[$k]['osType'] = 'wap';
            }
            $list[$k]['moneyHongbao'] = $list[$k]['moneySubject'] - $list[$k]['money'];
        }


        //所有表
        $listAll = Db::view('cang','cangID,subjectID,moneySubject,status,addTime,year,investDay,interest,money,userID,isForged,hongbao')
            ->view('user','userID,trueName,mobile,channelID,isNewInvest,osType','cang.userID=user.userID')
            ->view('subject','subjectID,title','cang.subjectID=subject.subjectID')
            ->view('channel','channelID,name as channelName','user.channelID=channel.channelID','left')
            ->where($map)
            ->select();
        $moneyTotal_moneySubject = 0;
        $moneyTotal_interest = 0;
        $moneyTotal_money = 0;
        foreach ($listAll as $k=>$item) {
            $moneyTotal_moneySubject += $listAll[$k]['moneySubject'];
            $moneyTotal_interest += $listAll[$k]['interest'];
            $moneyTotal_money += $listAll[$k]['money'];
        }


        return Common::rm(1, '操作成功', [
            'cangList'=>$list,
            'count'=>count($listAll),
            'pageItemCount'=>$this->app['pageItemCount'],
            'moneyTotal_moneySubject'=>Common::price2($moneyTotal_moneySubject / 100),
            'moneyTotal_interest'=>Common::price2($moneyTotal_interest / 100),
            'moneyTotal_money'=>Common::price2($moneyTotal_money / 100)
        ]);
    }

    public function getStat() {
        $stat = Stat::getStat();
        return Common::rm(1, '操作成功', [
            'stat'=>$stat
        ]);
    }
}