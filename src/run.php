<?php
/**
 * 主函数
 * User: selden1992
 * Date: 2016/10/29
 * Time: 19:59
 */

namespace crontab\src;


class run
{
    static protected $_pid;     // 当前进程随机标识单例
    static protected $_config;  // 配置单例

    /**
     * 实例化对象,初始化pid单例，防止垃圾回收
     */
    public function __construct()
    {
        self::$_pid = uniqid();
    }

    /**
     * 启动函数
     */
    static private function begin()
    {
        session_write_close();// 保存会话数据并释放文件锁,防止挂起
        ignore_user_abort(true);
        set_time_limit(0);
        if( !file_exists( self::$_config['can_run'] ) ) mkdir(self::$_config['can_run'],0755,true);
        file_put_contents(self::$_config['can_run'].'/'.self::$_pid,time());
        // 循环体
        do{
            $num = count( scandir( self::$_config['can_run'] ) );
            if ( $num!=3 ){
                self::delete( self::$_config['can_run'] );// 删除可运行标识
                self::log( 'end  ：id='.self::$_pid.' and time='.date('Y-m-d H:i:s')."\n" );
                exit;// 停止
            }
            file_put_contents(self::$_config['run_file'],time());
            foreach (self::$_config['cron_list'] as $http){
                file_get_contents($http);
            }
            sleep(self::$_config['interval']);
        }while(true);
    }

    /**
     * 设置配置
     * @param $_config
     */
    static public function set( $_config )
    {
        if( !is_array($_config) ){
            die(___FILE__.'传入参数必须为数组');
        }elseif( !$_config['interval'] || !$_config['run_file'] || !$_config['can_run'] ){
            die(___FILE__.'空值');
        }
        self::$_config = $_config;
    }

    /**
     * 结束调用
     */
    public function __destruct()
    {
        // 设置默认值
        if( !isset(self::$_config) ){
            if( is_file( './config/cron_config.php' ) ){
                run::set( include './config/cron_config.php' );
            }else{
                file_put_contents('./config/cron_config.php',file_get_contents(dirname(__DIR__).'/cron_config.php'));
            }
        }

        if( !self::$_config['run'] ){
            // 不需要执行
            if( file_exists(self::$_config['can_run']) ){
                self::delete( self::$_config['can_run'] );// 删除可运行标识
            }
            return false;
        }
        // 检查是否已经进入循环,或旧的进程是否有效
        if( file_exists(self::$_config['run_file']) ){
            // 最后运行时间间隔
            $lastRun = file_get_contents( self::$_config['run_file'] );
            if( $lastRun>(time()-self::$_config['timeout']) ){
                return false;// 旧进程还在运行不启动，判断时间60，防止拖时
            }
        }
        // 允许运行，但是防止影响当前请求做处理
        if( isset($_POST['goto_cron']) ){
            self::log( 'begin：id='.self::$_pid.' and time='.date('Y-m-d H:i:s')."\n" );
            self::begin();
        }else{
            self::log( Http::url()."\n" );
            Http::sockOpen( Http::url(),array('goto_cron'=>true) );
        }
    }

    /**
     * 运行记录
     * @param $content
     */
    static public function log( $content )
    {
        if( !file_exists(self::$_config['run_log']) )
            mkdir(self::$_config['run_log'],0755,true);
        file_put_contents(self::$_config['run_log'].'/'.date('Y-m-d').'.log',$content,FILE_APPEND);
    }

    /**
     * 删除文件下所有文件
     * @param $path
     */
    static public function delete( $path )
    {
        $op = dir($path);
        while(false != ($item = $op->read())) {
            if($item == '.' || $item == '..') {
                continue;
            }
            if(is_dir($op->path.'/'.$item)) {
                self::delete($op->path.'/'.$item);
                rmdir($op->path.'/'.$item);
            } else {
                unlink($op->path.'/'.$item);
            }

        }
        rmdir( $path );
    }
}