<?php
session_start();

require( __DIR__ . '/config.inc.php');
include('./includes/product_functions.inc.php');

// Check for, or create, a user session:
if (isset($_COOKIE['SESSION']) && (strlen($_COOKIE['SESSION']) === 32)) {
	$uid = $_COOKIE['SESSION'];
} else {
	$uid = openssl_random_pseudo_bytes(16);
	$uid = bin2hex($uid);
}
setcookie('SESSION', $uid, time()+(60*60*24*1));// keep cookie 1 day


$dbc = ConnectFrontEnd::getConnection();
// include(MODELS. 'Cart.php'); // Autoload function will automatically includ


if(isset($_GET['product_id'], $_GET['product_code'] )) 
{
	$options = array("min_range"=>1, "max_range"=>10000);
	$pid = filter_var($_GET['product_id'], FILTER_VALIDATE_INT, $options);
	$type = filter_var($_GET['product_code'], FILTER_VALIDATE_INT, $options);	
} 
	
	
try {
	
	$msg = '';//var which displays message to user if product was successfully added or removed to Cart

	if (isset($pid, $type, $_GET['action'],  $_GET['size']) && ($_GET['action'] === 'add') ) 
	{   		   
		if( preg_match('/^[a-zA-Z0-9\/\%\-]{1,30}$/', $_GET['size'] ) ) { 
			$size = trim($_GET['size']);
		} else {
			exit('An error occured, we apoligize!');
		}
	  
	   
		$rows = Cart::add_to_cart($dbc, $uid, $type, $pid, 1, $size);
		
		if($rows) { //Ajax, if true added successfully to cart
			//Returned data from server is returned to ajax javascript. 
			//anything echoed is returned
			//echo 'irfan';
			
			$data = array(
				'status' => 'success',
				'message' => 'Item added to basket'
			);
			echo json_encode($data);
			exit();
		}
			
	} 
	elseif (isset($type, $pid, $_GET['action']) && ($_GET['action'] === 'remove') ) 
	{
		$rows = Cart::remove_from_cart($dbc, $uid, $type, $pid);
		
		if($rows) {        
			$msg = 'item removed successfully from cart';            
		} else {
			$msg = 'item could not be removed from the shopping cart';            
		}
		

	} elseif (isset($type, $pid, $_GET['action'], $_GET['qty']) && ($_GET['action'] === 'move') ) {
		
		$r = Cart::add_to_cart($dbc, $uid, $sp_type, $pid, $qty);
		
		if($r) {
			$msg = 'item moved successfully to cart';         
		} 		
		
		//Remove from wish list
		$r = Cart::remove_from_wish_list($dbc, $uid,  $sp_type, $pid);
		
		if($r) {            
			$msg = 'item removed successfully from wish list';         
		} 
		

	} elseif (isset($_POST['quantity'])) { // Update quantities in the cart.
		
		/* 
		foreach ( $_POST['quantity'] as $product_code => $values ) {
			echo "<h3>$product_code :: $values </h3>";
		}		
		*/
		
		foreach ($_POST['quantity'] as $prodCodeAndprodID => $qty) { 		

			//list($type, $pid) = parse_sku($sku);// Parse the SKU:
			
			//split $prodCodeAndprodID from -
			list($pCode, $pid)  = explode("-", $prodCodeAndprodID);
			
			//echo "<h3>$pCode : $pid </h3>";
			//exit('Cik');

			if (isset($pCode, $pid)) {
				// Determine the quantity:
				$qty = (filter_var($qty, FILTER_VALIDATE_INT, array('min_range' => 0)) !== false) ? $qty : 1;
				// Update the quantity in the cart:
				//:::$r = mysqli_query($dbc, "CALL update_cart('$uid', '$type', $pid, $qty)");
				
				$r = Cart::update_cart($dbc, $uid, $pCode, $pid, $qty);
				/* 
				if($r) {
					//echo "<h3>item updated in the cart</h3>";
					//$itemUpdated = '<div class="alert alert-success"> <strong>Success!</strong> Indicates a successful or positive action.</div>';
				} else {
					echo "<h3>item NOT updated in the cart str117</h3>";
				}
				*/            
			}        
		}
		
	}

	// shopping Cart
	$rows = Cart::get_shopping_cart_contents($dbc, $uid);
} 
catch (PDOException $ex) 
{
	$err_title = 'An error has occurred';	
	$pdo_err_output = 'Database error: ' . $ex->getMessage() . ' in ' .$ex->getFile() . ':' . $ex->getLine();	
	error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email
} 


 

//=============== HTML =====================
//=============== HTML =====================
//=============== HTML =====================
include(INCLUDES. 'header.php');

if($rows) {  
    include ( VIEWS . "cart_view.php" );         
    if( isset($itemRemoved) ) {
        echo '
		<div class="container">
			<div class="row">
				<div class="alert alert-success">'. $msg . '</div>
			</div>
		</div>';
    }
} else {    
    include ( VIEWS . "emptycart_view.php" );        
}
 
include(INCLUDES. 'footer.php');
?>