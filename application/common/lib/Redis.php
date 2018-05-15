<?php namespace app\common\lib;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/15
 * Time: 16:10
 */

class Redis
{
    /**
     * redis server
     * @var
     */
    private $redis;

    /**
     * 实例
     *
     * @var
     */
    private static $instance;

    private function __construct()
    {
        $this->redis = new \Redis();
        if (false === $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.timeout'))) {
            echo "redis connect fail";
        }
    }

    /**
     * 获取Redis实例
     *
     * @return Redis
     */
    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new Redis();

        return self::$instance;
    }

    /**
     * 获取一个key
     *
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * 设置某个key
     *
     * @param     $key
     * @param     $value
     * @param int $timeout
     * @return bool
     */
    public function set($key, $value, $timeout = 0)
    {
        return $this->redis->set($key, $value, $timeout ?: config('redis.timeout'));
    }

    /**
     * 设置某个key，并指定过期时间
     *
     * @param $key
     * @param $time
     * @param $val
     * @return bool
     */
    public function setex($key, $time, $val)
    {
        return $this->redis->setex($key, time() + $time, $val);
    }
}