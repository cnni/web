<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 10:57
 */
namespace app\common\model;

use think\Model;

class UsernameLogin extends Model
{
    protected $pk = 'id';
    protected $auto = ['ip'];
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = false;
    protected $readonly = ['member_id', 'username_id', 'status', 'username', 'password', 'reason', 'ip'];

    protected function setIpAttr()
    {
        return request()->ip();
    }
}