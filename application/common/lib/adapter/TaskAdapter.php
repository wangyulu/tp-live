<?php namespace app\common\lib\adapter;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/18
 * Time: 10:23
 */

use Swoole\Server;
use app\common\lib\contracts\TaskInterface;

abstract class TaskAdapter implements TaskInterface
{
    /**
     * 传递的数据
     *
     * @var array
     */
    protected $data = [];

    /**
     * 是否执行成功
     *
     * @var bool
     */
    protected $isSuccess = false;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * 任务执行时的回调方法
     *
     * @param Server $server
     * @param        $taskId
     * @param        $srcWorkerId
     */
    public function execute(Server $server, $taskId, $srcWorkerId)
    {
        // TODO: Implement execute() method.
    }

    /**
     * 任务执行结束时的回调方法
     *
     * @param Server $server
     * @param        $taskId
     */
    public function finish(Server $server, $taskId)
    {
        echo "task finish {$taskId}" . PHP_EOL;
        if (!$this->isSuccess) {
            echo "task err {$taskId}" . PHP_EOL;
        }
    }
}