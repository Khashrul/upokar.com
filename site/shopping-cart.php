<?php
//$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
//echo $dt->format('F j, Y, g:i a');
$baseUrl = Yii::app()->request->baseUrl;
$total = 0;
//Generic::_setTrace($_SESSION["product"]);
?>
	<!--Page Title-->
    <!--Page Title Ends-->
   <section class="checkout-area">
            <div class="container">
                <div class="row bottom">
                    <div class="col-lg-12 col-md-12" id="shopping-cart-results-data">
                        <?php


                        if(isset($_SESSION["product"]) && count($_SESSION["product"])>0){?>

                        <div class="table">
                            <div class="sec-title-two">
                                <h3 style="text-transform: uppercase">Order Summary</h3>
                            </div>
                            <table class="cart-table">
                                <thead class="cart-header">
                                <tr>
                                    <th class="product-column">Particular</th>

                                    <th>&nbsp;</th>
                                    <th class="expert">Expert Name</th>
                                    <th>Quantity</th>
                                    <th class="price">Total</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                if(isset($_SESSION["product"]) && count($_SESSION["product"])>0){
                                    //if we have session variable
                                    $total = 0;
                                    $counter = 0;
                                    foreach($_SESSION["product"] as $product){ //loop though items and prepare html content

                                        $service_name = $product["service_name"];
                                        $tag_name = $product["tag_name"];
                                        $tag_price = $product["tag_price"];
                                        $product_qty = $product["quantity"];
                                        $service_image = $product["service_image_url"];
                                        $currency = "BDT ";
                                        $product_code = $product["tag_id"];
                                        $expert_id = $product["expert_name"];
                                        $expert_details = Generic::getExpertDetailsUsingExpertID($expert_id);
                                        $subtotal = ($tag_price * $product_qty);
                                        $total = ($total + $subtotal);


                                        ?>

                               <tr>
                                   <td colspan="2" class="product-column">
                                       <div class="column-box">
                                           <div class="prod-thumb">
                                               <a href="#"><img src="<?=$service_image?>" alt=""></a>
                                           </div>
                                           <div class="product-title">
                                               <h3> <?=$tag_name?></h3>
                                           </div>
                                       </div>
                                   </td>
                                   <td class="qty">
                                       <?=$expert_details['expert_name']?>
                                   </td>
                                   <td class="qty">
                                       <?=$product_qty?>
                                   </td>
                                   <td class="price">&#2547; <?=$subtotal?></td>
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
                            <div class="sec-title-two">
                                <h3 style="text-transform: uppercase">Total amount</h3>
                            </div>
                            <ul class="cart-total-table">
                                <li class="clearfix">
                                    <span class="col col-title">Cart Subtotal</span>
                                    <span class="col">&#2547; <?=$total?>.00</span>
                                </li>
                                <li class="clearfix">
                                    <span class="col col-title">Shipping and Handling</span>
                                    <span class="col">&#2547; 0.00</span>
                                </li>
                                <li class="clearfix">
                                    <span class="col col-title">Order Total</span>
                                    <span class="col">&#2547; <?=$total?>.00</span>
                                </li>
                            </ul>
                            <div class="payment-options">
                                <div class="placeorder-button text-left">
                                    <a href="<?=$baseUrl?>/checkout"> <button type="submit" class="thm-btn">Proceed to Checkout</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <?php } else{
                    echo "Your Shopping Cart Is Empty.";

                } ?>



            </div>

        </section>


<script>
    function baseUrl(){
        var href=window.location.href.split('/');
        return href[0]+'//'+href[2]+'/';
    }
    var SITE_URL=baseUrl();
    var cart_url = '<?=$baseUrl?>/shopping-cart';
    $(document).ready(function(){

        //Remove items from cart
        $("#shopping-cart-results-data").on('click', 'a.remove-item', function(e) {
            e.preventDefault();
            var pcode = $(this).attr("data-code"); //get product code
            $(this).parent().fadeOut(); //remove item element from box
            $.getJSON( SITE_URL+"site/CartProcessForService",
                {"remove_code":pcode} , function(data){ //get Item count from Server
                window.location.href=cart_url;
            });
        });

    });
</script>