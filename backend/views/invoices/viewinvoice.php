
<div class="invoice-popup-head d-flex justify-content-between mb-4">
    <p class="ltit mb-0">
   <?php echo Yii::t('app','Invoice'); ?>
    </p>
    <button type="button" class="ly-close" id="btn-browses" data-dismiss="modal" aria-label="Close" style="border: none;">x</button>
</div>
<div class="invoice-popup">
    <div id="userdata">
        <h2 style="background: #EFEFEF; font-size: 16px;"class="inv-head p-2">
            <?php echo Yii::t('app','Order'); ?> #
            <?php echo $invoiceData['invoiceNo']; ?>
          <?php echo Yii::t('app','on'); ?>
            <?php echo date("m/d/Y",$invoiceData['invoiceDate']); ?>
        </h2>
        <div class="d-flex pay-status mb-0 mt-2">
           <p class="mb-0 mr-1" style="color: #8D8D8D; font-size: 12px;"><?php echo Yii::t('app','Payment Method'); ?> : </p>
           <p class="mb-0" style="color:#000;font-size: 12px;"> <?php echo ucfirst($invoiceData['paymentMethod']); ?></p>
        </div>
        <div class="d-flex pay-status mb-0 mt-2">
        <p class="mb-0 mr-1" style="color: #8D8D8D; font-size: 12px;"> <?php echo Yii::t('app','Payment Status'); ?> : </p> 
        <p class="mb-0" style="color:#000;font-size: 12px;"> <?php echo ucfirst($invoiceData['invoiceStatus']); ?> </p>
        </div>
        <div style="border-bottom: 1px solid #DEDEDE;" class="inv-clear my-2"></div>
        <div class="buyerdiv">
        <div class="row">
        <div class="col-12 col-sm-6">
            <div class="buyerper">
                <span style="color: #8D8D8D; font-size: 12px;"
                    class="pay-status mb-0 mt-2"><?php echo Yii::t('app','Buyer Details'); ?></span><br> <span
                    style="font-size: 14px; font-weight: bold;" class="pay-to"><?php echo $userdata['username']; ?>
                </span><br> <span
                    style="color: #8D8D8D; font-size: 12px;"
                    class="pay-status mb-0"><?php echo Yii::t('app','Email'); ?> : <p style="color:#000;"> <?php echo $userdata['email']; ?></p>
                </span>
            </div>
            </div>
            <div class="col-12 col-sm-6">
            <div class="inv-shipping">
                <span
                    style="color: #8D8D8D; font-size: 12px;"
                    class="pay-status mb-0"><?php echo Yii::t('app','Shipping Address'); ?></span><br>
                    <p class="mb-0 details" style="color: #000; font-size: 12px;">
                    <?php echo $userdata['username']; ?>
                
                <?php echo $shipping->address1; ?>
                
                <?php echo $shipping->address2; ?>
                
                <?php echo $shipping->city; ?>
                - 962
                <?php echo $shipping->state; ?>
                
                <?php echo $shipping->country; ?>
                <?php echo Yii::t('app','Phone'); ?> :
                <?php echo $shipping->phone; ?>
                </p>
            </div>
            </div>
            </div>

        </div>
        <div
            style="border-bottom: 1px solid #DEDEDE;"
            class="inv-clear my-2"></div>
            <div class="">

        <div class="table-responsive" style="height: unset;border: 1px solid;">
  <table class="table mb-0">
  <thead style="background-color: #D3D3D3; color: #4D4D4D;">
    <tr>
                    <th class="text-center p-2" style="font-size: 14px;">Sl no.</th>
                    <th class="text-center p-2" style="font-size: 14px;"><?php echo Yii::t('app','Item Name'); ?></th>
                    <th class="text-center p-2" style="font-size: 14px;"><?php echo Yii::t('app','Item Quantity'); ?></th>
                    <th class="text-center p-2" style="font-size: 14px;"><?php echo Yii::t('app','Item Unitprice'); ?></th>
                    <th class="text-center p-2" style="font-size: 14px;"><?php echo Yii::t('app','Shipping fee'); ?></th>
                    <th class="text-center p-2" style="font-size: 14px;"><?php echo Yii::t('app','Total Price'); ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
    <td class="text-center p-2"
                        style="font-size: 14px;">1</td>
                    <td class="text-center p-2"
                        style="font-size: 14px;"><?php echo $orderitem['itemName']; ?>
                    </td>
                    <td class="text-center p-2"
                        style="font-size: 14px;"><?php echo $orderitem['itemQuantity']; ?>
                    </td>
                    <td class="text-center p-2"
                        style="font-size: 14px;"><?php 
                        echo round($orderitem['itemunitPrice'],2).' '.$orderData->currency; ?>
                    </td>
                    <td class="text-center p-2"
                        style="font-size: 14px;"><?php
                         echo round($orderitem['shippingPrice'],2).' '.$orderData->currency; ?>
                    </td>

                  
                    <td class="text-center p-2"
                        style="font-size: 14px;"><?php 
                        $total = round($orderData->totalCost,2);
                        echo $total.' '.$orderData->currency; ?></td>
    </tr>
  </tbody>
  </table>
</div>
      
        <div class="d-flex justify-content-end">
        <div class="mt-3" style=" width: 300px;">
            <table>
                <tbody>
                    <tr>
                        <td align="left" style="width: 200px;"><p class="gtotal" style="font-size:14px"><?php echo Yii::t('app','Item Total'); ?></p></td>
                        <td style="width: 50px;"></td>
                        <td align="right" style="width: 100px;"><p class="gtotal"
                                style="text-align: right;">
                                <b><?php echo $total-round($orderData->totalShipping,2).' '.$orderData->currency; ?>
                                </b>
                            </p></td>
                    </tr>
                    <?php if(!empty($orderData->discount)) { ?>
                    <tr>
                        <td align="left"><p class="gtotal" style="font-size:14px"><?php echo Yii::t('app','Discount Amount'); ?></p></td>
                        <td style="width: 50px;"></td>
                        <td align="right"><p class="gtotal" style="text-align: right;">
                                <b><?php echo '(-) '.round($orderData->discount,2).' '.$orderData->currency; ?>
                                </b>
                            </p></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td align="left"><p class="gtotal" style="font-size:14px"><?php echo Yii::t('app','Shipping fee'); ?></p></td>
                        <td style="width: 50px;"></td>
                        <td align="right"><p class="gtotal" style="text-align: right;">
                                <b><?php echo round($orderData->totalShipping,2).' '.$orderData->currency; ?>
                                </b>
                            </p></td>
                    </tr>

                    <tr>
                        <td colspan="2"><div
                                style="border-top: 1px solid black; width: 300px; position: absolute; margin-top: -6px;"
                                id="horizonal"></div></td>
                    </tr>
                    <?php $grandTotal = round($total,2) - round($orderData->discount,2);?>
                    <tr>
                        <td align="left"><p class="gtotal" style="font-size:14px"><?php echo Yii::t('app','Grand Total'); ?></p></td>
                        <td style="width: 50px;"></td>
                        <td align="right"><p class="gtotal" style="text-align: right;">
                                <b><?php echo $grandTotal.' '.$orderData->currency; ?> </b>
                            </p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><div style="border-top: 1px solid black; width: 300px; position: absolute;"
                                id="horizonal"></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<style>
#invoice-modal .modal-dialog {
    max-width: 700px;
}
</style>