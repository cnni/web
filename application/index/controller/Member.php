<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 11:35
 */
namespace app\index\controller;

use app\common\controller\Common;
use app\common\model\NicknameMember;
use app\common\model\UsernameLogout;

class Member extends Common
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->member) {
            if ($this->request->isAjax()) return $this->err(110, '您还没有登陆');
            header('Location:' . url('@index/login'));
        }
    }

    public function myfetch($template = '', $vars = [], $config = [])
    {
        $vars['myfetch'] = $this->fetchs($template, $vars, $config);
        return $this->fetchs('index@member:fetch', $vars, $config);
    }

    public function index()
    {
        $this->assign([
            'title' => '会员中心',
            'keywords' => '会员中心',
            'description' => '会员中心',
        ]);
        return $this->myfetch();
    }

    public function logout()
    {
        session('member', null);
        switch ($this->member['logintype']) {
            case 'username':
                (new UsernameLogout())->save([
                    'username_id' => $this->member['username_id'],
                    'username' => $this->member['username'],
                    'onlinetime' => time() - $this->member['logintime'],
                ]);
                break;
            default:
                break;
        }
        if ($this->request->isAjax()) return $this->succ();
        $param = $this->request->only(['go']);
        if (isset($param['go']) && strlen($param['go'])) header('Location:' . $param['go']);
        header('Location:' . url('@index/login'));
    }

    public function username()
    {
        $this->assign([
            'title' => '用户名',
            'keywords' => '用户名',
            'description' => '用户名',
        ]);
        return $this->myfetch();
    }

    public function nickname()
    {
        $this->assign([
            'title' => '昵称',
            'keywords' => '昵称',
            'description' => '昵称',
            'list' => NicknameMember::where('member_id', '=', $this->member['id'])->order(['status' => 'desc', 'id'])->paginate(),
        ]);
        return $this->myfetch();
    }
}