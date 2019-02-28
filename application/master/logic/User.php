<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\core\model\Channel;
use app\core\model\User as Model;
use app\core\service\Finance;

use think\Db;
use tool\Common;

class User extends Base
{
    public function getUserList() {

        $map = [
            'isForged'=>0
        ];
        if( isset($this->app['addTimeFrom']) &&
            isset($this->app['addTimeTo'])) {
            $map['addTime'] = ['between time', [$this->app['addTimeFrom'],$this->app['addTimeTo']]];
        }
        /*if($this->app['repayTimeFrom'] && $this->app['repayTimeTo']) {
            $map['repayTime'] = ['between', [$this->app['repayTimeFrom'],$this->app['repayTimeTo']]];
        }
        if($this->app['repayTimeFrom'] && $this->app['repayTimeTo']) {
            $map['repayTime'] = ['between', [$this->app['repayTimeFrom'],$this->app['repayTimeTo']]];
        }*/
        if(isset($this->app['isAuthBank']) && $this->app['isAuthBank']) {
            $map['isAuthBank'] = $this->app['isAuthBank'];
        }
        if(isset($this->app['status']) && $this->app['status']) {
            $map['status'] = $this->app['status'];
        }

        if(isset($this->app['osType']) && $this->app['osType']) {
            $map['osType'] = $this->app['osType'];
        }

        if(isset($this->app['keyword']) && $this->app['keyword']) {
            $map['mobile|trueName'] = ['like', '%'.$this->app['keyword'].'%'];
        }

        if(!isset($this->app['pageIndex']) || !$this->app['pageIndex']) {
            $this->app['pageIndex'] = 1;
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }
        $count = Model::where($map)->count();
        $list = Model::with(['userAccount', 'channel'])->where($map)->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->order('addTime desc')->select();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }
        $list->visible(['trueName','mobile','userID','addTime','isAuthBank','osType','ip','loginTime','isNewInvest',
            'userAccount'=>[
                'money','money','moneyAcc','moneyYesterday','moneyToday','waitBen','waitInterest','hasInvestBenTotal','hasInvestMoneyTotal','hasRepayBenTotal','hasRepayBenTotal','hasRepayInterestTotal'
            ],
            'channel'=>[
                'name'
            ]
        ]);

        return Common::rm(1, '操作成功', [
            'userList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount']
        ]);
    }

    public function getChannelList() {
        $list = Channel::all();
        return Common::rm(1, '操作成功', [
            'channelList'=>$list
        ]);
    }

    public function cancelUser() {
        $finance = new Finance();
        $user = Model::get($this->app['userID']);
        $result = $finance->userCancel($msg, $user['mobile'], 'http://www.jiaqiancaifu.com');
    }
}