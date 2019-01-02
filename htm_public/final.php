<?php
session_start();

require( __DIR__ . '/config.inc.php');

// The session ID is the user's cart ID:
$uid = session_id();

// Check that this is valid:
if (!isset($_SESSION['customer_id'])) { // Redirect the user.
    $location = 'https://' . BASE_URL . 'checkout.php';
    header("Location: $location");
    exit();
} elseif (!isset($_SESSION['response_code']) || ($_SESSION['response_code'] != 1)) {
    // SESSION[response_code] was set up 
    $location = 'https://' . BASE_URL . 'billing_stripe.php';
    header("Location: $location");
    exit();
}


require(PDO);
$dbc = ConnectFrontEnd::getConnection();


$r = Cart::clearCart($dbc, $uid);

// ================
// Send the email:
// ====================
include('./includes/email_receipt.php');


// ============ HTML ===================
// Include the header file:
$page_title = ' - Checkout - Your Order is Complete';
include('./includes/header-checkout.php');
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            
            <h2>Your Order is Complete</h2>
            <p>Thank you for your order. Your order number is: (#<?php echo $_SESSION['order_id']; ?>). 
                Please use this order number in any correspondence with us.</p>

            <p>
            A charge of £<?php echo number_format($_SESSION['order_total']/100, 2); ?> will appear on your credit card 
            <strong>(if paid with card) when the order ships.  </strong>
            <strong> You will be contacted in case of any delays. </strong>  
            </p>
            <p>
                An email confirmation has been sent to your email address. 
                <a href="receipt.php?x=<?php echo $_SESSION['order_id'] . '&y=' . sha1($_SESSION['email']); ?>">Click here</a> 
                to create a printable receipt of your order.
            </p>
            
        </div>
    </div>    
</div>


<?php
// Clear the session:
$_SESSION = array(); // Destroy the variables.
session_destroy(); // Destroy the session itself.

// Include the footer file:
include('./includes/footer.php');
?>