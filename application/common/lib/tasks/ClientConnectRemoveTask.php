<?php namespace app\common\lib\tasks;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/18
 * Time: 14:49
 */

use Swoole\Server;
use app\common\lib\Redis;
use app\common\lib\consts\RedisConst;
use app\common\lib\adapter\TaskAdapter;

class ClientConnectRemoveTask extends TaskAdapter
{
    /**
     * 任务执行时的回调方法
     *
     * @param Server $server
     * @param        $taskId
     * @param        $srcWorkerId
     * @return $this
     */
    public function execute(Server $server, $taskId, $srcWorkerId)
    {
        Redis::getInstance()->sRemove(RedisConst::getClientConnectKey(), $this->data['fd']);
        $this->isSuccess = true;

        return $this;
    }
}