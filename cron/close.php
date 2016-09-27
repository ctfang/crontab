<?php
/**
* 
*/
class close
{
	
	function __construct( $config )
	{
		self::$config = $config;
		@unlink( self::$config['un_close'] );
	}
}