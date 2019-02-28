<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    //游客可访问的路径
    'tourist_path'=>[
        /*'mobile_v_2_1/user/registerSendMobileCode',
        'mobile_v_2_1/user/register',
        'mobile_v_2_1/user/login',
        'mobile_v_2_1/user/loginByPassword',
        'mobile_v_2_1/user/loginByMobileCode',
        'mobile_v_2_1/user/checkUserByMobile',
        'mobile_v_2_1/user/resetPassword',
        'mobile_v_2_1/user/loginSendMobileCode',
        'mobile_v_2_1/subject/getSubjectList',
        'mobile_v_2_1/subject/getSubjectDetail',
        'mobile_v_2_1/subject/getSubjectContent',
        'mobile_v_2_1/index/getIndexInfo',

        'mobile_v_2_1/system/getConfig',
        'mobile_v_2_1/h5/subjectContent',
        'mobile_v_2_1/ext/getLoginAd',
        'mobile_v_2_1/h5/guarantee',
        'mobile_v_2_1/h5/activeList',
        'mobile_v_2_1/h5/safe',
        'mobile_v_2_1/system/getUpgradeInfo'*/


        'mobile_v_2_1/user/registerSendMobileCode',
        'mobile_v_2_1/user/register',
        'mobile_v_2_1/user/login',
        'mobile_v_2_1/user/logout',
        'mobile_v_2_1/user/loginByPassword',
        'mobile_v_2_1/user/loginByMobileCode',
        'mobile_v_2_1/user/checkUserByMobile',
        'mobile_v_2_1/user/resetPassword',
        'mobile_v_2_1/user/loginSendMobileCode',
        'mobile_v_2_1/subject/getSubjectList',
        'mobile_v_2_1/subject/getSubjectDetail',
        'mobile_v_2_1/subject/getSubjectContent',
        'mobile_v_2_1/index/getIndexInfo',
        'mobile_v_2_1/active/getActiveList',

        'mobile_v_2_1/system/getConfig',
        'mobile_v_2_1/h5/subjectContent',
        'mobile_v_2_1/ext/getLoginAd',
        'mobile_v_2_1/h5/guarantee',
        'mobile_v_2_1/h5/regAgreement',
        'mobile_v_2_1/h5/activeList',
        'mobile_v_2_1/h5/about',
        'mobile_v_2_1/h5/safe',
        'mobile_v_2_1/h5/userInvitationPage',
        'mobile_v_2_1/system/getUpgradeInfo',
        'mobile_v_2_1/system/getUpgradeInfoForIOS'
    ],

    'app_skin_config'=>[
        'app_index_big_button'=>[
            [
                'imageUrl'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/02/08/8hjT5P9D0d.png',
                'link'=>'mobile_v_2_1/h5/userInvitationPage',
                'text'=>'邀请有礼',
                'subText'=>'邀请有礼',
                'share'=>[
                    'headImgUrl'=>'https://static.qimai.cn/static/img/newaso100@2x.png',
                    'title'=>'邀请有礼',
                    'desc'=>'邀请有礼',
                    'link'=>'http://www.qq.com'
                ]
            ],
            [
                'imageUrl'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/02/08/IOPOqaHTei.png',
                'link'=>'mobile_v_2_1/h5/activeList',
                'text'=>'活动中心',
                'subText'=>'活动中心',
                'share'=>[
                    'headImgUrl'=>'https://static.qimai.cn/static/img/newaso100@2x.png',
                    'title'=>'活动中心',
                    'desc'=>'活动中心',
                    'link'=>'http://www.qq.com'
                ]
            ],
            [
                'imageUrl'=>'http://slb.dahengdian.com/jiaqiancaifu/2018/02/08/hhWK3Ea7Dk.png',
                'link'=>'active/index/xinshou',
                'text'=>'新手888元',
                'subText'=>'新手888元',
                'share'=>[
                    'headImgUrl'=>'https://static.qimai.cn/static/img/newaso100@2x.png',
                    'title'=>'新手888元',
                    'desc'=>'新手888元',
                    'link'=>'http://www.qq.com'
                ]
            ]
        ]
    ],
    'h5RootUrl'=>'https://wxapp.dahengdian.com'
];
