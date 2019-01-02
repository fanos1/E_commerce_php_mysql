<?php
session_start();

require( __DIR__ . '/config.inc.php');


$page_title = 'Dobaln - Checkout - Your Shipping Information';

include(INCLUDES. 'header.php');

if(isset($_GET['fruit'])){ ?>
    <div class="container" style="margin-top: 4em;">
        <div class="row">
            <div class="col-md-12">
                <h2>Please note that we only deliver Fruit and veg to London areas!</h2>
                <p>Unfortunately, we cannot deliver perishable food to other cities at the moment as we are based in London.</p>
                <a class="lead" href="/cart.php" title="to cart">Please Remove this item from your cart!  &rarr;</a>
                
            </div>
        </div>
    </div>

<?php 
}

if(isset($_GET['msg'])){ ?>
    <div class="container" style="margin-top: 4em;">
        <div class="row">
            <div class="col-md-12">
                <p class="lead">The minimum order is £4!</p>
            </div>
        </div>
    </div>
<?php 
}

include(INCLUDES. 'footer-plain.php');
?>