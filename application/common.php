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