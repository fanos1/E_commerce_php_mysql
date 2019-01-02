<?php
session_start();

require (__DIR__ . '/config.inc.php');	

// Users must be logged in to access this page. If the user isn't logged in, redirect them:
redirect_invalid_user();


function logoutUser() 
{
    /*
     *      
     ********************************************      
     * http://php.net/manual/en/function.session-destroy.php 
     * **************************************************************
        session_cache_expire — Return current cache expire
        session_cache_limiter — Get and/or set the current cache limiter          
    */
    
            
    // First, we must initiate session_start(). Check to see if it's already initiated.
    // Note that the most proper way of checking if SESSION is set is by using 
    // session_status() == PHP_SESSION_ACTIVE. However, this function is available only if PHP version > 5.4	
    if (strlen(session_id()) < 1 && session_id() == '') {
        session_start();       
    }  
   
    $_SESSION = array(); // Unset all session values
    
	 
    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
	/*
    if (ini_get("session.use_cookies")) 
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);		
    }
	*/
	
	setcookie (session_name(), '', time()-300); // Destroy the cookie.
	
	
	// Destroy the session:
	/*
	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie (session_name(), '', time()-300); // Destroy the cookie.
	*/
    
    session_destroy(); // Destroy session
    
    //----- Custom deletion below, because isset(SESSION) continued to be TRUE afte above operatiosns
    unset($_SESSION);
    session_unset(); 
    session_write_close();
   // session_regenerate_id(true);  
	
}


logoutUser(); // Call 



// Include the header file:
$page_title = 'Logout';
include ('./includes/header.php');

echo '
	<div class="container">
		<div class="col-12">
			<h3 class="alert alert-success">Logged Out</h3>
			<p>Thank you for visiting. You are now logged out. Please come back soon!</p>
		</div>
	</div>
	';


// Include the HTML footer:
include ('./includes/footer.php');
?>