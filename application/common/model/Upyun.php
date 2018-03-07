<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-07
 * Time: 10:50
 */
namespace app\common\service;

use think\Model;

class Upyun extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = 'lasttime';
}