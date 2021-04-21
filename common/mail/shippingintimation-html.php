<?php
use yii\helpers\Html;
?>
<?php require_once 'emailheader.php';//$this->renderPartial('emailheader',array('siteSettings'=>$siteSettings)); ?>  
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
								<h2 style=" font-weight: normal; margin: 0; padding: 0 0 12px; font-style: inherit; line-height: 30px; 
									font-size: 25px; font-family: Trebuchet MS; border-bottom: 1px solid #333333; "> 
									<?php echo Yii::t('app','Your order shipping confirmation - The order ID(s)')." ".$orderId; ?>.
								</h2>
							</td>
						</tr>
						
							<tr>
								<td style="padding: 15px 0;"  valign="top">
									<p style='margin-bottom: 10px'>
										 <?php echo Yii::t('app','Hi'); ?> <?php echo $userModel->name; ?>,
									</p>
									<p style='margin-bottom: 10px'>
										 <?php echo Yii::t('app','Thank you for shopping on'); ?> <?php echo $siteSettings->sitename; ?> 
										 <?php echo Yii::t('app','! Kindly note down the order ID(s) '); ?> 
										<?php echo $orderId; ?> <?php echo Yii::t('app','as a reference number for this purchase.'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','Your order is shipped. The same has been notified to the seller too. In case if you have ordered multiple items from multiple sellers, all your orders may be delivered separately. All your orders can be seen and tracked through your'); ?>
									 <?php echo $siteSettings->sitename; ?> <?php echo Yii::t('app','account also.'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','Your order details:'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php
										echo Yii::t('app','Seller Name'). ':';
											echo '<a href="'.Yii::$app->urlManager->createAbsoluteUrl(['user/profiles/','id'=> Myclass::safe_b64encode($sellerId.'-'.rand(0,999))]).'">'.$sellerName.'</a>,';
										
										?>
										<br />			
										 <?php echo Yii::t('app','Order ID(s):'); ?><?php echo $orderId; ?>
										<br>
										<?php echo Yii::t('app','Order Date:'); ?> <?php echo date('j-M-Y'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<table width="100%" class='order-details-table' style='border-spacing: 0;border-collapse: collapse;border: none;'>
										  <tr>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Subject'); ?></th>
										    <th style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Message'); ?></th>
										  </tr>
										  <tr>
										
											<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo $subject; ?></td>
											<td style='padding: 6px 10px;border: 1px solid rgba(0, 0, 0, 0.12);text-align: center;'><?php echo $message; ?></td>
										  </tr>
										</table>
									</p>
									<p style='margin-bottom: 10px'>
										<b><?php echo Yii::t('app','Shipped to:'); ?></b>
										<br clear="all" /><br />
										<?php echo $tempShippingModel->name; ?><br />
										<?php echo $tempShippingModel->address1; ?><br />
										<?php if  ($tempShippingModel->address2 != ""){echo $tempShippingModel->address2."<br />";} ?>
										<?php echo $tempShippingModel->city." - ".$tempShippingModel->zipcode; ?><br />
										<?php echo $tempShippingModel->state; ?><br />
										<?php echo $tempShippingModel->country; ?><br />
										<?php echo "Ph.: ".$tempShippingModel->phone; ?><br />
									</p>
									
									<p style='margin-bottom: 10px'>
										 <?php echo Yii::t('app','Your order is safe and been monitored closely with the seller when you shop on'); ?>
										 <?php echo $siteSettings->sitename; ?><?php echo Yii::t('app','. In case if you don’t receive your order from the seller(s) or it is delivered in an unsatisfactory condition, you can reach us through'); ?><?php echo $siteSettings->sitename; ?>
										 <?php echo Yii::t('app','. When you write to us about any orders, please mention the order ID to get quick response from the support team.'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','It’s also recommended to update the system once you received the order from the seller as expected and you are satisfied. In case of any order’s receipt is not notified in the system will be automatically confirmed as “Delivered” and will processed in the favor of sellers. Within this duration you are recommended to reach us in case of any problem in the orders.'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','To update receipt of the order, please login to your account and go to profile and settings, then click “My orders” and select the “Actions” and choose “Mark as received”.'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','We look forward to see you again.'); ?>
									</p>
								</td>
							</tr>			
							
							<tr>
								<td style="padding: 15px 0"  valign="top">
									<p style="color: #333333; font-weight: normal; margin: 0; padding: 0; line-height: 20px; 
										font-size: 14px;font-family: Arial; ">
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
<?php //die; ?>
 
