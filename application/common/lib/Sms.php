<?php namespace app\common\lib;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/13
 * Time: 14:29
 */

class Sms
{
    /**
     * 发送短信验证码
     *
     * @param $phone
     * @param $code
     * @return bool
     */
    public static function sendSms($phone, $code)
    {
        // 模拟发送耗时
        sleep(1);

        //return true;
    }
}