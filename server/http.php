<?php

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/15
 * Time: 17:30
 */
class http
{
    /**
     * host
     *
     * @var string
     */
    protected $host = '0.0.0.0';

    /**
     * 端口
     *
     * @var int
     */
    protected $port = 8811;

    /**
     * http服务
     *
     * @var
     */
    protected $http;

    protected $resp;

    public function __construct()
    {
        $this->http = new \Swoole\Http\Server($this->host, $this->port);
        $this->http->set(
            [
                'enable_static_handler' => true,
                'document_root'         => __DIR__ . '/../public/static',
                'worker_num'            => 2,
                'task_worker_num'       => 1
            ]
        );
        $this->http->on('workerStart', [$this, 'workerStart']);
        $this->http->on('request', [$this, 'request']);
        $this->http->on('task', [$this, 'task']);
        $this->http->on('finish', [$this, 'finish']);

        $this->http->start();
    }

    /**
     * 启动worker进程时回调
     *
     * @param \Swoole\Server $server
     * @param                $workerId
     */
    public function workerStart(\Swoole\Server $server, $workerId)
    {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载基础文件
        require_once __DIR__ . '/../thinkphp/base.php';
    }

    /**
     * 启动任务进程时回调
     *
     * @param \Swoole\Server                          $server
     * @param                                         $taskId
     * @param                                         $srcWorkerId
     * @param \app\common\lib\contracts\TaskInterface $task 序列化后的对象
     */
    public function task(\Swoole\Server $server, $taskId, $srcWorkerId, $task)
    {
        // 执行应用并响应
        \think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
            ->initialize();
        $object = unserialize($task);

        try {
            return $object->execute($server, $taskId, $srcWorkerId);
        } catch (Exception $e) {
            echo "task err：", $e->getMessage(), $e->getFile(), $e->getLine();
        }
    }

    /**
     * 任务结束时回调
     *
     * @param \Swoole\Server                          $server
     * @param                                         $taskId
     * @param \app\common\lib\contracts\TaskInterface $task 序列化后的对象
     */
    public function finish(\Swoole\Server $server, $taskId, \app\common\lib\contracts\TaskInterface $task)
    {
        $task->finish($server, $taskId);
    }

    /**
     * 客户端请求时回调
     *
     * @param \Swoole\Http\Request  $request
     * @param \Swoole\Http\Response $response
     */
    public function request(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        $_SERVER = [];
        if (isset($request->server)) {
            $servers = $request->server;
            foreach ($servers as $key => $val) {
                $_SERVER[strtoupper($key)] = $val;
            }
        }

        if (isset($request->header)) {
            $headers = $request->header;
            foreach ($headers as $key => $val) {
                $_SERVER[strtoupper($key)] = $val;
            }
        }

        $_POST = [];
        if (isset($request->post)) {
            $posts = $request->post;
            foreach ($posts as $key => $val) {
                $_POST[$key] = $val;
            }
        }

        $_GET = [];
        if (isset($request->get)) {
            $gets = $request->get;
            foreach ($gets as $key => $val) {
                $_GET[$key] = $val;
            }
        }

        $this->resp = $response;

        $container = \think\Container::getInstance();
        // 把server放入容器中，后续task任务时会用到
        $container->instance('serv', $this->http);

        // 把$response放入容器中，后续输出会用到
        $container->instance('resp', $this->resp);

        // 执行应用并响应
        try {
            \think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
                ->run()
                ->send();
        } catch (\Exception $e) {
            echo "request err：", $e->getMessage(), $e->getFile(), $e->getLine();
        }
    }
}

new Http();