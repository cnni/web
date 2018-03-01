<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 11:35
 */
namespace app\index\controller;

use app\common\controller\Common;
use app\common\service\Register;

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

    public function usernameregister()
    {
        if (!$this->request->isAjax()) {
            if ($this->member) header('Location:' . url('@index/member'));
            header('Location:' . url('@index/member/register'));
        }
        if ($this->member) return $this->err(120, '您已登陆，无需重复登陆');
        return (new Register())->username($this->request->only(['username', 'password', 'password2', 'sex', 'inviter']));
    }

    public function logout()
    {
        if ($this->member) session('member', null);
        if ($this->request->isAjax()) return $this->succ();
        header('Location:' . url('@index/member/login'));
    }
}