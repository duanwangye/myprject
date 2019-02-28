<?php
namespace app\master\controller;
use app\core\model\InterestTimeType;
use app\core\model\InterestType;
use app\core\model\SubjectType;
use app\core\model\Subject as Model;
use app\master\logic\Subject as Logic;
use think\Config;


class Subject extends Base
{
    //得到产品类型
    public function getSubjectTypeList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //得到计息类型
    public function getInterestTypeList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //生成一个标的
    public function update() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //得到列表
    public function getSubjectList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //得到状态列表
    public function getStatusList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //得到放款列表
    public function getStatusLoanList() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //审核通过，上线
    public function actionOnline() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //审核通过，上线
    public function actionDelete() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //虚拟认购
    public function actionForged() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    //得到详细
    public function getDetail() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function actionFang() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }

    public function actionExport() {
        $function = __FUNCTION__;
        return json($this->logic->$function());
    }
    /*public function index()
    {
        $subjectTypeList = SubjectType::all();
        $statusList = Model::getStatusList();
        $this->assign('subjectTypeList',$subjectTypeList->toArray());
        $this->assign('statusList',$statusList);
        // 模板输出
        return $this->fetch();
    }

    public function update()
    {

        $subjectTypeList = SubjectType::all();
        $interestTypeList = InterestType::all();
        $interestTimeTypeList = InterestTimeType::getInterestTimeTypeList(Config::get('interest.default_interest_time_type'));

        // 模板变量赋值
        $this->assign('subjectTypeList', $subjectTypeList->toArray());
        $this->assign('interestTypeList', $interestTypeList->toArray());
        $this->assign('interestTimeTypeList', $interestTimeTypeList);
        $this->assign('defaultInterestTimeTypeID', Config::get('interest.default_interest_time_type'));
        $this->assign('overtimeIsinterest', Config::get('interest.overtime_isinterest'));
        $this->assign('repaytimeDelaySpan', Config::get('interest.repaytime_delay_span'));

        // 或者批量赋值
        $this->assign([
            'name'  => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
        // 模板输出
        return $this->fetch();
    }*/







    public $logic;
    public function __initialize() {
        $this->logic = new Logic($this->request);
    }
}
