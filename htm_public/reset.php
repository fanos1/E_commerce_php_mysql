<?php
session_start();
// This page is used to change an existing password when a user resets it
// User reqests this page when he/she clicks the URL link in his Email

require( __DIR__ . '/config.inc.php');
include('./includes/lib/password.php');

$dbc = ConnectFrontEnd::getConnection();


$reset_error = '';
$html = '';
$pass_errors = array(); // For storing password errors:

if($_SERVER['REQUEST_METHOD'] === 'GET') {
	
	if( isset($_GET['t']) && (strlen($_GET['t']) === 64) )  
	{
		$token = strip_tags($_GET['t']); 
		try {		 
			$q = "SELECT user_id FROM access_tokens WHERE token = :token AND date_expires > NOW() ";
			$stmt = $dbc->prepare($q);
			$stmt->bindParam(':token', $token);        
			$stmt->execute();
			$r = $stmt->fetch(PDO::FETCH_ASSOC);

			$count = $stmt->rowCount();
			
			if($count === 1) {            
				//Create a new session ID
				session_regenerate_id(true);
				$_SESSION['user_id'] = $r['user_id']; // Log-In user

				$q = "DELETE FROM access_tokens WHERE token=:token";
				$stmt = $dbc->prepare($q);                
				$stmt->bindParam(':token', $token);
				$stmt->execute();

			} else {
				$reset_error = 'Either the provided token does not match that on file or your time has expired. Please resubmit the "Forgot your password?" form.';    
			}
			
		} catch (Exception $ex) { 
			$err_title = 'An error has occurred';	
			$pdo_err_output = 'Database error: ' . $ex->getMessage() . ' in ' .$ex->getFile() . ':' . $ex->getLine();			
			error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email			
		}

	} else { // No token!
		$reset_error = 'This page has been accessed in error. Not token';
	}
	
}



//======
// POST 
//=======
if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_SESSION['user_id'])) 
{
	// Check for a password and match against the confirmed password:
	if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']) ) {
            
		if ($_POST['pass1'] == $_POST['pass2']) {
			$p = $_POST['pass1'];
		} else {
			$pass_errors['pass2'] = 'Your password did not match the confirmed password!';
		}
            
	} else {
		$pass_errors['pass1'] = 'Please enter a valid password!';
	}
	
	// If everything's OK.
	if(empty($pass_errors)) 
	{
		try {
			$pass = password_hash($p, PASSWORD_BCRYPT);
		
			$q = "UPDATE user SET password=:pass WHERE user_id=:id LIMIT 1";
			$stmt = $dbc->prepare($q);
			$stmt->bindParam(':pass', $pass);
			$stmt->bindParam(':id', $_SESSION['user_id']);
			$stmt->execute();

			if($stmt->rowCount() === 1) {				
				//Send Email if desired
				$html .= "<h2>Your password has been changed</h2>";                    				
			} else {
				trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.'); 
			}
			
		} catch (PDOException $e) {
			$err_title = 'An error has occurred';	
			$pdo_err_output = 'Database error: ' . $e->getMessage() . ' in ' .$e->getFile() . ':' . $e->getLine();		
			error_log($pdo_err_output, 1, CONTACT_EMAIL); // Send erro to email, CONTACT_EMAIL defined in config.php
			exit('An Error occured(1), we apologise1');
		}

	}
	
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    // Stop if only POST but SESSION login not true. GET request above sets SESSON[login]
	exit('An error occured, we apologise! temporary login is required!'); 
} 


// ========= HTML ========
include (INCLUDES. 'header.php');
include (VIEWS. 'reset_view.php');
include (INCLUDES. 'footer.php'); 
?>