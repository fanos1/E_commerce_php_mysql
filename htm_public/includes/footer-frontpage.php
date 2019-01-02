       


    
<footer id="footer">
    
    <div class="container">    
        <div class="col-4">
            <h3 class="title">About us</h3>
            <ul class="list-unstyled">
                <li>
                    <a href="/contact-us.php" title="About Us">Who we are</a>
                </li>                                   

                <li> 
                    <a href="/contact-us.php" title="contact us">Contact us</a> 
                </li>
            </ul>
        </div>
        <div class="col-4">
            <h3 class="title">Product</h3>
            <ul class="list-unstyled">                                    
                <li>
                    <a href="/static-pages/fresh-turkish-coffee.php" title="fresh turkish coffee">Fresh Turkish Coffee</a>
                </li>
                <li>
                    <a href="/static-pages/wholesale.php">Wholesale</a>
                </li>
            </ul>
        </div>
        <div class="col-4">
            <h3 class="title">Support</h3>
            <ul class="list-unstyled">                                    
                <li>
                    <a href="/static-pages/terms-and-conditions.php" title="terms of service">Terms ofservices</a>
                </li>
                <li>
                    <a href="/static-pages/privacy.php" title="privacy">Privacy</a>
                </li>
            </ul>
        </div>        
        
    </div>
    
    
    <div class="container" style="text-align: center;">
        <hr />        
        <div class="col-12">
            <a href="https://www.facebook.com/Dobaln-141425666495899/" title="to facebook page" target="_blank">
                <img src="img/facebook-icon.png" alt="facebook icon" />
            </a>
        </div>

    </div>



    <div class="container">
        <hr />        
        <div class="col-12">
            <small class="copyright"> Copyright @ 2017 Fresc Ltd.</small>
            <span class="pull-right"><small>Site by irfan</small></span>
        </div>
    </div>
    <style type="text/css">
        img#fincan {
            opacity: 0; /* starte from 0 */
        }
    </style>

</footer>

<script  src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>    
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>  


<script type="text/javascript">
    
    $(document).ready(function() {
        
        var $fincan = $("#fincan"),
                $daily = $("h1").eq(0), //the first h2 in the document
                $fresh = $("h2").eq(0),
                $turkish = $("h3").eq(0),
                $bg = $("#bg"),
                tl;


        CSSPlugin.defaultTransformPerspective = 800; //apply the same transformPerspectiveto every element that will be animated in 3D space        
        //transformOrigin:"center top" GIVES flipping down from the top EFFECT

        tl = new TimelineLite();
        tl//.from($bg, 0.5, {opacity:0})
            //.from($bg, 1, {opacity: 0, rotationX: -90, transformOrigin: "center top"}, 1.5)
			// .from($bg, 1, {opacity: 0, rotationX: -90}, 1.5)
			.to($bg, 1, {opacity: 1, rotationX: 0}, 1.5)
            .from($daily, 1, {opacity: 0, x: -200, ease: Back.easeOut})
            .from($fresh, 1, {opacity: 0, x: 200, ease: Back.easeOut}, "-=0.25")
            .from($turkish, 1, {opacity: 0, x: -200, ease: Back.easeOut}, "-=0.25")
            .fromTo($fincan, 0.8, {opacity: 0, y: 50}, {opacity:1} )
            .staggerFrom("section#3img .col-sm-4", 1, {opacity: 0, yPercent: 100, ease: Back.easeOut}, 0.3, "-=3")
        ;
        
        
        /* 
        //var LoginTween = TweenMax.from("#myModal", 1, {rotation:"20_cw", ease:Bounce.easeOut, paused:true});
        //var LoginTween = TweenMax.from("#myModal", 1, {rotation:90, ease:Bounce.easeOut, paused:true});        
        var LoginTween = TweenMax.from("#myModal", 1, {rotation:90, transformOrigin:"left top", ease:Bounce.easeOut, paused:true});        
         $('#login-trigger').on('click', function(e) {
             LoginTween.play();
        });//End onclick
		*/



			var tracker = "hidden"; // default

			
			var menu_icon = document.getElementById('menu-icon');			
			var mbMenuCont= document.getElementById('mb-menu-container');

			menu_icon.onclick = function() {
				if (tracker == "hidden") {						
					mbMenuCont.style.display = "block";					
					tracker = "showing";
				} else if (tracker == "showing") { 					
					mbMenuCont.style.display = "none";
					tracker = "hidden";
				}	
			};
			

			
			/* 
			$mbMenuContainer = $("#mb-menu-container");
			
			$("#menu-icon").click(function() {
				
				if (tracker == "hidden") {					
					// mb-menu-container.style.display = "block";
					$("#mb-menu-container").css("display", "block");
					tracker = "showing";
					
				} else if (tracker == "showing") {
					$("#mb-menu-container").css("display", "none");
					tracker = "hidden";
				
				}				
			}); 
			*/ 


			
			
    });
</script>


<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>



<script defer type="text/javascript">
	 {        
      "@context": "http://schema.org",
      "@type": "WholesaleStore",
      "image": "https://www.alphina.uk/img/fresh-nuts.jpg",
      "url": "https://www.alphina.uk",
      "logo": "https://www.alphina.uk/img/logo-medium.png",
      "description":"Fresh raw walnuts, almonds and pistachios for sale. Low Prices! Free delivery in London",
      "name": "Alphina wholesale and retail online store",  
          "telephone": "+447429649179",
      "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.5",
            "bestRating": "5",
            "worstRating": "1",
            "reviewCount": "100"
      },
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Coomercial Road",
        "addressLocality": "Aldgate",
        "addressRegion": "London",
        "postalCode": "E1 1NL",
        "addressCountry": "UK"
      },              
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 51.514717,
        "longitude": -0.063170
           
      },
      "contactPoint": [{ 
          "@type": "ContactPoint",
          "telephone": "+447724557911",
          "contactType": "customer service"
        }],          
      "potentialAction":{
          "@type":"BuyAction",
          "target":{
              "@type":"EntryPoint",
              "urlTemplate":"https://www.alphina.uk/nuts/6",
              "inLanguage":"en-UK"         
            },
            "result":{
              "@type":"Order",
              "name":"Order walnuts. Low Prices!"
            }
      },
      "priceRange": "$"
        
    }   	
</script>

</body>
</html>
