<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 11:35
 */
namespace app\index\controller;

use app\common\controller\Common;
use app\common\model\Nickname;
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
                    'member_id' => $this->member['id'],
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
        if (!$this->request->isAjax()) {
            $this->assign([
                'title' => '昵称',
                'keywords' => '昵称',
                'description' => '昵称',
                'list' => NicknameMember::where('member_id', '=', $this->member['id'])->order(['id' => 'desc'])->paginate(),
            ]);
            return $this->myfetch();
        }
        $param = $this->request->only(['type']);
        if (!isset($param['type'])) return $this->err(1, '参数错误');
        switch ($param['type']) {
            case 'add1':
                $params = $this->request->only(['nickname']);
                if (!isset($params['nickname'])) return $this->err(3, '昵称不能为空');
                if (!isNickname($params['nickname'])) return $this->err(4, '昵称格式不正确');
                $nickname = Nickname::getByNickname($params['nickname']);
                if (!$nickname) {
                    $nickname = new Nickname();
                    $nickname->nickname = $params['nickname'];
                    $nickname->member_id = $this->member['id'];
                    if (!$nickname->save()) return $this->err(5, '新增失败');
                    $member = \app\common\model\Member::get($this->member['id']);
                    if (!$member) return $this->err(6, '查询会员信息失败');
                    if (!$member->save(['nickname_id' => $nickname->id])) return $this->err(7, '更新失败');
                    (new NicknameMember())->save([
                        'nickname_id' => $nickname->id,
                        'member_id' => $this->member['id'],
                        'nickname' => $nickname->nickname,
                    ]);
                    session('member.nickname', $nickname->nickname);
                    session('member.nickname_id', $nickname->id);
                    return $this->succ();
                }
                if ($this->member['nickname_id'] == $nickname->id) return $this->err(8, '没有变化');
                $member = \app\common\model\Member::get($this->member['id']);
                if (!$member) return $this->err(9, '查询会员信息失败');
                if (!$member->save(['nickname_id' => $nickname->id])) return $this->err(10, '更新失败');
                $nicknameMember = NicknameMember::where('nickname_id', '=', $nickname->id)->where('member_id', '=', $this->member['id'])->find();
                if (!$nicknameMember) (new NicknameMember())->save([
                    'nickname_id' => $nickname->id,
                    'member_id' => $this->member['id'],
                    'nickname' => $nickname->nickname,
                ]);
                session('member.nickname', $nickname->nickname);
                session('member.nickname_id', $nickname->id);
                return $this->succ();
            case 'add2':
                $params = $this->request->only(['id']);
                if (!isset($params['id'])) return $this->err(3, '参数错误');
                $params['id'] = intval($params['id']);
                if ($params['id'] < 1) return $this->err(4, '参数错误');
                $nickname = Nickname::get($params['id']);
                if (!$nickname) return $this->err(5, '没有找到记录');
                if ($this->member['nickname_id'] == $nickname->id) return $this->err(6, '没有变化');
                $member = \app\common\model\Member::get($this->member['id']);
                if (!$member) return $this->err(7, '查询会员信息失败');
                if (!$member->save(['nickname_id' => $nickname->id])) return $this->err(8, '更新失败');
                $nicknameMember = NicknameMember::where('nickname_id', '=', $nickname->id)->where('member_id', '=', $this->member['id'])->find();
                if (!$nicknameMember) (new NicknameMember())->save([
                    'nickname_id' => $nickname->id,
                    'member_id' => $this->member['id'],
                    'nickname' => $nickname->nickname,
                ]);
                session('member.nickname', $nickname->nickname);
                session('member.nickname_id', $nickname->id);
                return $this->succ();
            case 'up':
                $params = $this->request->only(['id']);
                if (!isset($params['id'])) return $this->err(3, '参数错误');
                $params['id'] = intval($params['id']);
                if ($params['id'] < 1) return $this->err(4, '参数错误');
                $nicknameMember = NicknameMember::get($params['id']);
                if (!$nicknameMember) return $this->err(5, '没有找到记录');
                if ($nicknameMember->member_id != $this->member['id']) return $this->err(6, '非法操作');
                if ($nicknameMember->nickname_id == $this->member['nickname_id']) return $this->err(7, '已设为默认无需重复设置');
                $member = \app\common\model\Member::get($this->member['id']);
                if (!$member) return $this->err(8, '查询会员信息失败');
                if (!$member->save(['nickname_id' => $nicknameMember->nickname_id])) return $this->err(9, '更新失败');
                session('member.nickname', $nicknameMember->nickname);
                session('member.nickname_id', $nicknameMember->nickname_id);
                return $this->succ();
            default:
                return $this->err(2, '参数错误');
        }
    }

    public function sex()
    {
        if (!$this->request->isAjax()) {
            $this->assign([
                'title' => '性别',
                'keywords' => '性别',
                'description' => '性别',
            ]);
            return $this->myfetch();
        }
        $param = $this->request->only(['sex']);
        if (!isset($param['sex'])) return $this->err(1, '参数错误');
        $param['sex'] = intval($param['sex']);
        if ($param['sex'] < 1) {
            $param['sex'] = 0;
        } elseif ($param['sex'] > 1) {
            $param['sex'] = 2;
        } else {
            $param['sex'] = 1;
        }
        $member = \app\common\model\Member::get($this->member['id']);
        if ($member->sex == $param['sex']) return $this->err(2, '没有变化');
        if (!$member->save(['sex' => $param['sex']])) return $this->err(3, '更新失败');
        session('member.sex', $param['sex']);
        return $this->succ();
    }
}