<?php
require( __DIR__ . '/config.inc.php');
include './includes/product_functions.inc.php';

require(PDO);
$dbc = ConnectFrontEnd::getConnection();




// if ( isset($type, $pid) && filter_var($pid, FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
if ( isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
	
    $pid = $_GET['pid'];    
 
} else {
    exit('GET pid NOT SET');
    $page_title = 'Error!';        
    include(INCLUDES. 'header.php');
    include ( VIEWS . "error_view.php" ); 
    include(INCLUDES. 'footer.php');
    exit();
}


try {
	include(MODELS. 'Product.php');	
	$rows = Products::select_product_byID($dbc, $pid);		
} 
catch (PDOException $ex) 
{
	$err_title = 'An error has occurred';	
	$pdo_err_output = 'Database error: ' . $ex->getMessage() . ' in ' .$ex->getFile() . ':' . $ex->getLine();	
	error_log($pdo_err_output, 1, CONTACT_EMAIL); 
}



//============= HTML ==============
// ============ HTML ==============
include(INCLUDES. 'header.php');
if ($rows) {           
    $page_title = $rows[0]['name'];    
    include ( VIEWS . "product_detail_view.php" );     
} else {
    //echo 'NO ROWS';
    include ( VIEWS . "noproducts_view.php" ); 
}


// Include the footer file:
include(INCLUDES. 'footer-plain.php');
?>