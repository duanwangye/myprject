<?php
namespace app\laojiaqian\controller;
use app\laojiaqian\model\User;
use think\Controller;
use think\Log;

class Index extends Controller
{
    public function index()
    {
        dump(User::get([
            'id'=>1
        ]));
    }
}
