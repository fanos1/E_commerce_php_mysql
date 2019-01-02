
<div class="container">		

	<div class="col-6">
		<?php // If it's safe to change the password, show the form:
		if (empty($reset_error)) { ?>    
			<form action="reset.php" method="post" accept-charset="utf-8">
				<fieldset>
					<legend>New Password</legend>
					<input class="form-control" type="password" name="pass1" id="pass1" />
					<label for="password" class="sr-only">Password</label>
					<small> Must be at least 6 characters long, with at least one lowercase letter, one uppercase letter, and one number</small>
					<input class="form-control" type="password" name="pass2" id="pass2" />                    
					<label for="confirm password" class="confirm password">Confirm password</label>					
					<div>
						<input type="submit" name="submit_button" value="Change &rarr;" id="submit_button" class="btn btn-success" />
					</div>
				</fieldset>				
			</form>            
		<?php } else { ?>
				<div class="alert alert-danger"> <?php echo $reset_error; ?></div>
		<?php } ?>            
	</div>
	
	<div class="col-6">
		<?php if (!empty($html)) { ?>
				<div class="alert alert-success"> <?php echo $html; ?> </div>
		<?php } ?>
		
			
		<?php if (!empty($pass_errors['pass2']) ) { ?>
				<div class="alert alert-danger"> 
					<?php echo $pass_errors['pass2']; ?> 
				</div>
		<?php } ?>

		
		<?php if (!empty($pass_errors['pass1']) ) { ?>
				<div class="alert alert-danger"> 
					<?php echo $pass_errors['pass1']; ?> 
				</div>
		<?php } ?>
	</div> 
	
</div>