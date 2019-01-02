<?php

require __DIR__ . '/config.inc.php';

if (!isset($_GET['x'], $_GET['y']) 
        || !filter_var($_GET['x'], FILTER_VALIDATE_INT,  array('min_range' => 1)) 
        || (strlen($_GET['y']) !== 40) ) { // Redirect the user.
		
    $location = 'https://' . BASE_URL . 'index.php';
    header("Location: $location");
    exit();
} else {
    $order_id = $_GET['x'];
    $email_hash = $_GET['y'];
}

//require(MYSQL);
require(PDO);
$dbc = ConnectFrontEnd::getConnection();

include(MODELS. 'Cart.php');
include(MODELS. 'Order.php');


// Set the page title and include the header:
include('./includes/header-checkout.php');

$output = '';
    
try {
     
    $q = 'SELECT 
    FORMAT(total/100, 2) AS total, 
    FORMAT(shipping/100,2) AS shipping, 
    credit_card_number, 
    DATE_FORMAT(order_date, "%a %b %e, %Y at %h:%i%p") AS od, 
    CONCAT(last_name, ", ", first_name) AS name, 
    o.delivery_slot,
    CONCAT(address1, " ", address2, ", ", city, " - ", post_code) AS address,
    email, 
    phone, 
    CONCAT_WS(" - ", cat.category, prod.name, s.size) AS item, 
    quantity, 
    
    FORMAT(price_per/100,2) AS price_per 
    FROM orders AS o 
    INNER JOIN customers AS c ON (o.customer_id = c.id) 
    INNER JOIN order_contents AS oc ON (oc.order_id = o.id) 
    INNER JOIN products AS prod ON (oc.product_id = prod.id ) 
    INNER JOIN categories AS cat ON (cat.id = prod.category_id) 
    INNER JOIN sizes AS s ON ( s.id = prod.size )
    WHERE o.id=:oid 
    AND SHA1(email)=:email_hash
    ';
     
    /* 
    DATE_FORMAT()  http://www.w3schools.com/sql/func_date_format.asp 
    -------------------
    CONCAT v CONCAT_WS 
    --------------------
    Both CONCAT & CONCAT_WS are largely equivalent. However, the most noteworthy difference 
    is that CONCAT might not return the results you are expecting for cases where any of the inputs are NULL. 
    In these cases CONCAT will return NULL whereas CONCAT_WS will skip NULL values and still return a string with 
    the result of the remaining inputs. This means that in most cases you will probably want to use CONCAT_WS.
    */
     
    
    $stmt = $dbc->prepare($q);            						
    $stmt->bindParam(':oid', $order_id);			
    $stmt->bindParam(':email_hash', $email_hash);			
    $stmt->execute();
    //$r = $stmt->fetch(PDO::FETCH_ASSOC);
    $r = $stmt->fetchAll();
    
    
    //var_dump($r);    
    
    // Display the order and customer information:       
    $output .= 
        '<p><strong>Order ID</strong>: ' . $order_id . '</p>
        <p><strong>Order Date</strong>: ' . $r[0]['od'] . '</p>
        <p><strong>Customer Name</strong>: ' . htmlspecialchars($r[0]['name']) . '</p>
        <p><strong>Delivery Slot</strong>: ' . htmlspecialchars($r[0]['delivery_slot']) . '</p>
        <p><strong>Shipping Address</strong>: ' . htmlspecialchars($r[0]['address']) . '</p>
        <p><strong>Customer Email</strong>: ' . htmlspecialchars($r[0]['email']) . '</p>
        <p><strong>Customer Phone</strong>: ' . htmlspecialchars($r[0]['phone']) . '</p>';
            
    
    
    // ======= SHOPPING CART Table ==============
    $output .= '
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
          </tr>
        </thead>
    <tbody>';
    
    
    foreach ($r as $k=>$array) {
        
        $output .= '<tr>
            <td>' . $array['item'] . '</td>
            <td>' . $array['quantity'] . '</td>
            <td>£' . $array['price_per'] . '</td>
            <td>£' . number_format($array['price_per'] * $array['quantity'], 2) . '</td>
        </tr>';
    }
    
    
    // Show the shipping and total:    
    $output .= '<tr>
        <td colspan="3"><strong>Shipping</strong></td>
        <td>£' . $r[0]['shipping'] . '</td>
    </tr>';
    
    $output .= '<tr>
        <td colspan="3"><strong>Total</strong></td>
        <td>£' . $r[0]['total'] . '</td>
    </tr>';
            

    // Complete the table and the form:
    //echo '</tbody></table>';
    $output .= '</tbody></table>';
    

} catch (Exception $ex) {
    echo 'An Error occured, we apologize! str254';
   // echo '<h3>' .$ex->getMessage(). '</h3>'; //DEBUG
}
?>

<div class="container" style="padding-left: 1em; ">
    <div class="row">
        <div class="col-md-12">
            <?php echo $output; ?>
        </div>
    </div>    
</div>


</body>
</html>