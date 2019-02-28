<?php
namespace app\mobile_v_2_1\controller;

use app\mobile_v_2_1\logic\User;

class Parnter
{

    //http://wxapp.dahengdian.com/mobile/test/register
    public $app = [];
    public $data = [];
    public function __construct()
    {
        $this->data = request()->param();

    }


    public function register() {
        $user = new User();

    }
}
