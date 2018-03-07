<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-07
 * Time: 10:25
 */
namespace app\common\model;

use think\Model;

class FileServer extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = 'lasttime';
    protected $readonly = ['type', 'type_id'];
}