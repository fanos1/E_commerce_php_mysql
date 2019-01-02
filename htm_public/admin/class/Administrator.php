<?php

class Administrator {
	
    private $errorMessage;
    
    
    
    public function __construct() {

    }


	
    /*
    * Insert a new Product into the Table
    */
	public function add_new_product($dbc, $cat_id, $name, $desc, $img, $price, $stock, $size, $prod_code) {
        try {
            
           /*
             * The TIMESTAMP data type is the only way to have MySQL automatically set the time when a row was inserted and/or updated. 
             * DATETIME columns can’t do this. TIMESTAMP columns are identical to DATETIME columns with one important exception — they can be 
             * set to take the current time value when a row is created or updated.
            */
            $q = 'INSERT INTO products (category_id, name, description, image, price, stock, size, product_code) 
                    VALUES (:cat_id, :name, :descrip, :image, :price, :stock, :size, :product_code)';
            
            $smtp = $dbc->prepare($q);
            $smtp->bindParam(':cat_id', $cat_id);
            $smtp->bindParam(':name', $name);
            $smtp->bindParam(':descrip', $desc);
            $smtp->bindParam(':image', $img);
            $smtp->bindParam(':price', $price);
            $smtp->bindParam(':stock', $stock);
            $smtp->bindParam(':size', $size);            
            $smtp->bindParam(':product_code', $prod_code);  
            $r = $smtp->execute();
            
            if($r) {
                return $r; //OUT:: bool(true) 
            } else {
                return false;
            }
            
            
        } catch (PDOException $ex) {			
            exit('An Exception Occured! 39');
			// TO BE IMPLEMENTD: Log this erro instead of exit
        } catch (Exception $ex) {
			// Unexpected Exception
            exit('An Exception Occured! 45');
			// TO BE IMPLEMENTD: Log this erro instead of exit
        }
    }
    
    
    
	public function uploadImage($dbc, $img_dir) {
        
        try {
            
            // is_uploaded_file($html):: Tells weather the file was uploaded via HTTP
            if (is_uploaded_file ($_FILES['image']['tmp_name']) && ($_FILES['image']['error'] == UPLOAD_ERR_OK))  
            {		
            
                $_FILES['image'] = $_FILES['image'];
                $size = ROUND($_FILES['image']['size']/1024);

                if ($size > 512) { // Validate the file size:                
                    // First, set the message which we will display to user. Than return False to stop script
                    $this->setMessage("The uploaded file was too large. !");
                    return FALSE; // stop
                }

                $allowed_mime = array ('image/gif', 'image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
                $allowed_extensions = array ('.jpg', '.gif', '.png', 'jpeg');

                //getimagesize() retuns an Array with following info. We are only interested in the $image_info[mime] type retunred
                $image_info = getimagesize($_FILES['image']['tmp_name']);
                /*
                * $image_info[0] = 500
                * $image_info[1] = 400
                * $image_info[2] = 2
                * $image_info[3] = width="500" height="400"
                * $image_info[bits] = 8
                * $image_info[channels] = 3
                * $image_info[mime] = image/jpeg
                */


                $ext = substr($_FILES['image']['name'], -4); //get last 4 characters of the submited filename

                // Validate the file type: 
                // IF Browser supplied (submited by user) MIME type is NOT in our allowed types
                // OR IF SERVER supplied MIME is not in our allowed types (You can extract this from PHP's native getimagesie() )
                // OR IF submited filename extension in NOT in our allowed extensions list.
                // Create Error!
                if ((!in_array($_FILES['image']['type'], $allowed_mime)) 
                        || (!in_array($image_info['mime'], $allowed_mime) ) 
                        || (!in_array($ext, $allowed_extensions) ) ) 
                {
                    // First, set the message which we will display to user. Than return False to stop script
                    $this->setMessage("The uploaded file was not of the proper type!");
                    return FALSE; // STOP
                }


                // If script arrived here, it means ALL OK. it did not stop with the return FALSE statement, No Errors

                // Move the file over, if no Errors Up to here:                   
                // The image's new name and its original file name are both stored in the session for using it later.
                // if (!array_key_exists('image', $add_product_errors)) {

                        // Create a new name for the file:
                        $new_name = (string) sha1($_FILES['image']['name'] . uniqid('',true)); //sha1() will generate a 40-character-long random name
                        // Add the extension:
                        $new_name .= ( (substr($ext, 0, 1) != '.') ? ".{$ext}" : $ext );

                        //ORIGINAL ::::::::: $dest =  "../products/$new_name";   

                        $dest = $img_dir."$new_name"; // F:/xampp/htdocs/sites/products/$new_name

                        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest))  {

                            // Store the data in the session for later use:
                            $_SESSION['image']['new_name'] = $new_name;
                            $_SESSION['image']['file_name'] = $_FILES['image']['name'];				

                            return TRUE;  //stop

                        } else {
                           // trigger_error('The file could not be moved.'); 
                            $this->setMessage("The image file could not be moved due to system Error!");
                            unlink ($_FILES['image']['tmp_name']);	//unlink($dest) method deletes a file
							unlink ($dest);	//when problem because of database occurs, uploaded file is removed (to prevent deadwood from cluttering products folder)
							
                            return FALSE; //stop

                        }

                // }
                
            } 
            else  //else if (!isset($_SESSION['image']))  
            { 
                
                //if Error, find out which one?
                switch ($_FILES['image']['error']) {
                    case 1:
                    case 2:
                            // $add_product_errors['image'] = 'The uploaded file was too large.';
                            $this->setMessage("The uploaded file was too large!");
                            break; //  If you don't write a break statement at the end of a case's statement list, PHP will go on executing the statements of the following case
                            return FALSE;
                    case 3:
                            // $add_product_errors['image'] = 'The file was only partially uploaded.';
                            $this->setMessage("The file was only partially uploaded!");
                            break;
                            return FALSE;
                    case 6:
                    case 7:
                    case 8:
                            // $add_product_errors['image'] = 'The file could not be uploaded due to a system error.';
                            $this->setMessage("The file could not be uploaded due to a system error!");
                            break;
                            return FALSE;
                        
                    case 4:
                    default: 
                            // $add_product_errors['image'] = 'No file was uploaded.';
                            $this->setMessage("No file was uploaded!");
                            break;
                            return FALSE;
                } 

            } 
            
            
            
        } catch (Exception $ex) {
            exit('An Exception Occured! 34');
        }
    }
        
     

    	
    public function setMessage($message){
            $this->errorMessage =   $message;
    }

    
    public function getMessage(){
        return $this->errorMessage;
    }
    
	
	
} 