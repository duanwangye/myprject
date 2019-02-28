<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;


use think\Model;


class Sms extends Model
{
    protected $resultSetType = 'collection';
    protected $pk = 'smsID';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;
}