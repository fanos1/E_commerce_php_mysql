       
        
<footer class="footer">
    
 
    <div class="container">
        <hr />

        <div class="col-4">
            <small class="copyright"> Copyright @ 2017 Dobaln Ltd.</small>                    
        </div>
        <div class="col-4">
            <img src="/img/footer-cards.png" alt="image" />
        </div>
        <div class="col-4">
            <span><small>Site by iKissa</small></span>
        </div>
    </div>	
</footer>
  
 
 



<script  src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>    
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>    

<script type="text/javascript">
    
    $(document).ready( function () {

            CSSPlugin.defaultTransformPerspective = 800; 
            //transformOrigin:"center top" GIVES flipping down from the top EFFECT
            tl = new TimelineLite();
            tl.staggerFrom("div.products .col-4", 1, {opacity:0, x:-100, ease:Back.easeOut}, 0.2 );				  


            var cartElement = $("div#cart").offset(); //Get the position of <idv id=cart> relative to documnetn


            //$('#myForm').on('submit', function(e) {
            $('.myForm').on('submit', function(e) { 

                //alert('inside handler');

                e.preventDefault(); //When form is submitted prevent it being sent
                //e.stopImmediatePropagation(); //Use this as well in case preventDefault() doesnt do the trick

                 var $currentForm = $(this);
                 // Let's select and cache all the fields
                 var $inputs = $currentForm.find("input, select, button, textarea");
                 var formData = $currentForm.serialize();

                 //========== GREENSOCK ============
                 var formParents = $currentForm.parents();
                 //console.log(formParents.parents() );     //[0]=><div class=thumbnail>    [1]=><div class=col-md-3> ...
                 //console.log(formParents.parents()[0] );  //<div class=thumbnail>
                 //console.log(formParents.parents()[1] );    //<div class=col-md-3>

                 /* 
                 var thumbnail = $(formParents.parents()[1]);//Select <div class col-md-3> and put it inside jQuery objct                     
                 //var currentImg = $(thumbnail.find('img.one') );  //find <span class=one> which is inside <div class=col-md-3>
                 var currentImg = $(thumbnail.find('div#one') );  //find <span class=one> which is inside <div class=col-md-3>
                 var currentImgPos = $(currentImg).offset();//offset() gets postion relative to document
                 */

                var thumbnail = $(formParents.parents()[1]);    //Select <div class col-md-3> and put it inside jQuery objct   
                // console.log(thumbnail);
                var currentImg = $(thumbnail.find('span.top-abslt-img'));
                var currentImgPos = $(currentImg).offset();		//offset() gets postion relative to document
                currentImg.css("z-index", "9999"); //Set z-index of current image so it will move on top of the bottom images


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

                     var parsedRespon = jQuery.parseJSON( response ); 

                     if(parsedRespon.status == 'success') {            
                        //alert('success');                                                                                
						// $mb-item-added = $("#mb-item-added");
						$("#mb-item-added").css("display", "block");
						
                        TweenMax.from("div#cart", 1, {yPercent:20,  ease:RoughEase.ease.config({strength:3, points:10})} );                            
                        /* 
                        tl = new TimelineLite();                                                       
                        tl.to("#display", 1, {autoAlpha:1} )
                        tl.to("#display", 1, {autoAlpha:0}, "+=2" );
                        */


                        //$("#display").append('<div id="response-msg" style="color:red;">Item Added</div>').fadeIn(200).fadeOut(2000, function() { $(this).remove(); });

                        var destinationLeft = cartElement.left - currentImgPos.left;
                        var destinationTop = cartElement.top - currentImgPos.top;

                        //currentImg.css("z-index", "9999999");//Set z-index of current image so it will move on top of the bottom images

                        function myFunction() {
                            tl.set(currentImg, {clearProps:"all"}); //clear top and left: properties, otherwise next time user clicks, the 2nd image will not be on top of the other
                        }

                        tl = new TimelineLite({onComplete:myFunction});
                        //tl.to( currentImg, 3, {opacity:0, scale:0.3, bezier: [{left:10, top:-30}, {left:0, top:0}, {left:800, top:30}], ease:Power1.easeInOut} );                            
                        //tl.to( currentImg, 3, { scale:0.3, bezier: [{left:10, top:-30}, {left:0, top:0}, {left:destinationLeft, top:destinationTop}], ease:Power1.easeInOut} );
                        tl.to( currentImg, 3, {scale:0.3, autoAlpha:0, bezier: [{left:10, top:-30}, {left:0, top:0}, {left:destinationLeft-40, top:destinationTop-10}], ease:Power1.easeInOut} );

                     } else if (parsedRespon.status == 'error') {
                         //alert('error');
                         $('div#cart').append('<div>Error! item not added to cart</div>');
                     }

                 });

                // Callback handler that will be called on failure
                request.fail( function(jqXHR, textStatus, errorThrown) { 
                    //console.error("The following error occurred: "+textStatus, errorThrown);
                    //alert('failed, We apologize! ');
                    $("#display").append('<div id="response-msg">Ajax error occured!</div>');
                });

                /* 
                // Callback handler that will be called regardless if the request failed or succeeded
                request.always(function () {
                    // Reenable the inputs
                    $inputs.prop("disabled", false);
                }); 
                */


        });//End on(submit)

			

            //LOGIN MOADAL aNIMATION
            var LoginTween = TweenMax.from("#myModal", 1, {rotation:90, transformOrigin:"left top", ease:Bounce.easeOut, paused:true});      
        
            $('#login-trigger').on('click', function(e) {
                LoginTween.play();
            }); //End onclick
        
        
        
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
			
			$("#mb-item-add-close").click(function() {
				
				$("#mb-item-added").css("display", "none");
			});
			
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


    }); //End Ready


</script>


</body>
</html>
