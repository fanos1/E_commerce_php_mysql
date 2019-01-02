<div class="container">
    <div class="row">
        <div class="col-lg-12">
            

<?php
$orderHeader = '';
$tr = '';
$shipped = TRUE;// For confirming that the order has shipped: Default is to assume it is shipped
        
if (is_array($rows) || is_object($rows)) {   
    
    $orderHeader .= 
    '<p>
        <strong>Order ID</strong>: ' . $order_id . '<br />
        <strong>Total</strong>: £' . $rows[0]['total'] . '<br />
        <strong>Shipping</strong>: £' . $rows[0]['shipping'] . '<br />
        <strong>Order Date</strong>: ' . $rows[0]['od'] . '<br />
        <strong>Customer Name</strong>: ' . htmlspecialchars($rows[0]['name']) . '<br />
        <strong>Customer Address</strong>: ' . htmlspecialchars($rows[0]['address']) . '<br />
        <strong>Customer Email</strong>: ' . htmlspecialchars($rows[0]['email']) . '<br />
        <strong>Customer Phone</strong>: ' . htmlspecialchars($rows[0]['phone']) . '<br />
        <strong>Credit Card Number Used</strong>: *' . $rows[0]['credit_card_number'] . '<br />
        <strong>DELIVERY SLOT</strong>: ' . $rows[0]['delivery_slot'] . 
    '</p>';
    
    foreach ($rows as $k=>$array) {
        $tr .= '
        <tr>
            <th>' . htmlspecialchars($array['item']) . '</th>
            <th>' . htmlspecialchars(number_format($array['price_per']/100, 2)) . '</th>
            <th>' . htmlspecialchars($array['stock']) . '</th>
            <th>' . htmlspecialchars($array['quantity']) . '</th>
            <th>' . html_entity_decode($array['sd']) . '</td>
        </tr>';
        
        //[sd] is 'ship_date' COLUMN in our DB. That column is NULL by default. When admin clicks the ship button here, 
        //the 'ship_date' COLUMN will be populated with the currnet DATE. If 'ship_date' COLUMN is not NULL, We don't want to display it, so assign FALSE. 
        if (!$array['sd']) {
            $shipped = FALSE; 
        }
    }
    
    //======================================
    // Only show the submit button if the order hasn't already shipped: We will also pass the $order_id via POST
    //=============================================
    if (!$shipped) {                        
        //TESTING points to 'capture_patmnt_testinj' page
        $submitButton .= '
            <p>Note that actual payments will be collected once you click this button! payment was authorized in billing page</p>                
            
            <button type="button" class="btn btn-primary">
                <a href="capture_payment_testing.php?oid='.$order_id.'" style="color:#fff;">Ship This Order - Get Paid</a>
            </button>
        ';			                
    }
    
}
?>


            <!-- <form action="capture_payment_testing.php" method="post" accept-charset="utf-8">   --> 
            <form action="#" method="post" accept-charset="utf-8">   
                    <?php  echo $orderHeader; ?>
                <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price Paid</th>
                                <th>Quantity in Stock</th>
                                <th>Quantity Ordered</th>
                                <th>Shipped?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo isset($tr) ? $tr : 'tr not set'; ?>
                        </tbody>
                    </table>

                    <div>
                        <?php echo $submitButton; ?>
                    </div>                

            </form>

            
        </div>
    </div>
</div>


<!-- 
<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript"> 
    // Enable Datatables:
    $(function() { 
        $("#orders").dataTable();
    }); 
</script>
*/
 