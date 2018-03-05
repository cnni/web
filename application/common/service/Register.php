<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 18:04
 */
namespace app\common\service;

use app\common\controller\Common;

class Register extends Common
{
    public function username($data = [])
    {
        try {
            if (!isUsername($data['username'])) return $this->err(2, '用户名由2-16个数字字母汉字下划线和横杠组成');
            if (!isPassword($data['password'])) return $this->err(3, '密码由6-16个字符组成');
            $insert_username = [
                'username' => strtolower($data['username']),
                'password' => md5($data['password']),
            ];
            if (md5($data['password2']) != $insert_username['password']) return $this->err(4, '两次密码输入不同');
            if (db('username')->where('username', $insert_username['username'])->count()) return $this->err(5, '用户名被占用');
            $insert_member = ['sex' => $data['sex'] < 1 ? 0 : ($data['sex'] > 1 ? 2 : 1)];
            $data['inviter'] = intval($data['inviter']);
            if ($data['inviter'] > 0) {
                $inviter = db('member')->field(['id', 'level', 'path'])->find($data['inviter']);
                if (!$inviter) return $this->err(6, '邀请人不存在');
                $insert_member['pid'] = $inviter['id'];
                $insert_member['level'] = intval($inviter['level']) + 1;
                $insert_member['path'] = $inviter['path'] . $inviter['id'] . ',';
            }
            $member_id = db('member')->insertGetId($insert_member);
            if (!$member_id) return $this->err(7, '创建会员ID失败');
            $insert_username['member_id'] = $member_id;
            $time = time();
            $insert_username['lasttime'] = date('Y-m-d H:i:s', $time);
            $username_id = db('username')->insertGetId($insert_username);
            if (!$username_id) return $this->err(8, '保存用户信息失败');
            session('member', [
                'id' => $member_id,
                'nickname' => $insert_username['username'],
                'logintype' => 'username',
                'username' => $insert_username['username'],
                'username_id' => $username_id,
                'logintime' => $time,
                'logindatetime' => date('Y-m-d H:i:s', $time),
            ]);
            try {
                $nickname = db('nickname')->where('nickname', $insert_username['username'])->find();
                if ($nickname) {
                    db('nickname_member')->insert([
                        'nickname_id' => $nickname['id'],
                        'member_id' => $member_id,
                        'nickname' => $nickname['nickname'],
                    ]);
                } else {
                    $nickname_id = db('nickname')->insertGetId([
                        'nickname' => $insert_username['username'],
                        'member_id' => $member_id,
                    ]);
                    if ($nickname_id) db('nickname_member')->insert([
                        'nickname_id' => $nickname_id,
                        'member_id' => $member_id,
                        'nickname' => $insert_username['username'],
                    ]);
                }
                db('username_login')->insert([
                    'username_id' => $username_id,
                    'username' => $insert_username['username'],
                    'password' => $data['password'],
                    'reason' => '注册成功直接登陆',
                    'ip' => $this->request->ip(),
                ]);
            } catch (\Exception $e) {
            }
            return $this->succ();
        } catch (\Exception $e) {
            return $this->err(1, '服务异常', $e);
        }
    }
}