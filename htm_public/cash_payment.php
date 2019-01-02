<?php 
require (__DIR__ . '/config.inc.php');

include('./includes/product_functions.inc.php');



// Start the session:
session_start();

// The session ID is the user's cart ID:
$uid = session_id();

// Check that this is valid:
if (!isset($_SESSION['customer_id'])) { // Redirect the user.
       
    $location = 'https://' . BASE_URL . 'checkout.php';
    header("Location: $location");
    exit();
}
if(!isset($_SESSION['free-delivery']) || $_SESSION['free-delivery'] === FALSE) {
    // Redirect because only Local London postcodes can pay with cash
    $location = 'https://' . BASE_URL . 'checkout.php';
    header("Location: $location");
    exit();
}

try {
	
	$dbc = ConnectFrontEnd::getConnection();
	$billing_errors = array(); 
	
	
	// Check for a form submission:
	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		// Check for Magic Quotes:
		// Strip slaeshes if it is on. We don't want to end up with 2 escaping slashes.
		// We strip slashes, we will add them later ourselves if necessary. 
		// we use PDO prepared statemnts in this project, so no need to escape before inseting. Sweet!
	
		// Is magic quotes on? Nowadays, this is mostly off, but just in case
		if (get_magic_quotes_gpc()) {
			// Yes? Strip the added slashes
			$_REQUEST = array_map('stripslashes', $_REQUEST);
			$_GET = array_map('stripslashes', $_GET);
			$_POST = array_map('stripslashes', $_POST);
			$_COOKIE = array_map('stripslashes', $_COOKIE);
		} 
			

		// Check for a first name:
		if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $_POST['cc_first_name'])) {
			$cc_first_name = $_POST['cc_first_name'];
		} else {
			$billing_errors['cc_first_name'] = 'Please enter your first name!';
		}

			
		// Check for a street address:
		if (preg_match ('/^[A-Z0-9 \',.#-]{2,160}$/i', $_POST['cc_address'])) {
			$cc_address  = $_POST['cc_address'];
		} else {
			$billing_errors['cc_address'] = 'Please enter your street address!';
		}
			
		// NO NEED TO CHECK city name. All POST CODES VALID WILL BE IN HACKNEY - LONDON
		// TELEPHONE numbers are also not needed. the phone number was ALREADY INSERTED in /checkou.php/ File
		   
		// Check for a zip code:		
		if( preg_match('/^(?:[A-Za-z]\d ?\d[A-Za-z]{2})|(?:[A-Za-z][A-Za-z\d]\d ?\d[A-Za-z]{2})|(?:[A-Za-z]{2}\d{2} ?\d[A-Za-z]{2})|(?:[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]{2})|(?:[A-Za-z]{2}\d[A-Za-z] ?\d[A-Za-z]{2})$/', $_POST['cc_zip'])) 
		{
			$cc_zip = $_POST['cc_zip'];
		} else {
			$billing_errors['cc_zip'] = 'Please enter your zip code!';
		}
			
			
			//=======================
			// If everything's OK...        
			// IF All inputs are valid, AND Stripe returned 'token', continue          
			//===========================
		if (empty($billing_errors)) {

			// Check for an existing order ID:
			if (isset($_SESSION['order_id'])) { // Use existing order info:
				$order_id = $_SESSION['order_id'];
				$order_total = $_SESSION['order_total'];
							
			} else { // Create a new order record:

				// Get the last four digits of the credit card number:
				// Temporary solution for Stripe:
				$cc_last_four = 0000; // temporary solution, just use 0 to indicate cash payment upon delivery                        
				$shipping = 0;
				$delivery_slot = $_SESSION['slot_day']. ' at '. $_SESSION['slot_time'];
				
			  
				$r = Order::add_order($dbc, $_SESSION['customer_id'], $uid, $shipping, $delivery_slot, $cc_last_four);
				
				if( is_array($r) && !empty($r) ) { //if the add_order() returned an Array, not empty                            
												
					list($order_id, $order_total) = $r;
					 
					 $_SESSION['order_total'] = $order_total;
					 $_SESSION['order_id'] = $order_id;                                                          
					 
				} else { // The add_order() method failed for some reason. it could be that this method couln not retrieve the order ID and total.                            
					unset($cc_number, $cc_cvv, $_POST['cc_number'], $_POST['cc_cvv']);
					trigger_error('Your order could not be processed due to a system error. We apologize for the inconvenience.');
				}                         
						 
			}
			
			
			//------------------------
			// Process the payment! NOT NEED BECAUSE THIS IS CASH TRANSACTION. NO NEED FOR STRIP
			//---------------------
			if (isset($order_id, $order_total)) {

				// $charge->id is VARCHAR() type in TABLE. Since this is cash transaction, we can record something like CASH
				// $full_response IS ALSO VARCHAR(). 
				$chargeId = 'CASH PAYMENT';
				$full_response = 'cash';


				// Record the transaction:
				//$r = Order::add_charge($dbc, $charge->id, $order_id, 'auth_only', $order_total, $full_response);
				$r = Order::add_charge($dbc, $chargeId, $order_id, 'auth_only', $order_total, $full_response);


				// Add the transaction info to the session:
				$_SESSION['response_code'] = 1;



				// Redirect to the next page:
				$location = 'https://' . BASE_URL . 'final.php';
				header("Location: $location");
				exit();

			} 	
					
		} 

	} // End of REQUEST_METHOD IF.

	
	
} catch (PDOException $ex) {
	$err_title = 'An error has occurred';	
	$pdo_err_output = 'Database error: ' . $ex->getMessage() . ' in ' .$ex->getFile() . ':' . $ex->getLine();
	
	error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email
}




//============== HTML ==============				
//============== HTML ==============
//============== HTML ==============
// Include the header file:
$page_title = 'Fresc - Checkout - Your Billing Information';
include('./includes/header-checkout.php');

$rows = Cart::get_shopping_cart_contents($dbc, $uid);//SHOPPING CART

if($rows) {           
    include('./views/cash_payment_view.php');    
} else { // Empty cart!
    include('./views/emptycart.php');
}

// Finish the page:
include('./includes/footer-plain.php');
?>