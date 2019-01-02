<?php
session_start();
$uid = session_id();

require( __DIR__ . '/config.inc.php');
include('./includes/product_functions.inc.php');
require( __DIR__ . '/../API_keys.php');	


// Check that user arrived from /checkout.php
if (!isset($_SESSION['customer_id'])) { 
	$location = 'https://' . BASE_URL . 'checkout.php';
	header("Location: $location");
	exit();
}

$dbc = ConnectFrontEnd::getConnection();
$billing_errors = array();


/* IF LOCAL LONDON POSTCODE is TRUE, DELIVERY IS FREE */
if (isset($_SESSION['free-delivery']) && $_SESSION['free-delivery'] === TRUE) {
	 $shipping = 0;                         
} else {            
	$shipping = 330;
}


// ===== 
// POST 
// =========	
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	try {
		
		/*
		 * Check for a Stripe token: if no token returned from Stripe, this means stripe rejected our form inputs such as CVV
		 * in that case, Add error to $billing_errors.
		 * POST['token'] is not in our FORM, it's returned from stripe as hidden field after is sent to Stripe via Ajax
		 * We validate our own custom fields in the FORM such as 'first name' etc.. The fields which have "name" attribute
		 * Stripe will validate the form's CVV, CARD NUMBER and ExPIREY DATE 
		 */
		if (isset($_POST['token'])) {
			$token = $_POST['token'];
		} elseif (isset($_POST['stripeToken'])) { //Stripe could alos send $_POST['stripeToken'] instead of $_POST['token']
			$token = $_POST['stripeToken'];
		} else {
			$message = 'The Stripe Token was not generated correctly. Please make sure you have JavaScript enabled and try again, str105';
			$billing_errors['token'] = TRUE; //We set $billing_errors to TRUE if stipe did not return token; When a var has TRUE value, empty() returns false                
		}

		
		$required = array('cc_first_name');
		
		$obj = new Validator($required);			
		$obj->validBillingForm();
		$billing_errors  = $obj->getErrors(); // Fetch Billing Validation Errors if any
	

		//=======================
		// If everything's OK...        
		// IF All inputs are valid, AND Stripe returned 'token', continue          
		//===========================
		if (empty($billing_errors)) 
		{
			// POST[] inputs have been validated at this point. 
			// PDO prepared statement used for DB, no need to escape
			$cc_first_name = strip_tags($_POST['cc_first_name']);
			$cc_address = strip_tags( $_POST['cc_address']);
			$cc_city = strip_tags( $_POST['cc_city']);
			$cc_zip = strip_tags( $_POST['cc_zip']);
			
			// Check for an existing order ID:
			if (isset($_SESSION['order_id'])) {				
				
				// Use existing order info:
				$order_id = $_SESSION['order_id'];
				$order_total = $_SESSION['order_total'];
				
			} else { // Create a new order record:
				
				//WITH STRIPE WE DON'T NEED TO STORE CREDIT CARD INFORMATION
				
				$cc_last_four = 1234;
				//$cc_last_four = substr($cc_number, -4);
				
				if(isset( $_SESSION['slot_day'] )) {
					$delivery_slot = $_SESSION['slot_day'] . ', ' . $_SESSION['slot_time'];
				} else {
					$delivery_slot = NULL;
				}				
			
				//Retrieve the order ID and total ::  add_order() Returns the 'LastOrderID'					 
				$r = Order::add_order($dbc, $_SESSION['customer_id'], $uid, $shipping, $delivery_slot, $cc_last_four);

				if (is_array($r) && !empty($r)) {					
					list($order_id, $order_total) = $r;
					$_SESSION['order_total'] = $order_total;
					$_SESSION['order_id'] = $order_id;					
				} else { 
					// unset($cc_number, $cc_cvv, $_POST['cc_number'], $_POST['cc_cvv']);
					trigger_error('Your order could not be processed due to a system error. We apologize for the inconvenience.');
				}				
				
			}


			
			//------------------------
			// Process the payment!
			//---------------------
			if (isset($order_id, $order_total)) 
			{ 
				// STRIPE 
				try {                                                                                
					require_once './includes/stripe-php-5.1.3/init.php';  // this init.php file loads all Stripe files required. it s like autoload
					// ruequire_once('vendor/autoload.php');
				   
				   \Stripe\Stripe::setApiKey($secretLive);          
				   

					/*
					  // Create a Customer:
					  $customer = \Stripe\Customer::create(array(
					  "email" => $_SESSION['email'],
					  "source" => $token,
					  ));
					 * 
					 */

					$charge = \Stripe\Charge::create(array(
						//$charge = Stripe_Charge::create(array(
						'amount' => $order_total,
						'currency' => 'gbp',
						// 'card' => $token,
						'source' => $token,
						'description' => $_SESSION['email'],
						'capture' => false // if capture param  == false, this charge will be only authorized, not charged immediately. We will cahpture this later
					));


					// Did it work? if Success
					if ($charge->paid == 1) {

						$full_response = addslashes(serialize($charge));			
						$r = Order::add_charge($dbc, $charge->id, $order_id, 'auth_only', $order_total, $full_response);                    
						$_SESSION['response_code'] = $charge->paid;

						// Redirect to the next page:
						$location = 'https://' . BASE_URL . 'final.php';
						header("Location: $location");
						exit();
						
					} else { // Charge was not paid!
						$message = $charge->response_reason_text;
						echo "<h3>268</h3>";
						//echo $message;
					}

					
				} catch (\Stripe\Error\Card $e) { // Stripe declined the charge.    
					$e_json = $e->getJsonBody();
					$err = $e_json['error'];
					$message = $err['message'];
					$errType = $err['type'];
					echo '267' . $message;
					
				} catch (\Stripe\Error\RateLimit $e) {
					// Too many requests made to the API too quickly
					exit('Too many requests made to the API too quickly!');
				} catch (\Stripe\Error\InvalidRequest $e) {                
					// You screwed up in your programming. Shouldn't happen!   
					// invalid parameters were supplied to Stripe's API
					$e_json = $e->getJsonBody();
					$err = $e_json['error'];
					$message = $err['message'];
					$errType = $err['type'];
					echo '269' . $message;
					exit('Error: invalid parameters were supplied to Stripes API');
				} catch (\Stripe\Error\Authentication $e) {
					// Authentication with Stripe's API failed
					// (maybe you changed API keys recently)
					exit('Authentication with Stripes API failed');
				} catch (\Stripe\Error\ApiConnection $e) {
					//Network problems, try again                        
					exit('Network Error!');
				} catch (\Stripe\Error\Api $e) {
					// Stripe's servers are down!    
					exit('Stripe Error!');
				} catch (\Stripe\Error\Base $e) {
					// Something else that's not the customer's fault.  
					exit('An error occured, we apologise! please contact us');
				} catch (Exception $e) { // Try block failed somewhere else.
					trigger_error(print_r($e, 1));
					exit('We apologise, an Error occured!');
				}	
				
			} 
		}

		
	}	
	catch (PDOException $e) {
		$err_title = 'An error has occurred';	
		$pdo_err_output = 'Database error: ' . $e->getMessage() . ' in ' .$e->getFile() . ':' . $e->getLine();		
		error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email
		exit('An Error occured(1), we apologise1');
	} 

	
}

	
	
// Get SHOPPING CART	
try {	
	$rows = Cart::get_shopping_cart_contents($dbc, $uid); 
}	
catch (PDOException $e) {
	$err_title = 'An error has occurred';	
	$pdo_err_output = 'Database error: ' . $e->getMessage() . ' in ' .$e->getFile() . ':' . $e->getLine();		
	error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email
	exit('An Error occured(1), we apologise1');
} 



$_SESSION['formtoken'] = md5(uniqid(rand(), true));
$formToken = htmlspecialchars($_SESSION['formtoken']);



//============== HTML ==============				
//============== HTML ==============
//============== HTML ==============
$page_title = 'Checkout - Your Billing Information';

include(INCLUDES. 'header-checkout.php');

if ($rows) { // if Cart not Empty
    if (isset($_SESSION['shipping_for_billing']) && ($_SERVER['REQUEST_METHOD'] !== 'POST')) {
        $values = 'SESSION';
    } else {
        $values = 'POST';
    }
	
    include('./views/billing_stripe_view.php');
	
} else { // Empty cart!
    include('./views/emptycart.php');
}

// Finish the page:
include(INCLUDES. 'footer-plain.php');

?>