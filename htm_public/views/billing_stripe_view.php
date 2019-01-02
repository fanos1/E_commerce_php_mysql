
<?php 
//================== Display Shopping Cart ====================

$remove = array(); // For removing problematic items:
$total = 0;


try {
    $htmlShopCart = '
    <div class="container"> 
            <div class="col-12"> 
            
                <h2>Your Shopping Cart</h2>
                <table class="table table-bordered">

                    <tr>
                        <th align="center">image</th>
                        <th align="center">Item</th>
                        <th align="center">Quantity</th>
                        <th align="right">Price</th>
                        <th align="right">Subtotal</th>                    
                    </tr>';

                    //Fetch each product
                    foreach ($rows as $k => $array) {
                        
                        // Check if user has Fruit and Veg item in the basket
                        //if ($array['category'] == 'fruitandveg') {
                        //    $fruitItem = TRUE;
                        //}
                        
                        // Check the stock status:
                        if ($array['stock'] < $array['quantity']) {
                            
                            $hide_pay_options = TRUE; // We don't want to display the payment options to user if STOCK is low.
                            

                            $htmlShopCart .= '
                            <tr class="alert alert-danger">
                                <td colspan="4">
                                    There are only ' . $array['stock'] . ' left in stock of the ' . htmlspecialchars($array['name']) . '. This item has been removed from your cart and placed in your wish list.
                                </td>
                            </tr>';

                            $remove[$array['sku']] = $array['quantity'];

                        } else {

                            // Get the correct price:
                            $price = get_just_price($array['price'], $array['sale_price']);

                            // Calculate the subtotal:
                            $subtotal = $price * $array['quantity'];

                            // NOTE: $array['category'] is not displayed to save screen on mobile
                            // <td>' . htmlspecialchars($array['category']) . ' :: ' . htmlspecialchars($array['name'])
                            $htmlShopCart .= '
                                <tr>
                                    <td>
                                        <img src="/img/'.$array['image'].'" alt="'.htmlspecialchars($array['category']).'-'.htmlspecialchars($array['name']) .'" width="48" height="48" />
                                    </td>
                                    <td>' .  htmlspecialchars($array['name']) . ' , '.       htmlspecialchars($array['size_name']). 
                                    '</td>                                    
                                    <td>' . $array['quantity'] . '</td>
                                    <td>£' . $price . '</td>
                                    <td>£' . number_format($subtotal, 2) . '</td>
                                </tr>
                            ';

                            // Add the subtotal to the total:
                            $total += $subtotal;

                        }
                
                    }
                    
                   
                    
                    
                //$shipping = get_shipping($total);

    
                $total += $shipping/100; // shipping is stored as digit, not floated i.e. 270 NOT 2.70
                $htmlShopCart .= '<tr>
                        <td colspan="2"> </td>
                        <th>Shipping</th>
                        <td>£' . number_format($shipping/100, 2) . '</td>                        
                </tr>
                ';

                // Store the shipping in the session:
                $_SESSION['shipping'] = $shipping;

				
                // Display the total:
                $htmlShopCart .= '
                <tr>
                    <td colspan="2"> </td>
                        <th>Total</th>
                        <td>£' . number_format($total, 2) . '</td>
                    <td>&nbsp;</td>
                </tr>
                ';                    
                    
                // Remove any problematic items:
                if (!empty($remove)) 
                {        
                    // Clear the results:
                    // ::: mysqli_next_result($dbc);

                    // Loop through the array:
                    foreach ($remove as $sku => $qty)  {

                        list($sp_type, $pid) = parse_sku($sku); //$remove Array stores [sku] as key

                        /* =================================================================
                        // Move it to the wish list:
                        $r = mysqli_multi_query(
                                $dbc, "CALL add_to_wish_list('$uid', '$sp_type', $pid, $qty);
                                CALL remove_from_cart('$uid', '$sp_type', $pid)"
                        );              
                        =============================================== 
                        */		

                      
                        /*
                        // Move it to the wish list:
                        $r = Cart::add_to_wish_list($dbc, $uid, $sp_type, $pid, $qty); //call
                       
                        $r2 = Cart::remove_from_cart($dbc, $uid, $sp_type, $pid, $qty); //call  
                        */
                       

                    }
                }
                
                $htmlShopCart .= '</table>                
            </div>
        </div>';

    
} catch (\Error $ex) { // Error is the base class for all internal PHP error exceptions.
    // var_dump($ex);
    exit('We aplogise! A call to undefined function error occured.');
}



  
                

// ========= Display Shopping Cart ========
echo $htmlShopCart;   



// if total is 0, no point to display payment options. Show a link to Redirect to /cart.php
// if ($total === 0) {
if (isset($hide_pay_options) && $hide_pay_options === TRUE ) {
	// echo "<h3>$total</h3>";	
	echo '<div class="container">
		
        <div class="col-12"> 
            <p class="alert alert-danger"> 
                Unfortunately the products you requested are out of stock 
                <a href="/cart.php" title="to cart page"> &rarr; Back To Cart </a>
            </p>			
        </div>

	</div>';
	exit();
} 
?>

<div class="container">
	<div class="row"> 
		<div class="col-12"> 
			<a class="btn btn-primary" href="/cart.php" title="go back to cart"> &larr; Back To Cart </a>
		</div>
	</div>
</div>

<div class="container">          
    <div class="row">        
        <div class="col-12">
            
            <?php 
            // if submited postcode is in London, display the PAY WITH CASH BUTTON. Cash payments will be collected by driver upon delivery
            if (isset($_SESSION['free-delivery']) && $_SESSION['free-delivery'] === TRUE ){ ?>
                <div>                    
                    <span style="color:red;">
                        <i>You don't have to pay with credit card. Click the button below if you prefer to pay cash when we deliver your order</i>
                    </span>   &rarr;           
                    <a class="btn btn-primary" href="/cash_payment.php" role="button">Pay by cash upon delivery</a>                    
                </div>
                <br/>
           <?php } ?>
            
            
            <div id="payment-errors"></div>             
                <?php if (isset($message)) {
                    echo "<p class=\"error\">$message</p>"; 
                }
                ?>
            
        </div>              
    </div>    
</div>




    
<div class="container" style="background-color: #ccc;">
    <hr/>  
      
    <?php include('./includes/form_functions.inc.php'); ?>
        
        <form action="/billing_stripe.php" method="POST" id="billing_form">
            
            <div class="col-4">
                <fieldset>
                    <h3>Billing Address</h3>
                    <div class="form-group">
                        <label for="cc_first_name"><strong>First Name </strong></label><br />
                        <input type="text" name="cc_first_name" value="<?php echo isset($_SESSION['cc_first_name']) ? htmlspecialchars($_SESSION['cc_first_name']) : ''; ?>" class="form-control" />
                        <?php                                             
                        if ( isset($billing_errors['cc_first_name']) ) {
                            echo '
                            <div class="alert alert-danger fade in">
                                Please enter your first name!
                            </div>';
                        }                        
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="cc_address"><strong>Street Address </strong></label><br />
                        <?php create_form_input('cc_address', 'text', $billing_errors, $values); ?>
                    </div>

                    <div class="form-group">
                        <label for="cc_city"><strong>City </strong></label><br />
                        <?php create_form_input('cc_city', 'text', $billing_errors, $values); ?>
                    </div>

                    <div class="form-group">
                        <label for="cc_zip"><strong>Post Code </strong></label>
                        <br />
                        <input type="text" class="form-control" name="cc_zip" value="<?php                             		 
                        if( isset($_SESSION['postcode']) ) { 
                            echo htmlspecialchars($_SESSION['postcode'], ENT_QUOTES, 'UTF-8');
                        } 
                        else if (isset($cc_zip)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                            echo htmlspecialchars($cc_zip, ENT_QUOTES, 'UTF-8');
                        }
                        ?>" required="required" />  

                        <?php 
                            //create_form_input('zip', 'text', $shipping_errors); 
                            if(!empty($billing_errors['cc_zip'] ) ) {
                                echo '<div class="alert alert-danger">'.$billing_errors['cc_zip'] .' </div>';
                            }                             
                        ?>
                    </div>

                    <div id="submit_div">
                        <!-- <input type="submit" value="Place Order" id="submitBtn" class="btn btn-success" /> -->
                    </div>
                    
                </fieldset>
            </div>
            
            <div class="col-3">   
                <?php if(isset($_SESSION['slot_day']) && isset($_SESSION['slot_time']) ) { ?>
                    <h3>Delivery Slot:</h3>
                <?php                     
                    echo isset($_SESSION['slot_day']) ? htmlspecialchars($_SESSION['slot_day']) .'<strong> Between </strong>' : '' ;                 
                    echo isset($_SESSION['slot_time']) ? htmlspecialchars($_SESSION['slot_time']) : '' ; 
                } ?>
                
            </div>
            
            
            <div class="col-3">
                <h3>Shipping Address:</h3>
                <?php  
                    echo '<strong>'
                        . $_SESSION['first_name'].' '. $_SESSION['last_name']. '<br/> '
                        . $_SESSION['address1']. '<br/> '
                        . $_SESSION['city']. ', '. $_SESSION['post-code']
                        .'</strong>'
                    ; 
                ?> 
            </div>
                   
            
            <div class="col-2">
				<!-- STRIPE BUTTON -->
                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                  data-key="pk_live_w7XiQVjVXf8jWkhaJEF16Nnt"
                  data-description="item descript"
                  data-amount=""
                  data-locale="auto">
                </script>
				
				
				<br/><br/>
				<!-- PAYPAL BUTTON -->				
				<!-- PAYPAL BUTTON --> 
				<script src="https://www.paypalobjects.com/api/checkout.js"></script>
				<div id="paypal-button"></div>                  
				<script>
					// EXAMPLE:: https://developer.paypal.com/demo/checkout/#/pattern/client
				
					var total_amount = <?php echo $total ?>; 
					
					var paypal_sandbox = "<?php echo $SANDBOX ?>"; 
					var paypal_production = "<?php echo $PRODUCTION ?>"; 
					
					
					paypal.Button.render({ // Selector that refers to the container element into which the PayPal button is rendered.

						env: 'production', // Or 'sandbox' | 'production',
						 
						  client: {
							  sandbox: paypal_sandbox,
							  production: paypal_production
						},
						  
						commit: true, // Show a 'Pay Now' button

						  style: {
							color: 'gold',
							size: 'small'
						},

						payment: function(data, actions) { // Function called by checkout.js when a buyer clicks the PayPal button. 
							 
							//This is where you set up and return a payment to initiate the checkout process.
							//Set up the payment here :: we call actions.payment.create() to set up the payment
							return actions.payment.create({
								payment: {
									transactions: [
										{										
											// amount: { total: '1.00', currency: 'GBP' }
											amount: { total: total_amount, currency: 'GBP' }
										}
									]
								}
							});
							
						},

						onAuthorize: function(data, actions) { // Function called by checkout.js after the buyer logs in and authorizes the payment on paypal.com. 
							 
							 // This is where you optionally show a confirmation page, and then execute the payment.
								
							// Finalize the payment
							return actions.payment.execute().then(function(payment) {
								// The payment is complete!
								// You can now show a confirmation message to the customer
								// window.alert('Payment Complete!');							
								
								window.location.replace("https://www.xxx.uk/final-paypal.php"); // RE-DIRECT doesnt work. Use SERVER INTEGRATION for this`
								
								// NOTE: Go to > https://www.paypal.com/businessmanage/preferences/website# ON PAYPAL TO ADD A REDIRECTION URL AFTER SUCCESSFULL PAYMENT
								// WE redirect to /final-paypal.php PAGE. That page sends email of the order
								
							});
							 
						},

						onCancel: function(data, actions) { // Function called by checkout.js if the buyer cancels the payment.
							 // By default, the buyer is returned to the original page, but you're free to use this function to take them to a different page.
							 // Buyer cancelled the payment 
							 
						},

						onError: function(err) { // Function called by checkout.js when an error occurs. 
							 
							  // You can allow the buyer to re-try or show an error message.
							  // An error occurred during the transaction 
							 
						}
						
					}, '#paypal-button');

				</script>
				
				
            </div>
            
            
        </form>
   
</div>


