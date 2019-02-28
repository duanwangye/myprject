<?php
/**
 * Created by PhpStorm.
 * User: qisse
 * Date: 2017/6/27
 * Time: 20:02
 */

namespace app\master\logic;
use app\core\exception\AppException;
use app\core\model\Cang as Model;
use app\core\model\CangRepay;
use app\core\model\User;
use app\core\model\Subject;
use app\core\model\UserAccount;
use app\core\service\Finance as FinanceService;
use app\core\model\Sms;
use think\Db;
use think\Exception;
use think\Log;
use tool\Common;

class Cang extends Base
{
    /**
     * @api {post} cang/getCangList 得到我的投资列表
     * @apiVersion 1.0.0
     * @apiName getCangList
     * @apiDescription 得到我的投资列表
     * @apiGroup Cang
     *
     * @apiParam {Number=[0,1,2,3,4,5]} [status] 0-全部，1-持有中，2-未支付，3-利息中，4-待回款，5-已回款
     * @apiParam {Number} [subjectID] 标的ID
     * @apiParam {Number=[0,1,2,3]} [timeType]  时间类型，0-全部，1-今日，2-一周内，3-一月内
     * @apiParamExample {json} 发送报文:
    {
    "status": 1,
    "subjectID": 0,
    "timeType":1
    }
     *
     * @apiSuccess {Object[]} cangList 我的投资列表
     * @apiSuccess {Number} cangList.cangID ID
     * @apiSuccess {Number} cangList.moneySubject 投资份额（非本金，非实际支付金额）
     * @apiSuccess {Number} cangList.status 状态，1-已持有，4-已计息，5-已回款
     * @apiSuccess {String} cangList.statusText 状态描述，参考状态
     * @apiSuccess {String} cangList.payTime 交易时间
     * @apiSuccess {String} cangList.interestBeginTime 起息日期
     * @apiSuccess {String} cangList.year 年化
     * @apiSuccess {String} cangList.ben 实际投入本金
     * @apiSuccess {String} cangList.interest 利息
     * @apiSuccess {Number} cangList.investDay 投资天数
     * @apiSuccess {Object} cangList.subject 相关标的
     * @apiSuccess {String} cangList.subject.title 标的标题
     * @apiSuccess {Number} cangList.subject.subjectTypeID 标的类型
     * @apiSuccess {Number} cangList.subject.interestTypeID 计息类型
     * @apiSuccess {Number} cangList.subject.interestTimeTypeID 计息时间类型
     * @apiSuccess {String} cangList.subject.reachTime 标的标题
     * @apiSuccess {String} cangList.subject.title 标的标题
     * @apiSuccess {Object[]} cangList.cangRepay 预回款清单
     * @apiSuccess {String} cangList.cangRepay.money 回款金额
     * @apiSuccess {String} cangList.cangRepay.repayTime 回款类型
     * @apiSuccess {String} cangList.cangRepay.resultTime 实际到款时间
     * @apiSuccess {String} cangList.cangRepay.reachTime 到款时间
     * @apiSuccess {Number} cangList.cangRepay.status 清单状态，1-未回款，2-已回款，3-已到账（该状态可隐藏）
     * @apiSuccess {Number} cangList.cangRepay.repayTypeID 清单类型，1-本金，2-利息
     * @apiSuccess {Number} count 查询到条目总数
     * @apiSuccess {Number} pageItemCount 每页条数
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "cangList": [
    {
    "cangID": 33,
    "moneySubject": "2000.00",
    "status": 4,
    "payTime": "2017-12-12 20:53:50",
    "interestBeginTime": "2017-12-12",
    "year": "12.80",
    "ben": "2000.00",
    "interest": "7.01",
    "investDay": 10,
    "subject": {
    "subjectTypeID": 2,
    "interestTypeID": 1,
    "interestTimeTypeID": 1,
    "title": "普通标11111"
    },
    "cangRepay": [
    {
    "money": "2000.00",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 1
    },
    {
    "money": "7.01",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 2
    }
    ],
    "statusText": "计息中"
    },
    {
    "cangID": 30,
    "moneySubject": "98000.00",
    "status": 4,
    "payTime": "2017-12-12 18:28:27",
    "interestBeginTime": "2017-12-12",
    "year": "12.80",
    "ben": "98000.00",
    "interest": "343.67",
    "investDay": 10,
    "subject": {
    "subjectTypeID": 2,
    "interestTypeID": 1,
    "interestTimeTypeID": 1,
    "title": "普通标11111"
    },
    "cangRepay": [
    {
    "money": "98000.00",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 1
    },
    {
    "money": "343.67",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 2
    }
    ],
    "statusText": "计息中"
    }
    ]
    },
    "sign": "4297f44b13955235245b2497399d7a93"
    }
     * @apiUse CreateUserError
     */
    public function getCangList() {
        $app = $this->app;
        $query = Model::with(['subject','cangRepay','user'=>function($query) use($app) {
            if(isset($app['keyword']) && $app['keyword']) {
                $query->where('mobile','like' ,'%'.$app['keyword'].'%');
            }
        }]);
        if(isset($this->app['subjectID']) && $this->app['subjectID']) {
            $query = $query->where('subjectID', $this->app['subjectID']);
        }

        if(isset($this->app['timeType']) && $this->app['timeType']) {
            if($this->app['timeType'] == 1) {
                $query = $query->whereTime('addTime', 'd');
            }
            else if($this->app['timeType'] == 2){
                $query = $query->whereTime('addTime', 'w');
            }
            else if($this->app['timeType'] == 3){
                $query = $query->whereTime('addTime', 'm');
            }
        }

        if(isset($this->app['status']) && $this->app['status']) {
            $query = $query->where('status', $this->app['status']);
        }

        if(isset($this->app['isForged'])) {
            $query = $query->where('isForged', $this->app['isForged']);
        }

        if(!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        //$count = $query->count();
        $query1 = $query;
        $query2 = $query;
        $list = $query1->order('addTime desc')->limit(($this->app['pageIndex'] - 1) * $this->app['pageItemCount'], $this->app['pageItemCount'])->select();
        $count = $query2->count();
        if($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }

        $list->append(['isForgedText','statusText','user'=>['mobileAsterisk']])->visible(['cangID','isForged','addTime','moneySubject','payTime','status','investDay','interestBeginTime','year','interest','ben','money','subject'=>[
            'title','interestTimeTypeID','interestTypeID','subjectTypeID'
        ],'cangRepay'=>[
            'money','repayTime','reachTime','resultTime','status','repayTypeID'
        ], 'user'=>[
            'trueName','mobile'
        ]]);
        return Common::rm(1, '操作成功', [
            'cangList'=>$list,
            'count'=>$count,
            'pageItemCount'=>$this->app['pageItemCount']
        ]);
    }

    public function exportCangList()
    {
        $query = Model::with(['subject', 'cangRepay', 'user']);
        if (isset($this->app['subjectID']) && $this->app['subjectID']) {
            $query = $query->where('subjectID', $this->app['subjectID']);
        }

        if (isset($this->app['timeType']) && $this->app['timeType']) {
            if ($this->app['timeType'] == 1) {
                $query = $query->whereTime('addTime', 'd');
            } else if ($this->app['timeType'] == 2) {
                $query = $query->whereTime('addTime', 'w');
            } else if ($this->app['timeType'] == 3) {
                $query = $query->whereTime('addTime', 'm');
            }
        }

        if (isset($this->app['status']) && $this->app['status']) {
            $query = $query->where('status', $this->app['status']);
        }

        if (isset($this->app['keyword']) && $this->app['keyword']) {
            $query = $query->where('status', 'like', '%' . $this->app['keyword'] . '%')->where('status', 'like', '%' . $this->app['keyword'] . '%');
        }

        if (isset($this->app['isForged'])) {
            $query = $query->where('isForged', $this->app['isForged']);
        }

        if (!isset($this->app['pageItemCount']) || !$this->app['pageItemCount']) {
            $this->app['pageItemCount'] = 10;
        }

        //$count = $query->count();
        $query1 = $query;
        $query2 = $query;
        $list = $query1->order('addTime desc')->select();
        $count = $query2->count();
        if ($list->isEmpty()) {
            return Common::rm(-2, '数据为空');
        }

        $list->append(['isForgedText', 'statusText', 'user' => ['mobileAsterisk']])->visible(['cangID', 'isForged', 'addTime', 'moneySubject', 'payTime', 'status', 'investDay', 'interestBeginTime', 'year', 'interest', 'ben', 'money', 'subject' => [
            'title', 'interestTimeTypeID', 'interestTypeID', 'subjectTypeID'
        ], 'cangRepay' => [
            'money', 'repayTime', 'reachTime', 'resultTime', 'status', 'repayTypeID'
        ], 'user' => [
            'trueName', 'mobile'
        ]]);


        vendor('excel.PHPExcel');
        vendor('excel.PHPExcel_IOFactory');


        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("石头理财")->setLastModifiedBy("石头理财")->setTitle("title")->setSubject("subject")->setDescription("description")->setKeywords("keywords")->setCategory("Category");
        $objPHPExcel->setActiveSheetIndex(0)->setTitle('产品名称')->setCellValue("A1", "日期")->setCellValue("B1", "产品名称")->setCellValue("C1", "利率(%)")->setCellValue("D1", "还款手续费(元)")->setCellValue("E1", "合同利率(%)")->setCellValue("F1", "合同手续费(%)")->setCellValue("G1", "募集款数(元)")->setCellValue("H1", "易宝购买(元)")->setCellValue("I1", "钱包购买(元)")->setCellValue("J1", "超过部分(元)")->setCellValue("K1", "幽灵账户(元)")->setCellValue("M1", "期限")->setCellValue("N1", "融资人")->setCellValue("O1", "备注");
        $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setName('宋体')->setSize(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);

        // 设置列表值
        $pos = 2;
        /*foreach ($list as $k => $item) {
            $objPHPExcel->getActiveSheet()->setCellValue("A".$pos, $item['addTime']);
            $objPHPExcel->getActiveSheet()->setCellValue("B".$pos, $item['subject']['title']);
            $objPHPExcel->getActiveSheet()->setCellValue("C".$pos, $item['subject']['title']);
            $objPHPExcel->getActiveSheet()->setCellValue("D".$pos, number_format($val['fee_info']['fee']));
            $objPHPExcel->getActiveSheet()->setCellValue("E".$pos, $val['contract_info']['interest']);
            $objPHPExcel->getActiveSheet()->setCellValue("F".$pos, $val['contract_info']['fee']);
            $objPHPExcel->getActiveSheet()->setCellValue("G".$pos, number_format($val['totlecapital'], 2));
            $objPHPExcel->getActiveSheet()->setCellValue("H".$pos, number_format($val['yibao_money'], 2));
            $objPHPExcel->getActiveSheet()->setCellValue("I".$pos, number_format($val['wallet_money'], 2));
            $objPHPExcel->getActiveSheet()->setCellValue("J".$pos, number_format($val['money_more'], 2));
            $objPHPExcel->getActiveSheet()->setCellValue("K".$pos, number_format($val['ghost_money'], 2));
            $objPHPExcel->getActiveSheet()->setCellValue("M".$pos, $val['project']['days']);
            $objPHPExcel->getActiveSheet()->setCellValue("N".$pos, $val['project']['financing']);
            $objPHPExcel->getActiveSheet()->setCellValue("O".$pos, $val['project']['remark']);
            $pos += 1;
        }

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename="日销售额('.$file_datetime.').xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        exit;*/
    }

    /**
     * @api {post} cang/actionRepay 还款
     * @apiVersion 1.0.0
     * @apiName actionRepay
     * @apiDescription 虚拟认购
     * @apiGroup Cang
     *
     * @apiParam {Number} subjectID 产品ID
     * @apiParam {String} moneySubject 投资金额
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 12,
    "moneySubject":"100000.00"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */
    public function actionRepay() {
        //第一步，得到subject
        $cang = Model::get($this->app['cangID']);
        if(!$cang || $cang['status'] != Model::STATUS_REPAY) {
            return Common::rm(-2, '不存在符合的订单');
        }

        //第二步，得到用户
        $user = User::get($cang['userID']);
        if(!$user) {
            return Common::rm(-3, '不存在该用户');
        }


        //第三步，得到用户
        $cangRepayList = CangRepay::where([
            'cangID'=>$this->app['cangID']
        ])->select();
        if($cangRepayList->isEmpty()) {
            return Common::rm(-4, '不存在符合的还款项');
        }

        //第四步，得到
        $subject = Subject::get([
            'subjectID'=>$cang['subjectID']
        ]);


        //第二步，还款
        Db::startTrans();
        try {
            //同步金账户
            vendor('payModel.Trade');
            foreach ($cangRepayList as $k=>$item) {
                if($item['status'] == CangRepay::STATUS_UNREPAY) {
                    $finance = new FinanceService();
                    $trade = new \Trade();
                    $trade->setPayment('15825631526');
                    $trade->setReceive($user['mobile']);
                    $trade->setMoney($item['money']);
                    if($item['repayTypeID'] == 1) {
                        $trade->setRem('本金');
                    }
                    else {
                        $trade->setRem('利息');
                    }
                    $result = $finance->order($msg, $trade);
                    if(!$result) {
                        Db::rollback();
                        return Common::rm(-10, $msg);
                    }


                    $item['status'] = CangRepay::STATUS_REPAY;
                    $item['reachTime'] = THINK_START_TIME;
                    $item['outerNumber'] = $result;
                    $item['outerName'] = $this->platform;
                    $item['outerUserFrom'] = '15825631526';
                    $item->save();

                    //同步账户余额
                    if($item['repayTypeID'] == 1) {
                        $user->userAccount = $user->userAccount->MODE_repayBen_addMoney($item, $user);
                    }
                    else {
                        $user->userAccount = $user->userAccount->MODE_repayInterest_addMoney($item, $user);
                    }
                }
            }

            $cang['status'] = Model::STATUS_FINISH;
            $cang->save();





            //判断是否全部还款了
            $cangRepayList = CangRepay::where([
                'subjectID'=>$cang['subjectID'],
                'status'=>CangRepay::STATUS_UNREPAY
            ])->select();
            if($cangRepayList->isEmpty()) {
                $subject->status = Subject::STATUS_REPAY;
                $subject->save();
            }

            Db::commit();

            //发送一个短信
            Sms::create([
                'mobile'=>$user['mobile'],
                'message'=>\app\core\service\SMS::message_setCodeRepay($user['trueName'], $subject['title'], 'http://t.cn/RQC0dnv'),
                'note'=>'还款',
                'sendTime'=>THINK_START_TIME
            ]);
            //(new SMS())->setCodeRepay($user['mobile'], );
            return Common::rm(1, '操作成功', [
                'detail'=>$cang->append(['statusText'])->visible(['status'])
            ]);
        }
        catch (Exception $e) {
            // 回滚事务
            Log::error($e->getMessage().'|'.$e->getLine().'|'.$e->getFile());
            Db::rollback();
            throw new AppException(-9, $e->getMessage());
        }

    }
}