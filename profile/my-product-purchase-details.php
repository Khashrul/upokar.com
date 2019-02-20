<?php
/**
 * Created by PhpStorm.
 * User: KHASHRUL
 * Date: 1/16/2018
 * Time: 1:10 PM
 */

$baseUrl = Yii::app()->getBaseUrl(true);
$user_name = isset($profile_data['user_name']) ? $profile_data['user_name'] : '';
$user_email = isset($profile_data['user_email']) ? $profile_data['user_email'] : '';
$user_mobile_number = isset($profile_data['user_mobile_number']) ? $profile_data['user_mobile_number'] : '';
$user_address = isset($profile_data['user_address']) ? $profile_data['user_address'] : '';
$payment_system = isset($shop_details[0]['payment_system']) ? $shop_details[0]['payment_system'] : '';
$order_status = isset($shop_details[0]['status']) ? $shop_details[0]['status'] : '';
$service_name = isset($shop_details[0]['service_name']) ? $shop_details[0]['service_name'] : '';
$tag_name = isset($shop_details[0]['tag_name']) ? $shop_details[0]['tag_name'] : '';
$particular = $service_name.' | '.$tag_name;
$date = date_create($shop_details[0]['transaction_date']);
$date_data = date_format($date, 'g:ia \o\n l jS F Y');

$total_amount = isset($shop_details[0]['total_amount']) ? $shop_details[0]['total_amount'] : '';
$vat_calculator = Generic::vatCalculator($total_amount,4.5);
$invoice_id = isset($shop_details[0]['invoice_id']) ? $shop_details[0]['invoice_id'] : '';
$parent_service_id = isset($shop_details[0]['parent_service_id']) ? $shop_details[0]['parent_service_id'] : 1;
$user_id = isset($profile_data['id']) ? $profile_data['id'] : 1;
$ratings_array = array(4,5);
$k = array_rand($ratings_array);
$ratings = $ratings_array[$k];
$user_ip = isset($shop_details[0]['browser_ip_address']) ? $shop_details[0]['browser_ip_address'] : '';

$location_name_in_cookie = isset($shop_details[0]['location']) ? $shop_details[0]['location'] : '';


$order_id = isset($shop_details[0]['id']) ? $shop_details[0]['id'] : '';


$scheduled_date = $shop_details[0]['transaction_date'];
$dates = date_create($scheduled_date);
//Generic::_setTrace($dates);
$dates_data = date_format($dates, ' l jS F Y');


?>

<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/component.css" />
<script src="<?=$baseUrl?>/js/modernizr.custom.js"></script>
<script src="<?=$baseUrl?>/js/emotion-ratings.js"></script>


<!-- This is what you need -->
<link rel="stylesheet" href="<?=$baseUrl?>/css/sweetalert.css">
<script src="<?=$baseUrl?>/js/sweetalert.js"></script>

<!--.......................-->

<input type="hidden" name="ratings" value="<?=$ratings?>">

<input type="hidden" name="user_id" value="<?=$user_id?>">


<div class="row" style="display: none">
	<button class="btn btn-primary sweet-4 alertss" onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-4']);">Try It</button>

</div>


<div class="md-modal md-effect-10" id="modal-10">
	<div class="md-content">
		<h3>Review Window</h3>
		<div>
			<form action="javascript:void(0);" id="review_form" method="POST">
				<div class="modal-diallog">
					<div class="modal-conltent" style="width:430px">
						<div class="modal-helader">
							<h5 class="modal-tlitle" style="text-align: justify">Write Your Review Here:</h5>
						</div>
						<div id="otp_message"></div>
						<div class="modall-body">
							<br>
							<textarea required id="reviews" rows="4" cols="54"></textarea>
							<br>
							<h5 class="">Please Enter Your Ratings:</h5>
							<br>
							<div id="element"></div>
							<script>
								var emotionsArray = ['angry','disappointed','meh', 'happy', 'inLove'];
								$("#element").emotionsRating({
									emotionSize: 48,
									bgEmotion: 'happy',
									emotions: emotionsArray,
									color: 'gold'
								});
							</script>
						</div>
						<div class="modal-foooter">
							<br>
							<button type="submit"  class="btn btn-primary place_review">Submit Review </button></p>
                            <div class="success_message"></div>
						</div>
						<hr>
					</div>
				</div>
			</form>
			<button class="md-close">Close me!</button>
		</div>
	</div>
</div>

<!--Page Title-->

        <!--Page Title Ends-->
<section class="error-page">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="details-bottom">
							<div class="pull-left col-md-6">

								<div>
									<dl class="dl-horizontal">
										<h3 style="text-transform: uppercase"><strong>Invoice To </strong></h3>

									</dl>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-label ng-binding"><?=$user_name?></td>
									</tr><br>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-value ng-binding"><?=$user_address?></td>
									</tr><br>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
									<td id="" class="detail-label ng-binding"><?=$user_mobile_number?></td>
								</tr><br>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-value ng-binding"><?=$user_email?></td>
									</tr>
									<br><br>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-label ng-binding"><?=$location_name_in_cookie?></td>
									</tr><br>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-value ng-binding">Browser ip: <?=$user_ip?></td>
									</tr>
								</div>
							</div>

							<div class="pull-left col-md-6">
								<div>
									<dl class="dl-horizontal">
										<h3 style="text-transform: uppercase"><strong>Invoice Details</strong><span> ( IN-<?=$invoice_id?> )</span></h3>
									</dl>
									<div class="row-fluid">
										<div id="" class="summary-section summary">

											<table class="table detail-table" order-summary-date-update="">
												<tbody>
												<tr data-udm-field="orderCreationDate" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Order Created Date</td>
													<td id="" class="detail-value ng-binding"><?=$date_data?></td>
												</tr>




												<tr data-udm-field="orderOutstandingAuthorizationAmount" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Payment Method</td>
													<td id="" class="detail-value ng-binding"><?=$payment_system?></td>
												</tr>
												<tr data-udm-field="orderId" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Product Delivery Status</td>
													<td id="" class="detail-value ng-binding"><?=$order_status?></td>
												</tr>

												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="pull-left col-md-12">
							<div class="row-fluid" data-ng-show="criteriaResultsData.results.length > 0 &amp;&amp; !criteriaResultsData.executing">
								<div class="span12 ng-scope" data-ng-controller="SearchListCtrl" data-ng-include="'assets/tns/search/views/search/list-orders.html?cache=' + warCacheToken"><div class="search search-list ng-scope">
									<table id="orderResults" class="table table-condensed table-bordered table-search-results ng-isolate-scope ng-scope" data-responsive-table="" data-table-data="data.criteriaResultsData.results" data-columns="columns" data-current-page="data.currentPage" data-page-length="data.pageLength" data-tns-details-url-callback="viewTransactionDetails()" data-context="searchContext">
										<thead class="table-header">
										<tr>
											<th data-responsive-column="expand" data-columns="columns" data-table-data="tableData" class="column column-action ng-isolate-scope ng-scope">
												S/N
											</th>

											<th data-responsive-column="orderDate" data-columns="columns" class="ng-isolate-scope ng-scope ng-binding">PARTICULAR</th>
											<th data-responsive-column="orderId" data-columns="columns" class="ng-isolate-scope ng-scope ng-binding">QTY</th>
											<th data-responsive-column="orderReference" data-columns="columns" class="ng-isolate-scope ng-scope ng-binding">UNIT PRICE</th>
											<th data-responsive-column="orderReference" data-columns="columns" class="ng-isolate-scope ng-scope ng-binding">TOTAL AMOUNT</th>
										</tr>
										</thead>

										<tbody class="table-body-search-results ng-scope" data-ng-repeat="row in tableData | startFrom:(currentPage - 1) * pageLength | limitTo:pageLength">

                                           <?php  if(($shop_details) && !empty($shop_details)){
                                                     $index_counter = 1;
											         $sub_total = 0;
											         $products_id = array();
											   $order_id = array();
											   foreach($shop_details as $individual_products){

												   $product_name = $individual_products['product_name'];
												   $product_price = $individual_products['product_price'];
												   $product_quantity = $individual_products['product_quantity'];


												   $total_amount = $product_price*$product_quantity;

												   array_push($products_id,$individual_products['products_id'] );
												   array_push($order_id,$individual_products['id'] );



												   $sub_total = $sub_total + $total_amount;
												   $particular = $product_name;
												   $final_price = $sub_total.'.00';
												   $amount_for_in_words = ($final_price + $vat_calculator);
												   $shipping = 50;
                                                   $discounted_amount = isset($individual_products['discounted_amount'])? $individual_products['discounted_amount'] : 0;
                                                   $grand_total = $individual_products['final_amount'];
												    ?>
											<tr>
											<td>0<?=$index_counter?></td>
											<td><?=$particular?></td>
											<td><?=$product_quantity?></td>
											<td><?=$product_price?></td>
											<td><?=number_format($total_amount)?></td>
										   </tr>

											 <?php $index_counter ++;
											   } } ?>
										</tbody>

									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

				<input type="hidden" name="service_status" value="<?=$order_status?>">
				<input type="hidden" name="products_id" value="<?php echo json_encode($products_id)?>">


				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="details-bottom">


							<div class="pull-left col-md-6">
								<div>
									<div class="row-fluid">
										<div id="" class="summary-section summary">

											<table class="table detail-table" order-summary-date-update="">
												<tbody>
												<tr data-udm-field="orderOutstandingAuthorizationAmount" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Sub-Total</td>
													<td id="" class="detail-value ng-binding"><?=number_format($sub_total)?></td>
												</tr>
												<tr ng-show="$parent.showTotalCapturedAmount()" data-udm-field="orderTotalCapturedAmount" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Shipping and Handling<span></span></td>
													<td id="" class="detail-value ng-binding"><?=$shipping?></td>
												</tr>
												<tr data-udm-field="orderId" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Discount ( - )</td>
													<td id="" class="detail-value ng-binding"><?=$discounted_amount?></td>
												</tr>
												<tr data-udm-field="orderId" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Grand Total</td>
													<td id="" class="detail-value ng-binding"><?=$grand_total?></td>
												</tr>
												<tr data-udm-field="orderCreationDate" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">In-word (BDT)</td>
													<td id="" class="detail-value ng-binding"><?=Generic::convertNumber($grand_total.'.00');?></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="pull-right col-md-6">

								<div>
									<dl class="dl-horizontal">
										<h3 style="text-transform: uppercase"><strong> </strong></h3>

									</dl>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-label ng-binding"></td>
									</tr><br>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-value ng-binding"></td>
									</tr>
									<br>
									<br>
									<br>




									<?php
									if($order_status == "Pending"){?>
										<input style="text-transform: uppercase" onclick="showAlertMessage()" class="btn btn-lg btn-primary btn-block btn-signin " type="submit" value="Your review with us">
									<?php }

									elseif($order_status == "Delivered"){?>
										<input data-modal="modal-10" style="text-transform: uppercase" class="btn btn-lg btn-primary btn-block btn-signin md-trigger" type="submit" value="your review with us"  name="loginForm">
									<?php }?>
									<br>
									<?php
									if($order_status == "Pending"){?>

										<div id="cancel_order">
											<p class="small pull-left"><a href="javascript:void(0);" class="cancel-order"  value="Cancel order">Cancel Order &nbsp; </a></p>
										</div>
										| <a href="/">Make new order</a>

									<?php } else{ ?>
										<a href="/">Make new order</a>
									<?php } ?>



									<br><div class="success_message_cancel_order alert-danger"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</section>



<div class="md-overlay"></div><!-- the overlay element -->
<script src="<?=$baseUrl?>/js/classie.js"></script>
<script src="<?=$baseUrl?>/js/modalEffects.js"></script>

<script>
	// this is important for IEs
	var polyfilter_scriptpath = '<?=$baseUrl?>/js/';
</script>


<script src="<?=$baseUrl?>/js/cssParser.js"></script>
<script src="<?=$baseUrl?>/js/css-filters-polyfill.js"></script>



<script type="text/javascript">


	function showAlertMessage(){
		$('.alertss').trigger('click');
	}

	function checkServiceStatus(){
		var service_status = $('input[name=service_status]').val();
		if((service_status) && service_status=="Pending"){
			$('#ConfirmationModal').modal('show');

		}
	}

	$('#review_form').on('submit',function(e){

		insertReview();
	});

	function insertReview(){
		var reviews = $('textarea#reviews').val();
		var ratings = $('input[name=ratings]').val();
		var products_id =  <?php echo json_encode($products_id)?>;
		var user_id = $('input[name=user_id]').val();
		var reviewForm = $("#review_form");
		var reviewFormSubmitButton = reviewForm.find('.place_review');

		$.ajax({
			type : 'POST',
			async: false,
			url  : SITE_URL+"site/InsertReviewAndRatingsForProducts",
			cache: false,
			data:{reviews:reviews,ratings:ratings,products_id:products_id,user_id:user_id},
			dataType:"json",
			beforeSend:function(){

				$(':button[type="submit"]').prop('disabled', true);
				reviewFormSubmitButton.html("<i class='fa fa-spinner fa-spin'></i> Submitting Review...");
			},
			success: function(data)
			{
				if (data.status=="Success") {

					window.setTimeout(showMessage,4000);
					function showMessage(){
						$(".success_message").html(data.message);
						$(':button[type="submit"]').removeClass("fa fa-spinner fa-spin");
						reviewFormSubmitButton.html("Submit Review");
						window.setTimeout(removeModal,2000);
						function removeModal(){
							$('.md-close').trigger('click');
						}

					}
				}
			},
			error: function(){
				alert('Error!');
			}
		})}


	$('#cancel_order').on('click',function(e){
		cancelOrder();
	});

	function cancelOrder(){

		var order_id =  <?php echo json_encode($order_id)?>;

		var redirect_url = SITE_URL+'user-profile/purchase-items';
		var cancel = $("#cancel_order");
		var cancelSubmitButton = cancel.find('.cancel-order');
		$.ajax({
			type : 'POST',
			async: false,
			url  : SITE_URL+"site/CancelProductsOrder",
			cache: false,
			data:{order_id:order_id},
			dataType:"json",
			beforeSend:function(){

				cancelSubmitButton.html("<i class='fa fa-spinner fa-spin'></i> Cancelling ...");
			},
			success: function(data)
			{
				if (data.status=="Success") {
					window.setTimeout(showMessage,2000);
					function showMessage(){
						$(".success_message_cancel_order").html(data.message);
						window.location=redirect_url ;

					}
				}
			},
			error: function(){
				alert('Error!');
			}
		})}


</script>
<script>
	document.querySelector('.sweet-4').onclick = function(){
		swal({
				title: "Warning !!",
				text: "Sorry ! Order Status Still Pending.",
				type: "warning",
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