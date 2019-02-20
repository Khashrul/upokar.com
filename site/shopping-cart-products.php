<?php
//$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
//echo $dt->format('F j, Y, g:i a');
$baseUrl = Yii::app()->getBaseUrl(true);
//Generic::_setTrace($baseUrl);
$total = 0;
$shipping_cost = 50;
//Generic::_setTrace($_SESSION["shop_product"]);
?>
	<!--Page Title-->
    <!--Page Title Ends-->
   <section class="checkout-area">
            <div class="container">
                <div class="row bottom">
                    <div class="col-lg-12 col-md-12" id="shopping-cart-results-productss">
                        <?php


                        if(isset($_SESSION["shop_product"]) && count($_SESSION["shop_product"])>0){?>

                        <div class="table">
                            <div class="sec-title-two">
                                <h3 style="text-transform: uppercase">Order Summary</h3>
                            </div>
                            <table class="cart-table">
                                <thead class="cart-header">
                                <tr>
                                    <th class="product-column">Particular</th>

                                    <th>&nbsp;</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th class="price">Total</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                if(isset($_SESSION["shop_product"]) && count($_SESSION["shop_product"])>0){
                                    //if we have session variable
                                    $total = 0;
                                    $counter = 0;
                                    foreach($_SESSION["shop_product"] as $product){ //loop though items and prepare html content

                                        $product_name = $product["product_name"];
                                        $product_price = $product["product_price"];
                                       // Generic::_setTrace($product_price);
                                        $product_qty = $product["quantity"];
                                        $product_image = json_decode($product["product_image"]);
                                        $product_image = $product_image[0];
                                        $currency = "BDT ";
                                        $product_code = $product["products_id"];
                                        $subtotal = ($product_price * $product_qty);
                                        $total = ($total + $subtotal);
                                        $discount_calculator = 0.00;

                                        ?>

                               <tr>
                                   <td colspan="2" class="product-column">
                                       <div class="column-box">
                                           <div class="prod-thumb">
                                               <a href="#"><img src="<?=$product_image?>" alt=""></a>
                                           </div>
                                           <div class="product-title">
                                               <h3> <?=$product_name?></h3>
                                           </div>
                                       </div>
                                   </td>
                                   <td class="product_price">
                                       &#2547; <?=$product_price?>
                                   </td>
                                   <td class="qty">
                                       <?=$product_qty?>
                                   </td>
                                   <td class="price">&#2547; <?=number_format($subtotal)?></td>
                                   <td><a href="#" id="remove-items" class="remove-item" data-code="<?=$product_code?>">Remove</a></td>
                               </tr>
                                        <?php
                                        $counter++;
                                    }}?>


                               </tbody>
                            </table>

                        </div>


                    </div>
                </div>
                <div class="row bottom">
                    <div class="col-lg-12 col-md-12">
                        <div class="cart-total">


                            <h4><label style="text-transform: uppercase" for="usr">Apply Coupon Code :</label></h4>
                            <br>
                            <input class="form-control" style="width: 30%" type='text' id='code'>
                            <br>
                            <div class="alert-danger" id='Promo_code_status'></div>
                            <br>
                            <div class="sec-title-two">
                                <h3 style="text-transform: uppercase">Total amount</h3>
                            </div>
                            <ul class="cart-total-table">
                                <li class="clearfix">
                                    <span class="col col-title">Cart Subtotal</span>
                                    <span class="col">&#2547; <?=$total?></span>
                                    <input type="hidden" name="total_price" id="total_price" value="<?=$total?>">
                                </li>
                                <li class="clearfix">
                                    <span class="col col-title">Discount</span>
                                    <span id="discounted_price" class="col">&#2547; <?=$discount_calculator?></span>
                                </li>
                                <li class="clearfix">
                                    <span class="col col-title">Shipping and Handling</span>
                                    <span class="col">&#2547; 50</span>
                                </li>
                                <?php
                                $calculated_amount = ($total - $discount_calculator) + $shipping_cost;
                                ?>
                                <li class="clearfix">
                                    <span class="col col-title">Order Total</span>
                                    <span id="calculated_amount" class="col">&#2547; <?=$calculated_amount?></span>
                                </li>
                            </ul>
                            <br>

                            <div class="payment-options">
                                <div class="placeorder-button text-left">
                                     <button onClick='submitDetailsForm()' type="submit" class="thm-btn">Proceed to Checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <form id="hidden_form" method="POST" action="<?=$baseUrl?>/products-checkout">

                    <input type="hidden" name="order_total" value="<?=$calculated_amount?>" id="order_total">
                    <input type="hidden" name="cart_total" value="<?=$total?>" id="cart_total">
                    <input type="hidden" name="discounted_amount" value="<?=$discount_calculator?>" id="discounted_amount">
                    <input type="hidden" name="final_amount" value="<?=$calculated_amount?>" id="final_amount">
                    <input type="hidden" name="shipping_handling" value="50" id="shipping_handling">
                   


                </form>


                <?php } else {

                    echo "Your Shopping Cart Is Empty.";

                } ?>



            </div>



        </section>


   <script>
    $(document).ready(function() {
        //the min chars for promo-code
        var min_chars = 6;
        //result texts
        var checking_html = 'Checking...';
        //when keyup
        $('#code').keyup(function(event){
            //run the character number check
            if($('#code').val().length == min_chars){
                //show the checking_text and run the function to check
                $('#Promo_code_status').html(checking_html);
                check_code();
            }
        });

    });

    function submitDetailsForm() {
        $("#hidden_form").submit();
    }




    //function to check the promo code
    function check_code(){
        //get code
        var code = $('#code').val();
        var total_price = $('#total_price').val();
        //use ajax to run the check

        $.ajax({
            type: "POST",
            url: SITE_URL+"site/CheckCouponCode",
            data: {code: code,total_price:total_price},
            cache: false,
            dataType:"json",
            success: function(data) {

                if(data.status == "Success"){
                    //show that the code is correct
                    $('#Promo_code_status').html('Coupon Code   Is Valid.');
                    $('#discounted_price').html('&#2547; '+ data.discounted_value);
                    $('#calculated_amount').html('&#2547; '+ data.calculated_amount);
                    $('input[name=order_total]').val(data.calculated_amount);
                    $('input[name=discounted_amount]').val(data.discounted_value);
                    $('input[name=final_amount]').val(data.final_amount);
                }else{
                    //show that the code is not correct
                    $('#Promo_code_status').html('Coupon Code  Is Invalid.');
                }
            }});
    }
</script>

<script>
    function baseUrl(){
        var href=window.location.href.split('/');
        return href[0]+'//'+href[2]+'/';
    }
    var SITE_URL=baseUrl();
    var cart_url = '<?=$baseUrl?>/shopping-cart-products';
    $(document).ready(function(){

        //Remove items from cart
        $("#shopping-cart-results-productss").on('click', 'a.remove-item', function(e) {
            e.preventDefault();
            var pcode = $(this).attr("data-code"); //get product code
            $(this).parent().fadeOut(); //remove item element from box
            $.getJSON( SITE_URL+"site/CartProcessForProducts",
                {"remove_code":pcode} , function(data){ //get Item count from Server
                window.location.href=cart_url;
            });
        });

    });
</script>