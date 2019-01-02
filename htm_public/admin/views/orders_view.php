<?php


$tableRows = '';

if (is_array($rows) || is_object($rows)) {            

    foreach ($rows as $k => $array) {

        list($date, $time) = formatDate($array['order_date']); // function which will split date from time
        

        $tableRows .= '
        <tr>
            <td>' . htmlentities($date) .'</td>
            <td>' . htmlentities($time) .'</td>  
            <td>
                <a href="single_order.php?oid=' . htmlentities($array['id']) . '">' . htmlentities($array['id']) . '</a>
            </td>    
            <td>
                <a href="view_single_customer.php?cid=' . htmlentities($array['cid']) . '">' . htmlentities($array['cid']) .'</a>
            </td>
            <td>£' . htmlentities($array['total']) . '</td>        
            <td>' . htmlentities($array['shipping']) . '</td>    
            <td>' . htmlentities($array['name']). '</td>    
            <td>' . htmlentities($array['delivery_slot']). '</td>    
            <td>' . htmlentities($array['paid_status']). '</td>    
        </tr>';

    } //End foreach()              
}

?> 

<div class="container"> 
    <div class="row">
        
        <h3>View Orders</h3><table border="0" width="100%" cellspacing="4" cellpadding="4">
        <table id="orders" class="table">
            <thead>
                <tr>
                    <th align="left">order date</th>
                    <th align="left">order time</th>
                    <th align="left">Order ID</th>
                    <th align="left">customer ID</th>
                    <th align="left">Total</th>
                    <th align="left">Shipping</th>
                    <th align="left">customer name</th>    
                    <th align="left">Delivery slot</th>
                    <th align="left">Paid status</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $tableRows; ?>    
            </tbody>
            
            
        </table>    


        
    </div>
    
</div><!-- container --> 


<!-- jQuery is already included in the header page above 
<script src="js/jquery.dataTables.min.js" type="text/javascript" charset="utf-8"></script>
-->

<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script type="text/javascript"> 
    // Enable Datatables:
    $(function() { 
        $("#orders").dataTable();
    }); 
</script>
 