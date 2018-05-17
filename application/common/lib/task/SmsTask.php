<?php namespace app\common\lib\task;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/16
 * Time: 8:34
 */

use Swoole\Server;
use think\Container;
use app\common\lib\Sms;
use app\common\lib\Redis;
use app\common\lib\consts\RedisConst;
use app\common\lib\contracts\TaskInterface;

class SmsTask implements TaskInterface
{
    /**
     * 任务数据
     *
     * @var array
     */
    public $data = [];

    /**
     * 任务是否执行成功
     *
     * @var bool
     */
    public $isSuccess = false;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

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

    /**
     * 任务执行结束
     *
     * @param Server        $server
     * @param               $taskId
     */
    public function finish(Server $server, $taskId)
    {
        echo $taskId . PHP_EOL;
        if (!$this->isSuccess) {
            echo "err {$taskId}" . PHP_EOL;
        }
        // TODO: Implement finish() method.
    }
}