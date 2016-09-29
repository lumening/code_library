<?php
/**
 * @desc: 常用的PHP函数-字符串函数
 * @date: 2015/11/25 18:11
 * @author: Tan <tanda517886160@163.com>
 * @version: 1.0
 */

/**
 * 字符串截取类
 * 
 * @param string $string
 * @param int    $length
 * @param string $suffix
 * @return string
 */
function cut_string($string, $length = 300, $suffix = '')
{
    $string = mb_convert_encoding($string,'UTF-8','gb2312, UTF-8');
    $s_length = mb_strlen($string,'UTF-8');

    if($length >= $s_length){
        $string = mb_substr($string, 0, $length, 'UTF-8').$suffix;
    }
    return $string;
}


