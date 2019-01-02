<?php
session_start(); 

require (__DIR__ . '/../config.inc.php');

require ('../includes/form_functions.inc.php');

require(PDO_ADMIN);

$dbc = dbConn::getConnection();


require(MODELS.'Category.php');
require(MODELS.'Product.php');
require('./class/Administrator.php');

$add_product_errors = array();
//$specific_table_name = 'specific_products';
//$general_table_name = 'categories';
$html = '';



if ($_SERVER['REQUEST_METHOD'] == 'POST')  { 	  
	
	// Validate Category:: if not set, or POST[category] not Array, create Error
	// Shoul be an Array because admin can assign product to 2 different categories
	if (!isset($_POST['category']) || !is_array($_POST['category']) ) {
		
		$add_product_errors['category'] = 'Please select a category! it is required';		
		
	} else {
		
		// validate each category input as INT, create Error if not valid
		foreach ($_POST['category'] as $k => $v) {
			
			if( !filter_var($v, FILTER_VALIDATE_INT, array('min_range' => 1) ) ) {
				$add_product_errors['category'] = 'Selected category value not valid!';
			}
		}	
	}
	

    // Check (validate) for a size:
    if (!isset($_POST['size']) || !filter_var($_POST['size'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $add_product_errors['size'] = 'Please select a size!';
    }

    if (empty($_POST['name'])) {                            
        $add_product_errors['name'] = 'Please enter the name!';
    } else {
        $name = strip_tags($_POST['name']);
    }	

    if (empty($_POST['price']) || !filter_var($_POST['price'], FILTER_VALIDATE_FLOAT) || ($_POST['price'] <= 0)) {
        $add_product_errors['price'] = 'Please enter a valid price!';
    } else {
        $price = $_POST['price'];
    }

    if (empty($_POST['stock']) || !filter_var($_POST['stock'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
        $add_product_errors['stock'] = 'Please enter the quantity in stock!';
    } else {
        $stock = strip_tags($_POST['stock']); 
    }
    

    if (empty($_POST['description'])) {        
        $add_product_errors['description'] = 'Please enter the description!';
    } else {        
        $desc = strip_tags($_POST['description']);
    }
        
     
    if ( !filter_var($_POST['product_code'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
        $add_product_errors['product_code'] = 'Please enter the Product Code!';
    }
    
    if (empty($_POST['product_code'])) {        
        $add_product_errors['product_code'] = 'Please enter the Product Code!';
    } else {
        $_POST['product_code'] = strip_tags($_POST['product_code']);      
    }
    
        
    // ========================================================
    // IF NO VALIDation ERRORS, Upload an image, and Add the product to the database:
	// ============================================
    if (empty($add_product_errors) ) 
	{        
            $administrator = new Administrator();

			// if user is trying to upload an image
			if(isset($_FILES['image']) ) {
					
				if( $administrator->uploadImage($dbc, IMAGE_DIR) ) {
					//image was uploaded successfully
					$html .= '<div class="alert alert-success"> 
								The image has been uploaded, and moved to /folder/
							</div> ';
				} else {
					$add_product_errors['uploading'] = $administrator->getMessage();
				}
			}
			

            //=============================================
            // NOW, WE CAN INSERT INTO DATABASE, if no errors
            //================================================
            if(empty($add_product_errors)) {              
                
                $img =  $_SESSION['image']['new_name']; // SESSION[image] created in uploadImage() method. OUT::  /images/new_img_name.JPG                                        

				/*
				  if Admin is trying to create a product which will belong in 2 different categories, Loop and 
				  call add_new_product() multiple times. Each time we pass the different category name where this item will belong				  
				*/
				foreach ($_POST['category'] as $k => $cat_id) {
					
					$result =  $administrator->add_new_product($dbc, $cat_id, $name, $desc, $img, $price, $stock, $_POST['size'], $_POST['product_code']);				
					
					// If it DID NOT ran OK
					if(!$result) {
						trigger_error('The product could not be added due to a system error. We apologize for any inconvenience.');
						exit("<h3>Cik 149</h3>");
					}
					
				}
				
				// If Queries above ran OK, script arrives here. print message, and cleanup
				$html .= '<div class="alert alert-success"> 
								<strong>Success!</strong> The product has been added!
							</div>';
				$_POST = array(); // Clear $_POST: cleanup because we'll display the form again, it shouldnt display same values			
				$_FILES = array(); // Clear $_FILES Array so that admin can submit new images and data:			
				unset($_FILES['image'], $_SESSION['image']);
                
                
            }

            
				
	}
	
} 
else  // IF GET REQUEST, Clear out the session on a GET request:
{
    unset($_SESSION['image']);	
}



//============== HTML ==============
//============== HTML ==============
//============== HTML ==============
$page_title = 'Add a fOOGIE';
include ('./includes/header.php');
include ('./views/add_product_view.php');
include ('./includes/footer.php');
?>

   