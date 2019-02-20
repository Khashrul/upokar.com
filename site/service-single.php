<?php
$service_name = isset($sub_service_details[0]['service_name']) ? $sub_service_details[0]['service_name'] : '';
$service_id = isset($sub_service_details[0]['id']) ? $sub_service_details[0]['id'] : '';
$service_image = isset($sub_service_details[0]['service_image']) ? $sub_service_details[0]['service_image'] : '';
$initial_price = isset($tag_details[0]['tag_price']) ? $tag_details[0]['tag_price'] : '0';
$service_description = isset($sub_service_details[0]['service_description']) ? $sub_service_details[0]['service_description'] : '';
$service_condition = isset($sub_service_details[0]['service_condition']) ? $sub_service_details[0]['service_condition'] : '';
$replace_policy = isset($sub_service_details[0]['replace_policy']) ? $sub_service_details[0]['replace_policy'] : '';
$initial_tag_name = isset($tag_details[0]['tag_name']) ? $tag_details[0]['tag_name'] : '';
$initial_tag_price = isset($tag_details[0]['tag_price']) ? $tag_details[0]['tag_price'] : '';
$initial_tag_id = isset($tag_details[0]['id']) ? $tag_details[0]['id'] : '';
$initial_quantity = 1;
$initial_expert_name = isset($expert_details[0]['id']) ? $expert_details[0]['id'] : '';
$baseUrl = Yii::app()->getBaseUrl(true);
$location_name_in_cookie = Yii::app()->request->cookies['location_name'];
if(!empty($location_name_in_cookie)){
    $location_name_in_cookie = Yii::app()->request->cookies['location_name']->value;
}
$actionUrl = "site/PlaceLocation";
?>
<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/component.css" />
<script src="<?=$baseUrl?>/js/modernizr.custom.js"></script>
<link href="<?=$baseUrl?>/css/datedropper.css" rel="stylesheet" type="text/css" />
<!-- jQuery lib -->

<!-- dateDropper lib -->
<script src="<?=$baseUrl?>/js/datedropper.js"></script>





<div class="md-modal md-effect-7" id="modal-7">
    <div class="md-content" style="background: transparent">
        <div>
            <div class="time-container">
                    <div class="time-body">
                        <div class="row custom-scrollbar" id="time-slot-container">
                            <div class="col-md-6 col-sm-6"><div id="time">
                                    <label for="time">9:00 AM-10:00 AM</label>
                                    <input onclick="placeTimeRange('9:00 AM-10:00 AM')" value="09:00:00-10:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">10:00 AM-11:00 AM</label>
                                    <input onclick="placeTimeRange('10:00 AM-11:00 AM')" value="10:00:00-11:00:00" type="radio"></div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">11:00 AM-12:00 PM</label>
                                    <input onclick="placeTimeRange('11:00 AM-12:00 PM')" value="11:00:00-12:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">12:00 PM-1:00 PM</label>
                                    <input onclick="placeTimeRange('12:00 PM-1:00 PM')" value="12:00:00-13:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">1:00 PM-2:00 PM</label>
                                    <input onclick="placeTimeRange('1:00 PM-2:00 PM')" value="13:00:00-14:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">2:00 PM-3:00 PM</label>
                                    <input onclick="placeTimeRange('2:00 PM-3:00 PM')" value="14:00:00-15:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">3:00 PM-4:00 PM</label>
                                    <input onclick="placeTimeRange('3:00 PM-4:00 PM')" value="15:00:00-16:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">4:00 PM-5:00 PM</label>
                                    <input onclick="placeTimeRange('4:00 PM-5:00 PM')" value="16:00:00-17:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">5:00 PM-6:00 PM</label>
                                    <input onclick="placeTimeRange('5:00 PM-6:00 PM')" value="17:00:00-18:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">6:00 PM-7:00 PM</label>
                                    <input onclick="placeTimeRange('6:00 PM-7:00 PM')" value="18:00:00-19:00:00" type="radio"></div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">7:00 PM-8:00 PM</label>
                                    <input onclick="placeTimeRange('7:00 PM-8:00 PM')" value="19:00:00-20:00:00" type="radio">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div id="time">
                                    <label for="time">8:00 PM-9:00 PM</label>
                                    <input onclick="placeTimeRange('8:00 PM-9:00 PM')" value="20:00:00-21:00:00" type="radio">
                                </div>
                            </div>
                        </div>
                    </div>
                <br>
                <button type="button" class="btn btn-danger md-close">Close !</button>
            </div>


        </div>
    </div>
</div>





<!--Page Title-->
    <section>
        <div class="container">
            <br>
            <h1><?=$service_name?></h1>
        </div>
    </section>
    <!--Page Title Ends-->
<section class="shop-single-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="single-products-details">

                        <form class="form-items">

                       <input name="service_id" type="hidden" value="<?=$service_id?>">
                       <input name="sub_service_name" type="hidden" value="<?=$service_name?>">
                       <input name="tag_name" type="hidden" value="<?=$initial_tag_name?>">
                       <input name="service_price" type="hidden" value="<?=$initial_tag_price?>">
                       <input name="tag_id" type="hidden" value="<?=$initial_tag_id?>">
                       <input name="expert_name" type="hidden" value="<?=$initial_expert_name?>">

                        <div class="product-content-box">
                            <div class="row">
                                <div class="col-md-6 img-box">
                                    <div class="flexslider">
                                        <ul class="slides">
                                            <input name="service_image_url" type="hidden" value="<?=$baseUrl?>/uploaded/service_image/<?=$service_image?>">
                                            <li data-thumb="<?=$baseUrl?>/uploaded/service_image/<?=$service_image?>">
                                                <div class="thumb-image">
                                                    <img src="<?=$baseUrl?>/uploaded/service_image/<?=$service_image?>" alt="">
                                                </div>
                                            </li>
                                            <li data-thumb="<?=$baseUrl?>/uploaded/service_image/<?=$service_image?>">
                                                <div class="thumb-image">
                                                    <img src="<?=$baseUrl?>/uploaded/service_image/<?=$service_image?>" alt="">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <ul class="post-filter list-inline">
                                        <li class="service_price" data-filter=".filter-item">
                                            <span>Service Charge Tk <?=$initial_price?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <div class="content-box btn-group-justified">
                                        <ul class="post-filter list-inline">

                                            <?php


                                            if(($tag_details) && !empty($tag_details)){
                                                $counter = 0;
                                                $li_class = "";
                                                foreach($tag_details as $individual_tags){

                                                    if($counter == 0){
                                                        $class = "active";
                                                    } else {
                                                        $class = "";
                                                    }
                                                $tag_title = $individual_tags['tag_name'];
                                                $tag_price = $individual_tags['tag_price'];
                                                $tag_id = $individual_tags['id'];

                                                    $counter++;

                                            ?>
                                                <li class="<?=$class?>" data-filter=".filter-item">
                                                <span onclick="changePrice('<?=$tag_price?>','<?=$tag_title?>','<?=$tag_id?>');"><?=$tag_title?></span>
                                                </li>

                                            <?php }

                                            } ?>

                                        </ul>
                                      <!--  <div class="text">
                                            <textarea style="border: 1px solid #eee; width: 100%;height: 20%" placeholder="Add your required instruction"></textarea>
                                        </div>-->
                                        <br>
                                        <div class="location-box btn-group-justified">

                                                <label>
                                                    <p>Quantity</p>
                                                    <input type="text"  name="quantity" value="1">
                                                </label>
                                                <label>
                                                    <p>Expected Date</p>
                                                    <input name="service_date" type="text"  name="perfect" readonly class="form_datetime">
                                                </label>
                                                <label>
                                                    <p>Expected Time</p>
                                                    <input name="time_range" id="time_range" class="md-trigger" placeholder="Time Range" data-modal="modal-7">

                                                </label>
                                         <br>
                                            <div>
                                                <p>Our Experts</p>
                                                <ul class="expert-filter list-inline">
                                                    <?php
                                                    if(($expert_details) && !empty($expert_details)){
                                                    $counter = 0;
                                                    $li_class = "";
                                                    foreach($expert_details as $individual_experts){

                                                       if($counter == 0){
                                                        $class = "active";
                                                       } else {
                                                        $class = "";
                                                       }
                                                    $expert_image = $individual_experts['expert_image'];
                                                    $expert_name = $individual_experts['expert_name'];
                                                    $expert_id = $individual_experts['id'];
                                                    $counter++;

                                                    ?>
                                                        <li class="<?=$class?>" data-filter=".filter-item">
				                                        <span><img onclick="changeExpert('<?=$expert_id?>');" height="85px" width="85px" src="<?=$baseUrl?>/uploaded/expert_image/<?=$expert_image?>" title="<?=$expert_name?>" alt="<?=$expert_name?>"></span>
                                                   </li>
                                                    <?php }} ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="addto-cart-box">
                                            <button class="thm-btn" style="width: 100%; text-align: center" type="submit">Take Service</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            </form>

                        <div class="product-tab-box">
                            <ul class="nav nav-tabs tab-menu">
                                <li class="active"><a href="#desc" data-toggle="tab">Descriprion</a></li>
                                <li><a href="#review" data-toggle="tab">Reviews (<?=count($ratings_and_reviews)?>)</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="desc">
                                    <div class="product-details-content">
                                        <div class="desc-content-box">
                                            <p class="text-justify"><?=$service_description?></p>
                                        </div>
                                        <hr>
                                        <div class="desc-content-box">
                                            <h5>Conditions</h5><br>
                                            <p class="text-justify"><?= $service_condition?></p>
                                        </div>
                                        <hr>
                                        <div class="desc-content-box">
                                            <p class="text-justify"><?=$replace_policy?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="review">

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
                                                $reviews = $single_ratings['reviews'];
                                                $ratings = $single_ratings['ratings'];
                                                $create_date = date_create($single_ratings['create_date']);
                                                $date_data = date_format($create_date, 'g:ia \o\n l jS F Y');
                                               // Generic::_setTrace($sub_service_details);

                                                ?>

                                        <div class="single-review-box">
                                            <div class="img-holder">
                                                <img height="60" width="60" src="<?=$baseUrl?>/images/avator.png" alt="Awesome Image">
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


<script src="<?=$baseUrl?>/js/classie.js"></script>
<script src="<?=$baseUrl?>/js/modalEffects.js"></script>

<script>
    // this is important for IEs
    var polyfilter_scriptpath = '<?=$baseUrl?>/js/';
</script>


<script src="<?=$baseUrl?>/js/cssParser.js"></script>
<script src="<?=$baseUrl?>/js/css-filters-polyfill.js"></script>


<script type="text/javascript">
    $('input.form_datetime').dateDropper();

    function placeTimeRange(time_range){
        $("#time_range").val(time_range);

    }


    function changeExpert(expert_name){
        $('input[name=expert_name]').val(expert_name);

    }

    function changePrice(price,tag_name,tag_id){
        $('input[name=service_price]').val(price);
        $('input[name=tag_name]').val(tag_name);
        $('input[name=tag_id]').val(tag_id);

          $.ajax({
            type : 'POST',
            async: false,
            url  : SITE_URL+"site/ChangePrice",
            cache: false,
            data:{tag_price:price},
            dataType:"json",
            success: function(data)
            {
                if (data) {
                    $(".service_price").html(data.html);
                    $('.service_price').addClass("btn-danger");

                    window.setTimeout(removeClass,2000);
                    function removeClass(){
                        $('.service_price').removeClass("btn-danger");
                    }


                }
            },
            error: function(){
                alert('Error!');
            }
        })}

</script>

<style>

    .time-container {
        width:100%;
        height:auto;
        background-color:#f2f2f2;
        border:3px solid #b7bfc2;
        border-radius:4px;
        box-shadow:0 1px 5px 0 #f8fafa;
        padding:26px
    }
    .time-container .time-body #time-slot-container {
        height:333px;
        overflow-y:auto
    }
    .time-container .time-body .no-slots-error {
        height:333px
    }
    .time-container .time-header {
        text-align:center;
        margin-bottom:10px;
        width:100%;
        padding:9px 15px 17px
    }
    .time-container .time-header span {
        position:relative
    }
    .time-container .time-header span .next-btn,.time-container .time-header span .prev-btn {
        content:"";
        border:none;
        margin:5px auto;
        height:12px;
        width:12px;
        border-left:1px solid #a1b3bc;
        border-bottom:1px solid #a1b3bc;
        cursor:pointer
    }
    .time-container .time-header span .next-btn {
        float:right;
        transform:rotate(-135deg)
    }
    .time-container .time-header span .prev-btn {
        float:left;
        transform:rotate(45deg)
    }
    .time-container #time {
        position:relative;
        width:100%;
        height:48px;
        margin:10px auto;
        text-align:center
    }
    .time-container #time input[type=radio] {
        -webkit-appearance:none;
        -moz-appearance:none;
        appearance:none;
        -webkit-background-color:#fff;
        background-color:#fff;
        border:1px solid #eaf0f2;
        width:100%;
        height:100%;
        border-radius:24px;
        cursor:pointer;
        transition:all .2s ease;
        box-sizing:border-box
    }
    .time-container #time input[type=radio]:checked,.time-container #time input[type=radio]:hover {
        -webkit-background-color:#00a7f7;
        background-color:#00a7f7;
        border:none;
        color:#fff
    }
    .time-container #time input[type=radio]:focus {
        -webkit-background-color:#00a7f7;
        background-color:#00a7f7;
        outline:0;
        border:none
    }
    .time-container #time input[type=radio]:checked+label {
        color:#fff
    }
    .time-container #time label {
        position:absolute;
        top:50%;
        left:50%;
        transform:translate(-50%,-50%);
        z-index:100;
        pointer-events:none;
        font-size:12px;
        width:100%
    }
    .time-container .load-more {
        width:100%;
        text-align:center
    }
    .time-container .load-more button.btn.btn-link {
        font-size:18px;
        color:#2ba88f
    }



    .product-content-box .content-box .location-box  input {
        border: 2px solid #f7f7f7;
        height: 40px;
        padding-left: 10px;
        padding-right: 10px;
        transition: all 500ms ease 0s;
        width: 170px;
    }

    .product-content-box .content-box .location-box  input:focus {
        border-color:#48c7ec ;
    }


</style>



