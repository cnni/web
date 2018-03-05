<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-05
 * Time: 10:00
 */
namespace app\index\controller;

use app\common\controller\Common;

class Register extends Common
{
    public function __construct()
    {
        parent::__construct();
        if ($this->member) {
            if ($this->request->isAjax()) return $this->err(120, '您已登陆无需注册');
            header('Location:' . url('@index/member'));
        }
    }

    public function index()
    {
        $this->assign([
            'title' => '注册',
            'keywords' => '注册',
            'description' => '注册',
        ]);
        return $this->fetchs();
    }

    public function username()
    {
        if (!$this->request->isAjax()) header('Location:' . url('@index/register'));
        return (new \app\common\service\Register())->username($this->request->only(['username', 'password', 'password2', 'sex', 'inviter']));
    }
}