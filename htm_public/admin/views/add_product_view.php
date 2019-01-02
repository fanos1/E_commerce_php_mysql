<div class="container">
        <div class="row">
            <div class="col-sm-7">        
                <form role="form" enctype="multipart/form-data" action="" method="post" accept-charset="utf-8">
                    <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
                    <fieldset>                
                        <div class="field">
                            <label for="category"><strong>Category (Select one two)</strong></label><br />
                            <select multiple class="form-control" name="category[]"<?php if (array_key_exists('category', $add_product_errors)) echo ' class="error"'; ?>>                                
                                <?php 
                                // Retrieve all the categories and add to the pull-down menu:                            
                                $rows = Category::getCategory($dbc);

                                foreach ($rows as $array) {
                                    echo "<option value=\"$array[0]\""; //row[0] ==== id for each record, on 1st loop id==1
                                     // Check for stickyness:
                                     if (isset($_POST['category']) && ($_POST['category'] == $array[0]) ) echo ' selected="selected"';
                                     echo ">$array[1]</option>\n";
                                }
                                ?>
                            </select>
							
							
							
                            <?php 
                            if(array_key_exists('category', $add_product_errors)) {
                                  echo '
                                    <div class="alert alert-danger">
                                        <strong>Error!</strong> ' . $add_product_errors['category'] . '
                                    </div>';
                            }
                            ?>
							<br/>
                        </div>


                        <div class="field">
                            <label for="size"><strong>Size</strong></label><br />
                            <select name="size">
                            <option>Select One</option>
                               <?php                         
                               $r = Products::getSizes($dbc);
                               foreach ($r as $k => $array) {
                                   echo "<option value=\"$array[0]\""; //row[0] == id for each record, on 1st loop id==1

                                    // Check for stickyness:
                                    if (isset($_POST['size']) && ($_POST['size'] == $array[0]) ) echo ' selected="selected"';
                                    echo ">$array[1]</option>\n";
                               }
                               ?>
                            </select>
                            <?php 
                                if(array_key_exists('size', $add_product_errors)) {
                                    echo '
                                    <div class="alert alert-danger">
                                        <strong>Error!</strong> ' . $add_product_errors['size'] . '
                                    </div>';
                                }
                            ?>
							<br/><br/>
                        </div>


                        <div class="field">
                            <label for="name"><strong>Name</strong></label><br />
                            <?php create_form_input('name', 'text', $add_product_errors); ?>
							<br/>
                        </div>

                        <div class="field">
                            <label for="price"><strong>Price</strong></label><br />
                            <?php create_form_input('price', 'text', $add_product_errors); ?>
							<br/>
                        </div>

                        <div class="field">
                            <label for="stock"><strong>Initial Quantity in Stock (NOTE: to update quantity go to Add Inventory ) </strong></label><br />
                            <?php create_form_input('stock', 'text', $add_product_errors); ?>
							<br/>
                        </div>

                        <div class="field">
                            <label for="description"><strong>Description</strong></label><br />
                            <?php create_form_input('description', 'textarea', $add_product_errors); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="product_code">Product Code</label>                            
                            <select class="form-control" name="product_code">
                                <option>Select One</option>
                               <?php                         
                               $r = Products::getProductCodes($dbc);
                               foreach ($r as $k => $array) {
                                   echo "<option value=\"$array[0]\""; //row[0] == id for each record, on 1st loop id==1

                                    // Check for stickyness:
                                    if (isset($_POST['product_code']) && ($_POST['product_code'] == $array[0]) ) echo ' selected="selected"';
                                    echo ">$array[1] :: $array[2]</option>\n";
                               }
                               ?>
                            </select>
                            <?php 
                                if(array_key_exists('produc_code', $add_product_errors)) {
                                    echo '
                                    <div class="alert alert-danger">
                                        <strong>Error!</strong> ' . $add_product_errors['produc_code'] . '
                                    </div>';
                                }
                            ?>
                        </div>
                        
                        
                        

                        <div class="field">
                            <p>Image size should not be more than 400x420 px</p>
                            <label for="image"><strong>Image</strong></label><br /> <?php
                            // Check for an error: Before creating the <input type=file>, do following error checks
                            // if an image-related error exists, the error message is first displayed, then the file input is created, with error .class
                            if (array_key_exists('image', $add_product_errors)) 
                            {
                                echo '<span class="error">' . $add_product_errors['image'] . '</span><br />
                                            <input type="file" name="image" class="error" />'; 
                            } 
                            else  // No error.
                            {
                                echo '<input type="file" name="image" />'; 
                                // If the file exists (from a previous form submission but there were other errors),store the file info 
                                // in a session and note its existence:		
                                if (isset($_SESSION['image'])) { 
                                    echo "<br />Currently '{$_SESSION['image']['file_name']}'";                      
                                }
                            } ?>
                        </div>

                        <br clear="all" />
                        <div class="field">
                            <input type="submit" value="Add This Product" class="btn btn-warning" />
                        </div>
                    </fieldset>
                </form> 
            </div>

            <div class="col-sm-5">
                <?php 
                /* 
                $q = "SELECT user_friendly_name FROM departments WHERE id=$department";
                $r = mysqli_query ($dbc, $q); 
                if(mysqli_num_rows($r) === 1) {//we expect only 1 department returned
                    list($departmentName) = mysqli_fetch_array($r, MYSQLI_NUM);
                } else {
                    exit('An error occured, we apologize!');
                } 
                echo isset($departmentName) ? '<p class="lead" style="padding-top: 1em;">Welcome to the'.$departmentName.' Department.</p>' :'';
                echo '<p>Use the form to add new products to the categories. Categories which belong to this department can be found in the dropbox</p>';
                 * 
                 */
                ?> 
                Departments here

            </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $html; ?>
        </div>
    </div>


</div>

    