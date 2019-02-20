<?php






$baseUrl = Yii::app()->getBaseUrl(true);
//Generic::_setTrace($profile_data);


$expert_name = isset($expert_profile_data['expert_name']) ? $expert_profile_data['expert_name'] : '';
$expert_email = isset($expert_profile_data['expert_email']) ? $expert_profile_data['expert_email'] : '';
$expert_mobile_number = isset($expert_profile_data['phone_number']) ? $expert_profile_data['phone_number'] : '';
$expert_location = isset($expert_profile_data['expert_location']) ? $expert_profile_data['expert_location'] : '';
$expert_image= isset($expert_profile_data['expert_image']) ? $expert_profile_data['expert_image'] : 'user.jpg';
$description= isset($expert_profile_data['description']) ? $expert_profile_data['description'] : '';

$expertise = Generic::getExpertExpertise($expert_profile_data['parent_service_id']);

$status = ((isset($expert_profile_data['current_status']) && $expert_profile_data['current_status']== 1) ? "Active" : "Inactive");

?>
		
		<section class="service-single">
			<div class="container">
				<div class="row">

					<?php echo $this->renderPartial('../elements/_expert_sidebar_common',array('expert_image'=>$expert_image));?>

					<section class="expart-details">
						<div class="container">
							<div class="row">
								<div class="col-md-8 single-team-member">
									<h3 style="text-transform: uppercase"><?=$expert_name?></h3>
									<span><?=$expertise['service_name']?></span>
									<div class="awards-wrapper clearfix">
										<div class="single-award">
											<div class="inner">
												<i class="fa fa-trophy" aria-hidden="true"></i>
												<p><span class="block">My</span>  Services</p>
								<span class="number">
									05
								</span>
											</div>
										</div>
										<div class="single-award">
											<div class="inner">
												<i class="fa fa-suitcase" aria-hidden="true"></i>
												<p><span class="block">My</span> Sales</p>
								<span class="number">
									05
								</span>
											</div>
										</div>
										<div class="single-award">
											<div class="inner">
												<i class="fa fa-map-marker"></i>
												<p><span class="block">My</span>  Review</p>
								<span class="number">
									05
								</span>
											</div>
										</div>
										<div class="single-award">
											<div class="inner">
												<i class="fa fa-graduation-cap"></i>
												<p><span class="block">Best</span>  Awards</p>
								<span class="number">
									05
								</span>
											</div>
										</div>
									</div>
									<p><?=$description?></p>
									<ul class="infos">
										<li><span>Expert Name :</span> <span>&nbsp;&nbsp;&nbsp;<?=$expert_name?></span></li>
										<li><span>Mobile :</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+88<?=$expert_mobile_number?></span></li>
										<li><span>Email :</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$expert_email?></span></li>
										<li><span>Speciality :</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$expertise['service_name']?></span></li>
										<li><span>Experience :</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;02 years of Experience</span></li>
										<li><span>Service Location :</span> <span>Monday - Friday(<?=$expert_location?>)</span></li>
										<br>
										<li><span>Status :</span> <span style="color: #8D1A2E "><?=$status?></span></li>
									</ul>
									<ul class="social">
										<li><a href="#"><i class="fa fa-facebook"></i></a></li>
										<li><a href="#"><i class="fa fa-twitter"></i></a></li>
										<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
										<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</section>




