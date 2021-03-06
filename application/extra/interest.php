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
    'overtime_isinterest'=>0,//到期时间是否结息，1为结息，0为不结息
    'repaytime_delay_span'=>0,//标的到期时间间隔多少秒再自动还款，比如43200，为中午12点自动打款；0为标的到期，马上还款
    'reachtime_delay_span'=>1,//天
    'default_interest_time_type'=>3,//默认起息时间类型ID，1为满标计息
    'default_daily_release_span'=>20000,//每日发飙时间，从凌晨算起
];
