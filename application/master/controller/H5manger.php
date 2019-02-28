<?php
namespace app\master\controller;


use app\core\model\HongbaoPlan;
use app\core\model\User;
use app\core\service\Finance;
use think\Controller;
use think\Db;
use tool\Common;

class H5manger extends Controller
{

    public function index() {
        $finance = new \app\master\logic\Index();
        $result = $finance->balance($msg, '18989706775');
        dump($result);
    }

    public function hongbao() {
        /*$user = User::get([
            'mobile'=>'18101707268'
        ]);
        (new HongbaoPlan())->sendUserOnRegister($user);*/
    }


    public function getStat30Days() {
        //echo "select FROM_UNIXTIME(addTime, '%y-%m-%d') dates, sum(moneySubject) from a_cang where isForged=0 and addTime between ".(THINK_START_TIME - 7 * 86400)." and ".Common::timetodate(THINK_START_TIME, 0)." group by dates";exit;
        dump(Db::query("select FROM_UNIXTIME(addTime, '%Y-%m-%d') date, sum(moneySubject) as moneyTotal,count(*) as count from a_cang where isForged=0 and addTime between ".(THINK_START_TIME - 30 * 86400)." and ".THINK_START_TIME." group by date"));
    }

    public function cancelUser() {
        $finance = new Finance();
        //$user = Model::get($this->app['userID']);
        $result = $finance->userCancel($msg, request()->param('mobile'), 'http://www.jiaqiancaifu.com');
        return view(__FUNCTION__, [
            'resourcePath'=>'/static/'.request()->path(),
            'param'=>$result['param'],
            'url'=>$result['url']
        ]);
    }

}

