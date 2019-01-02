
<?php if(isset($emailSent)) { ?>

	<div class="container">
		<div class="col-12">
			<?php echo $emailSent;  ?>
		</div>
	</div>	
	
<?php } else { ?>

	<div class="container">		
		<div class="col-12">            
			<h1>Reset Your Password</h1>
			<p>Enter your email address below to reset your password.</p> 
			<form action="forgot_password.php" method="post" accept-charset="utf-8">
				<fieldset>
					<legend>Your Email?</legend>				
					<input type="text" name="email" class="form-control" /> 
					<label class="sr-only" for="Email">Email </label>
				</fieldset>
				
				<?php  if(isset($pass_errors['email'])) { ?>				
					<div class="alert alert-danger">
							<?php echo  $pass_errors['email']; ?>
					</div>;
				<?php } ?>  
				
				<input type="submit" name="submit_button" value="Reset &rarr;" id="submit_button" class="btn btn-default" />				
			</form> 
		</div>
	</div>
	
<?php } ?>