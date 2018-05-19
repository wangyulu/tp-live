<?php namespace app\index\controller;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/19
 * Time: 10:56
 */

use think\Container;
use app\common\lib\Response;

class Chat
{
    public function index()
    {
        if (!isset($_POST['content']) || empty($_POST['content'])) {
            return Response::error('content is empty');
        }

        if (!isset($_POST['chat_id']) || empty($_POST['chat_id'])) {
            return Response::error('chat_id is empty');
        }
        // 获取监听的端口列表
        $ports = Container::get('serv')->ports;
        if (!isset($ports[1])) {
            return Response::error('ports not exist');
        }

        // 获取服务Serv
        $serv = Container::get('serv');

        // 获取指定监听的端口号对象
        $port = $ports[1];

        $data = [
            'user'    => rand(1111, 9999),
            'content' => $_POST['content']
        ];
        // 遍历连接此端口的客户端连接
        foreach ($port->connections as $fd) {
            $serv->push($fd, json_encode($data));
        }

        return Response::success(1);
    }
}