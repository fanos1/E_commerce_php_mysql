<?php
session_start();

require( __DIR__ . '/config.inc.php');

require './includes/lib/password.php';

//require './classes/PHPMailerAutoload.php';
require './classes/class.phpmailer.php';
require './classes/class.smtp.php';
require './classes/class.pop3.php';


//require(PDO);
$dbc = ConnectFrontEnd::getConnection();

$reg_errors = array();
$errors = array();

if($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    
	$required = array('first_name', 'last_name');
		    
	
	$obj = new Validator($required);	
	// $obj->validate();
	$obj->validSignUpForm();
	$reg_errors  = $obj->getErrors(); // Fetch Validation Errors if any
	
    
    //==================
    // no validation errors
    //======================
    if (empty($reg_errors)) {
		
		// All POST inputs were valideted in the Validator Class,
		$fn = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);	
		$ln = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING) ;		
		$e = filter_var( $_POST['email'], FILTER_SANITIZE_STRING);
		$city = filter_var( $_POST['city'], FILTER_SANITIZE_STRING);
		$p = $_POST['password'];		
		$address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
		$tel = filter_var($_POST['telephone'], FILTER_SANITIZE_STRING);
		$pc = filter_var($_POST['postcode'], FILTER_SANITIZE_STRING);	
        
        try {

			srand( (double)microtime() * 1000000 );  //Seed the random number generator  	                
			$confirmCode = md5( time() . rand(1, 1000000));
			
			$userObj = new User($dbc); // Autoload require User() class
			$result = $userObj->signUp($fn, $ln, $e, $city, $p, $address1, $tel, $pc, $confirmCode);			
			
			// =================================================
            // 	NOW THAT signup TABLE WAS POPULATED, SEND MESSAGE
            // ==================================================
            if($result) { 
			
				$isEemailSent = $userObj->sendConfirmationEmail($confirmCode, $e); 			
				
				if($isEemailSent) {
					$sentSuccess = '<strong>We have sent you an email!</strong> Please check your email to complete your registration';
					
					$_SESSION['first_name'] = "";
					unset($_SESSION['last_name']);                        
					unset($_SESSION['username']);                        
					unset($_SESSION['address1']);
					unset($_SESSION['email']);
					unset($_SESSION['city']);
					unset($_SESSION['postcode']);
					unset($_SESSION['telephone']);	
			
				} else {
					$errors = $userObj->getErrors();
				}
				
			} else { // SignUp-TABLE was not populated
				$errors = $userObj->getErrors();
			}
			
            
        } catch (PDOException $ex) {
             echo '<h3>'. $ex->getMessage() .'</h3>';
             echo '<h3>'. $ex->getLine() .'</h3>';
            exit('An exception error occured! We apologise! str273');
        }
        
    }
    
    
    
} //END POST


/*  Check whether the page is being requested as part of a confirmation. 
 * — we’ll check for the presence of the $_GET['code'] variable
 * If the confirmation code is present, we call the SignUp->confirm(), supplying the code the page received. 
 */
if (isset($_GET['code']) && strlen($_GET['code']) === 32 ) 
{	
    try {
        $confirmCode = filter_var($_GET['code'], FILTER_SANITIZE_STRING);
        
		$user = new User($dbc);
		$ok = $user->registerUser($confirmCode);
        if($ok) { 
            $successSignup = true; 
        } else {
            exit('not ok');
        }
        
    }  
    catch (SignUpException $e) {             
        var_dump($e->getMessage()); //FOR DEBUGGING
        //for user display
        echo '<h3>'. $reg_messages['confirm_error'] . '</h3>'; 
    }
	catch (PDOException $e) {             
        // var_dump($e->getMessage()); //FOR DEBUGGING        
		echo '<h3>'. $e->getMessage() .'</h3>';
        exit('An exception has occured. We apologise!');
    }

}


$_SESSION['formtoken'] = md5(uniqid(rand(), true));
$formToken = htmlspecialchars($_SESSION['formtoken']);



// ================= HTML ===============
// ================= HTML ===============
include(INCLUDES. 'header.php');

if(isset($successSignup)) {    
    include ( VIEWS . "signup_success_view.php" ); 
} else {
    include ( VIEWS . "signup_view.php" );    
}

include(INCLUDES. 'footer.php');  ?>
	
