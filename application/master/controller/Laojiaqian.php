<?php
namespace app\master\controller;


use app\master\logic\Laojiaqian as Logic;

class Laojiaqian extends Base
{

    //注销金账户
    /*public function cancelPlatformUser() {

        $param = request()->param();

        vendor('payModel.User');
        $user = new \User();
        $user->setMobile($param['mobile']);
        $user->setPageBackurl('http://wxapp.dahengdian.com/master/notify/cancelUser');
        $data = (new Finance())->userCancel($msg, $user);


        $model = Model::get([
            'mobile'=>$param['mobile']
        ]);
        if($model) {
            $model->isAuthTrueName = 0;
            $model->isAuthBank = 0;
            $model->save();

            UserBank::destroy([
                'userID'=>$model['userID']
            ]);
        }




        return view(__FUNCTION__, [
            'resourcePath'=>'/static/'.request()->path(),
            'param'=>$data['param'],
            'url'=>$data['url']
        ]);
    }*/


    public function getCangList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }

}

