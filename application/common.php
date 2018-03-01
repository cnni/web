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