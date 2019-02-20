<?php

$base_url = Yii::app()->getBaseUrl(true);











?>

		<!--Page Title-->
        <section class="page-title">
        	<div class="container">
            	<div class="row clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12 pull-left">
						<h1>contact us</h1>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
					</div>
                    <div class="col-md-6 col-sm-6 col-xs-12 pull-right text-right path"><a href="index.html">Home</a>&ensp;/&ensp;<a href="#">contact us</a></div>
					<div class="overlay"></div>
                </div>
            </div>
        </section>
        <!--Page Title Ends-->
		
		
		<section class="feature-style-three">
			<div class="container">			
				<div class="item-list">
					<div class="row">
						<div class="item">
							<div class="column col-md-4 col-sm-6 col-xs-12">
								<div class="inner-box">
									<div class="icon-box"><span class="icon flaticon-pin-1"></span></div>
									<h3>Location</h3>
									<div class="text"><p>PO Box 16122 Collins Street West Victoria 8007 Canada</p></div>
								</div>
							</div>
						</div>
						
						<div class="item">
							<div class="column col-md-4 col-sm-6 col-xs-12">
								<div class="inner-box">
									<div class="icon-box"><span class="icon flaticon-cell-phone"></span></div>
									<h3>Phone Number</h3>
									<div class="text"><p>(+48) 564-334-21-22-34 <br>(+48) 564-334-21-22-38</p></div>
								</div>
							</div>
						</div>
						
						<div class="item">
							<div class="column col-md-4 col-sm-6 col-xs-12">
								<div class="inner-box">
									<div class="icon-box"><span class="icon flaticon-message"></span></div>
									<h3>E-Mail Us</h3>
									<div class="text"><p>Supportinfo@frankfurt.com <br>Supportinfo@archit.com</p></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="contact_us">
			<div class="container">   
                <div class="sec-title text-center">
                    <h2>Get In <span>Touch</span></h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                </div>
                <div class="default-form-area">
					<form id="contact-form" name="contact_form" class="default-form" action="http://wp.hostlin.com/electrician-press/inc/sendmail.php" method="post">
						<div class="row clearfix">
							<div class="col-md-6 col-sm-6 col-xs-12">
												
								<div class="form-group style-two">
									<input type="text" name="form_name" class="form-control" value="" placeholder="Name" required="">
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group style-two">
									<input type="email" name="form_email" class="form-control required email" value="" placeholder="Email" required="">
								</div>
							</div>
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="form-group style-two">
									<input type="text" name="form_phone" class="form-control" value="" placeholder="Phone">
								</div>
							</div>
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="form-group style-two">
									<input type="text" name="form_subject" class="form-control" value="" placeholder="Date">
								</div>
							</div>	
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group style-two">
									<textarea name="form_message" class="form-control textarea required" placeholder="Your Message"></textarea>
								</div>
							</div>   											  
						</div>
						<div class="contact-section-btn text-center">
							<div class="form-group style-two">
								<input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value="">
								<button class="thm-btn thm-color" type="submit" data-loading-text="Please wait...">send message</button>
							</div>
						</div> 
					</form>
				</div>          
			</div>
		</section>
		<section class="contact_details sec-padd">

				<div class="home-google-map">
					<div
						class="google-map"
						id="contact-google-map"
						data-map-lat="22.8209544"
						data-map-lng="89.553081"
						data-icon-path="<?=$base_url?>/images/logo/map-marker.png"
						data-map-title="Chester"
						data-map-zoom="10" >
					</div>
				</div>
			
		</section>

<!-- google map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRvBPo3-t31YFk588DpMYS6EqKf-oGBSI"></script>
<script src="<?=$base_url?>/js/gmap.js"></script>
<script id="map-script" src="<?=$base_url?>/js/default-map.js"></script>