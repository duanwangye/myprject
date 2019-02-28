<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Config;
use think\Db;
use think\Exception;
use think\Log;
use think\Model;
use tool\Common;

class User extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const STATUS_OK = 0;
    const STATUS_UNBANDING = 1;

    const STATUSS = [
        self::STATUS_OK=>'正常',
        self::STATUS_UNBANDING=>'锁定',
    ];

    public function userAccount() {
        return $this->hasOne('userAccount', 'userID');
    }

    public function channel() {
        return $this->belongsTo('channel', 'channelID');
    }

    public function createUUID($userID) {
        return Common::timetodate(THINK_START_TIME,110).mt_rand(1000,9999).sprintf('%06s', $userID);
    }

    public function userBank() {
        return $this->hasOne('userBank', 'userID');
    }

    public function createPassword($password) {
        return md5('uaaoidusoia9a8d8fsd7f900a7d9sfadjfjadjf'.$password);
    }

    public function createToken() {
        return md5(THINK_START_TIME.Common::token_create(10));
    }

    public function createTokenOverTime() {
        return (int)THINK_START_TIME + 7 * 86400;
    }

    public function getMobileAsteriskAttr($value, $data) {
        return Common::mobileAsterisk($data['mobile']);
    }

    public function getLoginTimeAttr($value)
    {
        if($value == 0) {
            return '';
        }
        return 0;
    }

    public function getAvatarAttr($value, $data) {
        return 'http://slb.dahengdian.com/jiaqiancaifu/2018/01/03/V5KL8LKbLE.png';
    }

}