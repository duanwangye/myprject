<?php
namespace app\master\controller;


class Test
{
    public function loginByPassword()
    {
        $curl = curl_init($this->urlCreate('master/loginByPassword'));
        $app = [
            'mobile'=>'17316900863',
            'password'=>'111111'
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->loginDataCreate($app)));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        return curl_exec($curl);
    }

    public function cangCreate()
    {
        $curl = curl_init($this->urlCreate('cang/cangCreate'));
        $app = [
            'mobile'=>'17316900863',
            'password'=>'111111'
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->loginDataCreate($app)));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        return curl_exec($curl);
    }

    public function hongbaoPlanSendUser()
    {
        $curl = curl_init($this->urlCreate('market/hongbaoPlanSendUser'));
        $app = [
            'userID'=>15728,
            'hongbaoIDS'=>[42,43]
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->dataCreate($app)));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        return curl_exec($curl);
    }

    public function urlCreate($apiIcon) {
        return 'http://wxapp.dahengdian.com/master/'.$apiIcon;
    }

    //dataCreate
    public function dataCreate($app = []) {
        $data['token'] = '0a15d8e33aacbc6930fdca3c47112ad9';
        $data['app'] = $app;
        return $data;
    }


    public function loginDataCreate($app = []) {
        $data['token'] = '';
        $data['app'] = $app;
        return $data;
    }
}
