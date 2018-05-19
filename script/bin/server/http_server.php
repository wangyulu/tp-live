<?php
/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/12
 * Time: 21:04
 */
$http = new \Swoole\Http\Server('0.0.0.0', 8811);
$http->set(
    [
        'enable_static_handler' => true,
        'document_root'         => __DIR__ . '/../../../public/static/',
        'worker_num'            => 4
    ]
);

$http->on('workerstart', function (\Swoole\Server $http, $workerId) {
    // [ 应用入口文件 ]

    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../../../application/');
    // 加载基础文件
    require __DIR__ . '/../../../thinkphp/base.php';
});

$http->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use ($http) {
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

    // 把$response放入窗口中
    $respThink = \think\Container::getInstance()->instance('resp', $response);

    // 执行应用并响应
    try {
        \think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
            ->run()
            ->send();
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
});

$http->start();