<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\core\model;


use think\Model;


class BankPlatform extends Model
{
    protected $resultSetType = 'collection';
    protected $pk = 'bankPlatformID';


    public function bank() {
        return $this->belongsTo('bank', 'bankID');
    }
}
