<?php
/**
* 
*/
class run
{
	static public $config;

	function __construct( $config )
	{
		self::$config = $config;
		if( file_exists(self::$config['runing']) ){
			echo '<h1>已经在运行</h1>';
			exit;
		}
		file_put_contents(self::$config['runing'],'runing');
		file_put_contents(self::$config['un_close'],'un_close');
		file_put_contents(self::$config['run_time'],date('Y-m-d H:i:s'));
		$this->runing();
	}
	// 循环执行
	public function runing(){
		$context = stream_context_create(array(
			  'http'=>array(   
			    'method'=>"GET",   
			    'timeout'=>100,//单位秒  
			   ) 
		));
		do {
		    if( $this->isClose() ) exit;

		    // 定时请求某地址
		    file_get_contents(self::$config['curl_url'], 0, $context);

		    //延迟 10 描述
		    usleep(self::$config['usleep']);
		    $this->cronLog();
		} while(true);
	}
	// 写入log
	public function cronLog(){
		// 最后运行时间
		$now_time = date('Y-m-d H:i:s');
		
		$dir 	= self::$config['runtime'].date('Y-m-d').'/';
		if(!is_dir($dir)){
			mkdir($dir,0755,true);
		}
		file_put_contents($dir.date('H').'.log',$now_time."\n",FILE_APPEND);

		file_put_contents(self::$config['last_time'],$now_time);
	}
	// 是否需要关闭
	public function isClose(){
		if( file_exists(self::$config['un_close']) ){
			return false;
		}
		// 开始关闭操作
		@unlink( self::$config['runing'] );
		return true;
	}
}