
<!-- ================== Display Carty ==================== -->
<div class="container">    
    
    <div class="col-12">            

        <h2>Your Shopping Cart</h2>
        <table class="table table-bordered">

            <tr>
                    <th align="center">image</th>
                    <th align="center">Item</th>
                    <th align="center">Quantity</th>
                    <th align="right">Price</th>
                    <th align="right">Subtotal</th>                    
            </tr>

            <?php

            $total = 0;

            // For removing problematic items:
            $remove = array();

            //Fetch each product
            foreach ($rows as $k => $array) {

                // Check the stock status:
                if ($array['stock'] < $array['quantity']) {

                    echo '
                    <tr class="text-danger">
                        <td colspan="4">
                            There are only ' . $array['stock'] . ' left in stock of the ' . htmlspecialchars($array['name']) . '. This item has been 
                                removed from your cart and placed in your wish list.
                        </td>
                    </tr>';

                    $remove[$array['sku']] = $array['quantity'];

                } else {

                    // Get the correct price:
                    $price = get_just_price($array['price'], $array['sale_price']);

                    // Calculate the subtotal:
                    $subtotal = $price * $array['quantity'];

                    // Print out a table row:
                    echo '
                    <tr>
                        <td>
                            <img src="/img/'.$array['image'].'" alt="'.htmlspecialchars($array['category']).'-'.htmlspecialchars($array['name']) .'" width="48" height="84" />
                        </td>
                        <td>' . htmlspecialchars($array['category']) . '-' . htmlspecialchars($array['name']) . '</td>
                        <td align="center">' . $array['quantity'] . '</td>
                        <td align="right">£' . $price . '</td>
                        <td align="right">£' . number_format($subtotal, 2) . '</td>
                    </tr>
                    ';

                    // Add the subtotal to the total:
                    $total += $subtotal;

                }
                
            }

            
            //$shipping = get_shipping($total);            
            if(isset($_SESSION['free-delivery']) && $_SESSION['free-delivery'] === FALSE) { 
                $shipping = 2.7; 
            } else {
                $shipping = 0; //FREE shipping
            }
            
            $total += $shipping;
            echo '<tr>
                    <td colspan="2"> </td><th align="right">Shipping &amp; Handling</th>
                    <td align="right">£' . number_format($shipping, 2) . '</td>
            </tr>
            ';

            // Store the shipping in the session:
            $_SESSION['shipping'] = $shipping;

            // Display the total:
            echo '<tr>
                    <td colspan="2"> </td><th align="right">Total</th>
                    <td align="right">£' . number_format($total, 2) . '</td>
                    <td>&nbsp;</td>
            </tr>
            ';

            // Remove any problematic items:
            if (!empty($remove))  {
                
                // Clear the results:
                // ::: mysqli_next_result($dbc);

                // Loop through the array:
                foreach ($remove as $sku => $qty)  {

                    list($sp_type, $pid) = parse_sku($sku); //$remove Array stores [sku] as key

                    // Move it to the wish list:
                   // $r = Cart::add_to_wish_list($dbc, $uid, $sp_type, $pid, $qty); //call

                    $r2 = Cart::remove_from_cart($dbc, $uid, $sp_type, $pid, $qty); 
                }
                
            }

        ?>
        </table>

        </div>
    
</div><!-- container -->
<!-- =============== END Display Carty ================ -->


<div class="container">
        
        <?php include('./includes/form_functions.inc.php'); ?>
        <form action="/cash_payment.php" method="POST">
            
    
            
            <div class="col-6">
                <h4 style="padding-top: 20px; color: #a94442;">Your Delivery Slot Time:</h4>
		<?php 
                echo isset($_SESSION['slot_day']) ? htmlspecialchars($_SESSION['slot_day']) .'<strong> At </strong>' : '' ;                 
                echo isset($_SESSION['slot_time']) ? htmlspecialchars($_SESSION['slot_time']) : '' ; 
                ?>
            </div>
            
            <div class="col-6">
                <h4 style="padding-top: 20px; color: #a94442;">Your Delivery Address </h4>
                    <div class="form-group">
                        <label for="cc_first_name" class="sr-only">First Name</label><br />                        
                        <input type="text" name="cc_first_name" value="<?php 
                            echo isset($_SESSION['cc_first_name']) ? htmlspecialchars($_SESSION['cc_first_name']) : ''; ?>" class="form-control" 
                        />
                        <?php                                             
                        if ( isset($billing_errors['cc_first_name']) ) {
                            echo '
                            <div class="alert alert-danger fade in">
                                Please enter your first name!
                            </div>';
                        }                        
                        ?>
                    </div>

                   

                    <div class="form-group">
                        <label for="cc_address"><strong>Street Address * </strong></label><br />
                        <input type="text" name="cc_address" value="<?php 
                            echo isset($_SESSION['address1']) ? htmlspecialchars($_SESSION['address1']) : '';
                        ?>"  class="form-control" />                        
                    </div>

                    
                    <div class="form-group">
                        <label for="cc_zip"><strong>Post Code </strong></label><br />
                        <input type="text" class="form-control" name="cc_zip" placeholder="Post Code" value="<?php                             		 
                        if( isset($_SESSION['postcode']) ) { 
                            echo htmlspecialchars($_SESSION['postcode'], ENT_QUOTES, 'UTF-8');
                        } 
                        else if (isset($cc_zip)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                            echo htmlspecialchars($cc_zip, ENT_QUOTES, 'UTF-8');
                        }
                        ?>" required="required" />  
                        <?php 
                            //create_form_input('zip', 'text', $shipping_errors); 
                            if(!empty($billing_errors['cc_zip'] ) ) {
                                echo '<div class="alert alert-danger">'.$billing_errors['cc_zip'] .' </div>';
                            }                             
                            //create_form_input('cc_zip', 'text', $billing_errors, $values); 
                            
                        ?>
                    </div>

                    <div id="submit_div">
                        <input type="submit" value="Place Order" id="submitBtn" class="btn btn-primary" />
                    </div>
                 
            </div>
            
        
        </form>
           
</div>

