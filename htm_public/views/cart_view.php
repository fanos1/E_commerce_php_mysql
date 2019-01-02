
<div class="container">    
    <div class="col-12">
        
        <h2>Your Shopping Cart</h2>            
        
        <nav class="breadcrumb">
          <a  href="/index.php" title="home page">Home /</a>            
          <a  href="/cart.php" title="to shopping cart page"> Shopping Cart</a>
        </nav>
        
    </div>
        
    
</div>



<div class="container">    
    
    <div class="col-12">            
            
            <!-- shopping table -->                     
            <form action="/cart.php" method="POST">
                <table class="table table-bordered">
                    <tr>
                        <th align="center">image</th>
                        <th align="center">Item</th>
                        <th align="center">Quantity</th>
                        <th align="right">Price</th>
                        <th align="right">Subtotal</th>
                        <th align="center">Options</th>
                    </tr>
                    <?php
                        $total = 0;                                
                        foreach ($rows as $k => $array) { 	          
                            // Get the correct price:
                            $price = get_just_price($array['price'], $array['sale_price']);

                            // Calculate the subtotal:
                            $subtotal = $price * $array['quantity'];

                             // Without move to wish list
                            echo '
                            <tr>
                                <td>
                                    <img src="/img/'.$array['image'].'" alt="'.htmlspecialchars($array['category']).'-'.htmlspecialchars($array['name']) .'" width="68" height="80" />
                                </td>
                                <td>' . htmlspecialchars($array['category']) . '-' . htmlspecialchars($array['name']) . 
                                    '<br />Size : ' . $array['size_name'].  
                                '</td>
                                <td align="center">
                                    <input type="text" name="quantity[' . $array['product_code'] . '-'.$array['product_id']. ']" value="' . $array['quantity'] . '" size="2" class="small" />
                                </td>
                                <td align="right">£' . $price . '</td>
                                <td align="right">£' . number_format($subtotal, 2) . '</td>

                                <td align="right">                
                                    <a href="/cart.php?product_code=' . $array['product_code'] . '&action=remove&product_id='.$array['product_id'].'">
                                        <button type="button" class="btn btn-danger btn-sm">Remove from Cart</button>                
                                    </a>                                
                                </td>
                            </tr>
                            ';

                            // Check the stock status:
                            if ($array['stock'] < $array['quantity']) {
                                echo '
                                <tr class="alert alert-danger">
                                    <td colspan="5" align="center">
                                        There are only ' . $array['stock'] . ' left in stock of the ' . htmlspecialchars($array['name']) . '. Please update the quantity, remove the item entirely, or move it to your wish list.
                                    </td>
                                </tr>';
                            }


                            // Add the subtotal to the total:
                            $total += $subtotal;

                        } 

                    //$shipping = get_shipping($total);
                    $shipping = 0; //shipping is FREE
                    $total += $shipping;
                    echo '<tr>
                            <td colspan="3" align="right"><strong>Shipping &amp; Handling</strong></td>
                            <td align="right">£' . number_format($shipping, 2) . '</td>
                            <td>&nbsp;</td>
                    </tr>
                    '; 

                    // Display the total:
                    echo '<tr>
                            <td colspan="3" align="right"><strong>Total</strong></td>
                            <td align="right">£' . number_format($total, 2) . '</td>
                            <td>&nbsp;</td>
                    </tr>
                    ';   
                    ?>

                
                </table>

                <input type="submit" value="Update Quantities" class="btn btn-primary-outline btn-small" />
                <a href="/checkout.php" class="btn btn-success">Checkout</a>
                
            </form>                    
                
        </div>            
    
</div>            




