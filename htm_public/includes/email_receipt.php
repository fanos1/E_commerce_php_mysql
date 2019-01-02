<?php
// This script sends a receipt out in HTML format.


//require './classes/PHPMailerAutoload.php';
require ROOT.'/classes/class.phpmailer.php';
//require './classes/class.smtp.php';
//require './classes/class.pop3.php';


// Get the cart contents for the confirmation email:
$row = Order::get_order_contents($dbc, $_SESSION['order_id'] );

$shippAddress  = Order::get_customer_details_by_orderID($dbc, $_SESSION['order_id']);


if(isset($_SESSION['slot_day'])) {
    $deliv_slot = 'Your Delivery Slot :: '. $_SESSION['slot_day']. ', '. $_SESSION['slot_time'] ;
} else {
    $deliv_slot = 'We post items within 3 working days';
}



$shippngAddress = '
<div style="text-align: left;">'.
     $shippAddress[0]['first_name']. ' '. $shippAddress[0]['last_name'] . '<br/>'. 
    $shippAddress[0]['address1']. '<br/>'. 
    $shippAddress[0]['city'] . '<br/>' . 
    $shippAddress[0]['post_code'] . '<br/>'.
    $shippAddress[0]['phone'] . ' - '.$shippAddress[0]['email']  .
'</div>';


$items_title = '
    <tr>
        <td align="center" style="border: 1px solid #666;  padding: 5px;"><strong>ITEM</strong></td>
        <td align="center" style="border: 1px solid #666;  padding: 5px;"><strong>QTY</strong></td>
        <td align="center" style="border: 1px solid #666;  padding: 5px;"><strong>PRICE</strong></td>
        <td align="center" style="border: 1px solid #666;  padding: 5px;"><strong>SUBTOTAL</strong></td>
    </tr>
';

$items = '';
foreach ($row as $array) { 
     $items .= '
         <tr>
            <td align="center" style="border: 1px solid #666; padding: 5px;">' . $array['category'] . '-' . $array['name'] . '-'.$array['size'].  '</td>
            <td align="center" style="border: 1px solid #666; padding: 5px;">' . $array['quantity'] . '</td>
            <td align="center" style="border: 1px solid #666; padding: 5px;">
				&pound; '. number_format($array['price_per']/100, 2) . '</td>
            <td align="center" style="border: 1px solid #666; padding: 5px;">
				&pound;' . number_format($array['subtotal']/100, 2) . '</td>
        </tr>
     ';      
}

$shipping = '
<tr>
    <td align="right" style="border: 1px solid #666; padding: 5px;">SHIPPING: </td>
    <td align="center" style="border: 1px solid #666; padding: 5px;">&pound;'.number_format($row[0]['shipping']/100, 2).'</td>
</tr>  
';


$total = '
<tr>
    <td align="right" style="border: 1px solid #666; padding: 5px;">TOTAL: </td>
    <td align="center" style="border: 1px solid #666; padding: 5px;">
		&pound;'. number_format($row[0]['total']/100, 2)  .'</td>
</tr>';







$to = $_SESSION['email'];

// =============== PHP MAILER ============
$mail = new PHPMailer;

$mail->From = "info@XXX.uk";
$mail->FromName = "XX Foods";
//$mail->setFrom('from@example.com', 'Mailer');

//$mail->addAddress('irfankissa@yahoo.com', 'Joe User');                
$mail->addAddress($_SESSION['email']);                                                  
$mail->addReplyTo('info@xxx.uk', 'Information Reply To');
$mail->addCC('xxx@gmail.com');
$mail->isHTML(true);                                            // Set email format to HTML :: Send HTML or Plain Text email

$mail->Subject = 'Your Dobaln Order';
$mail->Body    = '
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title></title> 
	<style>
		/* ====== Start reset ===== */
		body {
		margin: 0; 
		padding: 0;
		}
		table, td {
		border-collapse: collapse; 
		border-spacing: 0;
		margin: 0;
		padding: 0;
		}
		img { /* Making images [block] prevents the gap that often appears below images. Setting the [line-height] further ensures that the images fit  into the layout. We also want to remove any borders from hyperlinked images. */
		display: block;
		border: 0 none;
		outline: none;
		text-decoration: none;
		line-height: 100%;
		}

		#wrapper {
			margin: 0 auto;
			min-width:320px; 	
			max-width: 640px; 	
			display: block; 	
			color: #4d4d4d; 
			background-color: #eeeeee;
		}      
        .col-50pc { 		
			max-width: 320px;			
			display: inline-block; 
			vertical-align: middle;
		}
        
		h1, h2, h3, p {
			font-family: sans-serif;
		}
        
		.header td {
			padding-top: 16px;
            color: #fff;
		}
		a.button {
			font-family: sans-serif;
			font-size: 18px;
			text-decoration: none;
			color: #ffffff;
			margin: 40px auto;
			display: block;
			border: 10px solid #000;
			background-color: #000;
			border-radius: 10px;
			width: 160px;
		}
		
		.footer td {
			padding: 80px 0 20px;
		}
		.footer p, .footer a {
			line-height: 10px;
			font-size: 12px;
			color: #999999;
		}
		.footer a {
			text-decoration: underline;
		}
		.innerWrapper td {
			padding: 20px 10px 0;
		}
		.socialLinksCol {
			display: inline-block;
			vertical-align: middle;
			max-width: 320px;
			padding: 10px;
		}
		
		/* This mso class is only for testing only. it has borders to see how tables will look on OUTLOOK. Not needed in production emails */
		.mso {
			margin: 0 auto;
			border: 3px dashed #999;
		}
</style>
</head>



<body>
    
<!--conditional  <table class="mso" width="640" align="center"><tr><td align="center"> -->
<!--[if (gte mso 9)|(IE)]><table width="640" align="center"><tr><td align="center"><![endif]-->
	<table id="wrapper" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
        <tbody id="wrapper" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
            <tr id="irf">
                <td align="center" width="100%">

                    <!-- ================= 
                    header 
                    ================ -->
                    <table  class="header" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                        <tr>
                            <!-- <td dir="rtl" bgcolor="#E27900" align="center" width="100%"> -->
                            <td  bgcolor="#E27900" align="center" width="100%">
                                <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                                    <tr>
                                        <td align="center" width="100%">
                                            <h1>Your Order</h1>
                                            <p>Thank you for choosing us!</p>
                                            <a class="button" href="http://www.dobaln.co.uk/" target="_blank">Dobaln Foods</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>   
                        </tr>
                    </table>



                    <!-- ===========
                     ORDER TABLE 
                    ============ -->
                    <table  cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                        <tr>
                            <td bgcolor=""  width="100%" style="padding: 5px;">
                                <table  cellpadding="0" cellspacing="0" border="0"  width="100%">
                                    <!-- plub products TITLE -->'. $items_title .'
                                    <!-- plug products -->'. $items .'
                                    <!-- PLUG shipping -->' . $shipping .'
                                    <!-- PLUG TOTAL -->' . $total .'
                                    
                                </table>

                            </td>
                        </tr>
                    </table>




                    <!-- ============= 
                    content table 
                    =============== -->
                    <table  class="innerWrapper" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                        <tr>
                            <td align="center" width="100%">
                                <h2>Order Number : '.$_SESSION['order_id'].'</h2>
                                <p>'.$deliv_slot.' </p>



                                <!-- =============== 
                                module 1 
                                ========== -->
                                <table class="module" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                                    <tr>
                                        <td dir="rtl" align="center" width="100%">
                                <!-- conditional  <table class="mso" width="600"><tr><td width="50%" align="center" class="mso">  -->
                                <!--[if(gte mso 9)|(IE)]><table width="600"><tr><td width="50%" align="center"><![endif]-->
                                            <div class="col-50pc">
                                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                                                <tr>
                                                    <td align="center" width="100%">
                                                        <img src="img/image1.png" width="150" alt="dobaln" />
                                                    </td>
                                                </tr>
                                            </table>
                                            </div>
                                <!--conditional    </td><td width="50%" align="center" class="mso"> -->
                                <!--[if(gte mso 9)|(IE)]></td><td  width="50%" align="center"><![endif]-->
                                            <div class="col-50pc">
                                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                                                <tr>
                                                    <td align="center" width="100%">
                                                        <h3>Shipping Address</h3>
                                                        <p>'. $shippngAddress .' </p>
                                                    </td>
                                                </tr>
                                            </table>
                                            </div>
                                <!-- conditional   </td></tr></table>  -->
                                <!--[if(gte mso 9)|(IE)]></td></tr></table><![endif]-->
                                        </td>
                                    </tr>
                                </table>                                
                            </td>
                        </tr>
                    </table>



                    <!-- ============== 
                    footer table 
                    ============== -->
                    <table style="margin-top: 10px;"  class="footer" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                        <tr>
                            <td align="center" width="100%">
                                <p>Dobaln foods</p>
                                <p>&#169; Dobaln.</p>							
                            </td>
                        </tr>
                    </table>



                </td>
            </tr>
        </tbody>
	</table>
<!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
<!--conditional </td></tr></table> -->

</body>
</html> ';                

$mail->AltBody = 'Thank you for your order! '; //This is the body in plain text for non-HTML mail clients

if(!$mail->send()) {
    //throw new SignUpEmailException('Error sending confirmation' .' email: ' .$mail->ErrorInfo );
    echo 'Message could not be sent.';
    // echo 'Mailer Error: ' . $mail->ErrorInfo;
    exit('cik');
} 



