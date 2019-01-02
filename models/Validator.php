<?php

class Validator{
	
	protected $errors = array();
	protected $required = array(); 


    public function __construct($required_fields ) {
		
		$this->required = $required_fields;		
		// All forms should pass this
		
		/*
		 * Check if the form submited is our own form		 
		 * We continue validation only if the form submitted here is the form which we sent to the user
		 */		
		if( trim(strip_tags($_POST['formtoken'])) !==   $_SESSION['formtoken'] )  {
			$this->errors['formtoken'] = "The form submited is not valid. Please try again or contact support for additional assistance.";        
			exit('Form submited is not valid');
		} 

		//honeypot field is hidden, and user will not be able to input value, only bots will populate that field    
		$honeypot = strip_tags($_POST['med']);   
		if ( !empty($honeypot) )  {
			$this->errors['hp'] = "The form submited is not valid. Please try again or contact support for additional assistance.";
			exit('Bot submission');
		}	
		
    }

	public function validBillingForm() {
		
		$postKeys = array_keys($_POST);	
		
		//----------
		// Billing Form 
		// --------------
		
		// cc first name: Billing.php						
		if (!preg_match('/^[A-Z \'.-]{2,20}$/i', $_POST['cc_first_name'])) {			
			$this->errors['cc_first_name'] = 'Please enter your first name!';				
		} 

		// Check for a Billing street address:		
		if (!preg_match('/^[A-Z0-9 \',.#-]{2,160}$/i', $_POST['cc_address'])) {				
			$this->errors['cc_address'] = 'Please enter your street address!';
		} 
		
		
		// Check for a city:		
		if (!preg_match('/^[A-Z \'.-]{2,60}$/i', $_POST['cc_city'])) {				
			$this->errors['cc_city'] = 'Please enter your city!';
		} 
		
		
		// Check for Billing zip code:			
		if(!preg_match('/^(?:[A-Za-z]\d ?\d[A-Za-z]{2})|(?:[A-Za-z][A-Za-z\d]\d ?\d[A-Za-z]{2})|(?:[A-Za-z]{2}\d{2} ?\d[A-Za-z]{2})|(?:[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]{2})|(?:[A-Za-z]{2}\d[A-Za-z] ?\d[A-Za-z]{2})$/', $_POST['cc_zip'])) {
			$this->errors['cc_zip'] = 'Please enter your zip code!';
		} 	
		
	}
	
	public function validPasswResetForm() {
		
		$postKeys = array_keys($_POST);	
		
		// Check for the existing password:
		if (empty($_POST['current'])) {
			$this->errors['current'] = 'Please enter your current password!';
		}
		
		
		// Check for a password and match against the confirmed password:
		if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1'])) {
			
			if ($_POST['pass1'] !== $_POST['pass2']) {				
				$this->errors['pass2'] = 'Your password did not match the confirmed password!';
			} 
			
		} else {
			$this->errors['pass1'] = 'Please enter a valid password!';
		}
		
	}
	
	
	public function validLogInForm() {
		
		$postKeys = array_keys($_POST);	
		
		// Check for an email address:			
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === $_POST['email']) {
			$this->errors['email'] = 'Please enter a valid email address!';
		} else {
			$_SESSION['email'] = $_POST['email'];	
		}
		
		// password 		
		if (!preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['password']) ) {
			$this->errors['password'] = 'passwords must have at least one Capital Letter and one number,!';
		}	
		
	}
	
    
	public function validSignUpForm() {
		
		if(!preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['first_name'])) {		        
			$this->errors['first_name'] = 'Please enter valid first name!';
		} else {
			$_SESSION['first_name'] = $_POST['first_name'];
		}

		if(!preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['last_name'])) {
			$this->errors['last_name'] = 'Please enter valid last name!';				
		} else {
			$_SESSION['last_name'] = $_POST['last_name'];
		}

		// Check for an email address:
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === $_POST['email']) {
			// $e = escape_data($_POST['email'], $dbc);
			$_SESSION['email'] = $_POST['email'];	
		} else {			
			$this->errors['email'] = 'Please enter a valid email address!';
		}

		

		// Check for a password and match against the confirmed password:
		if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,22}$/', $_POST['password']) ) {
			
			if ($_POST['password'] === $_POST['confirm_password']) {
				// $p = $_POST['password'];
				
			} else {
				$this->errors['confirm_password'] = 'Your password did not match the confirmed password!';
			}                
			
		} else {
			$this->errors['password'] = 'passwords must have at least one Capital Letter and one number,!';
		}

		// address
		if (!preg_match('/^[A-Z0-9 \',.#-]{1,100}$/i', $_POST['address1'])) {
			$this->errors['address1'] = 'Please enter your addres!';				
		} else {
			$_SESSION['address1'] = $_POST['address1'];
		}
		
		// city
		if (!preg_match('/^[a-z]{2,16}$/i', $_POST['city'])) {
			$this->errors['city'] = 'Please enter your city!';
		} else {
			$_SESSION['city'] = $_POST['city'];
		}
		
		//postcode
		if (!preg_match('/^(GIR 0AA)|(TDCU 1ZZ)|(ASCN 1ZZ)|(BIQQ 1ZZ)|(BBND 1ZZ)|(FIQQ 1ZZ)|(PCRN 1ZZ)|(STHL 1ZZ)|(SIQQ 1ZZ)|(TKCA 1ZZ)|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]|[A-HK-Y][0-9]([0-9]|[ABEHMNPRV-Y]))|[0-9][A-HJKS-UW])\s?[0-9][ABD-HJLNP-UW-Z]{2}$/i', $_POST['postcode'])) {		
			
			$this->errors['postcode'] = 'Please enter postcode!';
		} else {
			$_SESSION['postcode'] = $_POST['postcode'];
		}	

		// telephone
		if(!preg_match('/^[0-9]{6,16}$/i', $_POST['telephone'])) {								
			$this->errors['telephone'] = 'Please enter your telephone!';
		} else {				
			$_SESSION['telephone'] = $_POST['telephone'];
		}


	}
	
	public function validate() {		
		/*
		 * Check if the form submited is our own form		 
		 * We continue validation only if the form submitted here is the form which we sent to the user
		 */		
		if( trim(strip_tags($_POST['formtoken'])) !==   $_SESSION['formtoken'] )  {
			$this->errors['formtoken'] = "The form submited is not valid. Please try again or contact support for additional assistance.";        
			exit('Form submited is not valid');
		} 

		//honeypot field is hidden, and user will not be able to input value, only bots will populate that field    
		$honeypot = strip_tags($_POST['med']);   
		if ( !empty($honeypot) )  {
			$this->errors['hp'] = "The form submited is not valid. Please try again or contact support for additional assistance.";
			exit('Bot submission');
		}	
		
		
		
		//array_keys() returns the keys, numeric and string, from the array. ::: Returns an array of all the keys in array.
		
		$postKeys = array_keys($_POST);		
		/*		
			var_dump($postKeys);		
			array(12) { 
				[0]=> string(9) "formtoken" 
				[1]=> string(3) "med" 
				[2]=> string(10) "first_name" 
				[3]=> string(9) "last_name" 
				[4]=> string(8) "password" 
				[5]=> string(16) "confirm_password" 
				[6]=> string(5) "email" 
				[7]=> string(8) "address1" 
				[8]=> string(4) "city" 
				[9]=> string(8) "postcode" 
				[10]=> string(9) "telephone" 
				[11]=> string(6) "submit" 
			}
			$postKeys = array("first_name", "telephone", "last_name" );
		*/
	
		
		
		/* We pass [last_name] as Param to this Class. In /login.php page, we pass only [email]. In /signup.php page we pass more
		 * 
		 * if script arrives here from /login.php, [last_name] will not be in the $this->required Array, it was not passed here. Script continues				
		 * if script arrives here from /signup.php, [last_name] will be in the $this->required Array. We pass it from /signup.php
		 * 
		 * When script arrives here from /Signup.php page, and POST submitted should also have [last_name] <input>, if not it could be someone elses form			
		*/
		foreach($this->required as $k => $v) {
			
			if( !in_array($v, $postKeys))  { // if( ! [first_name] in POST[first_name] )
				exit('validator error, cikv');
			} 			
		}
		
			
		// validate only if ISset    	
    	if(isset( $_POST['first_name']) )
    	{
		    if(!preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['first_name'])) {		        
				$this->errors['first_name'] = 'Please enter valid first name!';
		    } else {
				$_SESSION['first_name'] = $_POST['first_name'];
			}
    	}
		
		
		// Last Name :: 
		if(isset($_POST['last_name']) ) 
		{
			if(!preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['last_name'])) {
				$this->errors['last_name'] = 'Please enter valid last name!';				
			} else {
				$_SESSION['last_name'] = $_POST['last_name'];
			}
		}
		
		// Check for an email address:		
		if(isset($_POST['email']) ) 
		{	
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === $_POST['email']) {
				$this->errors['email'] = 'Please enter a valid email address!';
			} else {
				$_SESSION['email'] = $_POST['email'];	
			}
		}
		
		//City
		if(isset($_POST['city']) ) 
		{
			if (!preg_match('/^[a-z]{2,16}$/i', $_POST['city'])) {
				$this->errors['city'] = 'Please enter your city!';
			} else {
				$_SESSION['city'] = $_POST['city'];
			}
		}
		
		
		
		// password 
		if( isset($_POST['password']) ) 
		{
			if (!preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,22}$/', $_POST['password']) ) {
				$this->errors['password'] = 'passwords must have at least one Capital Letter and one number,!';
			}
		}	
		
		// Password - confirm. If [confirm_password] Is set, this request is from SIgnup.php Form
		if(isset($_POST['confirm_password']) )  
		{
			// Check for a password and match against the confirmed password:
			if ($_POST['password'] === $_POST['confirm_password']) {
				// $p = $_POST['password'];				
			} else {
				$this->errors['confirm_password'] = 'Your password did not match the confirmed password!';
			} 
		}		

		
		// ADDRESS
		if(isset($_POST['address1']) )
		{			
			if (!preg_match('/^[A-Z0-9 \',.#-]{1,100}$/i', $_POST['address1'])) {
				$this->errors['address1'] = 'Please enter your addres!';				
			} else {
				$_SESSION['address1'] = $_POST['address1'];
			}
		}			
		
		// TELEPHONE
		if(isset($_POST['telephone']) ) 
		{			
			if(!preg_match('/^[0-9]{6,16}$/i', $_POST['telephone'])) {								
				$this->errors['telephone'] = 'Please enter your telephone!';
			} else {				
				$_SESSION['telephone'] = $_POST['telephone'];
			}
		}
			
		
		//POSTCODE
		if(isset($_POST['postcode']) ) 
		{
			if (!preg_match('/^(GIR 0AA)|(TDCU 1ZZ)|(ASCN 1ZZ)|(BIQQ 1ZZ)|(BBND 1ZZ)|(FIQQ 1ZZ)|(PCRN 1ZZ)|(STHL 1ZZ)|(SIQQ 1ZZ)|(TKCA 1ZZ)|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]|[A-HK-Y][0-9]([0-9]|[ABEHMNPRV-Y]))|[0-9][A-HJKS-UW])\s?[0-9][ABD-HJLNP-UW-Z]{2}$/i', $_POST['postcode'])) {		
				
				$this->errors['postcode'] = 'Please enter postcode!';
			} else {
				$_SESSION['postcode'] = $_POST['postcode'];
			}			
		}
		
		

		
    }

    public function getErrors() {
		return $this->errors;
    }

}