<?php namespace app\admin\controller;

use think\Container;
use app\common\lib\Response;
use app\common\lib\tasks\PushTask;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/18
 * Time: 11:49
 */
class Live
{
    public function push()
    {
        if (empty($_GET)) {
            return Response::error('params err');
        }

        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];

        $data = [
            'type'    => intval($_GET['type']),
            'title'   => !empty($teams[$_GET['team_id']]['name']) ? $teams[$_GET['team_id']]['name'] : '直播员',
            'logo'    => !empty($teams[$_GET['team_id']]['logo']) ? $teams[$_GET['team_id']]['logo'] : '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'image'   => !empty($_GET['image']) ? $_GET['image'] : '',
        ];

        // 任务投递
        $push = new PushTask($data);
        Container::get('serv')->task(serialize($push));

        Response::success([]);
    }
}