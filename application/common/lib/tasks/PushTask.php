<?php namespace app\common\lib\tasks;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/18
 * Time: 10:19
 */

use Swoole\Server;
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
        $server->push(7, 'succ test');

        return $this;
    }
}