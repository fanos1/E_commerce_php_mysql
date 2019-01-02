<?php
session_start();
$uid = session_id(); // The session ID is the user's cart ID:

require( __DIR__ . '/config.inc.php');

require(PDO);
$dbc = ConnectFrontEnd::getConnection();

require (MODELS. 'DeliverySlots.php');


$html = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') 
{

	$options = array('min_range' => 0);    
        
	if( isset($_POST['day_id'], $_POST['slot_id'])  
		&&  filter_var( $_POST['day_id'], FILTER_VALIDATE_INT, $options ) !== false 
		&&  filter_var( $_POST['slot_id'], FILTER_VALIDATE_INT, $options ) !== false ) 
	{	
     
		try {
			$r = DeliverySlots::isDelivDateAvail($dbc, $_POST['day_id'], $_POST['slot_id']);
		} 
		catch (PDOException $ex) {
			$err_title = 'An error has occurred';	
			$pdo_err_output = 'Database error: ' . $ex->getMessage() . ' in ' .$ex->getFile() . ':' . $ex->getLine();
			
			error_log($pdo_err_output, 1, "dobalnltd@gmail.com"); // Send erro to email	
		}
		
        
		if($r[0]['bookings'] >= 10) //only 10 bookings allowed per each hour time. i.e. maximum 10 bookings we accept at 6pm
		{ 		
			//$_POST['day_id'] will start from yesterday. We dont want users to be able to select 6pm option if POST[day time] <= 6
			// We want users to choose today's delivery until 5pm. After 5 All option should be unavailable
			// Easiest way is not to display the POST today in dropdown if time < 5pm
			
			$html = '<div class="alert alert-danger">
				<strong>Sorry!</strong>  this slot time is not available. choose another one.
			</div>';
		} 
		else // Else if date and time is available, INSERT
		{ 
			try {          				
				$dbc->beginTransaction();
				
				// ======= 1st Query; =========
				// we need the day table's date column displayed in user friendly manner i.e. 24 Jun 16. This will be stored in SESSION
				// This session will be INSERTED into the order TABLE in billing.php File
				$q1 = "SELECT 
					slot_name, 
					DATE_FORMAT(day, '%d %b %Y' ) AS daychosen
					FROM slots AS s
					INNER JOIN days AS d
					WHERE s.id = :slotId
					AND d.id = :dayId
				";

				$stmt= $dbc->prepare($q1);    
				$stmt->bindParam('slotId', $_POST['slot_id']);
				$stmt->bindParam('dayId', $_POST['day_id']);
				$stmt->execute();
				$r1 = $stmt->fetch(PDO::FETCH_ASSOC);
				
				//STORE THIS DELIVERY SLOT IN SESSION. WE RUN INSERT query in billing_strip.php File To remind user which slot they chose
				$_SESSION['slot_day'] = $r1['daychosen'];           
				$_SESSION['slot_time'] = $r1['slot_name'];                        
				$_SESSION['day_id'] = $_POST['day_id'];
				$_SESSION['slot_id'] = $_POST['slot_id'];
				
				
				// ========== 2nd query ================
				//Insert this in the [days_slots] TABLE
				$q = 'INSERT INTO days_slots (day_id, slot_id) 
						VALUES (:day, :slot)';

				$stmt = $dbc->prepare($q);                 
				$stmt->bindParam(':day', $_POST['day_id']);
				$stmt->bindParam(':slot', $_POST['slot_id']);    
				$stmt->execute();

				// if Commit ok, redirect
				// if($stmt->execute() ) {
				if($dbc->commit() ) {
					$location = 'https://' . BASE_URL . 'billing_stripe.php';
					header("Location: $location");
					exit();
				}
				
			} catch (Exception $exc) {
				// echo $exc->getTraceAsString();
				$dbc->rollBack(); // if TRANSACTION failed, rollback
				exit('We apologize, an exception occured. Plase let us know this error');
			}

		}
		
	} 
	else 
	{
		//user probably did not select any time slot
		$html = '<div class="alert alert-danger"> <strong>Please!</strong> choose a time slot for your delivery	</div>';
	}
	
	
}




//======= HTML =========
//======= HTML =========
include(INCLUDES. 'header.php');
?>
<div class="container">  
    
    <div class="row">
        <div class="col-12" style="padding: 20px 0 16px 10px;">
            <h1>Please choose your FREE delivery date and time slot</h1>
        </div>
    </div>
    
    
    <div class="row">           
        <style type="text/css">
            input[type="radio"] {
                width: 50px; /* expand clickable area */
                cursor: pointer;
            }
        </style>
            <form id="myForm" method="post" action="">
                 
                <div class="col-4" style="background-color: #ECECEC;"> 
				    <label for="day_id"><strong> Select delivey date </strong></label>
                    <select name="day_id" class="form-control">
                        <?php  
						$r = DeliverySlots::displayDates($dbc);

                        if($r) {
                            foreach ($r as $k => $array) {	          
                                $day = date("d-M-Y - D ", strtotime($array['day']) );	
                                echo '<option value="'.$array['id'].'">' .$day. '</option>';
                            }
                        }  
                        ?>
                    </select>      
                    
                    <br/>
					
                    
                    
                    <fieldset>
                      <legend>Time Slot: </legend>
                    
                        <div>
                            <label for="6pm - 7pm">
                                <input type="radio" name="slot_id" value="1" id="inlineRadio1">6pm - 7pm	
                            </label>                        					
                        </div>

                        <div class="radio">
                            <label for="7pm - 8pm">
                                <input type="radio" name="slot_id" value="2">7pm - 8pm
                            </label>                        
                        </div>

                        <div class="radio">
                            <label for="8pm - 9pm">
                                <input type="radio" name="slot_id" value="3">8pm - 9pm	
                            </label>                        					
                        </div>

                        <div class="radio">
                            <label for="9pm - 10pm">
                                <input type="radio" name="slot_id" value="4">9pm - 10pm
                            </label> 
                        </div>   

                        <div>
                            <br/>
                            <input class="btn btn-primary" type="submit" value="Choose" />
                        </div>
                         </fieldset>
					
                </div>
				

                <div class="col-6" >
                    <div id="result">
                        <?php echo isset($html) ? $html : ''; ?>
                    </div>                
                </div>
                
                <div class="col-2">
                    
                </div>
                 
            </form>
        </div>
    
        <div class="row">
            <div class="col-12">            
                    <p style="color:#e27900; font-style: italic;">
					Please   send us an email if you need your delivery in any other days.</p>					 
					 <div><strong> Email: alphina.uk@gmail.com </strong></div>
            </div>
        </div>
        
</div><!-- container -->


<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 

<script type="text/javascript">
/*     
        $(document).ready(function() {
            
            $('#myForm').on('submit', function(e) {
                //alert('inside');
                
                e.preventDefault(); 
                var $currentForm = $(this);
                
                var $inputs = $currentForm.find("input, select, button, textarea");
                var formData = $currentForm.serialize();
                
                // disable the inputs for the duration of the Ajax request.
                // Note: we disable elements AFTER the form data has been serialized.
                // Disabled form elements will not be serialized.
                //$inputs.prop("disabled", true);
                

                     
                // Fire off the request to /form.php
                request = $.ajax({
                    url: "/slots-ajax-process.php",
                    type: "post",
                    data: formData
                });
                
                request.done(function(response, textStatus, jqXHR) {                    
                    var parsedRespon = jQuery.parseJSON( response );
                    
                    if(parsedRespon.status == 'success') {  
                        
                        //console.log('successful');
                        $('#result').html(parsedRespon.message);
                        
                    } else if(parsedRespon.status == 'failed') { 
                        
                        $('#result').html(parsedRespon.message);                        
                    }
                    
                }); //Done
                
                
                // Callback handler that will be called on failure
                request.fail( function(jqXHR, textStatus, errorThrown) { 
                   console.error("The following error occurred: " + textStatus, errorThrown);                   
                   $("#result").append('<div class="alert alert-danger">Ajax error occured! Please report this to Customer Services</div>');                   
                });

                // Callback handler that will be called regardless if the request failed or succeeded
                request.always(function () {
                    // Reenable the inputs
                    //$inputs.prop("disabled", false);
                }); 
                
                
            });//Onclick
            
        });//Ready
   */
  
</script>    

</body>
</html>

