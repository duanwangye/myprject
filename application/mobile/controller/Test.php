<?php
namespace app\mobile\controller;

use app\mobile\logic\Cang as Logic;
use think\Controller;
use think\Exception;
use think\Log;

class Test extends Controller
{
    //jiaqiancaifu.dahengdian.com/mobile/test/registerSendMobileCode
    public function registerSendMobileCode()
    {

        $curl = curl_init("https://jiaqiancaifu.dahengdian.com/mobile/user/registerSendMobileCode");
        $app = [
            'mobile'=>'17316900863'
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/register
    public function register()
    {
        $curl = curl_init($this->urlCreate('user/register'));
        $app = [
            'mobile'=>'17316900863',
            'mobileCode'=>'6310',
            'password'=>'111111',
            'passwordRe'=>'111111'
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/logout
    public function logout()
    {
        $curl = curl_init($this->urlCreate('user/logout'));
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }


    //http://jiaqiancaifu.dahengdian.com/mobile/test/getLoginAd
    public function getLoginAd()
    {
        $curl = curl_init($this->urlCreate('ext/getLoginAd'));
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/loginByPassword
    public function loginByPassword()
    {
        $curl = curl_init($this->urlCreate('user/loginByPassword'));
        $app = [
            'mobile'=>'17316900863',
            'password'=>'qissen111111'
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }


    //http://jiaqiancaifu.dahengdian.com/mobile/test/getUserBankList
    public function getUserBankList()
    {
        $curl = curl_init($this->urlCreate('user/getUserBankList'));
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/getUserInfo
    public function getUserInfo()
    {
        $curl = curl_init($this->urlCreate('user/getUserInfo'));
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/cang
    public function cang() {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/cang/create");
        $app = [
            'moneySubject'=>'100.00',
            'subjectID'=>'44',
            'hongbaoIDS'=>[155]
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/getUserHongbaoList
    public function getUserHongbaoList()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/user/getUserHongbaoList");
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/getIndexInfo
    public function getIndexInfo()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/index/getIndexInfo");
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/getSubjectList
    public function getSubjectList()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/subject/getSubjectList");
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/contract
    public function contract()
    {
        $app = [
            'money'=>'100.32',
            'userBankID'=>13
        ];
        $url = 'http://jiaqiancaifu.dahengdian.com/mobile/h5/contract?track='.$this->dataCreateH5($app);
        $this->redirect($url);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/getBankList
    public function getBankList()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/system/getBankList");
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/checkInfo
    public function checkInfo()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/user/checkInfo");
        $app = [
            'trueName'=>'张奇',
            'passport'=>'230523198606180810',
            'mobile'=>'13136180523',
            'mobileCode'=>'892686',
            'bankNumber'=>'6217001540014586968',
            'bankID'=>4
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/checkInfoSendMobileCode
    public function checkInfoSendMobileCode()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/user/checkInfoSendMobileCode");
        $app = [
            'mobile'=>'13516825311'
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/getConfig
    public function getConfig()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/system/getConfig");
        $app = [
            'mobile'=>'17316800863'
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/getUserFinanceList
    public function getUserFinanceList()
    {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/user/getUserFinanceList");
        $app = new \stdClass();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/userRecharge
    public function userRecharge()
    {
        $app = [
            'money'=>'100.32',
            'userBankID'=>13
        ];
        $url = 'http://jiaqiancaifu.dahengdian.com/mobile/h5/userRecharge?track='.$this->dataCreateH5($app);
        $this->redirect($url);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/userDrawcash
    public function userDrawcash()
    {
        $app = [
            'money'=>'1',
            'userBankID'=>13
        ];
        $url = 'http://jiaqiancaifu.dahengdian.com/mobile/h5/userDrawcash?track='.$this->dataCreateH5($app);
        $this->redirect($url);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/activeList
    public function activeList()
    {
        $app = [
            'money'=>'1',
            'userBankID'=>13
        ];
        $url = 'http://jiaqiancaifu.dahengdian.com/mobile/h5/activeList?track='.$this->dataCreateH5($app);
        $this->redirect($url);
    }

    //http://jiaqiancaifu.dahengdian.com/mobile/test/subjectContent
    public function subjectContent()
    {
        $app = [
            'subjectID'=>13
        ];
        $url = 'http://jiaqiancaifu.dahengdian.com/mobile/h5/subjectContent?track='.$this->dataCreateH5($app);
        $this->redirect($url);
    }


    //http://jiaqiancaifu.dahengdian.com/mobile/test/safe
    public function safe()
    {
        $app = [
            'subjectID'=>13
        ];
        $url = 'http://jiaqiancaifu.dahengdian.com/mobile/h5/safe?track='.$this->dataCreateH5($app);
        $this->redirect($url);
    }

    public function getCangList() {
        $curl = curl_init("http://jiaqiancaifu.dahengdian.com/mobile/cang/getCangList");
        $app = [
            'status'=>1
        ];
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataCreate($app));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($curl);
    }

    public function urlCreate($apiIcon) {
        return 'http://jiaqiancaifu.dahengdian.com/mobile/'.$apiIcon;
    }

    public function dataCreateH5($app = []) {
        $data['apiV'] = 'v1';
        $data['osV'] = 'android1.1';
        $data['osType'] = '1';
        $data['appV'] = '1';
        $data['deviceID'] = 'a898723jhjka89789auioajhfa';
        $data['token'] = '9cc40f6e4f9a16d5d6691188eaccad4b';
        $data['ip'] = '192.168.1.1';
        $data['channel'] = '123';
        $data['time'] = (int)THINK_START_TIME;
        $appJson = json_encode($app, JSON_UNESCAPED_UNICODE);
        Log::info($appJson);
        $signPre = 'aladfa5a4g46jh4vb44n4e4r4t'.$data['token'].$data['time'].$data['appV'].$data['apiV'].$data['osV'].$data['osType'].$data['deviceID'].$data['ip'].$data['channel'].$appJson;
        Log::info($signPre);
        $data['sign'] = md5($signPre);
        $data['app'] = $app;

        return json_encode($data);
    }

    public function dataCreate($app = []) {
        $data['apiV'] = 'v1';
        $data['osV'] = 'android1.1';
        $data['osType'] = '1';
        $data['appV'] = '1';
        $data['deviceID'] = 'a898723jhjka89789auioajhfa';
        $data['token'] = '9cc40f6e4f9a16d5d6691188eaccad4b';
        $data['ip'] = '192.168.1.1';
        $data['channel'] = '123';
        $data['time'] = (int)THINK_START_TIME;
        $data['app'] = $app;
        $appJson = json_encode($data['app'], JSON_UNESCAPED_UNICODE);
        Log::info($appJson);
        $signPre = 'aladfa5a4g46jh4vb44n4e4r4t'.$data['token'].$data['time'].$data['appV'].$data['apiV'].$data['osV'].$data['osType'].$data['deviceID'].$data['ip'].$data['channel'].$appJson;
        Log::info($signPre);
        $data['sign'] = md5($signPre);

        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $data;
    }

    public function loginDataCreate($app) {
        $data['apiV'] = 'v1';
        $data['osV'] = 'android1.1';
        $data['osType'] = '1';
        $data['appV'] = '1';
        $data['deviceID'] = 'a898723jhjka89789auioajhfa';
        $data['token'] = '';
        $data['channel'] = '123';
        $data['time'] = (int)THINK_START_TIME;
        $data['app'] = $app;
        $data['ip'] = '192.168.1.1';
        $appJson = json_encode($data['app']);
        Log::info($appJson);
        $signPre = $data['token'].$data['time'].$data['appV'].$data['apiV'].$data['osV'].$data['osType'].$data['deviceID'].$data['ip'].$data['channel'].$appJson;
        Log::info($signPre);
        $data['sign'] = md5($signPre);
        Log::info($data);

        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
