


<div class="container">  
        
        <?php include('./includes/form_functions.inc.php'); ?>
        
        <div class="col-12">
            
            <div id="paypal-button"></div>        

            <script>
				var total_amount = <?php echo $total ?>; // 7
				var sandboxPay = "<?php echo $SANDBOX; ?>";
				var productionPay = "<?php echo $PRODUCTION; ?>";
				
                paypal.Button.render({ // Selector that refers to the container element into which the PayPal button is rendered.

                  env: 'sandbox', // Or 'sandbox',
				  
				  client: {
					  sandbox:sandboxPay
					  production: productionPay 
				  },

                  commit: true, // Show a 'Pay Now' button

                  style: {
                    color: 'gold',
                    size: 'small'
                  },

                  payment: function(data, actions) { // Function called by checkout.js when a buyer clicks the PayPal button. 
                    /* 
                     * This is where you set up and return a payment to initiate the checkout process.
                     * Set up the payment here 
                     */
					 
					//call actions.payment.create() to set up the payment
					return actions.payment.create({
						payment: {
							transactions: [
								{
									// amount: { total: '1.00', currency: 'gbp' }
									amount: { total: total_amount, currency: 'gbp' }
								}
							]
						}
					});
					
                  },

                  onAuthorize: function(data, actions) { // Function called by checkout.js after the buyer logs in and authorizes the payment on paypal.com. 
                    /* 
                     * This is where you optionally show a confirmation page, and then execute the payment.
                     * Execute the payment here 
                     */
					 
					// Finalize the payment
				    return actions.payment.execute().then(function(payment) {
						// The payment is complete!
						// You can now show a confirmation message to the customer
						window.alert('Payment Complete!');
						
						// Redirect to final.php to send email
						// or create another page to insert the order INTO DATABASE
					});
					 
                  },

                  onCancel: function(data, actions) { // Function called by checkout.js if the buyer cancels the payment.
                    /* 
                     * By default, the buyer is returned to the original page, but you're free to use this function to take them to a different page.
                     * Buyer cancelled the payment 
                     */
                  },

                  onError: function(err) { // Function called by checkout.js when an error occurs. 
                    /* 
                     * You can allow the buyer to re-try or show an error message.
                     * An error occurred during the transaction 
                     */
                  }
                }, '#paypal-button');

            </script>
            
        </div>
        
</div>

