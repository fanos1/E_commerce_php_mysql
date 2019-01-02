<?php
/*
require_once ('./includes/DatabaseSession.class.php');
$session = new DatabaseSession($dbUser, $dbPassword, 'session', $dbName,'localhost');

session_set_save_handler(array($session, 'open'),
    array($session, 'close'),
    array($session, 'read'),
    array($session, 'write'),
    array($session, 'destroy'),
    array($session, 'gc')
);
*/

session_start();
//session_regenerate_id();

require (__DIR__ . '/../config.inc.php');
require ('../includes/form_functions.inc.php'); // Need the form functions script, which defines create_form_input():

require(PDO_ADMIN);


try {
    $dbc = dbConn::getConnection();
} catch (Exception $ex) {        
    //echo '<h3>'.$ex->getMessage().'</h3>'; //testing only    
    exit("<h3>An Error Occured, We apologise</h3>");
}

require (MODELS. 'Order.php');


//initialize some vars
$orderHeader = '';
$submitButton = '';
$errors = '';



//The original above stores the order_id into SESSION, which was passed from 'view_orders.php' file. 
//This is done so that when user SUBMITS the FORM, the order_id is still remembered. 
//I have moved the PAYMENT CAPTURE AND PROCESSING in a seperate 'capture_charge.php' file. 
//for this reason, no need to store the order_id INTO SESSION. 
//User will be re-directed to 'caputre.php' page to do the payment process.
$order_id = FALSE;

if (isset($_GET['oid']) )  // First access
{
    //if GET[oid] was passed from 'view_orders.php' page, validate it. filter_var() RETURNS FALSE if not valid
    if(!filter_var($_GET['oid'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) 
    {
        //if not valid, STOP
        echo "<h3>Error!</h3>   <p>This page has been accessed in error. </p>";	
	exit("<h3>str60, CIK</h3>");
    } 
    else 
    {
        //if valid, the filter_var() will Return the var GET[oid]
        $order_id = htmlentities($_GET['oid']);
        $order_id = (int) $order_id; //Escape befor query 
        //$_SESSION['order_id'] = $order_id;
    }               
} 

// Stop here if there's no $order_id:
if (!$order_id) {
	echo '<h3>Error!</h3><p>This page has been accessed in error.</p>';	
	exit();
}

$rows = Order::view_single_order($dbc, $order_id);

//var_dump($rows);
//exit();


include('./includes/header.php');
include ( "./views/single_order_view.php" );
include ('./includes/footer.php'); 
?>