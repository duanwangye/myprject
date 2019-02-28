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

// 应用行为扩展定义文件
return [
    // 下单成功后
    'tag_cang_create_success'     => [
        'app\\mobile\\behavior\\CashSend',//发放现金
        'app\\mobile\\behavior\\UserHonor_Cang'//下单积分逻辑
    ],
    'tag_user_introduce_success'     => [
        'app\\mobile\\behavior\\UserIntroduce',//邀请注册
    ],
];
