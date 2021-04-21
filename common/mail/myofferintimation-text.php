<?php
use yii\helpers\Html;
?>
<?php require_once 'emailheader.php';//$this->renderPartial('emailheader',array('siteSettings'=>$siteSettings)); ?>
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

				<tr>
					<td style="padding: 18px 0 0;" align="left">
						<h2	style="font-weight: normal; margin: 0; padding: 0 0 12px; font-style: inherit; line-height: 30px; font-size: 25px;
								font-family: Trebuchet MS; border-bottom: 1px solid #333333;">
							<?php echo Yii::t('app','There is an offer placed in your account - The offer rate is'); ?>
							<?php //echo $currency.' '.$offerRate; ?><?php 	if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
													echo yii::$app->Myclass->convertArabicFormattingCurrency($currency,$offerRate);
												else
													 echo yii::$app->Myclass->convertFormattingCurrency($currency,$offerRate);?>
							<br> <?php echo Yii::t('app','Date:'); ?>
							.
						</h2>
					</td>
				</tr>

				<tr>
					<td style="padding: 15px 0;" valign="top">
						<p style='margin-bottom: 10px'>
							<?php echo Yii::t('app','Hi'); ?>
							<?php echo $sellerName; ?>
							,
						</p>
						<p style='margin-bottom: 10px'><?php echo Yii::t('app','Congratulations!'); ?></p>
						<p style='margin-bottom: 10px'><?php echo Yii::t('app','There is an offer placed in your profile. This email confirms that the same offer has been processed and recommend you to proceed with the sales of this product.'); ?></p>
						<a href="<?php echo $productURL; ?>" title="product url">
							<?php echo $productURL; ?>
						</a>

						<p style='margin-bottom: 10px'>
							 <?php echo Yii::t('app','Offer Rate:'); ?>
							<?php //echo $currency.' '.$offerRate; ?><?php 	if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
													echo yii::$app->Myclass->convertArabicFormattingCurrency($currency,$offerRate);
												else
													 echo yii::$app->Myclass->convertFormattingCurrency($currency,$offerRate);?>
							<br> <?php echo Yii::t('app','Date:'); ?>
							<br> <?php echo Yii::t('app','Date:'); ?>
							<?php //echo date('j-M-Y');
							$date = date('Y-m-d');
							echo date('M j,Y', strtotime($date));
                     
                            ?>
						</p>
						<p style='margin-bottom: 10px'>

						<table width="100%" class='order-details-table'
							style='border-spacing: 0; border-collapse: collapse; border: none;'>
							<tr>
								<th
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Name'); ?></th>
								<th
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Email'); ?></th>
								<th
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Phone'); ?></th>
								<th
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo Yii::t('app','Message'); ?></th>
							</tr>
							<tr>

								<td
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12);'><?php echo $name; ?>
								</td>
								<td
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12); text-align: center;'><?php echo $email; ?>
								</td>
								<td
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12); text-align: center;'><?php echo $phone; ?>
								</td>
								<td
									style='padding: 6px 10px; border: 1px solid rgba(0, 0, 0, 0.12); text-align: center;'><?php echo $message; ?>
								</td>
							</tr>
						</table>
						</p>


						<p style='margin-bottom: 10px'> <?php echo Yii::t('app','You have options to confirm this offer.'); ?></p>
						<p style='margin-bottom: 10px'><?php echo Yii::t('app','Happy selling !!!'); ?></p>
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
	<?php require_once 'emailfooter.php';//$this->renderPartial('emailfooter',array('siteSettings'=>$siteSettings)); ?>
</td>
</tr>
</table>
</body>
</html>
