<?php
namespace app\active\controller;
use app\core\model\Channel;
use app\core\model\HongbaoPlan;
use app\core\model\Introduce;
use app\core\model\User;
use app\core\model\UserAccount;
use app\core\service\SMS;
use app\core\service\Tongbu;
use think\Controller;
use think\Request;
use tool\Common;


class Index extends Controller
{
    public function updateApp() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->path()]);
    }

    public function xinshou() {
        return view(__FUNCTION__, ['resourcePath'=>'/static/'.request()->path()]);
    }

    public function register() {
        if(request()->isPost()) {
            $param = request()->param();

            $package = (new SMS())->checkVerificationCode($param['mobile'], $param['mobileCode']);
            if($package['code'] != 1) {
                return $package;
            }

            $model = User::get([
                'mobile'=>$param['mobile']
            ]);
            if($model) {
                return Common::rm(-3, '该手机号码已经被注册');
            }

            if($param['password'] != $param['passwordRe']) {
                return Common::rm(-4, '密码不一致');
            }


            //channel
            $channel = Channel::get([
                'code'=>$param['channel']
            ]);

            //introduce
            $introduceUser = null;
            if(isset($param['introduce']) && $param['introduce']) {
                $introduceUser = User::get([
                    'uuid'=>$param['introduce']
                ]);
                if($introduceUser) {
                    $introduceUserAccount = $introduceUser->userAccount['introduceCount'];
                    $introduceUserAccount['introduceCount'] = $introduceUserAccount['introduceCount'] + 1;
                    $introduceUserAccount->allowField(['introduceCount'])->save();
                }
            }

            //新用户
            $model = new User();
            $model['uuid'] = Common::token_create(32);
            $model['password'] = $model->createPassword($param['password']);
            $model['mobile'] = $param['mobile'];
            $model['channelID'] = isset($channel['channelID']) ? $channel['channelID'] : 0;
            $model['deviceID'] = '';
            $model['osV'] = '';
            $model['osType'] = $param['osType'];
            $model['loginOsType'] = $param['osType'];
            $model['apiV'] = '';
            $model['appV'] = '';
            $model['ip'] = request()->ip();
            $model['isNewInvest'] = 1;//为首投用户
            $model['loginTime'] = THINK_START_TIME;
            $model['introduce'] = $introduceUser ? $introduceUser['userID'] : 0;
            $model->save();

            //账户
            $userAccount = new UserAccount();
            $userAccount['userID'] = $model['userID'];
            $userAccount->save();

            //新手红包
            (new HongbaoPlan())->sendUserOnRegister($model);

            //开始建立关系


            /*//开始登录
            $token = '';
            //移动app登录
            if($this->data['osType'] == 1 || $this->data['osType'] == 2) {
                $model['token'] = $model->createToken();
                $model['tokenOverTime'] = $model->createTokenOverTime();
                $token =  $model['token'];
            }
            //pc登录
            else if($this->data['osType'] == 3){
                $model['tokenPc'] = $model->createToken();
                $model['tokenOverTimePc'] = $model->createTokenOverTime();
                $token =  $model['tokenPc'];
            }
            //app登录
            else if($this->data['osType'] == 4){
                $model['tokenWap'] = $model->createToken();
                $model['tokenOverTimeWap'] = $model->createTokenOverTime();
                $token =  $model['tokenWap'];
            }
            $model->save();

            $this->user = $model;*/


            //********************************** 同步，后期删掉就好了 *************************
            (new Tongbu())->DY_user($param['mobile']);
            //********************************** end *************************

            return json(Common::rm(1, '操作成功'));
        }

        $os = request()->param('os');
        $path = '/static/'.request()->path().'PC';
        $view = __FUNCTION__.'PC';
        if(!$os) {
            $path = '/static/'.request()->path();
            $view = __FUNCTION__;
        }

        return view($view, [
            'resourcePath'=>$path,
            'channel'=>request()->param('channel'),
            'introduce'=>request()->param('introduce')
        ]);
    }

    public function registerSendMobileCode() {
        $param = Request::instance()->param();
        if(strlen($param['mobile']) != 11) {
            return json(Common::rm(-2, '手机号码不正确'));
        }
        $model = User::get([
            'mobile'=>$param['mobile']
        ]);
        if($model) {
            return json(Common::rm(-3, '该手机号码已经被注册'));
        }
        return json((new SMS())->sendVerificationCode($param['mobile']));
    }

    public function regAgreement() {
        exit;
    }
}
