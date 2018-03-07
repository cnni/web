<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-07
 * Time: 15:51
 */
namespace app\common\model;

use think\Model;

class UpyunCache extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'addtime';
    protected $updateTime = 'lasttime';
    protected $json = ['extend'];
    protected $readonly = ['member_id', 'filename', 'md5', 'url', 'upyun_id', 'server_id', 'extend'];
}