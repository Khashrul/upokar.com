<?php

$baseUrl = Yii::app()->getBaseUrl(true);

?>

<!-- jQuery lib -->

<!-- dateDropper lib -->
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/fancybox/jquery.fancybox.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<link href='<?=$baseUrl?>/css/aria-tabs.css' rel='stylesheet' type='text/css'>

<script src="<?=$baseUrl?>/js/jquery.dynatable.js"></script>
<script src="<?=$baseUrl?>/js/aria-tabs.js"></script>
<style>


    .btn {
        display: block;
        max-width: 300px;
        text-align: center;
        text-decoration: none;
        background-color: #6216c9;
        color: #fff;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #510cad;
        margin: 40px auto;
    }

    .btn:hover,
    .btn:focus {
        background-color: #510cad;
    }

    .btn:avtive {
        background-color: #6216c9;
    }
</style>
<script>
    $(document).ready(function() {
        'use strict';
        $(window).on('ariaTabs.initialised', function(event, element) {
            console.log(element + 'init');
        });

        $('.tab-group').ariaTabs({
            contentRole: ['document', 'application', 'document'],
        });

        $(window).on('ariaTabs.select', function(event, element, index) {
            console.log(index);
        });

        $(window).on('ariaTabs.deselect', function(event, element, index) {
            console.log(index);
        });
    });
</script>




<div class="container">


    <div class="tab-group">
        <nav class="tab-group__tab-nav">
            <ul class="tab-group__tab-ul" style="padding: 8px;height: 4rem">
                <li class="tab-group__tab-li tab-group__tab-li_fill">
                    <button type="button" class="tab-group__tab-btn"><h4>Shop Transaction Details</h4></button>
                </li>
                <li class="tab-group__tab-li tab-group__tab-li_fill">
                    <button type="button" class="tab-group__tab-btn"><h4>Service Transaction Details</h4></button>
                </li>

            </ul>
        </nav>
        <div class="tab-group__tabs-cont">
            <div class="tab-group__tabpanel">
                <div class="tab-group__tab-content">
                    <table id="example" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Delivery Status</th>
                            <th>Order Amount (BDT)</th>
                            <th>Order Date</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php

                        if (isset($all_shop_data) && !empty($all_shop_data)){
                            $i=0;

                            foreach ($all_shop_data as $individual_data){

                                $invoice_id = $individual_data['invoice_id'];
                                $product_name = $individual_data['product_name'];
                                $delivery_status = $individual_data['status'];
                                $transaction_date = $individual_data['transaction_date'];
                                $final_amount = $individual_data['final_amount'];
                                $dates = date_create($transaction_date);
                                $dates_data = date_format($dates, ' l jS F Y');
                                $order_id = $individual_data['id'];
                                $change_status = isset($delivery_status) && $delivery_status == "Pending" ? "Change Status" : " ";

                                ?>


                                <tr>
                                    <td>#IN-<?=$invoice_id?> </td>

                                    <td><?=$delivery_status?> </td>
                                    <td><?=number_format($final_amount)?> </td>
                                    <td><?=$dates_data?> </td>
                                    <td><a class="fancybox" href="#inline<?php echo $i; ?>">View</a>
                                        <div id="inline<?php echo $i; ?>" style="width:700px;display: none;">
                                            <div class="wrapper" style="width: 700px;border: solid 2px #333;padding: 10px;">


                                                <table class="tbl2" width="100%">


                                                    <a target="_blank" href="<?=$baseUrl?>/user-profile/purchase-items/productDetails?InvoiceId=<?=base64_encode($invoice_id)?>" style="width: 100px; padding: 5px 8px 5px 8px;text-align: center;float: left;background-color: #02A6D8;color: #fff;text-decoration: none; margin: 10px;" onclick="">View Details</a>

                                                   <?php if($delivery_status == "Pending"){ ?>
                                              <a href="javascript:void(0);" onclick="changeOrderStatus('<?=$invoice_id?>');"style="width: 100px; padding: 5px 8px 5px 8px;text-align: center;float: left;background-color: #02A6D8;color: #fff;text-decoration: none; margin: 10px;">Change Status</a>



                                             <?php } ?>



                                                <div class="js_favorite_massage favorite-massage alert-warning"></div>
                                                <div class="favorite-send-controller">
                                        <span style="display: none; padding-left: 10px; width: 50px; float: left;" id="favorite-send-loading">
                                            <img alt="Loading..." src="<?=$baseUrl?>/images/ajax-loaders.gif">
                                        </span>
                                                </div>
                                                </table>

                                            </div>
                                        </div>
                                    </td>


                                </tr>
                            <?php
                                $i++;
                            }
                        } ?>


                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Delivery Status</th>
                            <th>Order Amount (BDT)</th>
                            <th>Order Date</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="tab-group__tabpanel">
                <div class="tab-group__tab-content">
                    <table id="example2" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Tag Name</th>
                            <th>Service Status</th>
                            <th>Total Amount</th>
                            <th>Service Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php

                        if (isset($all_service_data) && !empty($all_service_data)){

                            foreach ($all_service_data as $individual_data){

                                $invoice_id = $individual_data['invoice_id'];
                                $service_name = $individual_data['service_name'];
                                $tag_name = $individual_data['tag_name'];
                                $total_amount = $individual_data['total_amount'];
                                $service_status = $individual_data['service_status'];
                                $service_date = $individual_data['service_date'];
                                $dates = date_create($service_date);

                                $dates_data = date_format($dates, ' l jS F Y');


                                ?>


                                <tr>
                                    <td>#IN-<?=$invoice_id?></td>
                                    <td><?=$tag_name?></td>
                                    <td><?=$service_status?></td>
                                    <td><?=number_format($total_amount)?></td>
                                    <td><?=$dates_data?></td>
                                    <td><a class="fancybox" href="<?=$baseUrl?>/user-profile/order-history/ServiceDetails?InvoiceId=<?=base64_encode($invoice_id)?>">View</a></td>
                                </tr>
                            <?php   } } ?>


                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Tag Name</th>
                            <th>Service Status</th>
                            <th>Total Amount</th>
                            <th>Service Date</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <a href="/logout"><span></span>Logout</a>
</div>



<script>

    $(document).ready( function () {
        $('#example').DataTable();
        $('#example2').DataTable();
    } );




    function changeOrderStatus(order_id){

       // var order_id  = $('input[name=order_id]').val();
        $('#favorite-send-loading').show();
        $.ajax({
            type:"POST"
            ,url:SITE_URL+"site/ChangeOrderStatus"
            ,data:{order_id:order_id}
            ,dataType: "json"
            ,success:function(data){
                if(data.status == 'Success'){

                    $('#favorite-send-loading').hide();
                    $(".js_favorite_massage").html('<p class="alert-success">&nbsp;Order Submitted Successfully.</p>');
                    $(".js_favorite_massage").show();
                    location.reload();

                }
                else if(data.status == 'failed'){
                    $('#favorite-send-loading').hide();

                    $(".js_favorite_massage").show();

                }
            }
        });







    }








</script>

<script type="text/javascript" src="<?=$baseUrl?>/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?=$baseUrl?>/fancybox/main.js"></script>
<script src="<?=$baseUrl?>/js/jquery.PrintArea.js" type="text/JavaScript" language="javascript"></script>