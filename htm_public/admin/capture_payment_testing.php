<?php

/* 
 * We will not chapture stripe authorized from the admin panel.
 * we can capture these from Stripe's Admin Panel directly.
 * The API address of Stripe to capture : https://stripe.com/docs/api/php#create_charge
 */
exit();

try {

	// Include the Stripe library:
	require_once('../includes/Stripe.php');
	require( __DIR__ . '/../../API_keys.php');	

	// set your secret key: remember to change this to your live secret key in production
	// see your keys here https://manage.stripe.com/account
	Stripe::setApiKey($secretLive);
	$email = 'irfankissac@yahoo.com';
	
	$customer = Stripe_Customer::create(array(
		'description' => "Customer $email",
		'email' => $email,
		'card' => $token,
		'plan' => 'kip_basic'
	));


	// Charge the order:
	$charge = Stripe_Charge::retrieve('ch_102jyI2BAZoCjj35RP3QgGkT');
	$charge->capture();

	echo '<pre>' . print_r($charge, 1) . '</pre>';exit;

} 
catch (Stripe_CardError $e) { // Stripe declined the charge.
	$e_json = $e->getJsonBody();
	$err = $e_json['error'];
	$message = $err['message'];
} 
catch (Exception $e) { // Try block failed somewhere else.
	trigger_error(print_r($e, 1));
}
