<?php
/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/16
 * Time: 8:31
 */

namespace app\common\lib\contracts;

use Swoole\Server;

interface TaskInterface
{
    /**
     * 任务执行开始
     *
     * @param Server        $server
     * @param               $taskId
     * @param               $srcWorkerId
     * @return mixed
     */
    public function execute(Server $server, $taskId, $srcWorkerId);

    /**
     * 任务执行完成后执行
     *
     * @param Server        $server
     * @param               $taskId
     * @return mixed
     */
    public function finish(Server $server, $taskId);
}