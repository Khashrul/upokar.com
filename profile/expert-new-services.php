<?php

$baseUrl = Yii::app()->getBaseUrl(true);
$expert_name = isset($expert_profile_data['expert_name']) ? $expert_profile_data['expert_name'] : '';
$expert_email = isset($expert_profile_data['expert_email']) ? $expert_profile_data['expert_email'] : '';
$expert_mobile_number = isset($expert_profile_data['phone_number']) ? $expert_profile_data['phone_number'] : '';
$expert_location = isset($expert_profile_data['expert_location']) ? $expert_profile_data['expert_location'] : '';
$expert_image= isset($expert_profile_data['expert_image']) ? $expert_profile_data['expert_image'] : 'user.jpg';
$description= isset($expert_profile_data['description']) ? $expert_profile_data['description'] : '';

$expertise = Generic::getExpertExpertise($expert_profile_data['parent_service_id']);

$status = ((isset($expert_profile_data['current_status']) && $expert_profile_data['current_status']== 1) ? "Active" : "Inactive");
$view_url = $baseUrl.'/'.Yii::app()->request->getPathInfo().'/service-details?';
?>
		
		<section class="service-single">
			<div class="container">
				<div class="row">
					<?php echo $this->renderPartial('../elements/_expert_sidebar_common',array('expert_image'=>$expert_image));?>
					<section class="expart-details">
						<div class="container">
							<div class="row">
								<div class="col-md-8">
									<h3 style="text-transform: uppercase">New Service Order</h3>
									<br>

									<table id="orderResults" class="table table-condensed table-bordered table-search-results ng-isolate-scope ng-scope" data-responsive-table="" data-table-data="data.criteriaResultsData.results" data-columns="columns" data-current-page="data.currentPage" data-page-length="data.pageLength" data-tns-details-url-callback="viewTransactionDetails()" data-context="searchContext">
												<thead>
												<tr>
													<th>INV.ID</th>
													<th>DATE / TIME</th>
													<th>PARTICULAR</th>
													<th>AMOUNT</th>
													<th>STATUS</th>
													<th>ACTION</th>
												</tr>
												</thead>
												<tbody class="table-body-search-results ng-scope" data-ng-repeat="row in tableData | startFrom:(currentPage - 1) * pageLength | limitTo:pageLength">

												<?php if(($newService) && !empty($newService)){


													foreach($newService as $individual_service){
														$service_id = $individual_service['id'];
														$service_date = $individual_service['service_create_date'];
														$service_name = $individual_service['service_name'];
														$service_tag_name = $individual_service['tag_name'];
														$service_price = $individual_service['total_amount'];
														//$status = $individual_service['status'];
														$service_status = $individual_service['service_status'];
														$invoice_id = $individual_service['invoice_id'];

														$date = date_create($service_date);
														$date_data = date_format($date, 'g:ia \o\n l jS F Y');?>


												<tr class="">
													<td>IN-<?=$invoice_id?></td>
													<td><?=$date_data?></td>
													<td><?=$service_tag_name?></td>
													<td><?=$service_price?></td>
													<td><?=$service_status?></td>
													<td><a href="<?=$view_url?>ServiceId=<?=base64_encode($service_id)?>">View</a></td>
												</tr>

													<?php }}?>




												</tbody>
												</table>


								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</section>

