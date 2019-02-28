<?php
namespace app\active\controller;
use app\core\model\Channel;
use app\core\model\HongbaoPlan;
use app\core\model\User;
use app\core\model\UserAccount;
use app\core\service\SMS;
use app\core\service\Stat;
use app\core\service\Tongbu;
use think\Cache;
use think\Controller;
use think\Db;
use think\Log;
use think\Request;
use tool\Common;
use wechat\WechatAuth;
use wechat\Wechat as WechatCommon;


class Wechat extends Controller
{
   /* public function index() {
        $appid = 'wx858c4fed463742d7';
        $secret = '6390e4ae8cb2790e080666ae1a6cf5be';
        $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret);
        $result = json_decode($result, true);

        $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$result['access_token'].'&type=jsapi');
        $result = json_decode($result, true);
        //dump($result);exit;
        $data['jsapi_ticket'] = $result['ticket'];
        $data['noncestr'] = THINK_START_TIME;
        $data['timestamp'] = (int)THINK_START_TIME;
        $data['url'] = 'http://www.baidu.com';
        $data['signature'] = sha1(http_build_query($data));
        $data['appid'] = 'wx858c4fed463742d7';
        unset($data['jsapi_ticket']);

        header('Content-type:text/json');
        echo request()->param('callback').'('.json_encode(Common::rm(1, '操作成功', $data)).')';exit;
    }

    public function test() {
        $signature = $_GET['signature']; //微信加密签名
        $timestamp = $_GET['timestamp']; //时间戳
        $nonce = $_GET['nonce']; //随机数
        $echostr = $_GET['echostr']; //随机字符串
        $token = '9GQfeptfROO3v1rjXAwoYjFCMpon8bvS';
        $tempArr = array($token,$timestamp,$nonce);
        sort($tempArr);
        if($signature ==sha1(implode($tempArr))){
            echo $echostr;
        }else{
            exit();
        }
    }*/


    public function index() {
        //https://jiaqiancaifu.dahengdian.com/active/wechat/index
        //6a204bd89f3c8348afd5c77c717a097a
        //O0pHtIhxWbAvcnCWiKPKJeX8tpAcrnTKznh9AvH28cE
        $wechatAuth = new WechatAuth('wx5c2cb0242ae05436','0a668108cea8d819fbe48e41b9e0c396');
        Log::info($wechatAuth->getAccessToken());
        $wechat = new WechatCommon('6a204bd89f3c8348afd5c77c717a097a'/*, 'O0pHtIhxWbAvcnCWiKPKJeX8tpAcrnTKznh9AvH28cE', 'wx5c2cb0242ae05436'*/);
        $token = $wechatAuth->getAccessToken();
        $data = $wechat->request();
        Log::info($data);
        $stat = [];

        $userCanS = [
            'o1Bky0hQSqcmEeodarB8JP9rfKes',//徐总
            'o1Bky0mQVkNebqyQOqDB5M_Vj3TM',//张奇
            'o1Bky0m4xs9j_PqFyLvfAA3Uwux4',//张江
            'o1Bky0pq59Ho1oP5VzE5YFxLCxgg',//吴总
        ];
        if(!in_array($data['FromUserName'], $userCanS)) {
            exit;
        }
        if($data['Content'] == '存量') {
            $stat = Stat::getStat();
            $todayText = '今日总交易额'.$stat['moneySubjectTotal_today']."元\n";
            $todayText .= '订单数'.$stat['cangCount_today']."笔\n\n";

            $todayText .= '累计交易额'.$stat['moneySubjectTotal_all']."元\n";
            $todayText .= '当前存量'.$stat['cunTotal_all']."元\n\n";
            $todayText .= '以上信息仅限于新平台  :)';
            $wechat->replyText($todayText, $token['access_token']);
        }



        if($data['Content'] == '交易') {
            $list = Db::view('cang','cangID,subjectID,moneySubject,status,addTime,year,investDay,interest,money,userID,isForged,hongbao,yearExt')
                ->view('user','userID,trueName,mobile,channelID,isNewInvest,osType','cang.userID=user.userID and user.isForged=0')
                ->view('subject','subjectID,title','cang.subjectID=subject.subjectID')
                ->view('channel','channelID,name as channelName','user.channelID=channel.channelID', 'left')
                ->view('master','trueName as customer,masterID,mobile as customerMobile','master.masterID=cang.masterID', 'left')
                //->view('user_hongbao','userHongbaoID,sum(money),sum(interest)','cang.hongbao in (user_hongbao.userHongbaoID)', 'left')
                ->whereTime('addTime', 'today')
                ->order('cangID desc')
                ->select();

            $todayText = '';
            foreach ($list as $k=>$item) {
                $list[$k]['addTime'] = Common::timetodate($list[$k]['addTime']);
                $list[$k]['money'] = (int)($list[$k]['money'] / 100);
                $list[$k]['moneySubject'] = (int)($list[$k]['moneySubject'] / 100);
                $list[$k]['interest'] = Common::price2($list[$k]['interest'] / 100);
                $list[$k]['mobile'] = Common::mobileAsterisk($list[$k]['mobile']);
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


                $todayText .= "\n\n".$list[$k]['moneySubject'].'元 '.$list[$k]['mobile'].' '.$list[$k]['trueName']."\n";
                $todayText .= $list[$k]['title'].' '.$list[$k]['investDay'].'天 '."\n";
                $todayText .= $list[$k]['addTime']."\n";
                $todayText .= '渠道：'.$list[$k]['osType'].' '.$list[$k]['channelName']."\n\n";
                $todayText .= '--------------------------------';
                /*$todayText .= '订单数'.$stat['cangCount_today']."笔\n\n";

                $todayText .= '累计交易额'.$stat['moneySubjectTotal_all']."元\n";
                $todayText .= '当前存量'.$stat['cunTotal_all']."元\n\n";
                $todayText .= '以上信息仅限于新平台  :)';*/
            }
            $todayText .= "\n\n".'以上交易发生于 '.Common::timetodate(THINK_START_TIME, 0)."\n";
            $todayText .= '以上信息仅限于新平台  :)';
            $wechat->replyText($todayText, $token['access_token']);
        }


        /*$wechat->replyText($data['Content'], $token['access_token']);
        $wechat->replyText($data['Content'], $token['access_token']);
        $wechat->replyText($data['Content'], $token['access_token']);
        $wechat->replyText($data['Content'], $token['access_token']);
        $wechat->replyText($data['Content'], $token['access_token']);
        $wechat->replyText($data['Content'], $token['access_token']);
        $wechat->replyText($data['Content'], $token['access_token']);*/
        //$wechat->
    }
}
