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