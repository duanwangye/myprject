<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;


use think\Model;


class InterestTimeType extends Model
{
    protected $resultSetType = 'collection';
    protected $pk = 'interestTimeTypeID';






    public static function getInterestTimeTypeList($defaultInterestTimeType = 1) {
        $list = InterestTimeType::all();
        $list = $list->toArray();
        $resultList = [];
        foreach ($list as $k=>$item) {
            if($item['interestTimeTypeID'] == $defaultInterestTimeType) {
                array_push($resultList, $item);
            }
        }
        return $resultList;
    }
}