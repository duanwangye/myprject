<?php
namespace app\mobile\controller;


use app\core\exception\AppException;
use app\core\model\UserBank;
use app\core\model\UserDrawcash;
use app\core\model\UserHongbao;
use app\core\model\Active;
use app\core\model\Cang;
use app\core\model\Subject;
use app\core\model\Loan;
use app\core\model\UserRecharge;
use app\core\service\Finance;
use app\core\service\User;
use think\Config;
use think\Log;
use think\Request;
use tool\Common;

class H5
{
    public $user;
    public $data = [];
    public $track = [];
    public $request;
    public $app;


    public function __construct()
    {
        $this->request = request();
        $this->data = $this->request->param();
        $this->track = json_decode($this->data['track'], true);
        $this->check();
        $this->app = $this->track['app'];
        Log::info($this->app);
        Log::info($this->request);
        if(in_array($this->request->path(), Config::get('tourist_path')))
        {
            //游客直接跳过
        }
        else {
            $user = User::getUserByToken($this->track['token'], $this->track['osType']);
            if(!$user) {
                throw new AppException(-1000, 'token失效，需要重新登录');
            }
            $this->user = $user;
        }
    }



    public function check() {

        if(!isset($this->track['apiV'])) {
            throw new AppException(-101, 'api版本号不能为空');
        }

        if(!isset($this->track['osV'])) {
            throw new AppException(-102, 'osV操作系统版本号不能为空');
        }

        if(!isset($this->track['osType'])) {
            throw new AppException(-103, 'osType操作系统类型不能为空');
        }

        if(!isset($this->track['deviceID'])) {
            throw new AppException(-104, 'deviceID宿主app的唯一标识不能为空');
        }

        if(!isset($this->track['token'])) {
            throw new AppException(-105, 'token不能为空');
        }

        if(!isset($this->track['sign'])) {
            throw new AppException(-106, 'sign不能为空');
        }

        if(!isset($this->track['time'])) {
            throw new AppException(-107, 'time不能为空');
        }

        if(!isset($this->track['ip'])) {
            throw new AppException(-108, 'ip不能为空');
        }

        if(!isset($this->track['app'])) {
            throw new AppException(-109, 'app应用数据不能为空');
        }

        if(!isset($this->track['appV'])) {
            throw new AppException(-110, 'appV不能为空');
        }

        if(!isset($this->track['channel'])) {
            throw new AppException(-111, 'channel不能为空');
        }

        $key = '';
        if($this->track['osType'] == 1 || $this->track['osType'] == 2) {
            $key =  Config::get('system.mobile_key');
        }
        else if($this->track['osType'] == 3) {
            $key = Config::get('system.pc_key');
        }

        $this->app = $this->track['app'];
        $signPre = '';
        if($this->app === '') {
            $signPre = $key.$this->track['token'].$this->track['time'].$this->track['appV'].$this->track['apiV'].$this->track['osV'].$this->track['osType'].$this->track['deviceID'].$this->track['ip'].$this->track['channel'];
        }
        else if($this->app === []) {
            $signPre = $key.$this->track['token'].$this->track['time'].$this->track['appV'].$this->track['apiV'].$this->track['osV'].$this->track['osType'].$this->track['deviceID'].$this->track['ip'].$this->track['channel'].'{}';
        }
        else {
            $signPre = $key.$this->track['token'].$this->track['time'].$this->track['appV'].$this->track['apiV'].$this->track['osV'].$this->track['osType'].$this->track['deviceID'].$this->track['ip'].$this->track['channel'].json_encode($this->track['app'], JSON_UNESCAPED_UNICODE);
        }


        Log::info($signPre);
        $sign = md5($signPre);
        Log::info($sign);
        if($sign != $this->track['sign']) {
            throw new AppException(-108, 'sign签名不正确');
        }
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function getResourcePath()
    {
        $module = Request::instance()->module();
        $controller = Request::instance()->controller();
        $action = Request::instance()->action();
        return '/'.$module.'/'.$controller.'/'.$action;
    }

    public function activeList() {
        $list = Active::where([
            'isOnLineApp'=>1
        ])->order('addTime desc')->select();
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->path(), 'activeList'=>$list]);
    }

    //内容
    public function subjectContent() {
        //Log::info($this->app['subjectID']);
        $list = Cang::with(['user'])->where([
            'subjectID'=>$this->app['subjectID']
        ])->order('addTime desc')->select();
        $cangList = [];
        if(!$list->isEmpty()) {
            $cangList = $list->toArray();
        }

        $subject = Subject::get($this->app['subjectID']);
        $loan = null;
        if($subject) {
            $loan = Loan::get($subject['loanID']);
        }

        return view(__FUNCTION__, [
            'resourcePath'=>'/static/'.request()->path(),
            'cangList'=>$cangList,
            'loan'=>$loan
        ]);
    }

    //首页
    public function guarantee() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->path()]);
    }

    public function contract() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->module().'/'.strtolower(request()->controller()).'/'.'contract']);
    }

    public function risk() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->module().'/'.strtolower(request()->controller()).'/'.'contract']);
    }

    public function userRecharge() {

        $userBank = UserBank::get($this->app['userBankID']);
        if(!$userBank) {
            return json(Common::rm(-3, '银行卡不存在'));
        }


        //第一步，添加提现记录
        $userRecharge = new UserRecharge();
        $userRecharge->save([
            'userID'=>$this->user['userID'],
            'money'=>$this->app['money'],
            'status'=>UserRecharge::STATUS_UNPAY,
            'type'=>UserRecharge::TYPE_BANK,
            'bankID'=>$userBank->bank['bankID'],
            'bankAccount'=>$userBank['bankAccount'],
            'bankNumber'=>$userBank['bankNumber'],
            'bankName'=>$userBank['bankNameFull'],
            'trueName'=>$userBank['bankAccount'],
            'mobile'=>$userBank['mobile'],
            'outerName'=>$userBank['outerName']
        ]);


        //第二步，得到第三方提现记录
        vendor('payModel.Recharge');
        $recharge = new \Recharge();
        $recharge->setMoney($this->app['money']);
        $recharge->setLoginID($userBank['mobile']);

        $recharge->setPageUrl($this->h5RootUrl().'/notify/fuyou/rechargePage/userRechargeID/'.$userRecharge['userRechargeID'].'/from/'.request()->param('from'));
        $recharge->setNotifyUrl($this->h5RootUrl().'/notify/fuyou/rechargeNotify/userRechargeID/'.$userRecharge['userRechargeID']);
        $data = (new Finance())->userRecharge($msg, $recharge);

        //第三步，更新提现记录
        $userRecharge['outerNumber'] = $data['outerNumber'];
        $userRecharge['alias'] = $userRecharge->createAlias($userRecharge['userRechargeID']);
        $userRecharge->save();


        return view(__FUNCTION__, [
            'resourcePath'=>'/static/'.request()->path(),
            'param'=>$data['param'],
            'url'=>$data['url']
        ]);
    }

    public function userDrawcash() {


        $userBank = UserBank::get($this->app['userBankID']);
        if(!$userBank) {
            return json(Common::rm(-3, '银行卡不存在'));
        }


        //第一步，添加提现记录
        $userDrawcash = new UserDrawcash();
        $userDrawcash->save([
            'userID'=>$this->user['userID'],
            'money'=>$this->app['money'],
            'status'=>UserDrawcash::STATUS_SUBMIT,
            'type'=>UserDrawcash::TYPE_BANK,
            'userBankID'=>$userBank['userBankID'],
            'bankID'=>$userBank['bankID'],
            'bankAccount'=>$userBank['bankAccount'],
            'bankNumber'=>$userBank['bankNumber'],
            'bankName'=>$userBank['bankNameFull'],
            'trueName'=>$userBank['bankAccount'],
            'mobile'=>$userBank['mobile'],
            'outerName'=>$userBank['outerName']
        ]);


        //第二步，得到第三方提现记录
        vendor('payModel.Drawcash');
        $drawcash = new \Drawcash();
        $drawcash->setMoney($this->app['money']);
        $drawcash->setLoginID($userBank['mobile']);
        $drawcash->setPageUrl($this->h5RootUrl().'/notify/fuyou/drawcashPage/userDrawcashID/'.$userDrawcash['userDrawcashID'].'/from/'.request()->param('from'));
        $drawcash->setNotifyUrl($this->h5RootUrl().'/notify/fuyou/drawcashNotify/userDrawcashID/'.$userDrawcash['userDrawcashID']);
        $data = (new Finance())->userDrawcash($msg, $drawcash);

        //第三步，更新提现记录
        $userDrawcash['outerNumber'] = $data['outerNumber'];
        $userDrawcash['alias'] = $userDrawcash->createAlias($userDrawcash['userDrawcashID']);
        $userDrawcash->save();


        return view(__FUNCTION__, [
            'resourcePath'=>'/static/'.request()->path(),
            'param'=>$data['param'],
            'url'=>$data['url']
        ]);
    }

    public function safe() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->module().'/'.strtolower(request()->controller()).'/'.'safe']);
    }

    public function about() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->module().'/'.strtolower(request()->controller()).'/'.'activeList']);
    }

    //注册协议
    public function regAgreement() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->module().'/'.strtolower(request()->controller()).'/'.'regAgreement']);
    }

    public function userInvitationPage() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->module().'/'.strtolower(request()->controller()).'/'.'userInvitationPage']);
    }



    public function h5RootUrl() {
        return Config::get('h5RootUrl');
    }

}
