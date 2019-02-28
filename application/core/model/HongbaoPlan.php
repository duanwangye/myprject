<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Cache;
use think\Log;
use think\Model;
use tool\Common;


class HongbaoPlan extends Model
{
    protected $pk = 'hongbaoPlanID';
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const STATUS_ONLINE = 1;
    const STATUS_OFFLINE = 2;
    const STATUS_OVER = 3;

    const STATUSS = [
        self::STATUS_ONLINE=>'已启用',
        self::STATUS_OFFLINE=>'未启用',
        self::STATUS_OVER=>'已发放',
    ];


    const TYPE_USER_NEW = 1;
    const TYPE_TIMEING = 2;
    const TYPESS = [
        [
            'typeID'=>self::TYPE_USER_NEW,
            'name'=>'新手注册'
        ],
        [
            'typeID'=>self::TYPE_TIMEING,
            'name'=>'定时发送'
        ]
    ];

    public function hongbao() {
        return $this->belongsTo('Hongbao', 'hongbaoID');
    }

    public function getTypeList() {
        return self::TYPESS;
    }

    public function getSendTimeAttr($value) {
        return Common::timetodate($value);
    }

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function getTypeNameAttr($value, $data) {
        foreach (self::TYPESS as $item) {
            if($item['typeID'] == $data['typeID']) {
                return $item['name'];
            }
        }
        return '未知';
    }

    //新手注册领红包
    public function sendUserOnRegister($user) {
        //当前新手注册只能为一个有效
        $hongbaoPlan = self::where([
            'typeID'=>1
        ])->find();

        $hongbaoList = Hongbao::where([
            'hongbaoID'=>['in', $hongbaoPlan['hongbaoIDS']]
        ])->select();
        $data = [];
        if($hongbaoList->isEmpty()) {
            return false;
        }
        foreach ($hongbaoList->toArray() as $k=>$item) {
            //如果当前红包不可用
            if($item['status'] != Hongbao::STATUS_ONLINE) {
                continue;
            }

            $userHongbao = [];
            $userHongbao['userID'] = $user['userID'];
            $userHongbao['hongbaoID'] = $item['hongbaoID'];
            if($item['effectTimeType'] == 1) {
                $userHongbao['beginTime'] = Common::datetotime(Common::timetodate(THINK_START_TIME, 0));
                $userHongbao['endTime'] = Common::datetotime(Common::timetodate(THINK_START_TIME, 0)) + $item['term'] * 86400;
            }
            else if($item['effectTimeType'] == 2) {
                $userHongbao['beginTime'] = $item['beginTime'];
                $userHongbao['endTime'] = $item['endTime'];
            }
            $userHongbao['getType'] = 1;
            $userHongbao['status'] = UserHongbao::STATUS_UNUSED;
            $userHongbao['addTime'] = THINK_START_TIME;
            $userHongbao['updateTime'] = THINK_START_TIME;
            $data[] = $userHongbao;
        }
        (new UserHongbao())->insertAll($data);
        return true;
    }

    //自动派发红包
    public function sendUserOnTiming() {
        $userSpan = 99;
        $hongbaoPlanList = self::with(['hongbao'])->where([
            'typeID'=>2,
            'sendTime'=>['lt', (int)THINK_START_TIME],
            'status'=>HongbaoPlan::STATUS_ONLINE
        ])->select();

        foreach ($hongbaoPlanList->toArray() as $k=>$item) {

            //对于某一个红包，
            $userBeginID = Cache::get('HongbaoPlan'.$item['hongbaoPlanID'].'UserBeginID');
            $userBeginID = $userBeginID ? $userBeginID : 1;
            $userMaxID = User::max('userID');
            if($userBeginID > $userMaxID) {
                //已经全部发放完成
                self::update([
                    'status'=>HongbaoPlan::STATUS_OVER
                ], [
                    'hongbaoPlanID'=>$item['hongbaoPlanID']
                ]);
                Cache::rm('HongbaoPlan'.$item['hongbaoPlanID'].'UserBeginID');
                continue;
            }

            $userEndID = $userBeginID + $userSpan;
            $userList = User::where([
                'isForged'=>0,
                'userID'=>['between', [$userBeginID, $userEndID]]
            ])->select();

            $data = [];
            foreach ($userList as $user) {
                //给用户发红包啦
                if ($item['hongbao']['status'] != Hongbao::STATUS_ONLINE) {
                    continue;
                }

                //判断用户是否重复领取
                $userHongbao = [];
                $userHongbao['userID'] = $user['userID'];
                $userHongbao['hongbaoID'] = $item['hongbaoID'];
                if ($item['hongbao']['effectTimeType'] == 1) {
                    $userHongbao['beginTime'] = Common::datetotime(Common::timetodate(THINK_START_TIME, 0));
                    $userHongbao['endTime'] = Common::datetotime(Common::timetodate(THINK_START_TIME, 0)) + $item['hongbao']['term'] * 86400;
                } else if ($item['hongbao']['effectTimeType'] == 2) {
                    $userHongbao['beginTime'] = $item['hongbao']['beginTime'];
                    $userHongbao['endTime'] = $item['hongbao']['endTime'];
                }
                $userHongbao['getType'] = 2;
                $userHongbao['status'] = UserHongbao::STATUS_UNUSED;
                $userHongbao['addTime'] = THINK_START_TIME;
                $userHongbao['updateTime'] = THINK_START_TIME;
                $userHongbao['hongbaoPlanID'] = $item['hongbaoPlanID'];
                $data[] = $userHongbao;
            }
            (new UserHongbao())->insertAll($data);
            Cache::set('HongbaoPlan'.$item['hongbaoPlanID'].'UserBeginID', $userEndID + 1);
        }

    }

    //给某个用户发红包
    public function sendUser($user, $hongbaoIDS, $operator, $note, $mobile) {

        $hongbaoList = Hongbao::where([
            'hongbaoID'=>['in', $hongbaoIDS]
        ])->select();
        $data = [];
        if($hongbaoList->isEmpty()) {
            return false;
        }
        foreach ($hongbaoList->toArray() as $k=>$item) {
            //如果当前红包不可用
            if($item['status'] != Hongbao::STATUS_ONLINE) {
                continue;
            }
            if($user) {
                $userHongbao = [];
                $userHongbao['userID'] = $user['userID'];
                $userHongbao['hongbaoID'] = $item['hongbaoID'];
                if($item['effectTimeType'] == 1) {
                    $userHongbao['beginTime'] = Common::datetotime(Common::timetodate(THINK_START_TIME, 0));
                    $userHongbao['endTime'] = Common::datetotime(Common::timetodate(THINK_START_TIME, 0)) + $item['term'] * 86400;
                }
                else if($item['effectTimeType'] == 2) {
                    $userHongbao['beginTime'] = $item['beginTime'];
                    $userHongbao['endTime'] = $item['endTime'];
                }
                $userHongbao['getType'] = 1;
                $userHongbao['status'] = UserHongbao::STATUS_UNUSED;
                $userHongbao['addTime'] = THINK_START_TIME;
                $userHongbao['updateTime'] = THINK_START_TIME;
                $userHongbao['operator'] = $operator;
                $userHongbao['note'] = $note;
                $data[] = $userHongbao;
            }


            //发送一个短信
            Sms::create([
                'mobile'=>$mobile,
                'message'=>\app\core\service\SMS::message_setJiaxiYuebiao($user ? $user['trueName'] : '佳乾客户', '一张新年加息券'.$item['year'], 'http://t.cn/RQC0dnv'),
                'note'=>'狗年约标',
                'sendTime'=>THINK_START_TIME
            ]);
        }
        (new UserHongbao())->insertAll($data);
        return true;
    }
}