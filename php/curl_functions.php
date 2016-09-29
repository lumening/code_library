<?php
/**
 * @desc: 常用的PHP函数-CURL处理函数
 * @date: 2015/11/25 18:11
 * @author: Tan <tanda517886160@163.com>
 * @version: 1.0
 */

/**
 * CURL请求
 *
 * @param string $url  请求地址
 * @param array  $data 请求数据
 * @return mixed
 */
function curlRequest ($url, $data = null)
{
    //初始化一个curl会话
    $ch = curl_init();

    $option = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => array('Accept-Language: zh-cn', 'Connection: Keep-Alive', 'Cache-Control: no-cache'),
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0",
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_MAXREDIRS => 4,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_COOKIEJAR => $this->cookiefile,
        CURLOPT_COOKIEFILE => $this->cookiefile
    );

    if ($data) {
        $option[CURLOPT_POST] = 1;
        $option[CURLOPT_POSTFIELDS] = $data;
    }
    //为cURL传输会话批量设置选项
    curl_setopt_array($ch, $option);
    //执行给定的cURL会话。
    $response = curl_exec($ch);
    //检查错误
    if (curl_errno($ch) > 0) {
        exit("CURL ERROR:$url " . curl_error($ch));
    }
    //关闭curl会话
    curl_close($ch);
    return $response;
}