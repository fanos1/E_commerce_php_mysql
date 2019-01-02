
<!-- ===============================
Slider hidden by default on small screents 
=========================================== -->	
<section class="container" id="slider">            
    <div class="col-12" id="slider-col">
        <h1>
            <a href="/free-delivery.php" title="free delivery">	FREE DELIVERY! </a>
        </h1>		
        <img id="fincan"  src="img/celery-and-grapes-small.png" alt="turk kahvesi" />			
        <div id="bg"> <img src="img/fresh-nuts.jpg" alt="fresh nuts" /> </div> 
    </div>	
</section>    


<!-- =================
    3 messages 
===================== -->
<section class="container" id="messages-3">			
    <div class="col-4 center">						
        <a href="/free-delivery.php" title="free delivery">   FREE DELIVERY IN LONDON!    </a> 
    </div>
    <div class="col-4 center">						
        NO-FUSS RETURN POLICY!
    </div>            
    <div class="col-4 center">
          DOBALN FOODS
    </div>	
</section>




<!-- This section is hidden on small screens, displayed on Desktops -->
<section class="container" id="msg-3">	
    <div class="col-4 center">			
        <div>
            <svg class="icon icon-truck"><use xlink:href="#icon-truck"></use></svg>  
        </div>
        <h3> <a style="color:black;" href="/free-delivery.php" title="free delivery">FREE LOCAL DELIVERY</a> </h3>
        <p>Choose your delivery slot, and wait your delivery to arrive!</p>
    </div>

    <div class="col-4 center">
        <div>
            <svg class="icon icon-credit-card"><use xlink:href="#icon-credit-card"></use></svg>  
        </div>
        <h3>MONEY BACK GUARANTEE</h3>
        <p>We will refund your money if you don't like our products or service!</p>
    </div>

    <div class="col-4 center">			
        <div>
            <svg class="icon icon-coin-pound"><use xlink:href="#icon-coin-pound"></use></svg>  
        </div>
        <h3>PAY CASH UPON DELIVERY</h3>
        <p>No need for credit card. You can pay cash upon delivery. Simple!</p>
    </div> 
</section>





<section class="container" id="3img">

    <div class="col-4 center">
        <a href="/nuts/6" title="nuts fresh cheap">
            <img class="img-responsive" src="/img/cheap-nuts.jpg" alt="Freshly roasted turkish nuts"/>
        </a>
        <h4><strong> Fresh Nuts </strong></h4>
        <p>Order our fresh nuts selection online! Free Delivery and cheaper than Tesco. 
            You can pay cash when we deliver your food if you have no credit card
            or if you don't trust credit cards. 
        </p>
    </div>
    <div class="col-4 center">				
        <!--  <a class="center" href="/fruit-and-vegetables/3" title="coffees">  -->
        <a class="center" href="/hazelnuts/11/" title="hazelnuts">          
            <img class="img-responsive" src="/img/free-fruit-delivery-400x300.png" alt="Free fuit delivery London"/>
        </a>   
        <h4><strong>Hazelnuts Free Delivery! </strong></h4>
        <p>
            Choose the nuts you would like, add them to your basket and wait for the delivery to arrive. We deliver FREE only within some London post codes.
        </p>
    </div> 
    <div class="col-4 center">
        <a href="/coffees/1" title="fruit and veg">
            <img class="img-responsive" src="/img/turkish-coffee-fresh.png" alt="Freshly roasted turkish coffee"/>
        </a>            
        <h4><strong>Freshly roasted Turkish (Greek) Coffee</strong></h4>
        <p>Our Fresc Turkish coffee is roasted daily. 
            Please consume the freshly roasted coffee within 14 days of purchase because we don't use 
        preservatives to keep the coffee last long. </p>
    </div>
</section>



<article class="container" style="margin-top: 3em; background-color: aliceblue;">
			
	<div class="col-12" style="text-align: center;">		
		<h2>FREE Delivery?</h2>	
		<p>Enter your postcode to see if we deliver for FREE to you.</p>
		<?php 
			//just before starting HTML, create a new formtoken
			$_SESSION['formtoken'] = md5(uniqid(rand(), true));
		?>
		<form action="/free-delivery.php" method="post">

			<input type="hidden" name="formtoken" id="formtoken" value="<?php echo htmlspecialchars($_SESSION['formtoken']); ?>" />
			<p style="display: none;"> <input type="text" name="med" id="med" value=""> </p>

			<div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>
				<input type="text" id="" name="postcode" placeholder="M13 9PL">
			</div>
			<br />
			<input type="submit" value="Check Now!" class="btn btn-success" />
		</form>	
	</div>
	
</article>
