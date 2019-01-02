<?php
require (__DIR__ . '/../config.inc.php');
require(PDO_ADMIN);

$dbc = dbConn::getConnection();


require (MODELS. 'Customer.php');
$rows = Customer::getAllCustomers($dbc);



// ======= HTML =============
$page_title = 'Administration';

include('./includes/header.php');
include ( "./views/customer_view.php" );
include ('./includes/footer.php'); 
?>