<div class="container"> 
    
    <div class="col-12">            
    
        <style type="text/css">
                    #wrapper {
                            margin: 0 auto;
                            width:960px;	
                            
                    }


                    @-webkit-keyframes autopopup {
                            from {opacity: 0; margin-top:-200px;}
                            to {opacity: 1;}
                    }
                    @-moz-keyframes autopopup {
                            from {opacity: 0;margin-top:-200px;}
                            to {opacity: 1;}
                    }
                    @keyframes autopopup {
                            from {opacity: 0;margin-top:-200px;}
                            to {opacity: 1;}
                    }
                    #popup-overlay {
                            background-color: rgba(0,0,0,0.8); /* rgba(red, green, blue, alpha) */
                            position: fixed; /* The element is positioned relative to the browser window */
                            top:0;
                            left:0;
                            right:0;
                            bottom:0;
                            margin:0;

                            /* display:none; 4 GREENSOCK */		
                            z-index:999;

                            -webkit-animation:autopopup 2s;
                            -moz-animation:autopopup 2s;
                            animation:autopopup 2s; /* Call this animation */

                    }
                    #popup-overlay:target { 
                            -webkit-transition:all 1s;
                            -moz-transition:all 1s;
                            transition:all 4s;
                            /* Hide the overlay div. Note that the DIV#popup-conatiner is inside the OVERLAY DIV. So, it too will hide */
                            opacity: 0;
                            visibility: hidden;
                    } 
                    @media (min-width: 768px){
                            .popup-container {
                                    width:640px;
                            }
                    }
                    @media (max-width: 767px){
                            .popup-container {
                                    width:100%;
                            }
                    }
                    .popup-container {
                            position: relative;
                            margin:7% auto;
                            padding:30px 50px;
                            background-color: #fafafa;
                            color:#333;
                            border-radius: 3px;
                    }
                    a.popup-close {
                            position: absolute;
                            top:3px;
                            right:3px;
                            background-color: #333;
                            padding:7px 10px;
                            font-size: 20px;
                            text-decoration: none;
                            line-height: 1;
                            color:#fff;
                }
            </style>

            <h1>Thanks for signing up!</h1>
            <p>We will automatically send you an email whenever we have any promotions. Please let us know if you would rather not receive any emails</p>
            <p>Thanks!!</p>
            
            <div id="wrapper">

                <div id="popup-overlay"><!-- overlay has fixed positoin covering the whole page, not just the #wrapper DIV -->		
                <div class="popup-container">

                        <h2 id="heaher2" style="text-align:center;">Thanks for signing up!</h2>
                        <p>You have successfully signed up. You can now go to the login page to login if you would like.</p>
                        
                    <a class="popup-close" href="#popup-overlay">X</a>
                </div>  
                </div>

            </div>

        </div>    
</div>            