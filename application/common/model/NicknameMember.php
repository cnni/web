<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 11:00
 */
namespace app\common\model;

use think\Model;

class NicknameMember extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = 'lasttime';
    protected $readonly = ['nickname_id', 'member_id', 'nickname'];
}