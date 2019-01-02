<?php
require (__DIR__ . '/../config.inc.php');

require(PDO_ADMIN);

$dbc = dbConn::getConnection();


require (MODELS. 'Order.php');
$rows = Order::view_orders($dbc);



// ======= HTML =============
$page_title = 'Administration';

include('./includes/header.php');
include ( "./views/orders_view.php" );
include ('./includes/footer.php'); 
?>