<?php
use yii\helpers\Html;
?>
<div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20">
    <div class="d-flex justify-content-between  flex-column flex-sm-row">
        <div>
            <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','View').' '.Yii::t('app','Order'); ?></h4>
        </div>
        <div class="">
            <button class='btn btn-primary align-text-top border-0 m-b10'>
                <?=Html::a('<i class="fa fa-angle-double-left  p-r10"></i> '.Yii::t('app','Back'),Yii::$app->request->referrer); ?> 
            </button> 
        </div>
    </div>  
    <div class="panel-body  table-responsive" style="height: fit-content;">
        <div class="row">
            <div class="col-lg-12">
                <div class="containerdiv">
                    <?php $seller = yii::$app->Myclass->getUserDetailss($model->sellerId); 
                           // print_r($seller);exit;
                    ?>
                    <span class="pay-status"><?php echo Yii::t('app','Payment to')?>
                    </span><br> <span class="pay-to"><b><?php echo $seller->username; ?>
                    </b> </span><br> <span class="pay-status"><?php echo Yii::t('app','Email')?>
                    : <?php echo $seller->email; ?> </span>
                    <div class="inv-clear"></div>
                    <hr style="border-top: 3px solid rgba(236, 236, 236, 0.5)!important;">
                    <div class="buyerdiv d-flex justify-content-between  flex-column flex-sm-row m-t20" style="height: auto; ">
                        <div class="buyerper" style="width: 30%; float: left;">
                            <span class="pay-status"><?php echo Yii::t('app','Buyer Details')?>
                            </span><br> <span class="pay-to"><b><?php echo $userdata['username']; ?>
                            </b> </span><br> <span class="pay-status"><?php echo Yii::t('app','Email')?>
                            : <?php echo $userdata['email']; ?> </span>
                        </div>
                        <div class="inv-shipping m-b20" style="width: 35%; float: left;">
                            <?php if(!empty($shipping)) { ?>
                              <span class="pay-status"><?php echo Yii::t('app','Shipping Address')?>
                          </span><br> <b><?php echo $shipping->name; ?> </b>,<br>
                          <?php echo $shipping->address1; ?>
                          ,
                          <?php echo $shipping->address2; ?>
                          ,
                          <?php echo $shipping->city; ?>
                          -
                          <?php echo $shipping->zipcode; ?>
                          ,
                          <?php echo $shipping->state; ?>
                          ,
                          <?php echo $shipping->country; ?>
                          ,<br>
                          <?php echo Yii::t('app','Phone no.')?>
                          :
                          <?php echo $shipping->phone; ?>
                      <?php } ?>
                  </div>
                  <?php if(!empty($trackingDetails)) { ?>
                    <div class="inv-shipping" style="width: 35%; float: left;">
                        <span class="pay-status"><?php echo Yii::t('app','Tracking Details'); ?>
                    </span><br> <br>
                    <?php if(!empty($trackingDetails->trackingid)) { echo Yii::t('app','Tracking ID'); ?>
                    : <b><?php echo $trackingDetails->trackingid; ?> </b>
                <?php } ?>
                <br>
                <?php if(!empty($trackingDetails->shippingdate)) { echo Yii::t('app','Shipment Date');  ?>
                : <b><?php echo date("d-m-Y",$trackingDetails->shippingdate); ?>
            </b>
        <?php } ?>
        <br>
        <?php if(!empty($trackingDetails->couriername)) { echo Yii::t('app','Logistic Name'); ?>
        : <b><?php echo $trackingDetails->couriername; ?> </b>
    <?php } ?>
    <br>
    <?php if(!empty($trackingDetails->courierservice)) { echo Yii::t('app','Shipment Service'); ?>
    : <b><?php echo $trackingDetails->courierservice; ?> </b>
<?php } ?>
<br>
<?php if(!empty($trackingDetails->notes)) { echo Yii::t('app','Additional Notes'); ?>
: <b><?php echo $trackingDetails->notes; ?> </b>
<?php } ?>
<br>
</div>
<?php } ?>
</div>
<hr>
<div class="inv-clear"></div>
<table
class="tablesorter table table-striped table-bordered table-condensed">
<thead>
    <tr>
      <th>Sl no.</th>
      <th><?php echo Yii::t('app','Item Name'); ?></th>
      <th><?php echo Yii::t('app','Item Quantity'); ?></th>
      <th><?php echo Yii::t('app','Item Unitprice'); ?></th>
      <th><?php echo Yii::t('app','Shipping fee'); ?></th>
      <th><?php echo Yii::t('app','Total Price'); ?></th>
  </tr>
</thead>
<tbody>
    <tr>
        <td>1</td>
        <td><?php echo $orderitem['itemName']; ?></td>
        <td><?php echo $orderitem['itemQuantity']; ?></td>
        <td><?php echo yii::$app->Myclass->convertFormattingCurrency($model->currency,round($orderitem['itemunitPrice'],2)); ?>
    </td>
    <td><?php echo yii::$app->Myclass->convertFormattingCurrency($model->currency,round($orderitem['shippingPrice'],2)); ?>
</td>
<?php $totalCost =  round($orderitem['itemunitPrice'],2 * $orderitem['itemQuantity'],2) + round($orderitem['shippingPrice'],2); ?>
<td><?php echo yii::$app->Myclass->convertFormattingCurrency($model->currency,$totalCost); ?></td>
</tr>
</tbody>
</table>
<div style="margin-top: 12px; margin-left: 615px; width: 300px; margin-bottom: 12px;">
    <table>
        <tbody>
            <tr>
                <td align="left" style="width: 200px;"><p class="gtotal">
                    <?php echo Yii::t('app','Item Total'); ?>
                </p>
            </td>
            <td style="width: 50px;"></td>
            <td align="right" style="width: 100px;"><p class="gtotal">
                <b><?php $value = $orderitem['itemunitPrice'] * $orderitem['itemQuantity'];
                echo yii::$app->Myclass->convertFormattingCurrency($model->currency,$value); ?></b>
            </p></td>
        </tr>
        <tr>
            <td align="left"><p class="gtotal">
             <?php echo Yii::t('app','Shipping fee'); ?>
         </p></td>
         <td style="width: 50px;"></td>
         <td align="right"><p class="gtotal">
            <b><?php 
            if ($orderitem['shippingPrice'] == "" || $orderitem['shippingPrice'] == 0){
               echo yii::$app->Myclass->convertFormattingCurrency($model->currency,0);
           }else{
            echo yii::$app->Myclass->convertFormattingCurrency($model->currency,round($orderitem['shippingPrice'],2));
        } ?>
    </b>
</p></td>
</tr>
<?php if(!empty($model->discount)) { ?>
    <tr>
        <td align="left"><p class="gtotal">
            <?php echo Yii::t('app','Discount Amount'); ?>
        </p>
    </td>
    <td style="width: 50px;"></td>
    <td align="right"><p class="gtotal invoice-amnt">
        <b>(-) <?php echo yii::$app->Myclass->convertFormattingCurrency($model->currency,round($model->discount,2)); ?>
    </b>
</p>
</td>
</tr>
<?php } ?>
<tr>
    <td colspan="2"><div id="horizonal"
        style="border-top: 1px solid black; width: 300px; position: absolute;"></div>
    </td>
</tr>
<tr>
    <td align="left"><p class="gtotal">
       <?php echo Yii::t('app','Grand Total'); ?>
   </p></td>
   <td style="width: 50px;"></td>
   <td align="right"><p class="gtotal">
       <b><?php $grandTotal = round($totalCost,2) - round($model->discount,2); ?>
       <?php echo yii::$app->Myclass->convertFormattingCurrency($model->currency,$grandTotal); ?>
   </b>
</p></td>
</tr>
<tr>
    <td colspan="2"><div id="horizonal"
        style="border-top: 1px solid black; width: 300px; position: absolute;"></div>
    </td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>