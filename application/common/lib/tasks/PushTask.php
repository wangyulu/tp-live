<?php namespace app\common\lib\tasks;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/18
 * Time: 10:19
 */

use Swoole\Server;
use app\common\lib\Redis;
use app\common\lib\consts\RedisConst;
use app\common\lib\adapter\TaskAdapter;

class PushTask extends TaskAdapter
{
    /**
     * 任务执行时的回调方法
     *
     * @param Server $server
     * @param        $taskId
     * @param        $srcWorkerId
     * @return       $this
     */
    public function execute(Server $server, $taskId, $srcWorkerId)
    {
        // 获取连接的客户端
        $fds = Redis::getInstance()->sMembers(RedisConst::getClientConnectKey());
        if (empty($fds)) {
            echo 'pushTask fd empty' . PHP_EOL;
            $this->isSuccess = true;

            return $this;
        }

        // 推送消息给客户端
        foreach ($fds as $fd) {
            $server->push($fd, json_encode($this->data));
        }

        $this->isSuccess = true;

        return $this;
    }
}