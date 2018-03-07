<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-07
 * Time: 17:20
 */
namespace app\common\model;

use think\Model;

class AvatarMember extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = false;
    protected $readonly = ['member_id', 'file_id', 'avatar_id'];
}