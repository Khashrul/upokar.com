<?php

$baseUrl = Yii::app()->getBaseUrl(true);

$session = Yii::app()->session['user_selected_location'];
$location_name_in_cookie = Yii::app()->request->cookies['location_name'];
//Generic::_setTrace($location_name_in_cookie);
if(!empty($location_name_in_cookie)){
	$location_name_in_cookie = Yii::app()->request->cookies['location_name']->value;

}


?>

	<!--Page Title-->
	<section class="page-title">
		<div class="container">
			<div class="row clearfix">
				<div class="col-md-6 col-sm-6 col-xs-12 pull-left">
					<h1>error page</h1>

				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 pull-right text-right path"><a href="/">Home</a>&ensp;/&ensp;<a href="#">error page</a></div>
				<div class="overlay"></div>
			</div>
		</div>
	</section>
	<!--Page Title Ends-->

	<section class="error-page">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<figure class="image-box">
						<img src="<?=$baseUrl?>/images/resources/error1.jpg" alt="" />
					</figure>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="content-box">
						<h2>Oops! Page Not Found!</h2>
						<p>Try to Search for the Best Match or Visit our Home Page</p>
						<div class="sidebar_search">
							<form action="#">
								<input type="text" placeholder="Search....">
								<button class="tran3s color1_bg"><i class="fa fa-search" aria-hidden="true"></i></button>
							</form>
						</div>
						<ul class="link_btn">
							<li><a href="index.html" class="thm-btn style-two">go to home</a></li>
						</ul>
					</div>

				</div>
			</div>
		</div>
	</section>


