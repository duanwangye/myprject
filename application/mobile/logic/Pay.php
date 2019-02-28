<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\mobile\logic;

use app\core\service\Pay as Service;
use tool\Common;

class Pay extends Base
{
    public function createOrder()
    {
        $this->app = [
            'money'=>300,
            'orderID'=>12
        ];
        //Cang::get($this->app['order']);
        $service = new Service();
        $order = $service->createOrder(300);
        if(!$order) {
            return Common::rm(-3, '操作失败');
        }

        return Common::rm(1, '操作成功', $order);
    }



}