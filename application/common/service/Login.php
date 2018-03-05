<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 22:07
 */
namespace app\common\service;

use app\common\controller\Common;

class Login extends Common
{
    public function username($data = [])
    {
        try {
            if (!isUsername($data['username'])) return $this->err(2, '用户名由2-16个数字字母汉字下划线和横杠组成');
            if (!isPassword($data['password'])) return $this->err(3, '密码由6-16个字符组成');
            $username = db('username')->where('username', $data['username'])->find();
            if (!$username) return $this->err(4, '用户名不存在');
            if ($username['password'] != md5($data['password'])) return $this->err(5, '密码错误');
            if (!$username['status']) return $this->err(6, '用户禁用了用户名登录');
            $member = db('member')->find($username['member_id']);
            if (!$member) return $this->err(7, '用户数据异常');
            if (!$member['status']) return $this->err(8, '用户被禁用');
            $time = time();
            $session = [
                'id' => $member['id'],
                'logintype' => 'username',
                'username' => $username['username'],
                'username_id' => $username['id'],
                'logintime' => $time,
                'logindatetime' => date('Y-m-d H:i:s', $time),
            ];
            try {
                $nickname_member = db('nickname_member')->where('member_id', $member['id'])->order('id DESC')->find();
                if ($nickname_member) {
                    $session['nickname'] = $nickname_member['nickname'];
                } else {
                    $session['nickname'] = $username['username'];
                    $nickname = db('nickname')->where('nickname', $username['username'])->find();
                    if ($nickname) {
                        db('nickname_member')->insert([
                            'nickname_id' => $nickname['id'],
                            'member_id' => $member['id'],
                            'nickname' => $nickname['nickname'],
                        ]);
                    } else {
                        $nickname_id = db('nickname')->insertGetId([
                            'nickname' => $username['username'],
                            'member_id' => $member['id'],
                        ]);
                        if ($nickname_id) db('nickname_member')->insert([
                            'nickname_id' => $nickname_id,
                            'member_id' => $member['id'],
                            'nickname' => $username['username'],
                        ]);
                    }
                }
                db('username_login')->insert([
                    'username_id' => $username['id'],
                    'username' => $username['username'],
                    'password' => $data['password'],
                    'ip' => $this->request->ip(),
                ]);
            } catch (\Exception $e) {
                $session['nickname'] = $username['username'];
            }
            session('member', $session);
            return $this->succ();
        } catch (\Exception $e) {
            return $this->err(1, '服务异常', $e);
        }
    }
}