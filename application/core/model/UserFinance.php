<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;

use think\Model;
use tool\Common;

class UserFinance extends Model
{
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;
    const MODE_RECHARGE = 'MODE_RECHARGE';//充值
    const MODE_DRAWCASH = 'MODE_DRAWCASH';//提现
    const MODE_CANG = 'MODE_CANG';//下单
    const MODE_REPAY_INTEREST = 'MODE_REPAY_INTEREST';//还息
    const MODE_REPAY_BEN = 'MODE_REPAY_BEN';//还本
    const MODE_EXT = 'MODE_EXT';//其他
    const MODE_BI = 'MODE_BI';//本息
    const MODE_CASHSEND = 'MODE_CASHSEND';//送现金
    const STATUS_OK = 1;
    const STATUS_INVALID = 2;
    const STATUS_FROZEN =3;

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function setMoneyPreAttr($value) {
        return $value * 100;
    }

    public function getMoneyPreAttr($value) {
        return Common::price2($value / 100);
    }

    public function setMoneyNowAttr($value) {
        return $value * 100;
    }

    public function getMoneyNowAttr($value) {
        return Common::price2($value / 100);
    }

    public function getModeTextAttr($value, $data) {
        $modeText = '未知';
        switch ($data['mode']) {
            case 'MODE_RECHARGE':
                $modeText = '充值'.($data['status'] == self::STATUS_OK ? '成功' : '失败');
                break;
            case 'MODE_DRAWCASH':
                //$modeText = '提现'.$data['status'] == self::STATUS_OK ? '成功' : '失败';
                if($data['status'] == self::STATUS_OK) {
                    $modeText = '提现成功';
                }
                else if($data['status'] == self::STATUS_FROZEN){
                    $modeText = '预备到账';
                }
                else {
                    $modeText = '提现失败';
                }
                break;
            case 'MODE_CANG':
                $modeText = '购买'.($data['status'] == self::STATUS_OK ? '成功' : '失败');
                break;
            case 'MODE_REPAY_INTEREST':
                $modeText = '本金回款'.$data['status'] == self::STATUS_OK ? '成功' : '失败';
                break;
            case 'MODE_REPAY_BEN':
                $modeText = '利息回款'.$data['status'] == self::STATUS_OK ? '成功' : '失败';
                break;
            case 'MODE_EXT':
                $modeText = $data['status'] == self::STATUS_OK ? '成功' : '失败';
                break;
            case 'MODE_CASHSEND':
                $modeText = '送现金'.$data['status'] == self::STATUS_OK ? '成功' : '失败';
                break;
            default:
                $modeText = '未知';
                break;
        }
        return $modeText;
    }
}