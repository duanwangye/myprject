<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile_v_2_1\logic;

use app\core\model\BankPlatform;
use app\core\model\User;
use think\Config;
use tool\Common;

class System extends Base
{

    public function getBankList()
    {
        $list = BankPlatform::with(['bank'])->where([
            'platform'=>Config::get('platform.default_pay_gateway')
        ])->select();
        if($list->isEmpty()) {
            return Common::rm(1, '操作成功', [
                'bankList'=>[]
            ]);
        }
        $list->hidden([
            'status','platform','bankID'
        ]);
        return Common::rm(1, '操作成功', [
            'bankList'=>$list
        ]);
    }

    public function getConfig() {
        $config = [
            'bankBandMax'=>1,//至多绑定银行卡数量,
            'loanAgreementUrl'=>$this->h5RootUrl().'/mobile_v_2_1/h5/contract?######',//借款协议url,
            'riskBulletinUrl'=>$this->h5RootUrl().'/mobile_v_2_1/h5/risk?######',//风险揭示书url,
            'rechargeUrl'=>$this->h5RootUrl().'/mobile/h5/userRecharge?######',//充值url,
            'rechargeWYUrl'=>$this->h5RootUrl().'/mobile/h5/userRechargeWY?######',//网银充值url,
            'drawcashUrl'=>$this->h5RootUrl().'/mobile/h5/userDrawcash?######',//提现url,
            'cancelUserUrl'=>$this->h5RootUrl().'/mobile/h5/userCancelUser?######',//销户url,
            'userInvitationUrl'=>$this->h5RootUrl().'/mobile/h5/userRecharge?######',//邀请注册url
            'activeUrl'=>$this->h5RootUrl().'/mobile/h5/activeList?######',//活动模块url
            'customerUrl'=>'http://www.baidu.com?######',//联系客服
            'aboutUsUrl'=>$this->h5RootUrl().'/mobile_v_2_1/h5/about?######',//关于我们
            'regAgreementUrl'=>$this->h5RootUrl().'/mobile_v_2_1/h5/regAgreement?######',//注册协议
            'startPageImage'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/03/06/c3a9BiOrwf.png',//注册协议,
            'userExtNav'=>[
                [
                    'icon'=>'',
                    'text'=>'',
                    'link'=>'http://www.baidu.com?######'
                ]
            ],
            'stat'=>[
                'userRegCount'=>User::where([
                    'isForged'=>0
                ])->count()
            ],//注册协议
            'companyInfo'=>$this->skin['companyInfo']
        ];

        return Common::rm(1, '操作成功', [
            'config'=>$config
        ]);
    }

    public function getUpgradeInfo() {
        return Common::rm(1, '操作成功', [
            'versionName'=>'2.1.0',
            'isForce'=>1,
            'downloadAndroid'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/03/14/GOL9rxoUAv.apk',
            'downloadIOS'=>'',
            'day'=>5
        ]);
    }


    public function getUpgradeInfoForIOS() {
        return Common::rm(1, '操作成功', [
            'versionName'=>'2.1.0',
            'versionContent'=>[
                '1、更新了11231',
                '2、更新了21212312',
                '3、更新了333333'
            ],
            'channels'=>[
                '20180308'
            ],
            'download'=>'https://itunes.apple.com/cn/app/id1338057758?mt=8'
        ]);
    }
}