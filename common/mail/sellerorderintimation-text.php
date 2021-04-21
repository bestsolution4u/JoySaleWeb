<?php
use yii\helpers\Html;
use common\models\Orders;
?>
<?php require_once 'emailheader.php';//$this->renderPartial('emailheader',array('siteSettings'=>$siteSettings)); 
if(isset($keyarray['iteration'])){
	$i = 1;
}else{
	$i = '';
}
?>  
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="font-family: Georgia, serif; background: #fff;" bgcolor="#ffffff">
			      <tr>
			        <td width="14" style="font-size: 0px;" bgcolor="#ffffff">&nbsp;</td>
					<td width="100%" valign="top" align="left" bgcolor="#ffffff"style="font-family: Georgia, serif; background: #fff;">
						<table cellpadding="0" cellspacing="0" border="0"  style="color: #333333; font: normal 13px Arial; margin: 0; padding: 0;" width="100%" class="content">
						<!-- <tr>
							<td style="padding: 25px 0 5px; border-bottom: 2px solid #d2b49b;font-family: Georgia, serif; "  valign="top" align="center">
								<h3 style="color:#767676; font-weight: normal; margin: 0; padding: 0; font-style: italic; line-height: 13px; font-size: 13px;">~ <currentmonthname> <currentday>, <currentyear> ~</h3>
							</td>
						</tr> -->
						<tr>
							<td style="padding: 18px 0 0;" align="left">			
								<h2 style=" font-weight: normal; margin: 0; padding: 0 0 12px; font-style: inherit; 
									line-height: 30px; font-size: 25px; font-family: Trebuchet MS; border-bottom: 1px solid #333333; ">
									<?php echo Yii::t('app','There is an order placed in your account  - The order ID is')." #".$orderId; ?>.
								</h2>
							</td>
						</tr>
						
							<tr>
								<td style="padding: 15px 0;"  valign="top">
									<p style='margin-bottom: 10px'>
										 <?php echo Yii::t('app','Hi'); ?> <?php echo $sellerName; ?>,
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','Congratulations!'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app',"There is an order placed in your profile. This email confirms that the same order has been processed and recommend you to proceed with the shipping of this order."); ?>
									</p>
									<p style='margin-bottom: 10px'>
										 <?php echo Yii::t('app','Buyer:'); ?><?php echo $userModel->name; ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','Order ID:'); ?><?php echo $orderId; ?>
										<br>
										 <?php echo Yii::t('app','Order Date:'); ?><?php echo date('j-M-Y'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<table width="100%" class='order-details-table' style='border-spacing: 0;border-collapse: collapse;border: none;'>
										  <tr>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Item'); ?></th>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Quantity'); ?></th>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Size'); ?></th>
										  </tr>
										  <tr>
										
											<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo $keyarray['item_name'.$i]; ?></td>
											<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);text-align: center;'><?php echo $keyarray['quantity'.$i]; ?></td>
											<?php if ($custom == '') { ?>
												<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);text-align: center;'> -- </td>
											<?php }else { ?>
												<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);text-align: center;'><?php echo $custom; ?></td>
											<?php } ?>
										
										  </tr>
										</table>
									</p>
									<p style='margin-bottom: 10px'>
										<b><?php echo Yii::t('app','Ship to:'); ?></b>
										<br clear="all" /><br />
										<?php echo $tempShippingModel->name; ?><br />
										<?php echo $tempShippingModel->address1; ?><br />
										<?php if ($tempShippingModel->address2 != ""){echo $tempShippingModel->address2."<br />";} ?>
										<?php echo $tempShippingModel->city." - ".$tempShippingModel->zipcode; ?><br />
										<?php echo $tempShippingModel->state; ?><br />
										<?php echo $tempShippingModel->country; ?><br />
										<?php echo "Ph.: ".$tempShippingModel->phone; ?><br />
									</p>
									<p style='margin-bottom: 10px;font-size:16px;'>
									<?php $orderModel = Orders::findOne($orderId);?>
										 <?php echo Yii::t('app','Total:'); ?><b><?php // echo $orderModel->totalCost;//echo $keyarray['mc_gross']." ".$keyarray['mc_currency']; ?>
										 	
										 	 <?php 	if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
													echo yii::$app->Myclass->convertArabicFormattingCurrency($keyarray['mc_currency'],$orderModel->totalCost);
												else
													 echo yii::$app->Myclass->convertFormattingCurrency($keyarray['mc_currency'],$orderModel->totalCost);?>

										 </b>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','You have options to confirm this order and mark is as processing.You can also add the shipping details and tracking details to this order from “My Sales” under your profile account.'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','Happy selling !!!'); ?>
									</p>
								</td>
							</tr>			
							
							<tr>
								<td style="padding: 15px 0"  valign="top">
									<p style="color: #333333; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 14px;font-family: Arial; ">
										<?php echo Yii::t('app','Regards,'); ?>
										<br />
										<b><?php echo $siteSettings->sitename.' '.Yii::t('app','Team'); ?>.</b>
									</p>
									<br>
								</td>
							</tr>
						</table>	
					</td>
					<td width="16" bgcolor="#ffffff" style="font-size: 0px;font-family: Georgia, serif; background: #fff;">&nbsp;</td>
			      </tr>
				</table><!-- body -->
				  <?php require_once 'emailfooter.php';//$this->renderPartial('emailfooter',array('siteSettings'=>$siteSettings)); ?>  
		  	</td>
		</tr>
    </table>
  </body>
</html>