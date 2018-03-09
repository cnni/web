<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-08
 * Time: 17:48
 */
namespace app\common\model;

use think\Model;

class UpyunLog extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = false;
    protected $json = ['post', 'server', 'more'];
}