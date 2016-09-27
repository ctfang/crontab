<?php
return [
	// 本站地址根
	'url_root'=>defined('URL_ROOT')?URL_ROOT:(define ('URL_ROOT','http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']))?URL_ROOT:''),
	// 执行url地址
	'curl_url'=>URL_ROOT.'/test.php',

	// 权限码,false只允许本机
	'key'=>false,
	// 循环时间 延迟 10 描述
	'usleep'=>10000000,

	// 开始运行时间
	'run_time'=>__DIR__.'/../runtime/run-time',
	// 最后时间保存地址
	'last_time'=>__DIR__.'/../runtime/last-time',
	// 日记保存地址
	'runtime'=>__DIR__.'/../runtime/log/',
	// 不存在这个文件时，关闭
	'un_close'=>__DIR__.'/../runtime/un-close',
	// 正在运行标识
	'runing'=>__DIR__.'/../runtime/runing',
];