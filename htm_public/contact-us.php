<?php
session_start();
$page = 'booking';

//require('/path/to/recaptcha/autoload.php');
//require_once './includes/ReCaptcha/autoload.php';

require_once('./includes/ReCaptcha/ReCaptcha.php');
require_once('./includes/ReCaptcha/RequestMethod.php');
require_once('./includes/ReCaptcha/RequestParameters.php');
require_once('./includes/ReCaptcha/Response.php');
require_once('./includes/ReCaptcha/RequestMethod/Post.php');
require_once('./includes/ReCaptcha/RequestMethod/Socket.php');
require_once('./includes/ReCaptcha/RequestMethod/SocketPost.php');


//require './classes/PHPMailerAutoload.php';
require './classes/class.phpmailer.php';
//require './classes/class.smtp.php';
//require './classes/class.pop3.php';

require( __DIR__ . '/../API_keys.php');	

$pageTitle = 'Contact Us';

$errors = array();

$siteKey = $Goog_siteKey;
$secret = $Goog_secret;	
$lang = 'en'; // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check if the form submited is our own form
    if (!isset($_POST['formtoken1']) || $_POST['formtoken1'] !== $_SESSION['formtoken1']) {
        //$formtoken should always be set, if it is not set, create error
        $errors['token'] = '<div class="alert alert-danger">The form submited is not valid. Please try again or contact support for additional assistance.</div>';
    }

    $honeypot = trim(strip_tags($_POST['med']));
    if (!empty($honeypot)) { //!empty means bots must have populated form submited 
        $errors['pot'] = '<div class="alert alert-danger">The form submited is not valid. Please try again or contact support for additional assistance.</div>';
    }

    if ($siteKey === '' || $secret === '') {
        exit('Recaptach Error,');
    }


    if (isset($_POST['g-recaptcha-response'])) 
	{
        // The POST data here is unfiltered because this is an example.
        // In production, *always* sanitise and validate your input'
        // If the form submission includes the "g-captcha-response" field
        // Create an instance of the service using your secret
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);

        // Make the call to verify the response and also pass the user's IP address
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        if ($resp->isSuccess()) { // If the response is a success, that's it!   
            
            if (preg_match('/^[A-Z \'.-s]{2,45}$/i', $_POST['name'])) {
                $name = strip_tags($_POST['name']);
            } else {
                $errors['name'] = 'Please enter your name!';
            }
            
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === $_POST['email']) {
                $email = strip_tags($_POST['email']);
            } else {
                $errors['email'] = 'Please enter a valid email !';
            }
            
            if (preg_match('/^[0-9]{8,16}$/', $_POST['phone'])) {
                $phone = strip_tags($_POST['phone']);
            } else {
                $errors['phone'] = 'Please enter your phone';
            }
         

            $textAreaMsg = '';
            if (empty($_POST['textmessage'])) {
                $textAreaMsg = 'No Additional Info Requested';
            } else {
                //sanitize
                $textAreaMsg = strip_tags($_POST['textmessage']);
            }


            //===================== 
			// Send the email if no error 
			// ==========================
            if (empty($errors)) {

                $table = '
                <table class="table">
                    <thead>
                      <tr>                        
                        <th>Enquiry From Fresc website, form submitted by user</th>                    
                      </tr>
                    </thead>
                    <tbody>
                        <tr>                                              
                          <td>name: <strong>' . $name . '</strong></td>
                        </tr>
                        <tr>                      
                          <td>email: <strong>' . $email . '</strong></td>                    
                        </tr>
                        <tr>                                            
                          <td>phone: <strong>' . $phone . '</strong></td>                    
                        </tr>

                        <tr>                                            
                          <td>Any additional information: <strong>' . $textAreaMsg . '</strong></td>            
                        </tr>

                    </tbody>
                </table>                  
                ';
                
                $message = '';
                //$message .= "<b>This is a new HTML message coming from customer.</b>";                
                $message .= "<div>$table</div>";
                
                
					$mail = new PHPMailer;
                    /* 
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'user@example.com';                 // SMTP username
                    $mail->Password = 'secret';                           // SMTP password
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to 
                    * 
                    */
                    $mail->From = "info@dobaln.co.uk";
                    $mail->FromName = "Dobaln Foods";
                    //$mail->setFrom('from@example.com', 'Mailer');

                    //$mail->addAddress('irfankissa@yahoo.com', 'Joe User');                        // Add a recipient
                    $mail->addAddress('irfankissa@yahoo.com');                                      // Name is optional
                    $mail->addReplyTo('info@dobaln.co.uk', 'Information Reply To');
                    // $mail->addCC('irfankissa@yahoo.com');
                    //$mail->addBCC('bcc@example.com');
                    //$mail->addAttachment('/var/tmp/file.tar.gz');                 // Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');            // Optional name

                    $mail->isHTML(true);                                            // Set email format to HTML :: Send HTML or Plain Text email
                    $mail->Subject = 'Contact Request';                
                    $mail->Body    = '<html>
                    <body>
                        <h2>Contact Request!</h2>                        
                        <div>'.$message.'</div>                        
                    </body>
                    </html>';                
                    // $mail->AltBody = 'Please click this link to confirm your registration: '.$confirmationURL; //This is the body in plain text for non-HTML mail clients

                    if(!$mail->send()) {
                        //throw new SignUpEmailException('Error sending confirmation' .' email: ' .$mail->ErrorInfo );
                        echo 'Message could not be sent.';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                        exit('cik');
                        
                    } else {
                        
                        //echo "<h3>Message has been sent</h3>";
                        $sentSuccess = 
                            '<div class="alert alert-success">
                                Thank you!
                            </div>';
                        
                    }
                
                /* 
                 $to = "irfankissa@yahoo.com";
                 $to = "irfankissa@outlook.com";
                 $to = "khurshidmoghal@hotmail.co.uk";        
                 $subject = "New enquiry from Fresc website";            
                 $header = "From:info@fresc.co.uk \r\n";
                 $header = "Cc:irfankissa@outlook.com \r\n";

                 $header .= "MIME-Version: 1.0\r\n";
                 $header .= "Content-type: text/html\r\n";

                 $retval = mail($to, $subject, $message, $header);

                if ($retval) {
                   $success = '<h3 class="alert alert-success">Message sent successfully...We will be in touch soon!</h3>';                    
                } else {
                    echo '<h3 class="alert alert-error" Message could not be sent...</h3>';
                } 
                */
                
            }
           
        } else {

            //User probably did not submit Google reCapthc, user just did not complete the reCAPTCHA            
            //Or it could be another error.
            //echo "<h1>user probably did not subit reCapth, str78</h1>";;
            //  create an error
            $errors['recaptcha'] = 'Makue sure you tick Googles reCapth to prove you are human'; //ERROR not recognized

            /* --------- DEBUG ----------
              // Find out what Error  codes will be returned.
              foreach ($resp->getErrorCodes() as $code) {
              echo '<h3>' , $code , '</h3> ';
              }
              echo '<p>Check the eorro code reference</p>';
              echo '<a href="https://developers.google.com/recaptcha/docs/verify#error-code-reference">
              https://developers.google.com/recaptcha/docs/verify#error-code-reference
              </a>';
             * -----------------------------
             */
        }
    }
}


$_SESSION['formtoken1'] = md5(uniqid(rand(), true));
$formToken1 = htmlspecialchars($_SESSION['formtoken1']);

//=========== HTML ================
//=========== HTML ================
//=========== HTML ================
include './includes/header.php';

//Display any Errors
if (!empty($errors)) {
    echo '<div class="container">
    <div class="col-12">';
        foreach ($errors as $k => $v) {
            echo '<h4 class="alert alert-success">' . $v . '</h4>';
        }
    echo '</div></div>';
}
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <?php echo isset($sentSuccess) ? $sentSuccess : '' ; ?>
        </div>        
    </div>
</div>


<div class="container" style="margin-top: 2em;">	
    
        <div class="col-4">
            <h3>Contact Us</h3>       
            <div class="media">
                <i class="fa fa-home pull-left"></i>
                <div>
                    Alphina Foods.
                    <br> 124 Commercial Road
                    <br />London
                    <br />E1 1NL
                    <br />United Kingdom      
                    <br /> Company No. 10898609 
                </div>
            </div>       
            
            <div style="max-width: 240px; margin-top: 10px;" class="lead">
                We are an online shop based in  London - UK. We offer healthy food with affordable prices to the UK market. 
                <strong> Please contact us for wholesale price list.</strong>
            </div>
        </div>

        
        <div class="col-8"> 
            <form action="" role="form" method="post">
                <input type="hidden" name="formtoken1" id="formtoken" value="<?php echo isset($formToken1) ? $formToken1 : ''; ?>" />   
                <p class="hp" style="display: none;"> <input type="text" name="med" id="med" value=""> </p>

                <div class="form-group">
                    <label for="fullname">Full Name (required)</label>
                    <input type="text" name="name" value="" class="form-control" placeholder="Enter Name" required aria-required="true" />
                </div>
                <div class="form-group">
                    <label for="email">Email address (required)</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required aria-required="true" />
                </div>                    
                <div class="form-group">
                    <label for="telephonenumber">Telephone No:</label>
                    <input type="text" name="phone" class="form-control" placeholder="Telephone">
                </div>
                <!-- 
                <div class="form-group">
                    <label for="firstlineaddress">First Line Address (required)</label>
                    <input type="text" name="address1" class="form-control" placeholder="First Line Address" required aria-required="true" />
                </div> 
                -->

                <p>Any additional information?</p>

                <textarea name="textmessage" cols="40" rows="10" class="form-control"></textarea>

                <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>

                <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?php //echo $lang;  ?>"></script> 

                <p><button type="submit" class="btn btn-default">Submit</button></p>
            </form>
            
            <hr />
        </div>
        
</div>


<?php include './includes/footer.php'; ?>