<?php
namespace app\index\controller;


use think\Controller;
use think\Log;

class Index extends Controller
{
    public function index() {
        $this->redirect('http://slb.dahengdian.com/jiaqiancaifu/jqcf_2.0.0_bojia_20180115094647_release_200_jiagu_sign.apk');
    }
}
