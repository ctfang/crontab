<?php
/**
 * cron程序入口文件
 */
header('Content-Type: text/html; charset=UTF-8');
ignore_user_abort(true);
set_time_limit(0);
date_default_timezone_set('PRC'); // 切换到中国的时间

require __DIR__.'/cron/function.php' ;
$config = include(__DIR__.'/config/config.php');

// 查看执行密码
if( !$config['key'] ){
	if( get_client_ip()!=get_server_ip() ){
		echo get_client_ip(),' and ',get_server_ip();
		echo '<h1>只允许本机请求</h1>';
		exit;
	}
}elseif( $_GET['key']!=$config['key'] ){
	echo '<h1>权限码不对</h1>';
	exit;
}

if( isset($_GET['s']) && file_exists( './cron/'.$_GET['s'].'.php' ) ){
	header("Location:index.php");
	include_once( './cron/'.$_GET['s'].'.php' );
	new $_GET['s']($config);
}else{
	echo '<h1>不处理</h1>';
	exit;
}