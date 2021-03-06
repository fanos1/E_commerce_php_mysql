<?php

DEFINE ('DB_USER', 'xxxxx'); 	
DEFINE ('DB_PASSWORD', 'K!-xxxxxx'); 
DEFINE ('DB_HOST', '10.yyy.0.xxx');

// DEFINE ('DB_DSN', 'mysql:host=localhost;dbname=xxxxx');
DEFINE ('DB_DSN', 'mysql:host=10.xxx.0.xxx;dbname=sssss');



//db connection class using singleton pattern
//http://weebtutorials.com/2012/03/pdo-connection-class-using-singleton-pattern/
class ConnectFrontEnd {
	
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
			
			$out = 'Database error: '. $e->getMessage() .' in '. $e->getFile() . ':' . $e->getLine();
			
			// Send Email
			error_log($out, 1, "admin@example.com");			
			
			// Log this err to SERVERs log
			//error_log($out, 0); // @param 0 :: Message is sent to PHP's system logger, 
			
			exit('Connection Error: We apologise');			
		}
	
	}
	
		
		
	public static function getConnection() {	
	
		if (!self::$dbc) {
		//new connection object.
			new ConnectFrontEnd();
		}
		
		//return connection.
		return self::$dbc;
	}

} 
?>