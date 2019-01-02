



<div class="container" style="margin-top: 10px;">    
   
    <div class="col-3">
    
    </div>
    
    <div class="col-6">      

            <h2>Your Shipping Information</h2>
            <p>
                Please enter your shipping information. 
                <span class="required">*</span> <strong> Delivery is FREE only within some London postcodes!  </strong> 
                Delivery to other UK postcodes is <strong>£2.90. </strong> 
                <a href="/free-delivery.php" title="free delivery?" style="color: #E27900; font-style: italic;">
                    Check if we deliver FREE to you 
                </a>
            </p>


            
            <form action="/checkout.php" method="POST" role="form">                                       

                <div id="same-billing-address" class="form-group">
                    <label for="use"><strong style="color:#E27900;">Use Same Address for Billing?</strong></label> 
                    <input type="checkbox" name="use" value="Y" id="use" <?php if (isset($_POST['use'])) echo 'checked="checked" '; ?> />                    
                </div>  

                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <label for="first_name" class="sr-only">First Name</label><br />
                        <input type="text" class="form-control" name="first_name" placeholder="First Name?" value="<?php
                        if (isset($_SESSION['first_name'])) { //SESSION[firs_name] IS SET IN THE login.php File
                            echo htmlspecialchars($_SESSION['first_name'], ENT_QUOTES, 'UTF-8');
                        } else if (isset($fn)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                            echo htmlspecialchars($fn, ENT_QUOTES, 'UTF-8');
                        }
                        ?>" required="required" />                    
                               <?php
                               //create_form_input('first_name', 'text', $shipping_errors);                         
                               if (!empty($shipping_errors['first_name'])) {
                                   echo '<div class="alert alert-danger">' . $shipping_errors['first_name'] . ' </div>';
                               }
                               ?>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <label for="last_name" class="sr-only">Last Name</label><br /> 
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name?" value="<?php
                               if (isset($_SESSION['last_name'])) {
                                   echo htmlspecialchars($_SESSION['last_name'], ENT_QUOTES, 'UTF-8');
                               } else if (isset($ln)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                                   echo htmlspecialchars($ln, ENT_QUOTES, 'UTF-8');
                               }
                               ?>" required="required" />                    
                        <?php
                               //create_form_input('last_name', 'text', $shipping_errors); 
                               if (!empty($shipping_errors['last_name'])) {
                                   echo '<div class="alert alert-danger">' . $shipping_errors['last_name'] . ' </div>';
                               }
                               ?>
                    </div>
                </div>


                <div class="from-group">
                    <label for="address1" class="sr-only">Street Address</label><br />
                    <input type="text" class="form-control" name="address1" placeholder="23 Oxford street" value="<?php
                               if (isset($_SESSION['address1'])) {
                                   echo htmlspecialchars($_SESSION['address1'], ENT_QUOTES, 'UTF-8'); // SESSION[address1] is for Shipping address, set in
                               } else if (isset($a1)) {
                                   echo htmlspecialchars($a1, ENT_QUOTES, 'UTF-8');
                               }
                               ?>" required="required" />                    
                    <?php
                    //create_form_input('address1', 'text', $shipping_errors); 
                    if (!empty($shipping_errors['address1'])) {
                        echo '<div class="alert alert-danger">' . $shipping_errors['address1'] . ' </div>';
                    }
                    ?>
                </div>

                <div class="row">
                    <div class="col-md-6 col-xs-6">
                         <label for="city" class="sr-only">City Name</label><br />
                        <input type="text" class="form-control" name="city" placeholder="City?" value="<?php
                               if (isset($_SESSION['city'])) {
                                   echo htmlspecialchars($_SESSION['city'], ENT_QUOTES, 'UTF-8');
                               } else if (isset($c)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                                   echo htmlspecialchars($c, ENT_QUOTES, 'UTF-8');
                               }
                        ?>" required="required" />  
                        <?php
                        //create_form_input('city', 'text', $shipping_errors); 
                        if (!empty($shipping_errors['city'])) {
                            echo '<div class="alert alert-danger">' . $shipping_errors['city'] . ' </div>';
                        }
                        ?>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <label for="postcode" class="sr-only">Post Code</label><br />
                        <input type="text" class="form-control" name="postcode" placeholder="Post Code" value="<?php
                               if (isset($_SESSION['postcode'])) {
                                   echo htmlspecialchars($_SESSION['postcode'], ENT_QUOTES, 'UTF-8');
                               } else if (isset($z)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                                   echo htmlspecialchars($z, ENT_QUOTES, 'UTF-8');
                               }
                        ?>" required="required" />  
                        <?php
                        //create_form_input('zip', 'text', $shipping_errors); 
                        if (!empty($shipping_errors['postcode'])) {
                            echo '<div class="alert alert-danger">' . $shipping_errors['postcode'] . ' </div>';
                        }
                        ?>
                    </div>                    
                </div>
                
                <div class="from-group">
                    <label for="phone" class="sr-only">Phone Number</label><br />
                    <input type="text" class="form-control" name="phone" placeholder="Phone Number" value="<?php
                           if (isset($_SESSION['phone'])) {
                               echo htmlspecialchars($_SESSION['phone'], ENT_QUOTES, 'UTF-8');
                           } else if (isset($p)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                               echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8');
                           }
                    ?>" required="required" /> 
                    <?php
                    //create_form_input('phone', 'text', $shipping_errors); 
                    if (!empty($shipping_errors['phone'])) {
                        echo '<div class="alert alert-danger">' . $shipping_errors['phone'] . ' </div>';
                    }
                    ?>
                </div>

                <div class="from-group">
                    <label for="email" class="sr-only">Email Address</label><br />
                    <input type="text" class="form-control" name="email" placeholder="Email" value="<?php
                           if (isset($_SESSION['email'])) {
                               echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8');
                           } else if (isset($e)) { //if user had submited this and it was valid, repopulate field so they don't have to retype
                               echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8');
                           }
                           ?>" required="required" /> 
                    <?php
                    //create_form_input('email', 'text', $shipping_errors); 
                    if (!empty($shipping_errors['email'])) {
                        echo '<div class="alert alert-danger">' . $shipping_errors['email'] . ' </div>';
                    }
                    ?>
                </div>

                <div>
                    <br/>
                    <input type="submit" value="Continue to Billing" class="btn btn-success" />
                </div>
            </form>

        </div>


    <div class="col-3">
    
    </div>
</div><!-- container -->








