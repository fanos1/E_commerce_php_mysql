<?php

class User {
	
	protected $errors = array();
	private $dbc;
	
	public function __construct($dbc) {
		$this->dbc = $dbc;
    }
	
	 
	public function sendConfirmationEmail($confirmCode, $e) { 
		
		
		$query_string = 'code='. urlencode($confirmCode);
		
		$confirmationURL = '
		<a href="https://www.example.uk/signup.php?'. htmlentities($query_string) .'">		
			Last step is to confirm email
		</a>';                    
		
		$mail = new PHPMailer;
		
		$mail->isSMTP();                           
		$mail->Host = 'mail3.xxxx.co.uk';      			
		$mail->SMTPAuth = true;                  	// Enable SMTP authentication
		$mail->Username = $SMTP_Username;  
		$mail->Password = $SMTP_Password;  
		// $mail->SMTPSecure = 'tls';            	// Enable TLS encryption, `ssl` also accepted
		// $mail->SMTPSecure = 'ssl'; 				// secure transfer enabled REQUIRED for Gmail                   
		 $mail->Port = 587;                    		// TCP port to connect to                                         
		// $mail->Port = 465;
		
		$mail->From = "info@xxxx.uk";
		$mail->FromName = "xxxx  Ltd.";
		//$mail->setFrom('from@example.com', 'Mailer');

		//$mail->addAddress('irfankissa@yahoo.com', 'Joe User');                // Add a recipient
		$mail->addAddress($e);                                                  // Name is optional
		$mail->addReplyTo('info@xxx.uk', 'Information Reply To');
		
		$mail->isHTML(true);                                          

		$mail->Subject = 'Account Confirmation';
		$mail->Body    = '<html>
		<body>
			<h2>Thank you for registering!</h2>
			<div>The final step is to confirm
			your account by clicking on:</div>
			<div>'.$confirmationURL.'</div>
			<div>
			<b>Your Site Team</b>
			</div>
		</body>
		</html>';                
		$mail->AltBody = 'Please click this link to confirm your registration: '.$confirmationURL; 

		if($mail->send()) {							
			return TRUE;
		} else {					
			return FALSE;	
		}
		
	
	}
	
	/*
	 * Inserts a new record INOT signup TABLE. There are 2 tables; Signhu and User
	 * Real registration happens when user confirms his email. in that case the record 
	 * from SIGNUP table is transferred to USER table.
	 */
	public function signUp($fn, $ln, $e, $city, $p, $address1, $tel, $pc, $confirmCode) {
		
		// $q = "SELECT COUNT(*) AS num_row FROM user WHERE username=:username OR email =:email";   
		$q = "SELECT COUNT(*) AS num_row FROM user WHERE email =:email";   
		
		$stmt = $this->dbc->prepare($q);
		// $stmt->bindParam(':username', $u );
		$stmt->bindParam(':email', $e);
		$stmt->execute();            
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		
		
	   if ($rows['num_row'] == 0 ) { // if both email and user available, INSERT
            
			$hashedPassw = password_hash($p, PASSWORD_BCRYPT);     
			
			//=======================================
			//QUERY: INSERT the new registration INTO the signup table
			//======================================================
			// $sql = "INSERT INTO signup (username, password,  email, address1, city, postcode, first_name, last_name, telephone, confirm_code, time_created ) VALUES (:username, :password,  :email, :address1, :city, :postcode, :firstname, :lastname, :telephone, :confirm, :time )"; 
			$sql = "INSERT INTO signup ( password,  email, address1, city, postcode, first_name, last_name, telephone, confirm_code, time_created ) 
			VALUES ( :password,  :email, :address1, :city, :postcode, :firstname, :lastname, :telephone, :confirm, :time )"; 
			
			$time_created = time(); //$stmt->bindParam(':time', time()); //produced error:: Only variables should be passed by reference in C:\xampp\
					
			$stmt = $this->dbc->prepare($sql);
			// $stmt->bindParam(':username', $u);        
			$stmt->bindParam(':password', $hashedPassw);                     
			$stmt->bindParam(':email', $e);             
			$stmt->bindParam(':address1', $address1);            
			$stmt->bindParam(':city', $city);            
			$stmt->bindParam(':postcode', $pc); 
			$stmt->bindParam(':firstname', $fn);
			$stmt->bindParam(':lastname', $ln);    //POST['lastName']
			$stmt->bindParam(':telephone', $tel);             
			$stmt->bindParam(':confirm', $confirmCode);                        
			$stmt->bindParam(':time', $time_created);                      
			
			if( $stmt->execute() ) {				
				// return self::sendConfirmationEmail($confirmCode); 	// Static call of another method from same Class
				// return $this->sendConfirmationEmail($str); 			// or non-static									
				return TRUE;
			} else {
				return FALSE;
			}	
            
        } 
		else if ($rows['num_row'] == 1) { //either email or username is taken            
			//$errors['email-or-user'] = 'This username or email has already been registered. Please try another.';
			$this->errors['email-or-user'] = 'This username or email has already been registered. Please try another.';
            return FALSE;    
        } 
		else if ($rows['num_row'] == 2) { //Both email and username taken                			
			$this->errors['email'] = 'This email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.';
			return FALSE;
        }
		
		return FALSE; // DEFAULT
		
	}
	
	/*
	 * After user has signed up, the last step is for him/her to click the Link on the Email.
	 * When they click the email, we can now register them in the USER Table. 
	 * Initially, we inserted them in the SIGNUP. table
	*/
	public function registerUser($confirmCode) {
		
		//We select from the signup table all records that have a value in the confirm_code column         
        $sql = "SELECT * FROM signup WHERE confirm_code =:confirmCode";
        $stmt = $this->dbc->prepare($sql);
        $stmt->bindParam(':confirmCode', $confirmCode);
        $stmt->execute();
        $row = $stmt->fetchAll();        
        
        $howManyRows = count($row);//Count all elements in Array
		
        if($howManyRows == 1 ) //We should have only 1 record with that particular confirmation number
        { 	
			$q = "INSERT INTO user( password, salt, email, address1, city, postcode, first_name, last_name, telephone ) 
				VALUES ( :pass, :salt, :email, :address1, :city, :postcode, :firstname, :lastname, :telephone			
            )";
			
            $stmt = $this->dbc->prepare($q);
            
            // $stmt->bindParam(':username',$row[0]['username']);
            $stmt->bindParam(':pass',$row[0]['password']);          
            $stmt->bindParam(':salt',$row[0]['salt']);
            $stmt->bindParam(':email',$row[0]['email']);
            $stmt->bindParam(':address1',$row[0]['address1']);
            $stmt->bindParam(':city',$row[0]['city']);
            $stmt->bindParam(':postcode',$row[0]['postcode']);                                   
            $stmt->bindParam(':firstname',$row[0]['first_name']);
            $stmt->bindParam(':lastname',$row[0]['last_name']);            
            $stmt->bindParam(':telephone',$row[0]['telephone']);           
            $res = $stmt->execute();
            //$stmt->bindParam(':sign',$row[0][$sign_sig]);
            
			
			// $successSignup = true;
			
			
			//  DELETE record from SIGNUP TABLE. We transferred this record to USER table
			/* 
			$sql = "DELETE FROM signup WHERE signup_id = :id";
			$stmt2 = $this->dbc->prepare($sql);
			$stmt2->bindParam(':id', $row[0]['signup_id']);            
			$stmt2->execute();
			*/
			
					
			// if( $res ) { 
			//	echo "<h3>line 288, , items taken from SIGNUP table, and INSERTED into USER table</h3>";   
			//}
			
				
			/* 	
            if(!$stmt->execute() ) {
                echo "<h3> Error could not execute(), satir349, QUERY FAILED with message ". $error[2] . "</h3>";
            } else {
                
                $successSignup = true;
                
                // BETTER NOT TO DELETE  THESE, I WILL DELETE THEM PERIODICALLY FROM phpMyAdmin 
                $sql = "DELETE FROM signup WHERE signup_id = :id";
                $stmt2 = $this->dbc->prepare($sql);
                $stmt2->bindParam(':id', $row[0]['signup_id']);            
                
				if( $stmt2->execute() ) { 
					echo "<h3>line 288, CLASS signup, items taken from SIGNUP table, and INSERTED into USER table</h3>";   
				} 
				
            } 
			*/
			
			return TRUE; // success!
			
            
        } else {            
            exit('Confirmation doesnt exist in table');
        }
		
		return FALSE; //Default
	}
	
	
	public static function logOutUser() {
	   /*
		require_once '/includes/DatabaseSession.class.php';
		$session = new DatabaseSession('root', 'irfan_87Fanosis', 'session', 'mvc-ecommerce','localhost');
		//register our custom PHP session-handling methods
		session_set_save_handler(array($session, 'open'),
			array($session, 'close'),
			array($session, 'read'),
			array($session, 'write'),
			array($session, 'destroy'),
			array($session, 'gc')
		);
		*/
			
		//First, we must initiate session_start(). Check to see if it's already initiated.
		//Note that the most proper way of checking if SESSION is set is by using 
		//session_status() == PHP_SESSION_ACTIVE. However, this function is available only if PHP version > 5.4
		if (strlen(session_id()) < 1 && session_id() == '') {
			session_start();       
		} 
	   
		$_SESSION = array(); // Unset all session values
		   
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) 
		{
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		
		session_destroy(); // Destroy session
		
		//----- Custom deletion below, because isset(SESSION) continued to be TRUE afte above operatiosns
		unset($_SESSION);
		session_unset(); 
		session_write_close();
		//session_regenerate_id(true);               
	}
	
	/*
	 * Verify that password Fetched from Database is same as newly submitted password
	 * User can change password only if they know the Old password stored in Database 
	 */
	public function changePassword($oldPass, $newPass) {
		
		// First fetch the password from Database
		$q = "SELECT password FROM user WHERE user_id=:ID";
		$stmt = $this->dbc->prepare($q);
		$stmt->bindParam(':ID', $_SESSION['user_id']);
		$stmt->execute();   
		$r = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$hash = $r['password'];            

		if( password_verify($oldPass, $hash)) {
		
			$hashedPassw = password_hash($newPass, PASSWORD_BCRYPT);  
			
			//UPDATE the password in the user TAble with the newly submitted passwor
			$q2 = "UPDATE user SET password=:hashedPassw WHERE user_id = :ID  LIMIT 1";
			$stmt = $this->dbc->prepare($q2);
			$stmt->bindParam(':hashedPassw', $hashedPassw);
			$stmt->bindParam(':ID', $_SESSION['user_id']);
			
			if( $stmt->execute() ) { // If password changed ok
			
				// Send an email, if desired.

				// Should i Kill SESSION vars?? Larr has not done it
				// $this->logoutUser(); 
				self::logoutUser(); //
				
				return 	TRUE;				
			} 
			
			return FALSE;
			
		} else { // Invalid password.
			$this->errors['current'] = 'Your current password is incorrect!';
			return FALSE;
		}
            
		
	}
	
	public function forgotPassword($email, $token) {
		
		// Check for the existence of that email address...                
		$q = 'SELECT user_id FROM user WHERE email= :e';
		
		$smtp = $this->dbc->prepare($q);
		$smtp->bindParam(':e', $email);
		$smtp->execute();
		$r = $smtp->fetch(PDO::FETCH_ASSOC);
		
		// rowCount — Returns the number of rows affected by the last SQL statement
		$count = $smtp->rowCount(); 
	   
		if ($count === 1) {        
			// Retrieve the user ID:  				
			$uid = $r['user_id'];    			
		} else { 
			// No database match made.
			$this->errors['email'] = 'The submitted email address does not match those on file!';			
		}      
		
		// IF user with this Email is in our DATABASE, INSERT token
		if (empty($this->errors)) {
			
			// REPLACE works exactly like INSERT, except that if an old row in the table 
			// has the same value as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
			$q = "REPLACE INTO access_tokens(user_id, token, date_expires) 
					VALUES(:userId, :token, DATE_ADD(NOW(), INTERVAL 15 MINUTE ) ) 
				";
				
			$stmt = $this->dbc->prepare($q);
			$stmt->bindParam(':userId', $uid);
			$stmt->bindParam(':token', $token);
			$stmt->execute();
			
			$count = $stmt->rowCount();	
			
			if($count > 0) {
				return TRUE; // if token Inserted into DATABASE
			}
			
			// else default			
			return FALSE;
		
			/*  
			 * 2nd Option is to genereate temporary password 
			 * 
			try {			
				// Create a new, random password:
				$p = substr(md5(uniqid(rand(), true)), 10, 15);  
			
				//$salt = $r['salt']; 
				//$saltedPass = $p.$salt; // Create salted password. Add salt                 
				//$hashedPassw = password_hash($saltedPass, PASSWORD_BCRYPT);                   		
				$hashedPassw = password_hash($p, PASSWORD_BCRYPT);     
				
				$q = "UPDATE user 
					  SET password = :pass 
					  WHERE user_id = $uid
					";                
				$stmtp = $dbc->prepare($q);                
				$stmtp->bindParam(':pass', $hashedPassw);
				$stmtp->execute();
				
				if( $stmtp->rowCount() === 1 ) { //if 1 record means UPDATE Query updated the password for this user
				
					//SEN EMAIL
					$body = "Your password to log into <whatever site> has been temporarily changed to <strong> '$p' </strong>.
							Please log in using that password and this email address. Then you may change your password to something more familiar.
					";
					
					mail($_POST['email'], 'Your temporary password.', $body, 'From: info@alphina.uk');

					// Print a message and wrap up:
					echo '<h1>Your password has been changed.</h1>
						<p>You will receive the new, temporary password via email. Once you have logged in with this new password, 
						you may change it by clicking on the "Change Password" link.</p>
					';
					
					exit(); // Stop the script.
					
				} else { 
					trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.'); 
				}
				
			} catch (PDOException $ex) {
				echo 'An exception occured, we aplogise';
				exit('cik14fds2');
			} 
			*/
		
		} else {
			// exit("error");
			return false;
		}
	
	}
	
	
	public function getErrors() {
		return $this->errors;		
	}
	
}