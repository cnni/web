<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 11:35
 */
namespace app\index\controller;

use app\common\controller\Common;
use app\common\service\Login;
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
        if ($this->member) {
            if ($this->request->isAjax()) return $this->err(120, '您已登陆无需注册');
            header('Location:' . url('@index/member/register'));
        }
        $register = (new Register())->username($this->request->only(['username', 'password', 'password2', 'sex', 'inviter']));
        if ($this->request->isAjax()) return $register;
        if ($register['status']) header('Location:' . url('@index/member'));
        echo '<script>';
        echo 'alert("' . $register['msg'] . '[' . $register['code'] . ']' . '");';
        if (isset($_SERVER["HTTP_REFERER"]) && strlen($_SERVER["HTTP_REFERER"])) echo 'location.href="' . $_SERVER["HTTP_REFERER"] . '";'; else echo 'location.href="' . url('@index/member') . '";';
        echo '</script>';
    }

    public function login()
    {
        if ($this->member) header('Location:' . url('@index/member'));
        $this->assign([
            'title' => '登录',
            'keywords' => '登录',
            'description' => '登录',
        ]);
        return $this->fetchs();
    }

    public function usernamelogin()
    {
        if ($this->member) {
            if ($this->request->isAjax()) return $this->err(120, '您已登陆不能重复登录');
            header('Location:' . url('@index/member'));
        }
        $login = (new Login())->username($this->request->only(['username', 'password']));
        if ($this->request->isAjax()) return $login;
        if ($login['status']) header('Location:' . url('@index/member'));
        echo '<script>';
        echo 'alert("' . $login['msg'] . '[' . $login['code'] . ']' . '");';
        if (isset($_SERVER["HTTP_REFERER"]) && strlen($_SERVER["HTTP_REFERER"])) echo 'location.href="' . $_SERVER["HTTP_REFERER"] . '";'; else echo 'location.href="' . url('@index/member') . '";';
        echo '</script>';
    }

    public function logout()
    {
        if ($this->member) session('member', null);
        if ($this->request->isAjax()) return $this->succ();
        header('Location:' . url('@index/member/login'));
    }
}