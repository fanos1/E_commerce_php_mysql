<?php
session_start();

// This page is used to change an existing password. Users must be logged in to access this page.

require (__DIR__ . '/config.inc.php');

// If the user isn't logged in, redirect them:
redirect_invalid_user();


// require(PDO);
$dbc = ConnectFrontEnd::getConnection();


$pass_errors = array();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {	

	$required = array();
	
	$obj = new Validator($required);		
	$obj->validPasswResetForm();
	$pass_errors  = $obj->getErrors(); 
	
    if (empty($pass_errors)) { // If Validation OK.
        
		$oldPass = strip_tags($_POST['current']);
		$newPass = strip_tags($_POST['pass1']);
		
        try {			
            include('./includes/lib/password.php');
			
			$user =  new User($dbc);
			$ok = $user->changePassword($oldPass, $newPass);
			
			if($ok) {				
				// Let the user know the password has been changed:
				include(INCLUDES. 'header.php');
				echo '
					<div class="container" style="padding-top:2em;">
						<div class="row">
							<div  class="col-6"> 
								<h1>Your password has been changed.</h1> 
								<p><a href="/login.php" title="to login page"> Go To Login Page</a> </p>
							</div>
						</div>
					</div>                        
				';                                        
				
				include(INCLUDES. 'footer.php');
				exit();	
			}		
           
        } catch (Exception $ex) {
            echo "Exception occured";
            // echo $ex->getMessage();
			error_log($ex->getMessage(), 1, CONTACT_EMAIL); // Send erro to email
        }
        
    } 
} 

//just before starting HTML, create a new formtoken
$_SESSION['formtoken'] = md5(uniqid(rand(), true));
$formToken = htmlspecialchars($_SESSION['formtoken']);

// The file may already have been included by the header.
require_once('./includes/form_functions.inc.php');



// ============== HTML =====================
// ============== HTML =====================
include(INCLUDES. 'header.php');
include (VIEWS. 'change_password.php');
include(INCLUDES .'footer.php'); 
?>