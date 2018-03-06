<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 10:56
 */
namespace app\common\model;

use think\Model;

class Username extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = 'lasttime';
    protected $readonly = ['username', 'member_id'];

    public function setUsernameAttr($value)
    {
        return strtolower($value);
    }
}