<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-07
 * Time: 17:18
 */
namespace app\common\model;

use think\Model;

class Avatar extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = 'lasttime';
    protected $readonly = ['member_id', 'file_id', 'width', 'height'];
}