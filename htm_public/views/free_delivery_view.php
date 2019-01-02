
<div class="container">
    <div class="row">
        <?php 
            echo isset($out) ? $out : ''; 
        ?>
    </div>
</div>


<div class="container">
    <div class="col-4">
    
    </div>
    <div class="col-4">
        <h2>FREE Delivery?</h2>

        <p>Enter your postcode to see if we deliver for FREE to you.</p>

        <form action="" method="post">
		
			<input type="hidden" name="formtoken" id="formtoken" value="<?php echo isset($formToken) ? $formToken : ''; ?>" />
			<p style="display: none;"> <input type="text" name="med" id="med" value=""> </p>
			
            <div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>
                <input type="text" class="form-control" id="" name="postcode" placeholder="M13 9PL">
            </div>
            <br />
            <input type="submit" value="Check Now!" class="btn btn-success" />
      </form>
    </div>
    <div class="col-4">
    
    </div>
    
</div>



<div class="container" style="margin-bottom: 2em;">
    <div class="row">
        <?php 
            // echo $out; 
			if(isset($errors)) {					
				foreach($errors as $k => $v) {
					echo $v;
				}
			}
        ?>
    </div>
</div>
