<?php
require (__DIR__ . '/../config.inc.php');

//redirect_invalid_user('user_admin');

require(PDO_ADMIN);

$dbc = dbConn::getConnection();
   
//require (MODELS. 'Order.php');
//$rows = Order::view_orders($dbc);



// ======= HTML =============
$page_title = 'Administration';

include('./includes/header.php');
//include ( "./views/home_view.php" );
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1>This is the adimin panel</h1>
            <p>Here you can upload and change stuff</p>
        </div>
    </div>
</div>

<?php include ('./includes/footer.php');  ?>