<?php namespace app\index\controller;

use app\common\lib\consts\RedisConst;
use app\common\lib\Redis;
use app\common\lib\Response;
use think\Container;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/17
 * Time: 11:13
 */
class Login
{
    public function index()
    {
        $phone = isset($_GET['phone_num']) ? $_GET['phone_num'] : '';
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        if (empty($phone) || empty($code)) {
            return Response::error('phone or code is null');
        }

        if ($code != Redis::getInstance()->get(RedisConst::getSmsPrefix($phone))) {
            return Response::error('code err');
        }

        $user = [
            'phone' => $phone,
            'time'  => time()
        ];
        Redis::getInstance()->set(RedisConst::getUserPrefix($phone), json_encode($user));

        Container::get('resp')->cookie(
            config('cookie.prefix') . 'user',
            json_encode($user),
            time() + config('cookie.expire'),
            config('cookie.path'),
            config('cookie.domain')
        );

        return Response::success($user);
    }
}