<?php
//Generic::_setTrace($profile_data);
$baseUrl = Yii::app()->request->baseUrl;

 if(isset($_SESSION["product"]) && count($_SESSION["product"])>0){ //if we have session variable
   $total = 0;
    foreach($_SESSION["product"] as $product) { //loop though items and prepare html content

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
		$expert_details = Generic::getExpertDetailsUsingExpertID($product['expert_name']);
		$expert_code_name = $expert_details['expert_code_name'];
        $expert_name = $expert_details['expert_name'];

	}}

$cart_details_array = $_SESSION["product"];
$full_name = (isset($profile_data['user_name']) && !empty($profile_data['user_name']))?$profile_data['user_name']:'';
$user_email = (isset($profile_data['user_email']) && !empty($profile_data['user_email']))?$profile_data['user_email']:'';
$user_mobile_number = (isset($profile_data['user_mobile_number']) && !empty($profile_data['user_mobile_number']))?$profile_data['user_mobile_number']:'';
$user_address = (isset($profile_data['user_address']) && !empty($profile_data['user_address']))?$profile_data['user_address']:'';
$user_city = (isset($profile_data['user_city']) && !empty($profile_data['user_city']))?$profile_data['user_city']:'';
$date = date('y-m-d');





?>

<!-- This is what you need -->
<link rel="stylesheet" href="<?=$baseUrl?>/css/sweetalert.css">
<script src="<?=$baseUrl?>/js/sweetalert.js"></script>

<!--.......................-->



<div class="row" style="display: none">
	<button class="btn btn-primary sweet-4 alerts" onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-4']);">Try It</button>

</div>





	<!--Page Title-->

	<section class="checkout-area">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="form billing-info">
						<div class="sec-title">
							<h3 style="text-transform: uppercase">Billing Details</h3>
						</div>
						<form method="post" action="">
							<div class="row">
								<div class="col-md-12">
									<div class="field-label">Full Name*</div>
									<div class="field-input">
										<input type="text" name="fname" placeholder=""  value="<?=$full_name?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Address *</div>
									<div class="field-input">
										<input type="text" name="address" required value="<?=$user_address?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Town / City *</div>
									<div class="field-input">
										<input type="text" name="town_city" required value="<?=$user_city?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Contact Info *</div>
									<div class="field-input">
										<input type="text" name="email" placeholder="Email Address" required  value="<?=$user_email?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-input">
										<input type="text" name="phone" placeholder="Phone Number" value="<?=$user_mobile_number?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field-label">Reference (Optional)</div>
									<div class="field-input">
										<input type="text" name="ref" value="<?=$expert_name?>" readonly>
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
										<span class="col">&#2547; 0.00</span>
									</li>
									<li class="clearfix">
										<span class="col col-title">Order Total</span>
										<span class="col"> &#2547; <?=$total?>.00</span>
									</li>
								</ul>

								<div class="payment-options">
									<h3>Payment Method</h3><br>
									<form id="order_form" action="javascript:void(0);">
									<div class="option-block">
										<div class="checkbox">
											<label>
												<input name="pay-us" type="checkbox" value="Cash on Delivery">
												<span>Cash on delivery</span>
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
													<input class="terms_and_conditions" name="terms_and_conditions" type="checkbox">
													<span>I agree to accept the terms and conditions</b></span>
												</label>
											</div>
										</div>
									</div>
									<div class="placeorder-button text-left">
										<button onclick="insertServiceTransactionsData()" type="submit" class="thm-btn place-order">Place Order</button>
									</div>
									</form>
								</div>


							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<script type="text/javascript">


	$(':checkbox').on('change',function(){
		var th = $(this), name = th.attr('name');
		if(th.is(':checked')){
			$(':checkbox[name="'  + name + '"]').not(th).prop('checked',false);
		}
	});




	function baseUrl(){
		var href=window.location.href.split('/');
		return href[0]+'//'+href[2]+'/';
	}
	var SITE_URL=baseUrl();

	function  insertServiceTransactionsData(){

		if ($('#order_form').find('input[name="terms_and_conditions"]')[0].checked === false) {
			$('.alerts').trigger('click');
			return false;
		}




        var service_details =  <?php echo json_encode($cart_details_array)?>;
		var orderForm = $("#order_form");
		var orderFormSubmitButton = orderForm.find('.place-order');
		var redirect_url = SITE_URL  + 'user-profile/recent-order';
        var service_taker_id = "<?=$profile_data['id']?>";

		var address = $('input[name=address]').val();
		var town_city = $('input[name=town_city]').val();
		var phone = $('input[name=phone]').val();
		var email = $('input[name=email]').val();
		var payment_system = $("input[type='checkbox']").val();


		$.ajax({
			type: "POST",
			url: SITE_URL + "site/InsertServiceTransactionsData",
			data: {service_details:service_details,service_taker_id:service_taker_id,address:address,town_city:town_city,phone:phone,email:email,payment_system:payment_system},
			cache: false,
			dataType:"json",
			beforeSend:function(){

				$(':button[type="submit"]').prop('disabled', true);
				orderFormSubmitButton.html("<i class='fa fa-spinner fa-spin'></i> Submitting Data...");
			},
			success: function(data) {
				if(data.status=="Success"){
					function redirect(){
						window.location=redirect_url ;

						Session.Remove("product");

					}
					window.setTimeout(redirect,4000);

				}
			}});
	}


</script>

<script>
	document.querySelector('.sweet-4').onclick = function(){
		swal({
				title: "Warning !!",
				text: "Please Agree With Our Terms and Conditions !",
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: true
				//closeOnCancel: false
			},
			function(){
				var clicked = false;
				$(".terms_and_conditions").prop("checked", !clicked);
				clicked = !clicked;
			});
	};


</script>

