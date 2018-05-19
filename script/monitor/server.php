<?php

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/19
 * Time: 13:35
 */
class server
{
    public function __construct()
    {
        $shell = 'netstat -ano 2>/dev/null | grep 8811 | grep LISTEN | wc -l';
        $res = shell_exec($shell);
        if (!$res) {
            echo 'err ' . PHP_EOL;
        } else {
            echo 'succ ' . PHP_EOL;
        }
    }
}

swoole_timer_tick(2000, function () {
    (new server());
});

// nohup /usr/local/php7/bin/php /var/www/html/tp-live/script/monitor/server.php > /var/www/html/tp-live/script/monitor/txt &