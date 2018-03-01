<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 11:35
 */
namespace app\index\controller;

use app\common\controller\Common;

class Member extends Common
{
    public function index()
    {
        $this->assign([
            'title' => '会员中心',
            'keywords' => '会员中心',
            'description' => '会员中心',
        ]);
        return $this->fetchs();
    }

    public function register()
    {
        if ($this->member) header('Location:' . url('@index/member'));
        $this->assign([
            'title' => '注册',
            'keywords' => '注册',
            'description' => '注册',
        ]);
        return $this->fetchs();
    }
}