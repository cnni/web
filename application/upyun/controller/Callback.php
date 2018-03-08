<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-08
 * Time: 17:42
 */
namespace app\upyun\controller;

use app\common\controller\Common;
use app\common\model\UpyunLog;

class Callback extends Common
{
    public function avatar()
    {
        if (!$this->request->isPost()) return;
        $post = $_POST;
        $server = $_SERVER;
        $upyunLog = new UpyunLog();
        $upyunLog->save([
            'post' => $post,
            'server' => $server,
            'type' => 'avatar',
        ]);
    }
}