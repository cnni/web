<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-05
 * Time: 9:35
 */
namespace app\index\controller;

use app\common\controller\Common;

class Login extends Common
{
    public function __construct()
    {
        parent::__construct();
        if ($this->member) {
            if ($this->request->isAjax()) return $this->err(120, '您已登陆无需重复登陆');
            header('Location:' . url('@index/member'));
        }
    }

    public function index()
    {
        $this->assign([
            'title' => '登录',
            'keywords' => '登录',
            'description' => '登录',
        ]);
        return $this->fetchs();
    }

    public function username()
    {
        if (!$this->request->isAjax()) header('Location:' . url('@index/login'));
        return (new \app\common\service\Login())->username($this->request->only(['username', 'password']));
    }
}