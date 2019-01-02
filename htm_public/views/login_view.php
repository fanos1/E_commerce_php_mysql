
<div class="container" style="margin-top: 3em;">
    <div class="row">
        <div class="col-12">
            <h1>Please login to access your delivery address.</h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row" style="padding: 2em 0 2em 0;">
        <div class="col-12">
            
            <form class="form-inline" action="" method="post" accept-charset="utf-8">
			
				<input type="hidden" name="formtoken" id="formtoken" 
                       value="<?php echo isset($_SESSION['formtoken']) ? $_SESSION['formtoken'] : ''; ?>" />
                
				<p style="display: none;"> <input type="text" name="med" id="med" value=""> </p>
				
				<div class="form-group">
					<label class="sr-only" for="email">Email address</label>
					<input type="text" name="email" class="form-control" id="email" required placeholder="Email">                
			    </div>
				<div class="form-group">
					<label class="sr-only" for="password">Password</label>
					<input type="password" name="password" class="form-control" id="password" placeholder="Password">                
				 </div>
              
                <button type="submit" class="btn btn-primary">Sign in &rarr;</button>
              
            </form>
            
			
            <div>
                <hr>
                <?php 
                    if(!empty($login_errors['email']) ) {                     
                        echo '<div class="alert alert-danger">'.$login_errors['email'].'</div>';
                    }  
                    if(!empty($login_errors['password']) ) {                     
                        echo '<div class="alert alert-danger">'.$login_errors['password'].'</div>';
                    } 
                
                    if(isset($verificatFailed)  ) 
                    {                     
                        echo '<h2 class="alert alert-danger"> Are you sure you typed the correct Password? </h2>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>