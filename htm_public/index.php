<?php
session_start();

try {
	require (__DIR__ . '/config.inc.php');	
	$dbc = ConnectFrontEnd::getConnection(); 	
}	
catch (PDOException $e) {
	$err_title = 'An error has occurred';	
	$pdo_err_output = 'Database error: ' . $e->getMessage() . ' in ' .$e->getFile() . ':' . $e->getLine();		
	error_log($pdo_err_output, 1, CONTACT_EMAIL); 
	exit('An Error occured(1), we apologise1');
} 


// ========= HTML =============
// ========= HTML =============
// To prevent FATAL errors, check if file exist before including :: Custom error_handler() will do same thing 
include(INCLUDES. 'header.php');
include ( VIEWS . "front_page_view.php" );
include(INCLUDES. 'footer-frontpage.php');  	
?>