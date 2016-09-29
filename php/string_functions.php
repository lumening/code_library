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


//防SQL注入
function cleanInput ($input)
{

    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);
    return $output;
}

function sanitize ($input)
{
    if (is_array($input)) {
        foreach ($input as $var => $val) {
            $output[$var] = sanitize($val);
        }
    } else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}

/**
 *	获取data数组中的参数的签名
 * @param  [type] $data [description]
 * @param  [type] $key  [description]
 * @return [type]       [description]
 */
function sign ($data, $key)
{
    if (isset($data['sign'])) unset($data['sign']);
    ksort($data);
    $query = http_build_query($data);
    return md5($query . $key);
}
//测试
if (sign($data, 'QXqK0w3mmsr9YUAr') != $_GET['sign']) {
    exit(ajaxResponse(500));
}

