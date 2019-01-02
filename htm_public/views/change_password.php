
<div class="container" style="padding-top: 3em;">
    
    <div class="row">
        <div class="col-lg-12">
            <h1>Change Your Password</h1>
        </div>
    </div>
    
    
    <div class="row">        
        <div class="col-lg-6">            
           
            <form action="change_password.php" method="post" accept-charset="utf-8">
			
				<input type="hidden" name="formtoken" id="formtoken" value="<?php echo isset($formToken) ? $formToken : ''; ?>" />
				<p style="display: none;"> <input type="text" name="med" id="med" value=""> </p>
				
				
                <div class="form-group">		
                      <input class="form-control" type="password" name="current" id="current" placeholder="Current Password" />
                </div>
                <div class="alert alert-info">
					<strong>Note!</strong> New password must be at least 6 characters long, with at least one lowercase letter, one uppercase letter, and one number.
				</div>

                <div class="form-group">		
                      <input class="form-control" type="password" name="pass1" id="pass1" placeholder="New Password" />
                </div>		
                <div class="form-group">		
                      <input class="form-control" type="password" name="pass2" id="pass2" placeholder="Confirm New Password" />     
                </div>		

                <input type="submit" name="submit_button" value="Change &rarr;" id="submit_button" class="btn btn-danger" />		
            </form>
        </div>
        
        <div class="col-lg-6">
            <?php 
                if($pass_errors) {                    
                    foreach ($pass_errors as $k => $v) {
                        echo '<div class="alert alert-danger">'. $v. '</div>';
                    }
                }
            ?>
        </div>        
        
    </div>
</div>

