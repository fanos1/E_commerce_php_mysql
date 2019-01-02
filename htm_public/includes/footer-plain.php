       

<footer id="footer" >
        
    <div class="container">
            <hr />

            <div class="col-4">                
                <h3 class="title">About us</h3>
                <ul class="list-unstyled">
                    <li>
                        <a href="/static-pages/about-us.php">About Us</a>
                    </li>                                   
                    <li>
                        <a href="/contact-us.php">Contact us</a>
                    </li>
                </ul>
            </div>

            <div class="col-4">                
                <h3 class="title">Products</h3>
                <ul>                                                                       
                    <li>
                        <a href="/static-pages/fresh-turkish-coffee.php">Fresh Turkish Coffee</a>
                    </li>
                    <li>
                        <a href="/static-pages/wholesale.php">Wholesale</a>
                    </li>                                    
                </ul>
            </div>

            <div class="col-4">                
                <h3 class="title">Support</h3>
                <ul>                                    
                    <li>
                        <a href="/static-pages/terms-and-conditions.php">Terms of services</a>
                    </li>
                    <li>
                        <a href="/static-pages/privacy.php">Privacy</a>
                    </li>
                </ul>
            </div>

    </div>
    

	
    <div class="container center">
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
            <small class="copyright"> Copyright @ 2017 Dobaln Ltd.</small>
            <span><small>Site by iKissa</small></span>
        </div>
    </div>


</footer>





<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>    	

<script type="text/javascript">
    
    $(document).ready(function () {

        CSSPlugin.defaultTransformPerspective = 800;
        //transformOrigin:"center top" GIVES flipping down from the top EFFECT

        tl = new TimelineLite();

        tl
        // .from("div#products .col-md-3", 1, {opacity:0, x:-200, ease:Back.easeOut} )				  
        .staggerFrom("div.products .col-4", 1, {opacity: 0, x: -100, ease: Back.easeOut}, 0.2)
        //.from($fresh, 1, {opacity:0, x:200, ease:Back.easeOut}, "-=0.25")                                 
        ;

        //var LoginTween = TweenMax.from("#myModal", 1, {rotation:90, transformOrigin:"left top", ease:Bounce.easeOut, paused:true});        
        //$('#login-trigger').on('click', function(e) {
        //    LoginTween.play();
        //});
        
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


</body>
</html>
