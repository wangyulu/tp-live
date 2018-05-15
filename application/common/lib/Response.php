<?php namespace app\common\lib;

use think\Container;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/13
 * Time: 14:44
 */
class Response
{
    /**
     * 输出成功后的信息
     *
     * @param array $data
     */
    public static function success($data, $isHtml = false)
    {
        if ($isHtml) {
            return self::resp($data);
        }

        return self::response(0, $data);
    }

    /**
     * 输出失败后的信息
     *
     * @param     $msg
     * @param int $code
     */
    public static function error($msg, $code = -1)
    {
        return self::response($code, [], $msg);
    }

    /**
     * 输出返回信息
     *
     * @param        $code
     * @param array  $data
     * @param string $msg
     */
    private static function response($code, $data = [], $msg = '')
    {
        $res = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];

        self::resp($res);

        return null;
    }

    private static function resp($res)
    {
        Container::get('resp')->end(is_array($res) ? json_encode($res) : $res);

        return null;
    }
}