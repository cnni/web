<?php
if (!function_exists('p')) {
    function p()
    {
        foreach (func_get_args() as $v) {
            echo '<pre>';
            print_r($v);
            echo '</pre>';
        }
    }
}
if (!function_exists('isMobile')) {
    function isMobile($mobile = '')
    {
        return preg_match('/^1[3456789]\d{9}$/', $mobile);
    }
}
if (!function_exists('isUsername')) {
    function isUsername($username = '')
    {
        return preg_match('/^[A-Za-z0-9_\x4E00-\x9FA5-]{2,16}$/iu', $username);
    }
}
if (!function_exists('isPassword')) {
    function isPassword($password = '')
    {
        $strlen = strlen($password);
        return $strlen > 5 && $strlen < 17;
    }
}
if (!function_exists('timeToStr')) {
    function timeToStr($time = 0)
    {
        $d = floor($time / 86400);
        if ($d > 0) {
            echo $d . '天';
            $time -= $d * 86400;
        }
        $h = floor($time / 3600);
        if ($h > 0) {
            echo $h . '小时';
            $time -= $h * 3600;
        } else {
            if ($d > 0) {
                echo '0小时';
            }
        }
        $i = floor($time / 60);
        if ($i > 0) {
            echo $i . '分钟';
            $time -= $i * 60;
        } else {
            if ($d > 0 || $h > 0) {
                echo '0分钟';
            }
        }
        echo floor($time % 60) . '秒';
    }
}
if (!function_exists('isNickname')) {
    function isNickname($nickname = '')
    {
        $strlen = strlen($nickname);
        return $strlen > 0 && $strlen < 17;
    }
}