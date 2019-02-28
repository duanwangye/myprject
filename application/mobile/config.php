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
        'mobile/user/registerSendMobileCode',
        'mobile/user/register',
        'mobile/user/login',
        'mobile/user/loginByPassword',
        'mobile/user/loginByMobileCode',
        'mobile/user/checkUserByMobile',
        'mobile/user/resetPassword',
        'mobile/user/loginSendMobileCode',
        'mobile/subject/getSubjectList',
        'mobile/subject/getSubjectDetail',
        'mobile/subject/getSubjectContent',
        'mobile/index/getIndexInfo',
        'mobile/active/getActiveList',

        'mobile/system/getConfig',
        'mobile/h5/subjectContent',
        'mobile/ext/getLoginAd',
        'mobile/h5/guarantee',
        'mobile/h5/regAgreement',
        'mobile/h5/activeList',
        'mobile/h5/about',
        'mobile/h5/safe',
        'mobile/h5/userInvitationPage',
        'mobile/system/getUpgradeInfo',
        'mobile/system/getUpgradeInfoForIOS'
    ],


    'h5RootUrl'=>'https://wxapp.dahengdian.com'
];
