<?php
session_start();

try {
	require(__DIR__ . '/config.inc.php');
	require(PDO);
	$dbc = ConnectFrontEnd::getConnection();

	$out = '';
	$errors = array();	
	
		
	if($_SERVER['REQUEST_METHOD'] === 'POST') {   
		
		//check if the form submited is our own form
		if( strip_tags($_POST['formtoken']) !== $_SESSION['formtoken'])  {		       			
			$errors['formtoken'] = 'the form submitted is not valid';
		} 

		// honeypot field is hidden, and user will not be able to input value, only bots will populate that field    
		$honeypot = trim(strip_tags($_POST['med']) );   
		if(!empty($honeypot)) {		
			$errors['honeypost'] = 'the form submitted honeypost is not valid';
		}
		
		if (preg_match('/^(GIR 0AA)|(TDCU 1ZZ)|(ASCN 1ZZ)|(BIQQ 1ZZ)|(BBND 1ZZ)|(FIQQ 1ZZ)|(PCRN 1ZZ)|(STHL 1ZZ)|(SIQQ 1ZZ)|(TKCA 1ZZ)|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]|[A-HK-Y][0-9]([0-9]|[ABEHMNPRV-Y]))|[0-9][A-HJKS-UW])\s?[0-9][ABD-HJLNP-UW-Z]{2}$/i', $_POST['postcode'])) 
		{							
			// submited POST CODE is syntactically valid. But, we also want to limit acceptable postcodes to London only  
			include(INCLUDES. 'delivery_postcodes.php');  

			
			$postCod = str_replace(' ', '', $_POST['postcode']); // Remove - strip spaces from postcodes, i.e 'e5 0ha' should become 'e50ha'                       
			$postCod = filter_var($postCod, FILTER_SANITIZE_STRING);		
			$txCod = strtolower($postCod); //Because we have the postcodes in our Array stored in lowercase, We must first convert the submited postcode to lowercase
			
			if(!in_array($txCod, $freePostCod) ) { //if submitted not in our array, return error        
				$out .= '<div class="alert alert-danger" role="alert" style="margin-top:3em;"> 
					Unfortunately, We currently do not deliver within your area! 
				</div>';
			} else {
				$out .= 
				'<div class="alert alert-success" role="alert" style="margin-top:3em;"> 
					Great! We deliver within your area for FREE! 
				</div>';
			}
			
			$pc = filter_var($_POST['postcode'], FILTER_SANITIZE_STRING);       
		
		} else {
			$errors['postcode'] = '<div class="alert alert-danger">Please enter valid postcode! </div>';
		} 
		
	}

}
catch (ErrorException $ex) {
	// Log In Error?
	echo 'Unable to load/include some files';
}	
catch (PDOException $e) {
	$err_title = 'An error has occurred';	
	$pdo_err_output = 'Database error: ' . $e->getMessage() . ' in ' .$e->getFile() . ':' . $e->getLine();		
	error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email
	exit('An Error occured(1), we apologise1');
} 



$_SESSION['formtoken'] = md5(uniqid(rand(), true));
$formToken = htmlspecialchars($_SESSION['formtoken']);



// ============= HTML =================
// ============= HTML =================
// To prevent FATAL errors, check before including
// if (!file_exists(INCLUDES. 'header.php') && !file_exists(INCLUDES. 'footer.php') )  :: custom_error_handler() does it for us

include(INCLUDES. 'header.php');
include(VIEWS. 'free_delivery_view.php');
include(INCLUDES. 'footer.php');  

?>
