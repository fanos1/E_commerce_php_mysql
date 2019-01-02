
<div class="container">
	<div class="col-12">		
		<ul class="breadcrumb">
			<li>
				<a title="Bakc to shop page" href="/category.php?id=<?php echo isset($_GET['c']) ? htmlentities(urlencode($_GET['c']) ) : '#';  ?>">&larr; Back</a>
			</li>  
		</ul>
	</div>
</div>


<div class="container">	
    
        <?php
        //ONLY 1 ITEM RETRUNED IS EXPECTED
        foreach ($rows as $k => $array) {

           // $pieces = explode("-", $array['size']);
            ?>    

            <div class="col-6" style="overflow:hidden;">                
				<a href="/img/<?php echo $array['image']; ?>" title="<?php echo htmlspecialchars($array['name']);  ?>">
					<img class="img-responsive" src="/img/<?php echo $array['image']; ?>" alt="<?php echo htmlspecialchars($array['name']); ?>" />
				</a>
            </div>

            <div class="col-6" itemscope itemtype="http://schema.org/Product"  style="margin-top: 3em;"> 
                <h3 itemprop="name"> <?php echo htmlspecialchars($array['name']).' - Best Price'; ?> </h3>

                <!-- item description -->
                <div>
                    <?php echo htmlspecialchars($array['description']); ?>
                </div>        
                <!-- product id -->        
                <div>
                    <?php echo 'Product Code: '. $array['product_code']; ?>
                </div>
                <!-- item price -->
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">            
                    <?php 
						//echo get_price($type, $array['price'], $array['sale_price']); 
						$type = 'goodies';
                        echo '<span itemprop="price">' . get_price($type, $array['price'], $array['sale_price']).  '</span>';                        
						
                    ?>
                </div>
                <div>
                    <?php echo 'Available Stock: ' . get_stock_status($array['stock']); ?>
                </div>  
                
                <div>
                    <strong>Size: </strong> <?php echo htmlspecialchars($array['size']);?>
                </div>

                <!-- review rating 
                <div itemprop="reviews" itemscope itemtype="http://schema.org/AggregateRating">
                    <?php 
                        //untile we have rating stored in database, use following random rating
                        //$starImg = Array("/star-rating-4stars.jpg", "/star-rating-3stars.jpg", "/star-rating-5stars.jpg");
                    ?>
                    <img src="<?php //echo $starImg[array_rand($starImg)]; //Pick one element randomly from Array ?>" alt="star rating image"/>
                    <meta itemprop="ratingValue" content="4" />
                    <meta itemprop="bestRating" content="5" />
                    Based on <span itemprop="ratingCount">4</span> user ratings
                </div> 
                -->
                
                <form action="/cart.php" method="get" class="itemDetails">
                    <input type="hidden" name="action" value="add" />     
                    <input type="hidden" name="product_id" value="<?php echo $array['product_id']; ?>" /> 
                    <!-- Use htmlentities() in caes  product_code value has some tags <script> etc. Sanitize this -->
                    <input type="hidden" name="product_code" value="<?php echo htmlspecialchars($array['product_code']);?>" /> 
                    <!-- urlencode() size incase if it contains characters such as & or spaces -->
                    <input type="hidden" name="size" value="<?php echo urlencode($array['size_id']); ?>" />   

                    <br/>
                    <input class="btn btn-success btn-small" type="submit" value="Add to Cart &rarr;"  /> 
                                       
                </form>

                
                
                <div id="message"> </div>
				
				<div>
					<!-- <a href="https://www.facebook.com/sharer/sharer.php?u=example.org" target="_blank">Share on Facebook</a> -->
					<br/>
					<a href="https://www.facebook.com/sharer/sharer.php?u=dobaln.co.uk/nuts/6" target="_blank">
						<svg class="icon icon-facebook" style="color:#e27900;"> <use xlink:href="#icon-facebook"></use></svg> 
						<span style="color: #e27900;">Share </span>
					</a>
				</div>
            </div> 

        <?php } //FOREACH ?>

  
</div>
  
  


  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  

<script type="text/javascript">

    $(document).ready( function () {

            $('.itemDetails').on('submit', function(e) { 

                
                e.preventDefault(); //When form is submitted prevent it being sent
                e.stopImmediatePropagation(); //Use this as well in case preventDefault() doesnt do the trick

                 var $currentForm = $(this);
                 
                 // Let's select and cache all the fields
                 var $inputs = $currentForm.find("input, select, button, textarea");
                 
                // Serialize form data. Serializ() will put all info from the form and put it into a string ready to send to server
                // it encodes characters that cannot be used in a query string.
                // it will send only successfull form controls, which means it wont send Controls that have been disabled. it wont send the submit, and it wont send controls where no option has been selected
                 var formData = $currentForm.serialize(); 


                 // disable the inputs for the duration of the Ajax request.
                 // Note: we disable elements AFTER the form data has been serialized.
                 // Disabled form elements will not be serialized.
                 //$inputs.prop("disabled", true);

                 // Fire off the request to /form.php
                 request = $.ajax({
                     url: "/cart.php",
                     type: "get",
                     data: formData
                 });

                 // responseText     PROPERTY    ::  TExt-based data returnd
                 // responseXML      PROPERTY    ::  XML data returned
                 // status           PROPERTY    ::  status code                                
                 // done()           METHOD      ::  Code to run if request was successfull
                 // fail()           METHOD      ::  Code to run if request was NOT successfull
                 // always()         METHOD      ::  Code to run if request was successfull OR failed                
                 request.done( function(response, textStatus, jqXHR) {

                    // console.log(response);
                    // alert(response);    // {"status"."success","message"."Item added to basket"}
                    
                     var parsedRespon = jQuery.parseJSON(response); 
                     
                     
                     if(parsedRespon.status == 'success') {     
                        $('div#message').append('<div class="alert alert-success"> Item added to basket! <a href="/cart.php" title="to shopping cart">View Shopping Cart &rarr; </a> </div>');
                     } else if (parsedRespon.status == 'error') {
                        
                         $('div#message').append('<div class="alert alert-success">Error! item not added to cart </div>');
                     } 
                    

                 });

                // Callback handler that will be called on failure
                request.fail( function(jqXHR, textStatus, errorThrown) { 
                    //console.error("The following error occurred: "+textStatus, errorThrown);
                    //alert('failed, We apologize! ');
                    $("#display").append('<div id="response-msg">Ajax error occured!</div>');
                });


                // Callback handler that will be called regardless if the request failed or succeeded
                //request.always(function () {
                    // Reenable the inputs
                //    $inputs.prop("disabled", false);
                //}); 



        });//End on(submit)



    }); //End Ready

</script>