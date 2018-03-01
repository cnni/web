<?php
/**
 * Created by PhpStorm.
 * User: haiyin.ma
 * Date: 2018-03-01
 * Time: 14:25
 */
namespace app\common\controller;

use think\Controller;

class Common extends Controller
{
    public $sys = [];

    public function __construct()
    {
        parent::__construct();
        $this->sys = cache('sys');
        if (!$this->sys) {
            $this->sys = model('sys')->column('val', 'code');
            if ($this->sys) cache('sys', $this->sys);
        }
    }
}