<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 18:04
 */
namespace app\common\service;

use app\common\controller\Common;
use app\common\model\Member;
use app\common\model\Username;
use app\common\model\UsernameLogin;

class Register extends Common
{
    public function username($data = [])
    {
        if (!isset($data['username'])) return $this->err(1, '用户名不能为空');
        if (!isUsername($data['username'])) return $this->err(2, '用户名由2-16个数字字母汉字下划线和横杠组成');
        if (!isset($data['password'])) return $this->err(3, '密码不能为空');
        if (!isPassword($data['password'])) return $this->err(4, '密码由6-16个字符组成');
        $username = new Username();
        $username->username = $data['username'];
        $username->password = md5($data['password']);
        if (!isset($data['password2'])) return $this->err(5, '两次密码输入不同');
        if (md5($data['password2']) != $username->password) return $this->err(6, '两次密码输入不同');
        if (Username::where('username', '=', $username->username)->count()) return $this->err(7, '用户名被占用');
        if (!isset($data['sex'])) return $this->err(8, '请选择性别');
        $member = new Member();
        $member->sex = $data['sex'] < 1 ? 0 : ($data['sex'] > 1 ? 2 : 1);
        if (!isset($data['inviter'])) {
            $data['inviter'] = 0;
        } else {
            $data['inviter'] = intval($data['inviter']);
        }
        if ($data['inviter'] > 0) {
            $inviter = Member::get($data['inviter']);
            if (!$inviter) return $this->err(9, '推荐人不存在');
            $member->pid = $inviter->id;
            $member->level = $inviter->level + 1;
            $member->path = $inviter->path . $inviter->id . ',';
        }
        if (!$member->save()) return $this->err(10, '创建会员失败');
        $username->member_id = $member->id;
        if (!$username->save()) return $this->err(11, '保存用户名失败');
        session('member', [
            'id' => $member->id,
            'nickname' => $username->username,
            'nickname_id' => 0,
            'logintype' => 'username',
            'username' => $username->username,
            'username_id' => $username->id,
            'logintime' => time(),
            'logindatetime' => date('Y-m-d H:i:s'),
            'sex' => $member->sex,
        ]);
        (new UsernameLogin())->save([
            'member_id' => $member->id,
            'username_id' => $username->id,
            'username' => $username->username,
            'password' => $username->password,
            'reason' => '注册成功自动登录',
        ]);
        return $this->succ();
    }
}