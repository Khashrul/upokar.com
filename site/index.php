<?php

$baseUrl = Yii::app()->getBaseUrl(true);

$session = Yii::app()->session['user_selected_location'];
$location_name_in_cookie = Yii::app()->request->cookies['location_name'];
//Generic::_setTrace($location_name_in_cookie);
if(!empty($location_name_in_cookie)){
    $location_name_in_cookie = Yii::app()->request->cookies['location_name']->value;

}


?>


<!--Start rev slider wrapper-->
<section class="rev_slider_wrapper">
    <div id="slider1" class="rev_slider"  data-version="5.0">

        <img class="img-responsive" src="<?=$baseUrl?>/images/ramadan Banner-03-03.jpg"  alt="" width="1920" height="500" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >

    </div>
</section>
<!--End rev slider wrapper-->



<section class="feature">
        <div class="container">
            <div class="sec-title">
                <h2>Our <span>Services</span> </h2>
            </div>
            <div class="item-list">
                <div class="row">

                   <?php

                   if(($all_services) && !empty($all_services)){

                       foreach($all_services as $individual_services){

                         $service_title = $individual_services['service_name'];
                         $service_description = $individual_services['service_description'];
                        $url = Generic::slugToUrl($service_title)

                     ?>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="item">
                            <figure class="image-box">
                                <img src="<?=$baseUrl?>/uploaded/service_image/<?=$individual_services['service_image']?>" alt="" />
                                <div class="overlay">
                                    <div class="content-box">
                                        <h3><?=$service_title?></h3>
                                        <p><?=$service_description?></p>
                                    </div>
                                </div>
                            </figure>


                            <div class="feature-btn text-center">
                                <a href="<?=$url?>" class="button-style-two">read more</a>
                            </div>
                        </div>
                    </div>
                       <?php } } ?>
                </div>
            </div>
        </div>
    </section>


    <div class="container">
        <div class="sec-title">
            <h2>Our <span> Shop</span> </h2>
        </div>
        <section class="our-gallery" style="padding: 0px 0px 100px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="single-item">
                            <div class="img-holder">
                                <img src="<?=$baseUrl?>/images/gallery/g-1.jpg" alt="Awesome Image"/>
                                <div class="overlay">
                                    <div class="inner">
                                        <div class="social">
                                            <a href="<?=$baseUrl?>/shop-category/power-and-electronics" data-fancybox-group="example-gallery" class="view lightbox-image"><h4>Power And Electronics</h4></a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="single-item">
                            <div class="img-holder">
                                <img src="<?=$baseUrl?>/images/gallery/g-2.jpg" alt="Awesome Image"/>

                                <div class="overlay">
                                    <div class="inner">
                                        <div class="social">
                                            <a href="<?=$baseUrl?>/shop-category/security-and-safety" data-fancybox-group="example-gallery" class="view lightbox-image"><h4>Security And Safety</h4></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="single-item">
                            <div class="img-holder">
                                <img src="<?=$baseUrl?>/images/gallery/g-3.jpg" alt="Awesome Image"/>


                                <div class="overlay">
                                    <div class="inner">
                                        <div class="social">
                                            <a href="shop.html" data-fancybox-group="example-gallery" class="view lightbox-image"><h4>Computer And Networking</h4></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="single-item">
                            <div class="img-holder">
                                <img src="<?=$baseUrl?>/images/gallery/g-4.jpg" alt="Awesome Image"/>
                                <div class="overlay">
                                    <div class="inner">
                                        <div class="social">
                                            <a href="shop.html" data-fancybox-group="example-gallery" class="view lightbox-image"><h4>Smart Gadgets</h4></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </section>

    </div>






