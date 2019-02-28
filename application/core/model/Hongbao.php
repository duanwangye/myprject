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


class Hongbao extends Model
{
    protected $pk = 'hongbaoID';
    protected $resultSetType = 'collection';
    protected $updateTime = 'updateTime';
    protected $createTime = 'addTime';
    protected $autoWriteTimestamp = true;

    const STATUS_ONLINE = 1;
    const STATUS_OFFLINE = 2;

    const STATUSS = [
        self::STATUS_ONLINE=>'已启用',
        self::STATUS_OFFLINE=>'未启用',
    ];


    const TYPE_XIANJIN = 1;
    const TYPE_JIAXI = 2;
    const TYPESS = [
        [
            'typeID'=>self::TYPE_XIANJIN,
            'name'=>'现金券'
        ],
        [
            'typeID'=>self::TYPE_JIAXI,
            'name'=>'加息券'
        ]
    ];

    public function getTypeList() {
        return self::TYPESS;
    }


    public function setMinMoneyAttr($value) {
        return $value * 100;
    }

    public function getYearAttr($value) {
        return sprintf("%.1f",$value);
    }

    public function getMinMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function setMaxMoneyAttr($value) {
        return $value * 100;
    }

    public function getMaxMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function setMoneyAttr($value) {
        return $value * 100;
    }

    public function getMoneyAttr($value) {
        return Common::price2($value / 100);
    }

    public function getStatusTextAttr($value, $data) {
        return self::STATUSS[$data['status']];
    }

    public function getTypeNameAttr($value, $data) {
        foreach (self::TYPESS as $item) {
            if($item['typeID'] == $data['typeID']) {
                return $item['name'];
            }
        }
        return '未知';
    }

    public function getMinDayTextAttr($value, $data) {
        if($data['minDay'] == 0) {
            return '所有产品通用';
        }
        return '投资不低于'.$data['minDay'].'天使用';
    }

    public function getMinMoneyTextAttr($value, $data) {
        $minMoney = (int)$this->getMinMoneyAttr($data['minMoney']);
        if($minMoney == 0) {
            return '满0元使用';
        }
        return '满'.$minMoney.'元使用';
    }

    public function getBuyTextAttr($value, $data) {
        $text = '';
        if($data['typeID'] == self::TYPE_JIAXI) {
            $text = '加息'.$data['year'].'%';
        }
        else {
            $text = '现金券'.(int)$this->getMoneyAttr($data['money']).'元';
        }
        return $text;
    }
}