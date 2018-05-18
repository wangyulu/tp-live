<?php namespace app\common\lib\consts;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/15
 * Time: 16:34
 */

class RedisConst
{
    const TPLIVE_PRIFIX = 'tplive:';

    /**
     * 用户前缀
     */
    const USER_PREFIX = 'user:';

    /**
     * 通讯前缀
     */
    const SMS_PREFIX = 'sms:';

    /**
     * 获取用户前缀
     *
     * @param $phone
     * @return string
     */
    public static function getUserPrefix($phone)
    {
        return self::TPLIVE_PRIFIX . self::USER_PREFIX . $phone;
    }

    /**
     * 获取通讯前缀
     *
     * @param $phone
     * @return string
     */
    public static function getSmsPrefix($phone)
    {
        return self::TPLIVE_PRIFIX . self::SMS_PREFIX . $phone;
    }

    /**
     * 获取用于保存客户端连接的key
     *
     * @return mixed
     */
    public static function getClientConnectKey()
    {
        return self::TPLIVE_PRIFIX . config('live.key_client_connect');
    }
}