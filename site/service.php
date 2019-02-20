<?php
$baseUrl = Yii::app()->getBaseUrl(true);

$location_name_in_cookie = Yii::app()->request->cookies['location_name'];
if(!empty($location_name_in_cookie)){
	$location_name_in_cookie = Yii::app()->request->cookies['location_name']->value;
}
$actionUrl = "site/PlaceLocation";
?>


<script>

	jQuery(document).ready(function() {
		var location_in_cookie = '<?=$location_name_in_cookie?>';

		if(location_in_cookie){
			$('#myModal').modal('hide');
		}else{
			$('#myModal').modal('show');
		}
	});

</script>

<div id="myModal" class="modal fade" role="dialog">

	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div data-v-5fde72a8="" class="modal-mask">
					<div data-v-5fde72a8="" class="modal-wrapper">
						<div data-v-5fde72a8="" class="modal-container location-modal-full-container">
							<div data-v-5fde72a8="" class="modal-body">
								<form  action="javascript:void(0);" method="post">
									<div data-v-5fde72a8="" class="form-group">
										<label data-v-5fde72a8="">Select your location</label>
										<select id="selected_location" class="form-control" name="selected_location">
											<option  data-v-5fde72a8="" value="" disabled="disabled">Choose a Location</option>
											<option  data-v-5fde72a8="" value="Banani">Banani</option>
											<option  value="Baridhara DOHS">Baridhara DOHS</option>
											<option  value="Bashundhara R/A">Bashundhara R/A</option>
											<option  value="Dhanmondi">Dhanmondi</option>
											<option  value="Farmgate">Farmgate</option>
											<option  value="Gulshan">Gulshan</option>
											<option  value="Malibag">Malibag</option>
											<option  value="Mirpur">Mirpur</option>
											<option  value="Rest Of Dhaka">Rest Of Dhaka</option>
										</select>
									</div>
								</form>
								<button class="button-small">Select Location </button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<!--Page Title-->
        <section class="page-title">
        	<div class="container">
            	<div class="row clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12 pull-left">
						<h1><?=$sub_service_name?> </h1>
					</div>
					<div class="overlay"></div>
                </div>
            </div>
        </section>
        <!--Page Title Ends-->
		
		<section class="service style-2">
		    <div class="container">
		       <div class="row">
				   <?php
				   if(($sub_service_details) && !empty($sub_service_details)){

				   foreach($sub_service_details as $individual_services){

				       $service_title = $individual_services['service_name'];
				       $service_description = $individual_services['service_description'];
					   $initial_price = $individual_services['initial_price'];
					   $url = Generic::slugToUrl($service_title);
					?>

		       		<div class="col-md-4 col-sm-6">
		       		<!--Featured Service -->
		            <article class="single-column" >
		                <div class="item" style="height: 410px">
		                    <figure class="img-box">
		                        <img src="<?=$baseUrl?>/uploaded/service_image/<?=$individual_services['service_image']?>" alt="">
		                        <figcaption class="default-overlay-outer">
		                            <div class="inner">
		                                <div class="content-layer">
		                                    <a href="<?=$service_slug.'/'.$url?>" class="thm-btn thm-tran-bg">View Details</a>
		                                </div>
		                            </div>
		                        </figcaption>
		                    </figure>
		                    <div class="content center">
		                        <h5>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
								</h5>
		                        <a href="<?=$service_slug.'/'.$url?>"><h4><?=$service_title?></h4></a>
		                        <div class="text">
		                            <p>
										Start Form TK <?=$initial_price?>
									</p>
		                        </div>
		                    </div>
		                </div>
		            </article>
		       	</div>


				   <?php } } ?>

		             </div>
		            
		    </div>
		</section>


<script type="text/javascript">



	function showModal(){
		$('#myModal').modal('show');
	}

	$('#myModal').modal({backdrop: 'static', keyboard: false})
	$("button").click(function(){
		placeLocation();

	});

	function  placeLocation(){
		var selected_location = $('#selected_location').find(":selected").text();
		var actionUrl = '<?=$actionUrl?>';
		$.ajax({
			type: "POST",
			url: SITE_URL + actionUrl,
			data: {selected_location:selected_location},
			cache: false,
			dataType:"json",
			success: function(data) {
				$(".location_value").html(data.location);
				$(".modal").modal("hide");
			}});
	}


</script>



