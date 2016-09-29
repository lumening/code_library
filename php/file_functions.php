<?php
/**
 * @desc: 常用的PHP函数-字符串函数
 * @date: 2015/11/25 18:11
 * @author: Tan <tanda517886160@163.com>
 * @version: 1.0
 */



/**
 * 检测文件是否符合过滤条件
 *
 * @param string $file
 * @param array  $filter
 * @return bool
 */
function is_ignore ($file, $filter)
{
    $filter = (array)$filter;
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    if (in_array($file, $filter) || in_array('.' . $ext, $filter))
        return true;
    else
        return false;
}



/**
 * 当前目录下的文件
 *
 * @param  string $dir
 * @param  array  $filter
 * @action is_ignore()
 * @return array
 */
function list_dir ($dir, $filter = array())
{
    $filter = (array)$filter;
    if (!file_exists($dir) || !is_dir($dir)) return '';
    $dir = new DirectoryIterator($dir);
    $list = array();
    while ($dir->valid()) {
        if (!$dir->isDot() && !is_ignore($dir->getFilename(), $filter)) //去除.和..以及被过滤文件
        {
            $list[$dir->key()]['name'] = $dir->getFilename();
            $list[$dir->key()]['isDir'] = $dir->isDir() ? 1 : 0;
        }
        $dir->next();
    }
    return $list;
}

/**
 * 递归循环列出所有目录
 *
 * @param string $pathName
 * @param array  $filter
 * @action is_ignore()
 * @return array
 */
function list_recursive_dir ($pathName, $filter = array())
{
    $tmp = array();
    $result = array();
    if (!is_dir($pathName) || !is_readable($pathName)) {
        return null;
    }
    $allFiles = scandir($pathName);
    foreach ($allFiles as $fileName) {
        if (in_array($fileName, array('.', '..')) || is_ignore($fileName, $filter)) continue;
        $fullName = $pathName . '/' . $fileName;
        if (is_dir($fullName)) {
            $result[$fileName] = list_recursive_dir($fullName, $filter);
        } else {
            $temp[] = $fileName;
        }
    }
    if (isset($temp) && $temp) {
        foreach ($temp as $f) {
            $result[] = $f;
        }
    }
    return $result;
}
