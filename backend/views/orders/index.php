<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content">
                    <div class="row">
                        <div class="col-lg-12 userinfo">
                                                </div>
                    </div>

           
                <div class="container">
                <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
           
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                <?=Yii::t('app','Manage Orders')?>

                 <?=Html::a('New Orders',['orders/scroworders', 'status'=>'pending'],['class' => 'btn btn-sm btn-default pull-right','style' => 'cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;']); ?>   

               &nbsp;

                <?=Html::a('Delivered',['orders/scroworders', 'status'=>'delivered'],['class' => 'btn btn-sm btn-default pull-right','style' => 'cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;']); ?>

                &nbsp;
                <?=Html::a('Cancelled',['orders/scroworders', 'status'=>'cancelled'],['class' => 'btn btn-sm btn-default pull-right','style' => 'cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;']); ?>
                &nbsp;

                <?=Html::a('Refunded',['orders/scroworders', 'status'=>'refunded'],['class' => 'btn btn-sm btn-default pull-right','style' => 'cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;']); ?>
                &nbsp;

                 <?=Html::a('Claimed',['orders/scroworders', 'status'=>'claimed'],['class' => 'btn btn-sm btn-default pull-right','style' => 'cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;']); ?>

                &nbsp;

                 <?=Html::a('All Orders',['orders/scroworders', 'status'=>''],['class' => 'btn btn-sm btn-default pull-right','style' => 'cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;']); ?>
             &nbsp;

                 <?=Html::a('Approved',['orders/scroworders', 'status'=>'approved'],['class' => 'btn btn-sm btn-default pull-right','style' => 'cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;']); ?>
               &nbsp;   


                             </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                

                
<div id="mobileorders-grid" class="grid-view">

 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'orderId',
            'userId',
            'sellerId',
            'totalCost',
            'totalShipping',
            //'admincommission',
            //'discount',
            //'discountSource',
            //'orderDate',
            //'shippingAddress',
            //'currency',
            //'sellerPaypalId',
            //'status',
            //'statusDate',
            //'trackPayment',
            //'reviewFlag',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<div class="keys" style="display:none" title="/buynow/buynow/orders/scroworders"><span>3</span><span>2</span><span>1</span></div>
</div><div class="payment-form"></div>
<script type='text/javascript'>
jQuery('#mobileorders-grid a.callMobilePayment').live('click',function(e) {
    linkval = $(this).attr("href");
    if(linkval != "" && linkval != undefined)
    {
        $('.callMobilePayment').html(Yii.t('admin','Approve'));
        $('.callMobilePayment').removeAttr("href");
        $(this).html(Yii.t('admin','Please wait...'));
        $(this).attr("href",linkval);
        var fullData = $(this).attr('href').split('id/');
        var orderId = fullData[1];
        var url = fullData[0];
        $.ajax({
            type : 'POST',
            url: url,
            data : {orderId : orderId},
            beforeSend : function() {
                //$(this).html(Yii.t('admin','Please wait...'));
                //$('.callMobilePayment').html(Yii.t('admin','Please wait...'));
            },
            success : function(responce) {
                var output = responce.trim();
                if (output != 'false') {
                    $('.payment-form').html(output);
                    document.getElementById('mobile-paypal-form').submit();
                } else {
                    $('.callMobilePayment').html(Yii.t('admin','Try again!'));
                    $('.callMobilePayment').css({
                        "background-color" : "#fd2525"
                    });
                }
            },
            failed : function() {
                $('.callMobilePayment').html(Yii.t('admin','Try again!'));
                $('.callMobilePayment').css({
                    "background-color" : "#fd2525"
                });
            }
        });
    }
    return false;
});
</script>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
                </div>
                <div class="clear"></div>
                    <footer class="footer text-right">
                        2016 © .
                    </footer>
                </div>
