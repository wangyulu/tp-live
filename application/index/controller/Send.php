<?php namespace app\index\controller;

use app\common\lib\consts\RedisConst;
use app\common\lib\Response;
use app\common\lib\Sms;
use Swoole\Coroutine\Redis;

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

        // 发送验证码
        $code = rand(1000, 9999);
        Sms::sendSms($phone, $code);

        $redis = new Redis();
        $redis->connect(config('redis.host'), config('redis.port'), config('redis.timeout'));
        $res = $redis->set(RedisConst::getSmsPrefix($phone), $code, config('redis.expires_time'));

        return Response::success($res);
    }
}