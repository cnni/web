<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 14:25
 */
namespace app\common\controller;

use app\common\model\Sys;
use think\Controller;

class Common extends Controller
{
    public $sys = [];
    public $member = [];

    public function __construct()
    {
        parent::__construct();
        $this->sys = cache('sys');
        if (!$this->sys) {
            $this->sys = Sys::where('status', '=', 1)->column('val', 'code');
            if ($this->sys) cache('sys', $this->sys);
        }
        $this->member = session('member');
    }

    protected function succ($data = [], $code = 0, $msg = '成功')
    {
        return [
            'status' => true,
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
    }

    protected function err($code = 0, $msg = '失败', $data = [])
    {
        return [
            'status' => false,
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
    }

    protected function fetchs($template = '', $vars = [], $config = [])
    {
        $vars['sys'] = $this->sys;
        $vars['isMobile'] = $this->request->isMobile();
        return $this->fetch($template, $vars, $config);
    }
}