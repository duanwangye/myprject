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


class SubjectStat extends Model
{
    protected $resultSetType = 'collection';
    protected $pk = 'subjectStatID';

    public function setMoneyTotalInvestAttr($value) {
        return $value * 100;
    }

    public function getMoneyTotalInvestAttr($value) {
        return (int)($value / 100);
    }
}