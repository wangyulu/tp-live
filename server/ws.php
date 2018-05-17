<?php

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/7
 * Time: 15:10
 */
class ws
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
     * server服务
     *
     * @var
     */
    protected $ws;

    protected $resp;

    public function __construct()
    {
        $this->ws = new \Swoole\WebSocket\Server($this->host, $this->port);
        $this->ws->set(
            [
                'enable_static_handler' => true,
                'document_root'         => __DIR__ . '/../public/static',
                'worker_num'            => 2,
                'task_worker_num'       => 1
            ]
        );
        $this->ws->on('workerStart', [$this, 'workerStart']);
        $this->ws->on('open', [$this, 'open']);
        $this->ws->on('message', [$this, 'message']);
        $this->ws->on('request', [$this, 'request']);
        $this->ws->on('task', [$this, 'task']);
        $this->ws->on('finish', [$this, 'finish']);

        $this->ws->start();
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
     * 客户端与服务器建立连接且完成握手后调用
     *
     * @param \Swoole\WebSocket\Server $server
     * @param \Swoole\Http\Request     $resp
     */
    public function open(\Swoole\WebSocket\Server $server, \Swoole\Http\Request $resp)
    {
        echo "open {$resp->fd} success" . PHP_EOL;
    }

    /**
     * 服务器接收到来自客户端消息时调用
     *
     * @param \Swoole\Server          $server
     * @param \Swoole\WebSocket\Frame $frame
     */
    public function message(\Swoole\Server $server, \Swoole\WebSocket\Frame $frame)
    {
        echo "message {$frame->fd} data : $frame->data" . PHP_EOL;
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
        $_FILES = [];
        if (isset($request->files)) {
            $_FILES = $request->files;
        }

        $this->resp = $response;

        $container = \think\Container::getInstance();
        // 把server放入容器中，后续task任务时会用到
        $container->instance('serv', $this->ws);

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

new ws();