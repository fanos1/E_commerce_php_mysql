<?php

// Are we live?
if (!defined('LIVE')) DEFINE('LIVE', FALSE);


// Errors are emailed here:
DEFINE('CONTACT_EMAIL', 'exampletd@gmail.com');
DEFINE ('ALPHINA_EMAIL', 'info@domain.uk');

define ('BASE_URI', __DIR__ .'../../'); 

define ('BASE_URL', 'www.example.uk/');
define ('PDFS_DIR', BASE_URI . 'pdfs/'); 
define ('PDO', BASE_URI . 'ConnectFrontEnd.php');
define ('PDO_ADMIN', BASE_URI . 'db-connection-admin.php');

define ('ROOT', $_SERVER['DOCUMENT_ROOT']); 
define ('VIEWS', ROOT.'/views/');
define ('MODELS', ROOT.'/models/');
define ('INCLUDES', ROOT.'/includes/');

define ('IMAGE_DIR', ROOT.'/img/');
define ('ROOT_HTTP', 'http://www.xxxx.uk/');
define ('ROOT_HTTPS', 'https://www.xxxx.uk/');



/* 
* An autoloader is a function that takes a class name as an argument and then
* includes the file that contains the corresponding class
*/
function autoloader($className) {
	$file = __DIR__ 	. '/../models/' . $className . '.php';	
	// $file = __DIR__ 	. '/classes/' . $className . '.php';	
	include $file;		
}
spl_autoload_register('autoloader'); // This function tells PHP to automatically load a Class if it is needed, i.e. new Product() will load the class Product.php



// Function for handling errors.
// Takes five arguments: error number, error message (string), name of the file where the error occurred (string) 
// line number where the error occurred, and the variables that existed at the time (array).
// Returns true.
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {
    
	$message = "An error occurred in script '$e_file' on line $e_line:\n$e_message\n";
	
	// Add the backtrace:
	$message .= "<pre>" .print_r(debug_backtrace(), 1) . "</pre>\n";
	
	// Or just append $e_vars to the message:
	//	$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n";

	if (!LIVE) { // Show the error in the browser.            
            echo '<div class="alert alert-danger">' . nl2br($message) . '</div>';
	} else { 
		// Send the error in an email:
		error_log ($message, 1, CONTACT_EMAIL, 'From:sales@exampl.co.uk');
					
		// echo $message; //DEBUGGIN ONLY

		// Only print an error message in the browser, if the error isn't a notice:
		if ($e_number != E_NOTICE) {						
			echo $message; // TESTING ONLY BELOW, DON'T DISPLAY
			echo 'An error occured, please try again later...';
		} 
	}	
	
	return true; // So that PHP doesn't try to handle the error, too.	
} 
// Use my error handler:
set_error_handler('my_error_handler');




/* 
* PDO Uncaught Exception Handler:: Note avoid passing the type @param if you want your app to work with PHP7
* http://php.net/manual/en/migration70.incompatible.php
*/
function handleMissedException($e) {
	echo "An exception error occured, we apologize! Please contact us to report this error. <br/>";                   
	// error_log('Unhandle Error ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
	// error_log("Unhandle Error", 1, "dobalnltd@gmai.com"); // Send email instead loggin to SERVERs 
	echo 'Unhandle Error ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine();
}
set_exception_handler('handleMissedException');



// This function redirects invalid users.
// It takes two arguments: 
// - The session element to check
// - The destination to where the user will be redirected. 
function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'https://') {	
    // Check for the session item:
    if (!isset($_SESSION[$check]) ) {//i.e. $_SESSION['user_admin'] | $_SESSION['user_id']
        $url = $protocol . BASE_URL . $destination; // Define the URL.
        header("Location: $url");
        exit(); 
    }	
} 


//function to format date fetched from DB
function formatDate($param) {            
    // $param == '2013-07-29 14:35:09'
    // split string from space to get date and time seperate    
    $pieces = explode(' ', $param);    
    return $pieces;
}

// Omit the closing PHP tag to avoid 'headers already sent' errors!

