<?php namespace app\index\controller;

use think\Container;
use app\common\lib\Response;
use app\common\lib\tasks\SmsTask;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/13
 * Time: 14:31
 */
class Send
{
    public function index()
    {
        $phone = $_GET['phone_num'];
        if (empty($phone)) {
            return Response::error('err');
        }

        // 验证码
        $code = rand(1000, 9999);

        $smsTask = new SmsTask(['code' => $code, 'phone' => $phone]);
        // 调用异步任务
        Container::get('serv')->task(serialize($smsTask));

        return Response::success($code);
    }
}