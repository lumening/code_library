<?php 

/**
 * 杀掉已存在的进程
 * @return void
 * 
 */
function kill_process_if_exist()
{
	// 得到当前文件名和进程PID
	$cur_pid = posix_getpid();
	$basic_cmd = basename(__FILE__);
	$cmd = "ps aux | grep '".$basic_cmd."' | grep -v grep | awk '{print $2}' | grep -v $cur_pid | xargs kill -9";
	echo $cmd."\n";
	system($cmd);
}




 ?>