<?php

$product_category_name = isset($all_category_products[0]['products_category'])?$all_category_products[0]['products_category']:'';
$baseUrl = Yii::app()->request->baseUrl;




?>












		<!--Page Title-->
        <section class="page-title">
        	<div class="container">
            	<div class="row clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12 pull-left">
						<h1><?=$product_category_name?></h1>
					</div>
					<div class="overlay"></div>
                </div>
            </div>
        </section>
        <!--Page Title Ends-->
		
		<div class="shop">
    <div class="container">
        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row" >
                    <br>
                    <?php
                    if(($all_category_products) && !empty($all_category_products)){


                    foreach($all_category_products as $single_products){
                        //Generic::_setTrace($single_products);


                    $products_id = isset($single_products['id']) ? base64_encode($single_products['id']) : '1';
                    $products_name = isset($single_products['product_name']) ? $single_products['product_name'] : '';
                    $products_price = isset($single_products['product_price']) ? $single_products['product_price'] : 0;
                    $images = json_decode($single_products['product_image']); ?>



                    <div class="col-md-3 col-sm-6 col-xs-12 hover-effect">
                        <div class="single-shop-item" style="height: 390px">
                            <div class="img-box">
                                <img src="<?=$images[0]?>" alt="Awesome Image">
                                <div class="default-overlay-outer">
                                    <div class="inner">
                                        <div class="content-layer">
                                            <a href="<?=$baseUrl.'/shops-products?products_id='.$products_id?>"" class="thm-btn thm-tran-bg">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.img-box -->
                            <div class="content-box">
                                <h4><a href="<?=$baseUrl.'/shops-products?products_id='.$products_id?>"><?=$products_name?></a></h4>

                                <div class="item-price">BDT <?=$products_price?></div>
                            </div>
                        </div>

                    </div>

                    <?php     } }?>





                </div>
                <div class="border-bottom"></div>
                <br>
               <!-- <ul class="page_pagination center">
                    <li><a href="#" class="tran3s"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
                    <li><a href="#" class="active tran3s">1</a></li>
                    <li><a href="#" class="tran3s">2</a></li>
                    <li><a href="#" class="tran3s"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                </ul>-->
            </div>
        </div>
    </div> 
</div> 




