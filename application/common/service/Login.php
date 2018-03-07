<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 22:07
 */
namespace app\common\service;

use app\common\controller\Common;
use app\common\model\Member;
use app\common\model\Nickname;
use app\common\model\Username;
use app\common\model\UsernameLogin;

class Login extends Common
{
    public function username($data = [])
    {
        if (!isset($data['username'])) return $this->err(1, '用户名不能为空');
        if (!isUsername($data['username'])) return $this->err(2, '用户名由2-16个数字字母汉字下划线和横杠组成');
        if (!isset($data['password'])) return $this->err(3, '密码不能为空');
        if (!isPassword($data['password'])) return $this->err(4, '密码由6-16个字符组成');
        $username = Username::getByUsername($data['username']);
        if (!$username) return $this->err(5, '用户不存在');
        if (md5($data['password']) != $username->password) return $this->err(6, '密码错误');
        if (!$username->status) return $this->err(7, '用户禁用了该用户名登录');
        if (!$username->member_id) return $this->err(8, '会员ID异常,请联系我们');
        $member = Member::get($username->member_id);
        if (!$member) return $this->err(9, '会员信息异常,请联系我们');
        if (!$member->status) return $this->err(10, '用户被禁用');
        session('member', [
            'id' => $member->id,
            'nickname' => $username->username,
            'nickname_id' => $member->nickname_id,
            'logintype' => 'username',
            'username' => $username->username,
            'username_id' => $username->id,
            'logintime' => time(),
            'logindatetime' => date('Y-m-d H:i:s'),
            'sex' => $member->sex,
            'avatar_id' => $member->avatar_id,
            'avatar' => 'http://cnniimg.kushao.com/avatar/0.png',
        ]);
        if ($member->nickname_id) {
            $nickname = Nickname::get($member->nickname_id);
            if ($nickname) session('member.nickname', $nickname->nickname);
        }
        if ($member->avatar_id) {
            $avatar = db('avatar')->alias('a')->join([
                ['file f', 'f.id=a.file_id'],
                ['file_server fs', 'fs.id=f.server_id'],
            ])->where('a.id', '=', $member->avatar_id)->field(['fs.ssl', 'fs.domain', 'f.url'])->find();
            if ($avatar) session('member.avatar', ($avatar['ssl'] ? 'https://' : 'http://') . $avatar['domain'] . $avatar['url']);
        }
        (new UsernameLogin())->save([
            'member_id' => $member->id,
            'username_id' => $username->id,
            'username' => $username->username,
            'password' => $username->password,
        ]);
        return $this->succ();
    }
}