<?php namespace app\admin\controller;

/**
 * Created by PhpStorm.
 * User: wangyulu
 * Date: 2018/5/17
 * Time: 15:51
 */

use app\common\lib\Response;

class Image
{
    /**
     * 图片上传
     *
     * @return null
     */
    public function index()
    {
        $file = request()->file('file');
        $info = $file->move(APP_PATH . '../public/static/upload');
        if ($info) {
            $data = [
                'image' => config('live.host') . "/upload/" . $info->getSaveName(),
            ];

            return Response::success($data);
        } else {
            return Response::error('upload err');
        }
    }
}