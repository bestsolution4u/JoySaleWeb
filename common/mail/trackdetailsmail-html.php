<?php
use yii\helpers\Html;
?>
<?php require_once 'emailheader.php';?>
<table cellpadding="0" cellspacing="0" border="0" align="center"
	width="100%" style="font-family: Georgia, serif; background: #fff;"
	bgcolor="#ffffff">
	<tr>
		<td width="14" style="font-size: 0px;" bgcolor="#ffffff">&nbsp;</td>
		<td width="100%" valign="top" align="left" bgcolor="#ffffff"
			style="font-family: Georgia, serif; background: #fff;">
			<table cellpadding="0" cellspacing="0" border="0"
				style="color: #333333; font: normal 13px Arial; margin: 0; padding: 0;"
				width="100%" class="content">
				<!-- <tr>
							<td style="padding: 25px 0 5px; border-bottom: 2px solid #d2b49b;font-family: Georgia, serif; "  valign="top" align="center">
								<h3 style="color:#767676; font-weight: normal; margin: 0; padding: 0; font-style: italic; line-height: 13px; font-size: 13px;">~ <currentmonthname> <currentday>, <currentyear> ~</h3>
							</td>
						</tr> -->
				<tr>
					<td style="padding: 18px 0 0;" align="left">
						<h2
							style="font-weight: normal; margin: 0; padding: 0 0 12px; font-style: inherit; line-height: 30px; font-size: 25px; font-family: Trebuchet MS; border-bottom: 1px solid #333333;">
							<?php echo Yii::t('app','Shipment tracking details - Order ID is #'); ?>
							<?php echo $model->orderId; ?>
							.
						</h2>
					</td>
				</tr>

				<tr>
					<td style="padding: 15px 0;" valign="top">
						<p style='margin-bottom: 10px'>
							<?php echo Yii::t('app','Hi'); ?> <?php echo $userModel->name; ?>,
						</p>
						<p style='margin-bottom: 10px'>
							 <?php echo Yii::t('app','Your order has been marked as shipped'); ?>
						</p>
						<p style='margin-bottom: 10px'>
							 <?php echo Yii::t('app','Shipment tracking details are:'); ?><br> <br>
							<?php echo Yii::t('app','Shipment Started On').": ".date("d-m-Y",$tracking->shippingdate); ?>
							<br>
							<?php echo Yii::t('app','Logistic Name').": ".$tracking->couriername." &nbsp;&nbsp;&nbsp;&nbsp;".Yii::t('app','Service Type').": ".$tracking->courierservice; ?>
							<br>
							<?php echo Yii::t('app','Tracking Id').": ".$tracking->trackingid; ?>
							<br>
							<?php echo Yii::t('app','Additional Notes').": ".$tracking->notes; ?>
							<br>
						</p>
						<p style='margin-bottom: 10px'><?php echo Yii::t('app','Your order details:'); ?></p>
						<p style='margin-bottom: 10px'>
							<?php echo Yii::t('app','Order ID')?> : #
							<?php echo $model->orderId; ?>
							<br> <?php echo Yii::t('app','Order Date:'); ?>
							<?php echo date('j-M-Y', $model->orderDate); ?>
						</p>
						<p style='margin-bottom: 10px'>
										<table width="100%" class='order-details-table' style='border-spacing: 0;border-collapse: collapse;border: none;'>
										  <tr>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Item'); ?></th>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Quantity'); ?></th>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Size'); ?></th>
										  </tr>
										  <tr>
										
											<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo $model['orderitems'][0]['itemName']; ?></td>
											<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);text-align: center;'><?php echo $model['orderitems'][0]['itemQuantity']; ?></td>
											<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);text-align: center;'><?php echo ($model['orderitems'][0]['itemSize'] == '')?'NIL' :$model['orderitems'][0]['itemSize'] ; ?></td>
										
										  </tr>
										</table>
									</p>
						<p style='margin-bottom: 10px'>
							<b><?php echo Yii::t('app','Ship to:'); ?></b> <br clear="all" /> <br />
							<?php echo $tempShippingModel->name; ?>
							<br />
							<?php echo $tempShippingModel->address1; ?>
							<br />
							<?php if ($tempShippingModel->address2 != ""){echo $tempShippingModel->address2."<br />";} ?>
							<?php echo $tempShippingModel->city." - ".$tempShippingModel->zipcode; ?>
							<br />
							<?php echo $tempShippingModel->state; ?>
							<br />
							<?php echo $tempShippingModel->country; ?>
							<br />
							<?php echo "Ph.: ".$tempShippingModel->phone; ?>
							<br />
						</p>
						<p style='margin-bottom: 10px; font-size: 16px;'>
							 <?php echo Yii::t('app','Total:'); ?><b><?php //echo $model->totalCost." ".$model->currency; ?>


							 <?php 	if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
													echo yii::$app->Myclass->convertArabicFormattingCurrency($model->currency,$model->totalCost);
												else
													 echo yii::$app->Myclass->convertFormattingCurrency($model->currency,$model->totalCost);?>
							<br> <?php echo Yii::t('app','Date:'); ?>
							  </b>
						</p>
					</td>
				</tr>

				<tr>
					<td style="padding: 15px 0" valign="top">
						<p
							style="color: #333333; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 14px; font-family: Arial;">
							<?php echo Yii::t('app','Regards,'); ?> <br /> <b><?php echo $siteSettings->sitename.' '.Yii::t('app','Team'); ?>.</b>
						</p> <br>
					</td>
				</tr>
			</table>
		</td>
		<td width="16" bgcolor="#ffffff"
			style="font-size: 0px; font-family: Georgia, serif; background: #fff;">&nbsp;</td>
	</tr>
</table>
<!-- body -->
							<?php require_once 'emailfooter.php'; ?>
</td>
</tr>
</table>
</body>
</html>
