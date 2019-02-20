<?php

//$regex = strtoupper(strtr($name_string, array('.' => '', ',' => '',' '=>'')));
$baseUrl = Yii::app()->getBaseUrl(true);
$category_name = isset($shop_details[0]['category_name'])? $shop_details[0]['category_name'] : " ";
$view_url = $baseUrl.'/'.Yii::app()->request->getPathInfo();
//Generic::_setTrace($view_url);
//$date = date_create($service_date);
//$date_data = date_format($date, 'g:ia \o\n l jS F Y');

?>

		<!--Page Title-->
        <section class="page-title">
        	<div class="container">
            	<div class="row clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12 pull-left">
						<h1><?=$category_name?></h1>
					</div>
                    <div class="col-md-6 col-sm-6 col-xs-12 pull-right text-right path"><a href="/">Home</a>&ensp;/&ensp;<a href="#"><?=$category_name?></a></div>
					<div class="overlay"></div>
                </div>
            </div>
        </section>
        <!--Page Title Ends-->
		
		<!--team section-->
		<section class="our-gallery">
			<div class="container">
				<div class="row">
					<?php
					if(($shop_details) && !empty($shop_details)){

					foreach($shop_details as $individual_category){

					$sub_category_name = $individual_category['sub_category_name'];
					$images = json_decode($individual_category['sub_category_image']);
						$sub_category_slug = $individual_category['sub_category_slug'];
					?>

					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="single-item">
							<div class="img-holder">							
								<img src="<?=$images[0]?>" alt="<?=$sub_category_name?>"/>
								<div class="overlay">
									<div class="inner">
										<div class="social">
											<a href="<?=$view_url.'/'.$sub_category_slug?>" data-fancybox-group="example-gallery" class="view lightbox-image"><h4><?=$sub_category_name?></h4></a>
										</div>
										
									</div>
								</div>
							</div>
						
						</div>
					</div>
               <?php }} ?>

				</div>					
			</div>
		</section>
		

