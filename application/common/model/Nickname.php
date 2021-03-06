<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 10:59
 */
namespace app\common\model;

use think\Model;

class Nickname extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = 'lasttime';
    protected $readonly = ['nickname', 'member_id'];
}