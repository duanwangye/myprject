<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;

use app\core\model\BankPlatform;
use app\core\model\UserAccount;
use app\core\model\UserBank;
use app\core\model\UserRecharge;
use app\core\service\Pay;
use app\core\service\Check;
use app\core\service\SMS;
use app\core\model\Master as Model;
use app\core\model\Cang;
use think\Db;
use tool\Common;

class Master extends Base
{
    /**
     * @api {post} master/loginByPassword 密码登录
     * @apiVersion 1.0.0
     * @apiName loginByPassword
     * @apiDescription 密码登录
     * @apiGroup Master
     *
     * @apiParam {String} mobile 手机号码
     * @apiParam {String} password 密码
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523",
    "password": "111111"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "token": "a8ajdsjasdfuufayg8aasdfasdfasd"
    }
    }
     * @apiUse CreateUserError
     */
    public function loginByPassword() {
        $master = Model::get([
            'mobile'=>$this->app['mobile']
        ]);
        if(!$master) {
            return Common::rm(-3, '该管理员不存在');
        }
        if($master['password'] !=  $master->createPassword($this->app['password'])) {
            return Common::rm(-4, '密码不正确');
        }
        $master['token'] = $master->createToken();
        $master['tokenOverTime'] = $master->createTokenOverTime();
        $master['loginTime'] = THINK_START_TIME;
        $master['ip'] = $this->request->ip();
        $master->save();
        $this->master = $master;

        $group = Db::name('master_group')->where([
            'masterID'=>$master['masterID']
        ])->find();
        if(!$group) {
            return Common::rm(-5, '没有权限');
        }
        return Common::rm(1, '操作成功', [
            'token'=>$master['token'],
            'masterInfo'=>[
                'mobile'=>$this->app['mobile'],
                'roleID'=>$group['masterRoleID']
            ]
        ]);
    }


    /**
     * @api {post} master/logout 退出登录
     * @apiVersion 1.0.0
     * @apiName logout
     * @apiDescription 退出登录
     * @apiGroup Master
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */
    public function logout() {
        Model::update([
            'tokenOverTime'=>0
        ],[
            'token'=>$this->master['token']
        ]);
        return Common::rm(1, '操作成功');
    }



    public static function checkAuth($master = [], $action = '') {
        $group = Db::name('master_group')->where([
            'masterID'=>$master['masterID']
        ])->find();
        if(!$group) {
            return false;
        }
        if($group['masterRoleID'] == 1) {
            return true;
        }
        $auth = Db::name('master_auth')->where([
            'masterRoleID'=>$group['masterRoleID']
        ])->select();
        if(!$auth) {
            return false;
        }
        $actionS = array_column($auth, 'action');
        if(!in_array($action, $actionS)) {
            return false;
        }
        return true;
    }
}