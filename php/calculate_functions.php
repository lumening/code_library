<?php
/**
 * @desc: 常用的PHP函数-计算函数
 * @date: 2015/11/25 18:11
 * @author: Tan <tanda517886160@163.com>
 * @version: 1.0
 */


/**
 * 十进制转二进制
 * 
 * @param  int $number
 * @return string
 */
function decimal2binary ($number)
{
    $remainder = array();
    $integer = $number;
    while ($integer != 1) {
        array_unshift($remainder, $integer % 2);
        $integer = floor($integer / 2);
    }
    array_unshift($remainder, $integer);
    if (($bin_len = count($remainder)) % 4) {
        $t = floor($bin_len / 4) + 1;
    } else {
        $t = floor($bin_len / 4);
    }
    $t = $t * 4;
    $bin_str = sprintf("%-0{$t}s", trim(implode('', $remainder)));
    $bin_str = preg_replace('/([01]{4})/', '\1 ', $bin_str);
    return $bin_str;
}



/**
 * 计算两点指点的距离
 *
 * @param $latitude1
 * @param $longitude1
 * @param $latitude2
 * @param $longitude2
 * @return array
 */
function getDistanceBetweenPointsNew ($latitude1, $longitude1, $latitude2, $longitude2)
{
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
}

