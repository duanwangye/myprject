<?php
namespace app\mobile\controller;

use app\mobile\logic\User;

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
