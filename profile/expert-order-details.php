
<?php
/**
 * Created by PhpStorm.
 * User: KHASHRUL
 * Date: 1/16/2018
 * Time: 1:10 PM
 */
//Generic::_setTrace($profile_data);
$baseUrl = Yii::app()->getBaseUrl(true);
$user_name = isset($profile_data['user_name']) ? $profile_data['user_name'] : '';
$user_email = isset($profile_data['user_email']) ? $profile_data['user_email'] : '';
$user_mobile_number = isset($profile_data['user_mobile_number']) ? $profile_data['user_mobile_number'] : '';
$user_address = isset($profile_data['user_address']) ? $profile_data['user_address'] : '';
$payment_system = isset($service_details[0]['payment_system']) ? $service_details[0]['payment_system'] : '';
$order_status = isset($service_details[0]['service_status']) ? $service_details[0]['service_status'] : '';
$service_name = isset($service_details[0]['service_name']) ? $service_details[0]['service_name'] : '';
$tag_name = isset($service_details[0]['tag_name']) ? $service_details[0]['tag_name'] : '';
$particular = $service_name.' | '.$tag_name;
$date = date_create($service_details[0]['service_create_date']);
$date_data = date_format($date, 'g:ia \o\n l jS F Y');
$exploded_array  = explode("|",$service_details[0]['service_amount']);
$service_amount = $exploded_array[0];
$quantity = $exploded_array[1];
$total_amount = isset($service_details[0]['total_amount']) ? $service_details[0]['total_amount'] : '';
$vat_calculator = Generic::vatCalculator($total_amount,4.7);
$invoice_id = isset($service_details[0]['invoice_id']) ? $service_details[0]['invoice_id'] : '';
$parent_service_id = isset($service_details[0]['parent_service_id']) ? $service_details[0]['parent_service_id'] : 1;
$user_id = isset($profile_data['id']) ? $profile_data['id'] : 1;
$ratings_array = array(4,5);
$k = array_rand($ratings_array);
$ratings = $ratings_array[$k];
$user_ip = Generic::getUserIP();

$location_name_in_cookie = isset($service_details[0]['service_location']) ? $service_details[0]['service_location'] : '';

$order_id = isset($service_details[0]['id']) ? $service_details[0]['id'] : '';
$current_service_id  = base64_decode(Yii::app()->request->getParam('ServiceId'));
$get_next_service_id = Generic::getNextServiceId($current_service_id,$expert);
$view_url = $baseUrl.'/'.Yii::app()->request->getPathInfo();
$next_url = (isset($get_next_service_id) && !empty($get_next_service_id))? $view_url.'?ServiceId='.base64_encode($get_next_service_id['id']):"#";


$scheduled_date = $service_details[0]['service_date'];
$dates = date_create($scheduled_date);
//Generic::_setTrace($dates);
$dates_data = date_format($dates, ' l jS F Y');

$service_schedule = $dates_data.' | '. $service_details[0]['time_range'];
$expert_details = Generic::getExpertDetailsUsingExpertID($service_details[0]['expert_name']);
?>
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
										<!--dt>Description:</dt>
                                        <dd>Grameenphone Flexiload</dd>
                                        <dt>Amount:</dt>
                                        <dd>?</dd-->
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
										<!--dt>Description:</dt>
                                        <dd>Grameenphone Flexiload</dd>
                                        <dt>Amount:</dt>
                                        <dd>?</dd-->
									</dl>
									<div class="row-fluid">
										<div id="" class="summary-section summary">

											<table class="table detail-table" order-summary-date-update="">
												<tbody>
												<tr data-udm-field="orderCreationDate" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Order Created Date</td>
													<td id="" class="detail-value ng-binding"><?=$date_data?></td>
												</tr>
												<tr data-udm-field="orderCreationDate" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Service Schedule</td>
													<td id="" class="detail-value ng-binding"><?=$service_schedule?></td>
												</tr>

												<tr data-udm-field="orderOutstandingAuthorizationAmount" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Payment Method</td>
													<td id="" class="detail-value ng-binding"><?=$payment_system?></td>
												</tr>
												<tr data-udm-field="orderId" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Order Status</td>
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
											<th data-responsive-column="orderReference" data-columns="columns" class="ng-isolate-scope ng-scope ng-binding">AMOUNT</th>
										</tr>
										</thead>
										<!-- ngRepeat: row in tableData | startFrom:(currentPage - 1) * pageLength | limitTo:pageLength -->
										<tbody class="table-body-search-results ng-scope" data-ng-repeat="row in tableData | startFrom:(currentPage - 1) * pageLength | limitTo:pageLength">
										<tr>
											<td>01</td>
											<td><?=$particular?></td>
											<td>0<?=$quantity?></td>
											<td><?=$total_amount?></td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="details-bottom">
							<div class="pull-left col-md-6">

								<div>
									<dl class="dl-horizontal">
										<h3 style="text-transform: uppercase"><strong>reference by </strong></h3>
										<!--dt>Description:</dt>
                                        <dd>Grameenphone Flexiload</dd>
                                        <dt>Amount:</dt>
                                        <dd>?</dd-->
									</dl>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-label ng-binding"><?=$expert_details['expert_name']?></td>
									</tr><br>
									<tr data-udm-field="cardFundingMethod" class="ng-isolate-scope ng-scope">
										<td id="" class="detail-value ng-binding">+88<?=$expert_details['phone_number']?></td>
									</tr><br>

									<hr>
									<div id="complete_task">
									<input style="text-transform: uppercase" class="complete-order btn btn-lg btn-primary btn-block btn-signin" type="submit" value="Complete task" name="loginForm">
									</div>
										<p class="small pull-left"><a href="<?=$baseUrl?>/expert-profile/dashboard">Back My Profile</a> | <a href="<?=$next_url?>">Next Service</a></p>
									<br><div class="success_message_complete_order alert-danger"></div>

										</div>
							</div>
							<div class="pull-left col-md-6">
								<div>
									<div class="row-fluid">
										<div id="" class="summary-section summary">

											<table class="table detail-table" order-summary-date-update="">
												<tbody>
												<tr data-udm-field="orderOutstandingAuthorizationAmount" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Sub-Total</td>
													<td id="" class="detail-value ng-binding"><?=$total_amount?>.00</td>
												</tr>
												<tr ng-show="$parent.showTotalCapturedAmount()" data-udm-field="orderTotalCapturedAmount" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Shipping and Handling<span></span></td>
													<td id="" class="detail-value ng-binding">0.00</td>
												</tr>
												<tr data-udm-field="orderId" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">Total Amount</td>
													<td id="" class="detail-value ng-binding"><?=$total_amount?>.00</td>
												</tr>
												<tr data-udm-field="orderCreationDate" class="ng-isolate-scope ng-scope">
													<td id="" class="detail-label ng-binding">In-word (BDT)</td>
													<td id="" class="detail-value ng-binding"><?=Generic::convertNumber($total_amount.'.00');?></td>
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

		</section>

    <input type="hidden" name="order_id" value="<?php echo $order_id?>">

    <script>

		$('#complete_task').on('click',function(e){
			completeTask();
		});

		function completeTask(){

			var order_id =  <?php echo $order_id?>;
			var redirect_url = SITE_URL+'expert-profile/completed-service';
			var cancel = $("#complete_task");
			var cancelSubmitButton = cancel.find('.complete-order');

			$.ajax({
				type : 'POST',
				async: false,
				url  : SITE_URL+"site/CompleteOrder",
				cache: false,
				data:{order_id:order_id},
				dataType:"json",
				beforeSend:function(){

					cancelSubmitButton.html("<i class='fa fa-spinner fa-spin'></i> Completing ...");
				},
				success: function(data)
				{
					if (data.status=="Success") {
						window.setTimeout(showMessage,3000);
						function showMessage(){
							$(".success_message_complete_order").html(data.message);

							window.setTimeout(redirect,3000);
						}
						function redirect(){

							window.location=redirect_url ;

						}
					}
				},
				error: function(){
					alert('Error!');
				}
			})}


    </script>
