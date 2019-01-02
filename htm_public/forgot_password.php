<?php
/*
 * New password will be generated and emailed to user.
 * user will have 15 minutes to use that newly generated password to create his own password
 */
require( __DIR__ . '/config.inc.php');
require( __DIR__ . '/../API_keys.php');
include('./includes/lib/password.php');

//require './classes/PHPMailerAutoload.php';
require './classes/class.phpmailer.php';
require './classes/class.smtp.php';
require './classes/class.pop3.php';


require(PDO);
$dbc = ConnectFrontEnd::getConnection();

$pass_errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Validate the email address:
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
	{
		$email = $_POST['email'];

		try {				
			$token = openssl_random_pseudo_bytes(32);
			$token = bin2hex($token);
			
			$user =  new User($dbc);
			$ok = $user->forgotPassword($email, $token); 
			
			if($ok) { // if token was successfully Inserted iNTO Database, send Email
				
				$url = 'https://' . BASE_URL . 'reset.php?t=' . $token;
			
				$body = "This email is in response to a forgotten password reset request. 
				If you did make this request, click the following link to be able to access your account:
				<p>For security purposes, you have 15 minutes to do this. If you do not click this link within 15 minutes, 
				you'll need to request a password reset again.</p>
				<div> If you have _not_ forgotten your password, you can safely ignore this message and 
				you will still be able to login with your existing password. </div>
				<h3>Copya and paste following Link to your browser</h3>";
				
				$mail = new PHPMailer;		
				$mail->isSMTP();                            // Set mailer to use SMTP		
				$mail->Host = $SMTP_ServerHost;  			// Specify main and backup SMTP servers 				
				$mail->SMTPAuth = true;                  	// Enable SMTP authentication
				$mail->Username = $SMTP_Username;     		// SMTP username
				$mail->Password = $SMTP_Password;  			// SMTP password						
				$mail->Port = 587;                    		// TCP port to connect to                                         
				// $mail->Port = 465;
				
				$mail->From = ALPHINA_EMAIL;
				$mail->FromName = $SMTP_mailFromName;
				//$mail->setFrom('from@example.com', 'Mailer');		
				$mail->addAddress($email);
				$mail->addReplyTo(ALPHINA_EMAIL, 'Information Reply To');				
				$mail->isHTML(true); // Send HTML or Plain Text email
				
				$mail->Subject = 'Account Confirmation';
				$mail->Body    = '<html>
				<body>
					<h2>Final step to reset your password!</h2>
					<div>'.$body. '</div>
					<h4>'.$url.'</h4>
				</body>
				</html>';                
				$mail->AltBody = 'Please click this link to confirm your registration: '.$body; 

				if($mail->send()) {				
					$emailSent =  '					
						<h1>Final step to reset password!</h1>
						<p>You will receive an access code via email. <strong> copy and paste the link in that email </strong> to gain access to the site. 
						Once you have done that, you may then change your password.</p>
					';					
				} 
				
			} else {
				trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.'); 				
			}
			
		} catch (PDOException $exc) {
			//echo $exc->getTraceAsString();
			$err_title = 'An error has occurred';	
			$pdo_err_output = 'Database error: ' . $exc->getMessage() . ' in ' .$exc->getFile() . ':' . $exc->getLine();		
			error_log($pdo_err_output, 1, CONTACT_EMAIL); // Send erro to email, CONTACT_EMAIL defined in config.php
			exit('An Error occured! we apologise1');
			
		}			
			
	} 
	else { // No valid address submitted.
		$pass_errors['email'] = 'Please enter a valid email address!';
	}

} 


//=============== HTML ===================
//=============== HTML ===================
include(INCLUDES. 'header.php');
include(VIEWS. 'forgot_password_view.php');
include(INCLUDES. 'footer.php');   
?>
	
