<?php namespace app\common\lib\tasks;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/16
 * Time: 8:34
 */

use Swoole\Server;
use app\common\lib\Sms;
use app\common\lib\Redis;
use app\common\lib\consts\RedisConst;
use app\common\lib\adapter\TaskAdapter;

class SmsTask extends TaskAdapter
{
    /**
     * 任务执行开始
     *
     * @param Server $server
     * @param        taskId
     * @param        $srcWorkerId
     * @return $this
     */
    public function execute(Server $server, $taskId, $srcWorkerId)
    {
        Sms::sendSms($this->data['phone'], $this->data['code']);
        $redis = Redis::getInstance();
        if (false === $redis->set(
                RedisConst::getSmsPrefix($this->data['phone']),
                $this->data['code'])
        ) {
            // todo 记录日志
            return $this;
        }

        $this->isSuccess = true;

        return $this;
    }
}