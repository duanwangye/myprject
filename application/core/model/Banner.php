<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Model;


class Banner extends Model
{
    protected $pk = 'bannerID';
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const STATUS_ONLINE = 1;
    const STATUS_OFFLINE = 2;

    const STATUSS = [
        self::STATUS_ONLINE=>'已上线',
        self::STATUS_OFFLINE=>'已下线'
    ];

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function getClientTypeTextAttr($value, $data) {
        if($data['clientType'] == 1) {
            return 'app';
        }
        else if($data['clientType'] == 2) {
            return 'pc';
        }
        return '未知';
    }

   /* public function getUpdateTimeAttr($value) {
        return Common::timetodate($value, 6);
    }

    public function getAddTimeAttr($value) {
        return Common::timetodate($value, 6);
    }*/
}