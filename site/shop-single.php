<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$products_id = isset($individual_products[0]['id']) ? base64_encode($individual_products[0]['id']) : '1';
$products_category = isset($individual_products[0]['products_category']) ? $individual_products[0]['products_category'] : '';
$product_name = isset($individual_products[0]['product_name']) ? $individual_products[0]['product_name'] : '';
$images = json_decode($individual_products[0]['product_image']);
$product_price = isset($individual_products[0]['product_price']) ? $individual_products[0]['product_price'] : 0;
$product_code = isset($individual_products[0]['product_code']) ? $individual_products[0]['product_code'] : '';
$short_description = isset($individual_products[0]['short_description']) ? $individual_products[0]['short_description'] : '';
$description = isset($individual_products[0]['description']) ? $individual_products[0]['description'] : '';
$conditions = isset($individual_products[0]['conditions']) ? $individual_products[0]['conditions'] : '';
$replace_policy = isset($individual_products[0]['replace_policy']) ? $individual_products[0]['replace_policy'] : '';

?>

		<!--Page Title-->
        <section class="page-title">
        	<div class="container">
            	<div class="row clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12 pull-left">
						<h1>Product Details</h1>
					</div>
					<div class="overlay"></div>
                </div>
            </div>
        </section>
        <!--Page Title Ends-->
		
<section class="shop-single-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="single-products-details">       
                    <div class="product-content-box">
                        <div class="row">
                            <div class="col-md-6 img-box">
                                <div class="flexslider">
                                    <ul class="slides">
                                        <li data-thumb="<?=$images[0]?>">
                                            <div class="thumb-image">
                                                <img src="<?=$images[0]?>" alt="" data-imagezoom="true" class="img-responsive">
                                            </div>
                                        </li>
                                        <li data-thumb="<?=$images[1]?>">
                                            <div class="thumb-image">
                                                <img src="<?=$images[1]?>" alt="" data-imagezoom="true" class="img-responsive">
                                            </div>
                                        </li>  
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="content-box">
                                    <h3><?=$product_name?></h3>
                                    <div class="review-box">
                                        <ul>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <span class="price">Tk.<?=$product_price?></span>
                                    <p>Product Code: <b><?=$product_code?></b> </p>
                                    <div class="text">
                                        <p class="text-justify"><?=$short_description?></p>
                                    </div>
                                    <div class="addto-cart-box">
                                        <input class="quantity-spinner" type="text" value="1" name="quantity">
                                        <a onclick="productAddToCart('<?=base64_decode($products_id)?>')" href="javascript:void(0);" class="thm-btn">Add to Cart</a>
                                    </div>

                                    <div class="awards-wrapper clearfix">
                                        <div class="single-award">
                                            <div class="inner">
                                                <i class="fa fa-shield" aria-hidden="true"></i>
                                                <p><span class="block">100% Buyer protection</span></p>
                                            </div>
                                        </div>

                                        <div class="single-award">
                                            <div class="inner">
                                                <i class="fa fa-truck"></i>
                                                <p><span class="block">DELIVERED within 1-3 days</span></p>
                                            </div>
                                        </div>
                                        <div class="single-award">
                                            <div class="inner">
                                                <i class="fa fa-phone" aria-hidden="true"></i>
                                                <p><span class="block">ORDER BY CALL 01996-304100</span></p>
                                            </div>
                                        </div>
                                        <div class="single-award">
                                            <div class="inner">
                                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                                <p><span class="block">Payment by visa/mastercard</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                
                    <div class="product-tab-box">
                        <ul class="nav nav-tabs tab-menu">
                            <li class="active"><a href="#desc" data-toggle="tab">Descriprion</a></li>
                            <li><a href="#review" data-toggle="tab">Reviews (<?=count($ratings_and_reviews)?>)</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="desc">
                                <div class="product-details-content">
                                    <div class="desc-content-box">
                                        <h5>Feature</h5><br>
                                        <p class="text-justify"><?=$description?></p>

                                    </div>
                                    <hr>
                                    <div class="desc-content-box">
                                        <h5>Conditions</h5><br>

                                           <?=$conditions?>

                                    </div>
                                    <hr>
                                    <div class="desc-content-box">
                                        <h5>Replace Policy</h5><br>
                                            <?=$replace_policy?>

                                    </div>

                                </div>
                                <hr>


                            </div>
                            <div class="tab-pane " id="review">

                                <div class="review-box">
                                    <div class="tab-title-h4">
                                        <h4>Customer reviews</h4>
                                    </div>
                                    <?php

                                    if(($ratings_and_reviews) && !empty($ratings_and_reviews)){
                                    foreach($ratings_and_reviews as $single_ratings){
                                    $user_id = $single_ratings['user_id'];
                                    $criteria = new CDbCriteria();
                                    $criteria->condition = 'id = :id';
                                    $criteria->params = array(':id' => $user_id);
                                    $user_details = UserRegister::model()->find($criteria);
                                    $user_name = $user_details->user_name;
                                    $reviews = $single_ratings['review_text'];
                                    $ratings = $single_ratings['ratings'];
                                    $create_date = date_create($single_ratings['create_date']);
                                    $date_data = date_format($create_date, 'g:ia \o\n l jS F Y');
                                    // Generic::_setTrace($sub_service_details);

                                    ?>

                                        <div class="single-review-box">
                                            <div class="img-holder">
                                                <img height="60" width="60" src="<?=$baseUrl?>/images/people-avatar.png" alt="Awesome Image">
                                            </div>
                                            <div class="text-holder">
                                                <div class="top">
                                                    <div class="name pull-left">
                                                        <h4><?=$user_name?> | <?=$date_data?></h4>
                                                    </div>
                                                    <div class="review-box pull-left">
                                                        <ul>
                                                            <?php
                                                            for($i=1; $i<=$ratings; $i++){?>
                                                                <li><i class="fa fa-star"></i></li>
                                                            <?php }?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="text">
                                                    <p><?=$reviews?></p>
                                                </div>
                                            </div>

                                        </div>
                                    <?php  } } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</section>

<section class="service" style="padding: 0px 0px 30px;">
    <div class="container">
        <div class="sec-title">
            <h2>Related <span>Products</span> </h2>
        </div>

        <div class="service_carousel">
            <!--Featured Service -->

            <?php

            if(($related_products) && !empty($related_products)){


            foreach($related_products as $individual_products){

            $products_id = isset($individual_products['id']) ? base64_encode($individual_products['id']) : '1';
            $products_name = isset($individual_products['product_name']) ? $individual_products['product_name'] : '';
            $products_price = isset($individual_products['product_price']) ? $individual_products['product_price'] : 0;
            $images = json_decode($individual_products['product_image']);?>

            <article class="single-column">
                <div class="item">
                    <figure class="img-box">
                        <img src="<?=$images[0]?>" alt="">
                        <figcaption class="default-overlay-outer">
                            <div class="inner">
                                <div class="content-layer">
                                    <a href="<?=$baseUrl.'/shops-products?products_id='.$products_id?>" class="thm-btn thm-tran-bg">View Details</a>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                    <div class="content center">

                        <a href="<?=$baseUrl.'/shops-products?products_id='.$products_id?>"><h4><?=$products_name?></h4></a>

                    </div>
                </div>
            </article>
            <?php   } }  ?>
        </div>

    </div>
</section>




<div class="row" style="display: none">
    <div class="col-sm-2 text-center">
        <p><button class="btn btn-primary sweet-4 alert_shop" onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-4']);">Try It</button></p>
    </div>
</div>

   <script type="text/javascript">

       function productAddToCart(products_id){

        var button_content = $(this).find('button[type=submit]');
        button_content.html('Adding...'); //Loading button text

        var quantity =  $('input[name=quantity]').val();

        $.ajax({
            type : 'POST',
            async: false,
            url  : SITE_URL+"site/CartProcessForProducts",
            cache: false,
            data:{products_id:products_id,quantity:quantity},
            dataType:"json",
            success: function(data)
            {
                $("#cart-info-products").html(data.items); //total items in cart-info element
                button_content.html('Add to Cart'); //reset button text to original text
                window.setTimeout(showAlert,1000);
                function showAlert(){

                    $('.alert_shop').trigger('click');
                }
                if($(".shopping-cart-box-products").css("display") == "block"){ //if cart box is still visible
                    $(".cart-box-products").trigger( "click" ); //trigger click to update the cart box.
                }
            },
            error: function(){
                alert('Error!');
            }
        })}


    function baseUrl(){
        var href=window.location.href.split('/');
        return href[0]+'//'+href[2]+'/';
    }
    var SITE_URL=baseUrl();
    $(document).ready(function(){

        //Show Items in Cart
        $( ".cart-box-products").click(function(e) {
            //when user clicks on cart box
            e.preventDefault();
            $(".shopping-cart-box-products").fadeIn(); //display cart box
            //$("#shopping-cart-results").html('<img src="../../../images/ajax-loader.gif">'); //show loading image
            $("#shopping-cart-results-products" ).load( SITE_URL+"site/CartProcessForProducts", {"load_cart":"1"}); //Make ajax request using jQuery Load() & update results
        });

        //Close Cart
        $( ".close-shopping-cart-box-products").click(function(e){ //user click on cart box close link
            e.preventDefault();
            $(".shopping-cart-box-products").fadeOut(); //close cart-box
        });

        //Remove items from cart
        $("#shopping-cart-results-products").on('click', 'a.remove-item', function(e) {
            e.preventDefault();
            var pcode = $(this).attr("data-code"); //get product code
            $(this).parent().fadeOut(); //remove item element from box
            $.getJSON( SITE_URL+"site/CartProcessForProducts", {"remove_code":pcode} , function(data){ //get Item count from Server
                $("#cart-info-products").html(data.items); //update Item count in cart-info
                $(".cart-box-products").trigger( "click" ); //trigger click on cart-box to update the items list
            });
        });

    });

</script>

<script>
    document.querySelector('.sweet-4').onclick = function(){
        swal({
                title: "Success !!",
                text: "Product Successfully Added To Cart.",
                type: "success",
                showCancelButton: false,
                closeOnConfirm: true
               // closeOnCancel: false
            },
            function(){
                sweetAlert.close();
            });
    };



</script>