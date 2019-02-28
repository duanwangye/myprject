<?php

/**
 * Created by PhpStorm.
 * 服务提供者
 * User: qissen
 * Date: 2017/6/7
 * Time: 7:36
 * 注意调用顺序，checkClientType，checkData必须先调用，才可以验证其他
 */

namespace app\core\service;
use app\core\model\Cang;
use app\core\model\User as ModelUser;
use app\core\model\UserRecharge;
use think\Cache;
use think\Db;
use tool\Common;

class Stat
{

    public static function getStat() {
        //Cache::rm('stat');
        $stat = Cache::get('stat');
        if(!$stat) {
            $stat = [];
            $stat += self::getStatToday();
            $stat += self::getStatYesterday();
            $stat += self::getStatAll();
            $stat += self::getStatWeek();
            $stat += self::getStatCang30DayList();
            Cache::set('stat', $stat, 60);
        }
        return $stat;
    }



    public static function getStatToday()
    {
        $stat = [];
        $stat['moneySubjectTotal_today_ios'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=1')
            ->where('status', 'EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'today')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_today_ios'] = number_format($stat['moneySubjectTotal_today_ios'] / 100);

        $stat['moneySubjectTotal_today_android'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=2')
            ->where('status', 'EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'today')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_today_android'] = number_format($stat['moneySubjectTotal_today_android'] / 100);

        $stat['moneySubjectTotal_today_pc'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=3')
            ->where('status', 'EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'today')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_today_pc'] = number_format($stat['moneySubjectTotal_today_pc'] / 100);

        $stat['moneySubjectTotal_today_wap'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=4')
            ->where('status', 'EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'today')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_today_wap'] = number_format($stat['moneySubjectTotal_today_wap'] / 100);

        $stat['moneySubjectTotal_today'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('status', 'EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'today')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_today'] = number_format($stat['moneySubjectTotal_today'] / 100);


        $stat['cangCount_today'] = Db::name('cang')
            ->where('status', 'EGT', Cang::STATUS_PAY)
            ->where('isForged', 0)
            ->whereTime('addTime', 'today')
            ->count();


        $stat['userTotal_today'] = Db::name('user')
            ->whereTime('addTime', 'today')
            ->count();

        $stat['userTotal_today_auth'] = Db::name('user')
            ->whereTime('addTime', 'today')
            ->where('isAuthBank', 1)
            ->count();

        return $stat;
    }

    public static function getStatYesterday() {
        $stat = [];
        $stat['moneySubjectTotal_yesterday_ios'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=1')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'yesterday')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_yesterday_ios'] = number_format($stat['moneySubjectTotal_yesterday_ios'] / 100);

        $stat['moneySubjectTotal_yesterday_android'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=2')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'yesterday')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_yesterday_android'] = number_format($stat['moneySubjectTotal_yesterday_android'] / 100);

        $stat['moneySubjectTotal_yesterday_pc'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=3')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'yesterday')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_yesterday_pc'] = number_format($stat['moneySubjectTotal_yesterday_pc'] / 100);

        $stat['moneySubjectTotal_yesterday_wap'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=4')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'yesterday')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_yesterday_wap'] = number_format($stat['moneySubjectTotal_yesterday_wap'] / 100);

        $stat['moneySubjectTotal_yesterday'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'yesterday')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_yesterday'] = number_format($stat['moneySubjectTotal_yesterday'] / 100);


        $stat['cangCount_yesterday'] = Db::name('cang')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->where('isForged',0)
            ->whereTime('addTime', 'yesterday')
            ->count();


        $stat['userTotal_yesterday'] = Db::name('user')
            ->whereTime('addTime', 'yesterday')
            ->count();

        $stat['userTotal_yesterday_auth'] = Db::name('user')
            ->whereTime('addTime', 'yesterday')
            ->where('isAuthBank', 1)
            ->count();
        return $stat;
    }

    public static function getStatWeek() {
        $stat = [];
        $stat['moneySubjectTotal_week_ios'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=1')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'week')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_week_ios'] = number_format($stat['moneySubjectTotal_week_ios'] / 100);

        $stat['moneySubjectTotal_week_android'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=2')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'week')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_week_android'] = number_format($stat['moneySubjectTotal_week_android'] / 100);

        $stat['moneySubjectTotal_week_pc'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=3')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'week')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_week_pc'] = number_format($stat['moneySubjectTotal_week_pc'] / 100);

        $stat['moneySubjectTotal_week_wap'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=4')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'week')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_week_wap'] = number_format($stat['moneySubjectTotal_week_wap'] / 100);

        $stat['moneySubjectTotal_week'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->whereTime('addTime', 'week')
            ->sum('moneySubject');
        $stat['moneySubjectTotal_week'] = number_format($stat['moneySubjectTotal_week'] / 100);


        $stat['cangCount_week'] = Db::name('cang')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->where('isForged',0)
            ->whereTime('addTime', 'week')
            ->count();


        $stat['userTotal_week'] = Db::name('user')
            ->whereTime('addTime', 'week')
            ->count();

        $stat['userTotal_week_auth'] = Db::name('user')
            ->whereTime('addTime', 'week')
            ->where('isAuthBank', 1)
            ->count();
        return $stat;
    }

    public static function getStatAll() {
        $stat = [];
        $stat['moneySubjectTotal_all_ios'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=1')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->sum('moneySubject');
        $stat['moneySubjectTotal_all_ios'] = number_format($stat['moneySubjectTotal_all_ios'] / 100);

        $stat['moneySubjectTotal_all_android'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=2')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->sum('moneySubject');
        $stat['moneySubjectTotal_all_android'] = number_format($stat['moneySubjectTotal_all_android'] / 100);

        $stat['moneySubjectTotal_all_pc'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=3')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->sum('moneySubject');
        $stat['moneySubjectTotal_all_pc'] = number_format($stat['moneySubjectTotal_all_pc'] / 100);

        $stat['moneySubjectTotal_all_wap'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('user.osType=4')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->sum('moneySubject');
        $stat['moneySubjectTotal_all_wap'] = number_format($stat['moneySubjectTotal_all_wap'] / 100);

        $stat['moneySubjectTotal_all'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->sum('moneySubject');
        $stat['moneySubjectTotal_all'] = number_format($stat['moneySubjectTotal_all'] / 100);

        $stat['cunTotal_all'] = Db::view('cang', 'moneySubject,userID,status,addTime')
            ->view('user', 'userID,osType', 'cang.userID=user.userID and user.isForged=0')
            ->view('subject', 'BACKUPID', 'subject.subjectID=cang.subjectID')
            ->where('status','in', [Cang::STATUS_PAY,Cang::STATUS_INTEREST, Cang::STATUS_REPAY])
            ->where('subject.BACKUPID', 0)
            ->sum('moneySubject');
        $stat['cunTotal_all'] = number_format($stat['cunTotal_all'] / 100);


        $stat['cangCount_all'] = Db::name('cang')
            ->where('status','EGT', Cang::STATUS_PAY)
            ->where('isForged',0)
            ->count();


        $stat['userTotal_all'] = Db::name('user')
            ->count();

        $stat['userTotal_all_auth'] = Db::name('user')
            ->where('isAuthBank', 1)
            ->count();
        return $stat;
    }

    public static function getStatCang30DayList() {
        $list = Db::query("select FROM_UNIXTIME(addTime, '%Y-%m-%d') date, sum(moneySubject) as moneyTotal,count(*) as count from a_cang where isForged=0 and addTime between ".(THINK_START_TIME - 30 * 86400)." and ".THINK_START_TIME." group by date");
        foreach ($list as $k=>$item) {
            $list[$k]['moneyTotal'] = (int)($item['moneyTotal'] / 100);
        }
        $stat['cang30DayList'] = [
            'dateList'=>array_column($list, 'date'),
            'moneyTotalList'=>array_column($list, 'moneyTotal'),
            'countList'=>array_column($list, 'count')
        ];
        return $stat;
    }

    public static function getStatUserAction() {
        $listAll = Cache::get('statUserAction');
        if(!$listAll) {
            $listAll = [];
            $list = ModelUser::where([
                'isForged' => 0
            ])->field('mobile,trueName')->order('addTime desc')->limit(0, 10)->select();
            foreach ($list as $k => $item) {
                array_push($listAll, Common::mobileAsterisk($item['mobile']) . ' 刚刚开通新账号');
            }

            $list = UserRecharge::with(['user'])->where([
                'status' => UserRecharge::STATUS_PAY
            ])->order('addTime desc')->limit(0, 20)->select();
            foreach ($list as $k => $item) {
                array_push($listAll, Common::mobileAsterisk($item->user['mobile']) . '完成了一笔' . $item['money'] . '元' . '的充值');
            }

            $list = Cang::with(['subject', 'user'])->where([
                'isForged' => 0
            ])->group('userID')->order('addTime desc')->limit(0, 20)->select();
            foreach ($list as $k => $item) {
                array_push($listAll, Common::mobileAsterisk($item->user['mobile']) . '投资了' . $item->subject['title'] . ' ' . $item['moneySubject'] . '元');
            }

            $listAll = array_values(Common::shuffle_assoc($listAll));
            Cache::set('statUserAction', $listAll, 60);
        }
        return $listAll;
    }
}