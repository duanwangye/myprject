<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\laojiaqian\model;

use think\Model;


class Project extends Model
{
    protected $autoWriteTimestamp = true;
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => 'rm-bp1m6abztwo27gum6.mysql.rds.aliyuncs.com',
        // 数据库名
        'database'    => 'goldapi',
        // 数据库用户名
        'username'    => 'sqlyog',
        // 数据库密码
        'password'    => 'Qissen111111',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 's_',
    ];

}