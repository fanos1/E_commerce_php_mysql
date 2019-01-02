<?php
session_start();

require(__DIR__ . '/config.inc.php');



// The session ID is the user's cart ID:
$uid = session_id();

// Check that this is valid:
if (!isset($_SESSION['customer_id'])) { // Redirect the user.
    $location = 'https://' . BASE_URL . 'checkout.php';
    header("Location: $location");
    exit();
}


$dbc = ConnectFrontEnd::getConnection();


/* IF LOCAL LONDON POSTCODE is TRUE, DELIVERY IS FREE */
if (isset($_SESSION['free-delivery']) && $_SESSION['free-delivery'] === TRUE) {
     $shipping = 0;                         
} else {            
    $shipping = 3;
}


/*
 * INSERT ORDER INTO DATABASE:
 * Paypal already authorized the payment. we don't need to collect billing information, paypal already has collected
 * No need for POST[billing] Validation
 */
if (isset($_SESSION['order_id'])) // Use existing order info:
{ 
	$order_id = $_SESSION['order_id'];
	$order_total = $_SESSION['order_total'];	
} 
else  // Create a new order record:
{
	// Get the last four digits of the credit card number:	
	$cc_last_four = 1234;
	
	if(isset( $_SESSION['slot_day'] )) {
		$delivery_slot = $_SESSION['slot_day'] . ', ' . $_SESSION['slot_time'];
	} else {
		$delivery_slot = NULL;
	}
	
	//Retrieve the order ID and total: 
	//add_order() Returns the 'LastOrderID'
	$r = Order::add_order($dbc, $_SESSION['customer_id'], $uid, $shipping, $delivery_slot, $cc_last_four);

	
	if (is_array($r) && !empty($r)) {                 
		
		list($order_id, $order_total) = $r;
		
		$_SESSION['order_total'] = $order_total;
		$_SESSION['order_id'] = $order_id;
		
	} else { // The add_order() method failed for some reason           
	   // unset($cc_number, $cc_cvv, $_POST['cc_number'], $_POST['cc_cvv']);
		trigger_error('Your order could not be processed due to a system error. We apologize for the inconvenience.');
	}
}




// Clear out the shopping cart:
$r = Cart::clearCart($dbc, $uid);


// Send the email:
include('./includes/email_receipt.php');


// ============ HTML ===================
$page_title = 'Checkout - Your Order is Complete';
include('./includes/header-checkout.php');
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            
            <h2>Your Order is Complete</h2>
            <p>Thank you for your order. Your order number is: (#<?php echo $_SESSION['order_id']; ?>). 
                Please use this order number in any correspondence with us.</p>

            <p>
				A charge of £<?php 
					if(isset($_SESSION['order_total'])) {
						echo number_format($_SESSION['order_total']/100, 2); 
					}
				?> will appear on your credit card 
				<strong>(if paid with card) when the order ships.  </strong>
				<strong> You will be contacted in case of any delays. </strong>  
            </p>
			
			<p>Your transaction has been completed and we've emailed you a receipt for your purchase. Log in to your PayPal account to view transaction details.</p>
            
        </div>
    </div>    
</div>


<?php
// Clear the session:
$_SESSION = array(); // Destroy the variables.
session_destroy(); // Destroy the session itself.

include('./includes/footer.php');
?>