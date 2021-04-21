<div class="invoice-popup-head">
    <p class="ltit">
    <?php echo 'Invoice'; ?>
    </p>
    <button type="button" class="ly-close" id="btn-browses">x</button>
</div>
<div class="invoice-popup">
    <div style="margin-left: 10px;" id="userdata">
        <h2 style="background: #EFEFEF; font-size: 18px; padding: 6px 10px;"
            class="inv-head">
            <?php echo 'Order'; ?> #
            <?php echo $invoiceData['invoiceNo']; ?>
            <?php echo 'on'; ?>
            <?php echo date("m/d/Y",$invoiceData['invoiceDate']); ?>
        </h2>
        <p
            style="color: #8D8D8D; font-size: 12px; margin-bottom: 0px; margin-top: 12px;"
            class="pay-status">
            <?php echo 'Payment Method'; ?> :
            <?php echo ucfirst($invoiceData['paymentMethod']); ?>
        </p>
        <p
            style="color: #8D8D8D; font-size: 12px; margin-bottom: 0px; margin-top: 12px;"
            class="pay-status">
            <?php echo 'Payment Status'; ?> :
            <?php echo ucfirst($invoiceData['invoiceStatus']); ?>
        </p>
        <div
            style="border-bottom: 1px solid #DEDEDE; margin-bottom: 14px; padding-top: 14px;"
            class="inv-clear"></div>
        <div class="buyerdiv">
            <div style="display: inline-block; float: left; width: 50%;"
                class="buyerper">
                <span
                    style="color: #8D8D8D; font-size: 12px; margin-bottom: 0px; margin-top: 12px;"
                    class="pay-status"><?php echo 'Buyer Details'; ?></span><br> <span
                    style="font-size: 14px; font-weight: bold;" class="pay-to"><?php echo $userdata['username']; ?>
                </span><br> <span
                    style="color: #8D8D8D; font-size: 12px; margin-bottom: 0px; margin-top: 12px;"
                    class="pay-status"><?php echo 'Email'; ?> : <?php echo $userdata['email']; ?>
                </span>
            </div>
            <div style="display: inline-block; width: 50%;" class="inv-shipping">
                <span
                    style="color: #8D8D8D; font-size: 12px; margin-bottom: 0px; margin-top: 12px;"
                    class="pay-status"><?php echo 'Shipping Address'; ?></span><br>
                    <?php echo $userdata['username']; ?>
                ,<br>
                <?php echo $shipping->address1; ?>
                ,<br>
                <?php echo $shipping->address2; ?>
                ,<br>
                <?php echo $shipping->city; ?>
                - 962,<br>
                <?php echo $shipping->state; ?>
                ,<br>
                <?php echo $shipping->country; ?>
                ,<br><?php echo 'Phone'; ?> :
                <?php echo $shipping->phone; ?>
            </div>
        </div>
        <div
            style="border-bottom: 1px solid #DEDEDE; margin-bottom: 14px; padding-top: 14px;"
            class="inv-clear"></div>
        <table style="border: 1px solid;" class="Item-details">
            <thead style="background-color: #D3D3D3; color: #4D4D4D;">
                <tr>
                    <th style="font-size: 14px; text-align:center;">Sl no.</th>
                    <th style="font-size: 14px;text-align:center;"><?php echo 'Item Name'; ?></th>
                    <th style="font-size: 14px;text-align:center;"><?php echo 'Item Quantity'; ?></th>
                    <th style="font-size: 14px;text-align:center;"><?php echo 'Item Unitprice'; ?></th>
                    <th style="font-size: 14px;text-align:center;"><?php echo 'Shipping fee'; ?></th>
                    <th style="font-size: 14px;text-align:center;"><?php echo 'Total Price'; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td
                        style="font-size: 14px; padding: 6px; width: 145px; text-align: center;">1</td>
                    <td
                        style="font-size: 14px; padding: 6px; width: 145px; text-align: center;"><?php echo $orderitem['itemName']; ?>
                    </td>
                    <td
                        style="font-size: 14px; padding: 6px; width: 145px; text-align: center;"><?php echo $orderitem['itemQuantity']; ?>
                    </td>
                    <td
                        style="font-size: 14px; padding: 6px; width: 145px; text-align: center;"><?php echo $orderitem['itemunitPrice'].' '.$orderData->currency; ?>
                    </td>
                    <td
                        style="font-size: 14px; padding: 6px; width: 145px; text-align: center;"><?php echo (int)$orderitem['shippingPrice'].' '.$orderData->currency; ?>
                    </td>
                    <td
                        style="font-size: 14px; padding: 6px; width: 145px; text-align: center;"><?php $total = $orderData->totalCost;
                        echo $total.' '.$orderData->currency; ?></td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 12px; margin-left: 450px; width: 300px;">
            <table>
                <tbody>
                    <tr>
                        <td align="left" style="width: 200px;"><p class="gtotal"><?php echo 'Item Total'; ?></p></td>
                        <td style="width: 50px;"></td>
                        <td align="right" style="width: 100px;"><p class="gtotal"
                                style="text-align: right;">
                                <b><?php echo $total-(int)$orderData->totalShipping.' '.$orderData->currency; ?>
                                </b>
                            </p></td>
                    </tr>
                    <?php if(!empty($orderData->discount)) { ?>
                    <tr>
                        <td align="left"><p class="gtotal"><?php echo 'Discount Amount'; ?></p></td>
                        <td style="width: 50px;"></td>
                        <td align="right"><p class="gtotal" style="text-align: right;">
                                <b><?php echo '(-) '.(int)$orderData->discount.' '.$orderData->currency; ?>
                                </b>
                            </p></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td align="left"><p class="gtotal"><?php echo 'Shipping fee'; ?></p></td>
                        <td style="width: 50px;"></td>
                        <td align="right"><p class="gtotal" style="text-align: right;">
                                <b><?php echo (int)$orderData->totalShipping.' '.$orderData->currency; ?>
                                </b>
                            </p></td>
                    </tr>

                    <tr>
                        <td colspan="2"><div
                                style="border-top: 1px solid black; width: 300px; position: absolute; margin-top: -6px;"
                                id="horizonal"></div></td>
                    </tr>
                    <?php $grandTotal = $total - $orderData->discount;?>
                    <tr>
                        <td align="left"><p class="gtotal"><?php echo 'Grand Total'; ?></p></td>
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
