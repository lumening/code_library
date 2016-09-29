<?php

/**
 * @desc: 常用的PHP函数-文件压缩函数
 * @date: 2015/11/25 18:11
 * @author: Tan <tanda517886160@163.com>
 * @version: 1.0
 */


/**
 *文件压缩,将多个文件压缩成一个zip文件的函数
 *（PHP 5 >= 5.2.0, PECL zip >= 1.1.0） 内置标准函数库中的ZipArchive类，，需要引入php_zip.dll或者编译PECL包	
 * 
 * @param array  $files 		实例array("1.jpg","2.jpg");
 * @param string $destination	目标文件的路径  如"c:/androidyue.zip"
 * @param bool   $overwrite 	是否为覆盖与目标文件相同的文件
 * @return bool
 */
function create_zip ($files = array(), $destination = '', $overwrite = false)
{
    $isExist = file_exists($destination);
    //如果zip文件已经存在并且设置为不重写返回false
    if ($isExist && !$overwrite) {
        return false;
    }

    $valid_files = array();
    //获取到真实有效的文件名
    if (is_array($files)) {
        foreach ($files as $file) {
            if (file_exists($file)) {
                $valid_files[] = $file;
            }
        }
    }
    //如果存在真实有效的文件
    if (count($valid_files)) {
        $zip = new ZipArchive();
        //print_r($zip);die;
        //打开文件,如果文件已经存在则覆盖，如果没有则创建
        if ($zip->open($destination, $isExist ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return false;
        }
        //向压缩文件中添加文件
        foreach ($valid_files as $file) {
            $file_info_arr = pathinfo($file);
            $filename = $file_info_arr['basename'];
            $zip->addFile($file, $filename);
        }
        //debug
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
        //close the zip -- done!
        //关闭文件
        $zip->close();
        //检测文件是否存在
        return file_exists($destination);
    } else {
        //如果没有真实有效的文件返回false
        return false;
    }
}