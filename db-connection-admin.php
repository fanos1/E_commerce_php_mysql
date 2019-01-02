<?php

DEFINE ('DB_USER', 'admin'); 	
DEFINE ('DB_PASSWORD', 'xsxxasdasdxx-Myyxx'); 
DEFINE ('DB_HOST', 'xx.xx8.0.xxx');
DEFINE ('DB_DSN', 'mysql:host=1x.1.0.xxx;dbname=sdfasdf');


//db connection class using singleton pattern
//http://weebtutorials.com/2012/03/pdo-connection-class-using-singleton-pattern/
class dbConn {
	
	protected static $dbc;
	
	private function __construct() {	
		try {
			
			$options = array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
			); 
			
			self::$dbc = new PDO( DB_DSN, DB_USER, DB_PASSWORD, $options );			
			self::$dbc->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			
		}
		catch (PDOException $e) {			
			exit('Connection Error: We apologise');
		}
	
	}
	
		
		
	public static function getConnection() {	
	
		if (!self::$dbc) {
		//new connection object.
			new dbConn();
		}
		
		//return connection.
		return self::$dbc;
	}

} 
?>