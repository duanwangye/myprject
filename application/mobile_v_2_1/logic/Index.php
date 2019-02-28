<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile_v_2_1\logic;

use app\core\model\Banner;
use app\core\model\Subject;

use app\core\service\Stat;
use think\Cache;
use think\Config;
use tool\Common;

class Index extends Base
{

    public function getIndexInfo()
    {
        $buttonBigList = $this->skin['index_button'];
        foreach ($buttonBigList as $k=>$item) {
            if($item['link']) {
                if(strpos($item['link'], 'http') === false) {
                    $buttonBigList[$k]['link'] = $this->h5RootUrl().'/'.$item['link'].'?######';
                }
            }
            if(isset($item['share']['link']) && strpos($item['share']['link'], '@uuid@') !== false) {
                if($this->user) {
                    $buttonBigList[$k]['share']['link'] = str_replace('@uuid@', $this->user['uuid'], $buttonBigList[$k]['share']['link']);
                }
                else {
                    $buttonBigList[$k]['share']['link'] = str_replace('@uuid@', '', $buttonBigList[$k]['share']['link']);
                }
            }
        }


        //banner
        $map = [];
        $map['clientType'] = 1;
        if($this->data['osType'] == 3) {
            $map['clientType'] = 2;
        }
        $map['status'] = Banner::STATUS_ONLINE;
        $bannerList = Banner::where($map)->order('bannerID desc')->select();
        if($bannerList) {
            $bannerList->visible(['thumb','link','title']);
        }



        //subjectHot
        $map = [];
        $map['isShow'] = 1;
        if($this->data['osType'] != 3) {
            $map['isIndexPc'] = 1;
        }
        else {
            $map['isIndexApp'] = 1;
        }
        $map['subjectTypeID'] = 1;
        $map['status'] = ['in', [Subject::STATUS_ONLINE, Subject::STATUS_FULL]];
        $subjectHot = Subject::with('subjectType,interestType,interestTimeType,subjectStat')->where($map)->order('addTime desc')->find();
        if($subjectHot) {
            $subjectHot->append(['statusText','investDay', 'repayTime','reachTime','unit','interestBeginTime'])->hidden(['subjectTypeID','beginTime','endTime','multiplePrice','interestTypeID','interestTimeTypeID','isIndexApp','isIndexPc','operation','updateTime',
                'subjectStat'=>[
                    'subjectID','subjectStatID'
                ]
            ])->toArray();

            $subjectHot['contentUrl1'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=1' . '&######';
            $subjectHot['contentUrl2'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=2' . '&######';
            $subjectHot['contentUrl3'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=3' . '&######';
            $subjectHot['subjectType']['icon'] = isset($this->skin['subjectType_icon'][$subjectHot['subjectType']['subjectTypeID']]) ? $this->skin['subjectType_icon'][$subjectHot['subjectType']['subjectTypeID']] : '';
        }


        //$subjectRecommendList
        $map = [];
        $map['isShow'] = 1;
        if($this->data['osType'] != 3) {
            $map['isIndexApp'] = 1;
        }
        else {
            $map['isIndexPc'] = 1;
        }
        $map['subjectTypeID'] = ['neq', 1];
        $map['status'] = Subject::STATUS_ONLINE;
        if($subjectHot) {
            $map['subjectID'] = ['neq', $subjectHot['subjectID']];
        }
        $subjectRecommendList = Subject::with('subjectType,interestType,interestTimeType,subjectStat')->where($map)->limit(0,3)->order('addTime desc')->select();
        if(!$subjectRecommendList->isEmpty()) {
            $subjectRecommendList->append(['statusText','investDay', 'repayTime','reachTime','unit','interestBeginTime'])->hidden(['subjectTypeID','beginTime','endTime','multiplePrice','interestTypeID','interestTimeTypeID','isIndexApp','isIndexPc','operation','updateTime',
                'subjectStat'=>[
                    'subjectID','subjectStatID'
                ]
            ])->toArray();

            foreach ($subjectRecommendList as $k=>$item) {
                $subjectRecommendList[$k]['contentUrl1'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=1' . '&######';
                $subjectRecommendList[$k]['contentUrl2'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=2' . '&######';
                $subjectRecommendList[$k]['contentUrl3'] = $this->h5RootUrl().'/mobile/h5/subjectContent?tab=3' . '&######';
                $subjectRecommendList[$k]['subjectType']['icon'] = isset($this->skin['subjectType_icon'][$subjectRecommendList[$k]['subjectType']['subjectTypeID']]) ? $this->skin['subjectType_icon'][$subjectRecommendList[$k]['subjectType']['subjectTypeID']] : '';
            };
        }

        $stat = Cache::get('stat_laojiaqian');
        $tradeTotal = isset($stat['stat_totalAll']) ? $stat['stat_totalAll'] : "11231231.00";
        $userMakeTotal = isset($stat['stat_usermakeTotal']) ? $stat['stat_usermakeTotal'] : "54123.21";
        $safeDayTotal = (int)((THINK_START_TIME - Common::datetotime('2017-11-11')) / 86400);
        $userTotal = isset($stat['stat_userTotal']) ? $stat['stat_userTotal'] : "23665";
        return Common::rm(1, '操作成功', [
            'notice1'=>'',
            'notice2'=>'',
            'noticeList'=>Stat::getStatUserAction(),
            'noticeListConfig'=>[
                'cycle'=>1000
            ],
            'buttonBigList'=>$buttonBigList ? $buttonBigList : [],
            'bannerList'=>$bannerList ? $bannerList : [],
            'subjectHot'=>$subjectHot ? $subjectHot : new \stdClass(),
            'subjectRecommendList'=>$subjectRecommendList ? $subjectRecommendList : [],
            /*'bottomIcon'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/01/04/aghypFQ6t0.png',*/
            /*'bottomIcon'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/01/16/OFlkpTWxoI.png',*/
            'bottomIcon'=>'',
            'stat'=>[
                "tradeTotal"=>$tradeTotal,
                "userMakeTotal"=>$userMakeTotal,
                "safeDayTotal"=>$safeDayTotal,
                "userTotal"=>$userTotal
            ]
        ]);

        /*return Common::rm(1, '操作成功', [
            'notice1'=>'累计交易额<font color="red">6,449,502</font>元',
            'notice2'=>'已安全运营<font color="red">78</font>天',
            'buttonBigList'=>$buttonBigList ? $buttonBigList : [],
            'bannerList'=>$bannerList ? $bannerList : [],
            'subjectHot'=>$subjectHot ? $subjectHot : new \stdClass(),
            'subjectRecommendList'=>$subjectRecommendList ? $subjectRecommendList : [],
            //'bottomIcon'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/01/04/aghypFQ6t0.png',
            'bottomIcon'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/01/16/OFlkpTWxoI.png',
            'stat'=>[
                "tradeTotal"=>"1231231.20",
                "userMakeTotal"=>"123321.12",
                "safeDayTotal"=>"1313",
                "userTotal"=>"23665"
            ]
        ]);*/
    }
}