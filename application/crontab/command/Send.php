<?php
/**
 * 发送
 * User: qissen
 * Date: 2017/11/26
 * Time: 11:26a
 */
namespace app\crontab\command;

use app\core\model\HongbaoPlan;
use app\core\service\SMS;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Log;
use tool\Common;

class Send extends Command
{
    protected function configure()
    {
        $this->setName('Send')->setDescription('Send开始');
    }

    protected function execute(Input $input, Output $output)
    {
        //在这里我们进行结算
        Log::info('Send:'.Common::timetodate(THINK_START_TIME));

        $this->sendSMS();
        $this->hongbaoPlan();

        $output->writeln("Send结束");
    }

    /**
     * 红包派送计划 - 定时发送
     */
    private function hongbaoPlan() {
        (new HongbaoPlan())->sendUserOnTiming();
    }


    /**
     * 短信发送
     */
    private function sendSMS() {
        \app\core\model\Sms::where([
            'status'=>0,
            'sendTime'=>['lt', THINK_START_TIME]
        ])->chunk(100, function($list) {
            foreach ($list as $item) {
                SMS::sendSMS($item['mobile'], $item['message']);
                $item->status = 1;
                $item->save();
            }
        });
    }

}