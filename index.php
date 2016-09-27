<?php
/**
 * 管理页面
 */
header('Content-Type: text/html; charset=UTF-8');

date_default_timezone_set('PRC'); // 切换到中国的时间

if( !function_exists('ignore_user_abort') ){
	echo '<h1>ignore_user_abort()函数不存在</h1>';
	exit;
}elseif ( !function_exists('set_time_limit') ) {
	echo '<h1>set_time_limit()函数不存在</h1>';
	exit;
}
$config = include(__DIR__.'/config/config.php');

echo '<h1>CRON管理</h1>';
$key = isset($_GET['key'])?$_GET['key']:'';
$run = URL_ROOT.'/main.php?s=run&key='.$key;
$close = URL_ROOT.'/main.php?s=close&key='.$key;

echo "<h1><a href='{$run}'>启动</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    <a href='{$close}'>关闭</a> </h1>";



// 运行监测
echo '<h4>开始运行时间:'.file_get_contents( $config['run_time'] ).'</h4>';

echo '<h4>最后运行时间:'.file_get_contents( $config['last_time'] ).'</h4>';

echo "<h4>运行时间间隔:".round($config['usleep']/1000000)."秒</h4>";

echo "<h4>请求地址:{$config['curl_url']}</h4>";

