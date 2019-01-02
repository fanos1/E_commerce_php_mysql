<?php
session_start();

try 
{
	require (__DIR__ . '/config.inc.php');
	include('./includes/product_functions.inc.php');

	$type = $sp_cat = $category = false;

	if (isset( $_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) 
	{	
		$sp_cat = $_GET['id']; 		
		
	} else {
		$page_title = 'Error!';        
		include(INCLUDES. 'header.php');
		include ( VIEWS . "error_view.php" ); 
		include(INCLUDES. 'footer.php');
		exit("Error");
	}

	// require(PDO);
	$dbc = ConnectFrontEnd::getConnection(); 	
	$rows = Product::select_products($dbc, $sp_cat); // Autoload() function will include (MODELS. 'Product.php')
	
	// Title and Description
	if(isset($rows[0]['h1_title'])) {
		$page_title = $rows[0]['h1_title'];		
		$page_desc = $rows[0]['h1_title']. '. Free Delivery within London. '. $rows[0]['g_description'];
	}
	
} catch (PDOException $ex) {
	
	$err_title = 'An error has occurred';	
	$pdo_err_output = 'Database error: ' . $ex->getMessage() . ' in ' .$ex->getFile() . ':' . $ex->getLine();
	
	error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email
	exit('An Error occured(1), we apologise2');
	
}


// ========== HTML ==============
// ========== HTML ==============
include(INCLUDES. 'header.php');

if ($rows) {       
    include ( VIEWS . "category_view.php" );           
} else {
    include ( VIEWS . "noproducts_view.php" ); 
}

include('./includes/footer-category.php');
?>