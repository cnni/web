<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-08
 * Time: 9:21
 */
namespace app\common\service;

use app\common\controller\Common;
use app\common\model\Avatar;
use app\common\model\AvatarMember;
use app\common\model\File;
use app\common\model\FileServer;
use app\common\model\UpyunCache;
use Upyun\Config;
use Upyun\Signature;
use Upyun\Util;

class Upyun extends Common
{
    public function policy($data = [])
    {
        if (!isset($data['md5'])) return $this->err(1, '参数错误');
        if (!isset($data['type'])) return $this->err(2, '上传类型错误');
        $file = File::getByMd5($data['md5']);
        if (!$file) {
            if (!isset($data['server_id'])) return $this->err(3, '附件服务器错误');
            $fileServer = FileServer::get($data['server_id']);
            if (!$fileServer) return $this->err(4, '附件服务器错误');
            $upyunCache = new UpyunCache();
            $upyunCache->member_id = $this->member['id'];
            if (!isset($data['name'])) return $this->err(5, '参数错误');
            $upyunCache->name = $data['name'];
            $upyunCache->md5 = $data['md5'];
            switch ($data['type']) {
                case 'avatar':
                    $upyunCache->url = '/avatar/' . date('Y/m/d/') . $this->member['id'] . '_' . uniqid();
                    $callback = url('@upyun/callback/avatar');
                    $fileType = 'jpg,jpeg,png,gif';
                    break;
                default:
                    return $this->err(6, '未知上传');
            }
            if (!isset($data['size'])) return $this->err(7, '参数错误');
            $upyunCache->size = $data['size'];
            $upyunCache->upyun_id = $fileServer->type_id;
            $upyunCache->server_id = $fileServer->id;
            if (!isset($data['filetype'])) return $this->err(8, '参数错误');
            $upyunCacheExtend = [];
            switch ($data['filetype']) {
                case 'image/jpeg':
                    $upyunCacheExtend['type'] = 'jpeg';
                    break;
                case 'image/jpg':
                    $upyunCacheExtend['type'] = 'jpg';
                    break;
                case 'image/png':
                    $upyunCacheExtend['type'] = 'png';
                    break;
                case 'image/gif':
                    $upyunCacheExtend['type'] = 'gif';
                    break;
                default:
                    return $this->err(9, '上传类型错误');
            }
            $upyunCache->extend = $upyunCacheExtend;
            if (!$upyunCache->save()) return $this->err(10, '缓存失败');
            $upyun = new Config($fileServer->extend->service, $fileServer->extend->username, $fileServer->extend->password);
            $upyun->setFormApiKey($fileServer->extend->formApiKey);
            $policy = Util::base64Json([
                'bucket' => $fileServer->extend->service,
                'save-key' => $upyunCache->url,
                'expiration' => time() + 1800,
                'notify-url' => $callback,
                'allow-file-type' => $fileType,
                //'x-gmkerl-type' => 'get_theme_color',
                'ext-param' => $upyunCache->id,
            ]);
            return $this->succ([
                'policy' => $policy,
                'authorization' => Signature::getBodySignature($upyun, 'POST', '/' . $fileServer->extend->service, null, $policy),
            ], 1);
        }
        switch ($data['type']) {
            case 'avatar':
                $avatar = Avatar::getByFileId($file->id);
                if ($avatar) {
                    $avatarMember = AvatarMember::where('avatar_id', '=', $avatar->id)->where('member_id', '=', $this->member['id'])->find();
                    if ($avatarMember) return $this->err(11, '您已上传过这张头像');
                    $avatarMember = new AvatarMember();
                    $avatarMember->avatar_id = $avatar->id;
                    $avatarMember->file_id = $avatar->file_id;
                    $avatarMember->member_id = $this->member['id'];
                    if (!$avatarMember->save()) return $this->err(12, '上传失败');
                    return $this->succ();
                }
                $avatar = new Avatar();
                $avatar->member_id = $this->member['id'];
                $avatar->file_id = $file->id;
                if (!$avatar->save()) return $this->err(13, '上传失败');
                $avatarMember = new AvatarMember();
                $avatarMember->avatar_id = $avatar->id;
                $avatarMember->file_id = $avatar->file_id;
                $avatarMember->member_id = $this->member['id'];
                if (!$avatarMember->save()) return $this->err(14, '上传失败');
                return $this->succ();
            default:
                return $this->err(15, '未知上传');
        }
    }
}