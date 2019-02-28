<?php
/**
 * 持仓到期自动结算
 * User: qissen
 * Date: 2017/11/26
 * Time: 11:26a
 */
namespace app\crontab\command;

use app\core\model\Cang as Model;
use app\core\model\CangRepay;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Log;
use tool\Common;

class Cang extends Command
{
    protected function configure()
    {
        $this->setName('Cang')->setDescription('Cang开始');
    }

    protected function execute(Input $input, Output $output)
    {
        //在这里我们进行结算
        Log::info('Cang:'.Common::timetodate(THINK_START_TIME));


        $this->setStatusInterest();

        $this->setStatusRepayIng();


        $output->writeln("Cang开始");
    }




    /**
     * 设置持仓状态：持有状态->计息状态
     */
    private function setStatusInterest() {
        Model::where([
            'interestBeginTime'=>['lt', THINK_START_TIME],
            'status'=>Model::STATUS_PAY
        ])->chunk(100, function($list) {
            foreach ($list as $item) {
                $item->status = Model::STATUS_INTEREST;
                $item->save();
            }
        });
    }


    /**
     * 设置持仓状态：计息状态->回款中
     */
    private function setStatusRepayIng() {

        //兼容老佳乾，老佳乾客户全部转移后，可注释
        Model::where([
            'interestEndTime'=>['lt', THINK_START_TIME],
            'alias'=>'',
            'status'=>Model::STATUS_INTEREST
        ])->chunk(100, function($list) {
            foreach ($list as $item) {
                $item->status = Model::STATUS_REPAY;
                $item->save();
            }
        });
        //end

        Model::where([
            'interestEndTime'=>['lt', THINK_START_TIME - 86400],
            'alias'=>['neq', ''],
            'status'=>Model::STATUS_INTEREST
        ])->chunk(100, function($list) {
            foreach ($list as $item) {
                $item->status = Model::STATUS_REPAY;
                $item->save();
            }
        });

    }
}