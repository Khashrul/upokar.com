<?php
//Generic::_setTrace($profile_data);
$baseUrl = Yii::app()->request->baseUrl;
 if(isset($_SESSION["products"]) && count($_SESSION["products"])>0){ //if we have session variable
   $total = 0;
    foreach($_SESSION["products"] as $product) { //loop though items and prepare html content

		//set variables to use them in HTML content below
		$service_name = $product["service_name"];
		$tag_name = $product["tag_name"];
		$tag_price = $product["tag_price"];
		$product_qty = $product["quantity"];;
		$service_image = $product["service_image_url"];;
		$currency = "BDT ";
		$product_code = $product["tag_id"];;


		$subtotal = ($tag_price * $product_qty);
		$total = ($total + $subtotal);


	}}

$full_name = (isset($profile_data['user_name']) && !empty($profile_data['user_name']))?$profile_data['user_name']:'';
$user_email = (isset($profile_data['user_email']) && !empty($profile_data['user_email']))?$profile_data['user_email']:'';









?>

	<!--Page Title-->

	<section class="checkout-area">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="form billing-info">
						<div class="sec-title">
							<h3 style="text-transform: uppercase">Billing Details</h3>
						</div>
						<form method="post" action="http://wp.hostlin.com/electrician-press/checkout.html">
							<div class="row">
								<div class="col-md-12">
									<div class="field-label">Full Name*</div>
									<div class="field-input">
										<input type="text" name="fname" placeholder="" value="<?=$full_name?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Address *</div>
									<div class="field-input">
										<input type="text" name="address" placeholder="">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Town / City *</div>
									<div class="field-input">
										<input type="text" name="town-city" placeholder="">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Contact Info *</div>
									<div class="field-input">
										<input type="text" name="email" placeholder="Email Address" value="<?=$user_email?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-input">
										<input type="text" name="phone" placeholder="Phone Number">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Reference (Optional)</div>
									<div class="field-input">
										<input type="text" name="ref" placeholder="GP0123">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="row bottom">
						<div class="col-lg-12 col-md-12">
							<div class="cart-total">
								<div class="sec-title-two">
									<h3 style="text-transform: uppercase">Payment details</h3>
								</div>
								<ul class="cart-total-table">
									<li class="clearfix">
										<span class="col col-title">Cart Subtotal</span>
										<span class="col">&#2547; <?=$total?>.00</span>
									</li>
									<li class="clearfix">
										<span class="col col-title">Shipping and Handling</span>
										<span class="col">&#2547; 40.00</span>
									</li>
									<li class="clearfix">
										<span class="col col-title">Order Total</span>
										<span class="col"> &#2547; 146.00</span>
									</li>
								</ul>
								<div class="payment-options">
									<h3>Payment Method</h3><br>
									<div class="option-block">
										<div class="checkbox">
											<label>
												<input name="pay-us" type="checkbox">
												<span onclick="submitData()">Cash on delivery</span>
											</label>
										</div>
									</div>

									<div class="option-block">
										<div class="radio-block">
											<div class="checkbox">
												<label>
													<input name="pay-us" type="checkbox">
													<span>Visa / MasterCard</b></span>
												</label>
											</div>
										</div>
									</div>
									<br>
									<div class="option-block">
										<div class="radio-block">
											<div class="checkbox">
												<label>
													<input name="pay-us" type="checkbox">
													<span>I agree to accept the terms and conditions</b></span>
												</label>
											</div>
										</div>
									</div>
									<div class="placeorder-button text-left">
										<button type="submit" class="thm-btn">Place Order</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


  <script type="text/javascript">

	  function submitData(){
		  

	  }


  </script>