<?php
require( __DIR__ . '/config.inc.php');

require './includes/lib/password.php';


// Check for, or create, a user session:
if (isset($_COOKIE['SESSION']) && (strlen($_COOKIE['SESSION']) === 32)) {
    $uid = $_COOKIE['SESSION'];
} else {
    $uid = openssl_random_pseudo_bytes(16); 
    $uid = bin2hex($uid); //convert these strange charcter to hex
}
setcookie('SESSION', $uid, time()+(60*60*24*1));// keep cookie 1 day



session_id($uid ); // Use the existing user ID:
session_start(); // Start the session:


//require(PDO);
$dbc = ConnectFrontEnd::getConnection();

$login_errors = array();


if($_SERVER['REQUEST_METHOD'] === 'POST' ) 
{
 
	$required = array('email', 'password');
		
	// $obj = new Validator($_POST);
    $obj = new Validator($required);	
	$obj->validLogInForm();	
	$login_errors  = $obj->getErrors(); 
   
	
    if (empty($login_errors)) { // OK to proceed!
            
			$e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			$p = $_POST['password'];
        
            try {                                   
                $q = 'SELECT COUNT(*) AS howmany_users, 
                    user_id, type, password, salt, email, address1, city, postcode, first_name, last_name, telephone 
                    FROM user 
                    WHERE email = :email';  
                
                $stmt = $dbc->prepare($q);
                $stmt->bindValue(':email', $e); 
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
              
                $hash = $row['password'];
				/*
				 * With salt option, legacy way:: We check if user with submited email and submited password exist in DB
					$saltedPass = $p.$row['salt'];                
					if( password_verify($saltedPass, $row['password']) ) {                
				*/
                
         
                if( password_verify($p, $hash) ) {
                    
                    // If the user is an administrator, create a new session ID to be safe:
                    // This code is created at the end of Chapter 4:
                    if ($row['type'] == 'admin') {
						session_regenerate_id(true);
						$_SESSION['user_admin'] = true;
                    }

                    // Store the data in a session:
                    $_SESSION['user_id'] = $row["user_id"];

                    //echo '<h2>'. $_SESSION['user_id'] .'</h2>';
                    //exit('cik');

                    // $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];

                    $_SESSION['address1'] = $row['address1'];
                    $_SESSION['city'] = $row['city'];



                    $_SESSION['postcode'] = $row['postcode'];
                    $_SESSION['phone'] = $row['telephone'];


                    $_SESSION['login_success'] = 1;

                    // Only indicate if the user's account is not expired:
                    //if ($row[3] == 1) $_SESSION['user_not_expired'] = true;
                   
                } else {
                    
                    $verificatFailed = TRUE;
                }
                
            } 
            catch (PDOException $e) 
            {        
                $file = $e->getFile();
                $line = $e->getLine();
                $message = $e->getMessage();
                $trace = $e->getTrace();
                $theErrorString = "$file :: $line :: $message :: $trace";
                               
                error_log($theErrorString, 0); //Log Error in server's PHP file   	
                echo 'An exception error occured, Sorry!';
            }
       

    } 

}


//just before starting HTML, create a new formtoken
$_SESSION['formtoken'] = md5(uniqid(rand(), true));
$formToken = htmlspecialchars($_SESSION['formtoken']);


//============ HTML ================
//============ HTML ================
include(INCLUDES. 'header.php');

if(isset($_SESSION['login_success'])  ) {
    //include successful message view
    include ( VIEWS . "login_success_view.php" ); 
    
} else { ?>    
    
    <div class="container" style="margin-top: 3em;">
        <div class="row">
            <div class="col-12">
                <h1>Please login to access your delivery address.</h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row" style="padding: 2em 0 2em 0;">
            <div class="col-12">

                <form class="form-inline" action="" method="post" accept-charset="utf-8">

                    <input type="hidden" name="formtoken" id="formtoken" 
                           value="<?php echo isset($_SESSION['formtoken']) ? $_SESSION['formtoken'] : ''; ?>" />

                    <p style="display: none;"> <input type="text" name="med" id="med" value=""> </p>

                    <div class="form-group">
                        <label class="sr-only" for="email">Email address</label>
                        <input type="text" name="email" class="form-control" id="email" required placeholder="Email">                
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">                
                     </div>

                    <button type="submit" class="btn btn-primary">Sign in &rarr;</button>

                </form>


                <div>
                    <hr>
                    <?php 
                        if(!empty($login_errors['email']) ) {                     
                            echo '<div class="alert alert-danger">'.$login_errors['email'].'</div>';
                        }  
                        if(!empty($login_errors['password']) ) {                     
                            echo '<div class="alert alert-danger">'.$login_errors['password'].'</div>';
                        } 

                        if(isset($verificatFailed)  ) 
                        {                     
                            echo '<h2 class="alert alert-danger"> Are you sure you typed the correct Password? </h2>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>


<?php    
} 

include(INCLUDES. 'footer.php');  

?>

