<!-- 
<div id="demo" class="hide-on-desktop" style="color: red;">
	<strong> Item added to cart <a href="/cart.php"> view cart &rarr;</a> </strong>                            
</div> 
-->


<div id="cart">
    <a href="/cart.php">
        <img src="/img/cart.png" width="90" height="94" alt="shopping cart" />
    </a>    
</div> 


<div class="container hide-abv-1200 center">
	<div id="mb-item-added">
		<div class="col-9">
			Item Added!  &rarr; <br/>
			<a style="color: red;" href="/cart.php" title="to shopping cart">Please view your<strong> Shopping cart!</strong></a>			
		</div>
		<div class="col-3"> 
			<button type="button" class="btn btn-warning" id="mb-item-add-close">Close!</button>  
		</div>
	</div>
</div>


<div class="container">     
    <div class="col-12 center">
        <h1>
        <?php                
            $title = isset($rows[0]['h1_title']) ? htmlspecialchars($rows[0]['h1_title']) : ''; 
            echo $title;                
        ?>
        </h1>
        <?php
            if ($title == 'Fruits and Vegetables') {
                echo "<h3>Fruit delivery is available only within London - Hackney postcodes!</h3>";
            }
        ?>                
    </div>
</div>


 
<div class="container products center">
    
        <?php        
        if (is_array($rows) || is_object($rows)) {            
           
            $howManyItems = 1; //A row can have 4 columns. We create a new row if more than 4 items returnd from datbase            
            $zIndex = (int) 10;
            
            // images should be 400x400
            
            foreach ($rows as $k => $array) 
            {   
                $thePrice = get_price($type, $array['price'], $array['sale_price']);
                
				$url_string = 'pid=' . urlencode($array['product_id']) . '&c=' . urlencode($_GET['id']); // Dont escape special chars like &
				
                echo '
                    <div class="col-4" itemtype="http://schema.org/Product" itemscope="">
                        <div class="thumbnail">                
                            <a itemprop="url" href="/product-details.php?'.htmlentities($url_string).'" title="'.htmlspecialchars($array['name']).'">
                                <img itemprop="image" src="/img/'.htmlspecialchars($array['image']).'"  alt="' . htmlspecialchars($array['name']) . '" width="200" height="200" />
                                
                                <span class="top-abslt-img"> 
                                    <img src="/img/'.htmlspecialchars($array['image']).'" alt="'.htmlspecialchars($array['name']).'" width="200" height="200" /> 
                                </span>
                                
                                <div class="caption">
                                    <div itemprop="name">'.htmlspecialchars($array['name']).'</div>
                                        
                                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <meta itemprop="priceCurrency" content=”GBP” />
                                        <span itemprop="price" content="'.$thePrice.'">Price: £'.$thePrice.'</span>                                            
                                    </div>                                    
                                    <div><strong>'.htmlspecialchars($array['size']).'</strong></div>
                                    

                                    <form action="/cart.php" method="get" class="myForm">                
                                        <input type="hidden" name="action" value="add" />                   
                                        <input type="hidden" name="product_id" value="'. $array['product_id']. '" />   
                                        <input type="hidden" name="product_code" value="'. htmlspecialchars($array['product_code']). '" />   
                                        <input type="hidden" name="size" value="'. $array['size_id']. '" />       
                                        <br/>
                                        <input class="btn btn-success" type="submit" value="Add to Cart" data-toggle="collapse" data-target="#demo" />          
                                    </form>  
                                </div>
                            </a>                        
                            
                           
                        </div>  
                        
                        <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">                            
                            <div style="display:none;">
                                <span itemprop="reviewCount">1</span>
                                <span itemprop="ratingValue">5</span>
                            </div>
                        </div>
                       
                    </div>';
                
                
            }              

        }
    
        ?>
    
</div><!-- container products --> 




<script type="text/javascript">
    $(document).ready(function(){
        
       // alert(screen.width); // 1920
        
        
        if(screen.width < 400 ) {
            // Pass PHP var to javascript
            // http://www.dyn-web.com/tutorials/php-js/scalar.php

             // var val = "<?php  // echo $modal ?>";            
             // $("#mod").html(val);
             
        }
    
        
    });
    
 
</script>

