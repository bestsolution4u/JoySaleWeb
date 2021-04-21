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
						<h2 style="font-weight: normal; margin: 0; padding: 0 0 12px; font-style: inherit; line-height: 30px; font-size: 25px; 
							font-family: Trebuchet MS; border-bottom: 1px solid #333333;">
							<?php echo $r_username.' '; ?><?php echo Yii::t('app','Now Requested to Exchange on your product'); ?>.
						</h2>
					</td>
				</tr>

				<tr>
					<td style="padding: 15px 0;" valign="top">
						<p style='margin-bottom: 10px'>
							<?php echo Yii::t('app','Hi'); ?>
							<?php echo $c_username; ?>
							,
						</p>
						<p style='margin-bottom: 10px'><?php echo Yii::t('app','You Product is Requested to Exchange!'); ?></p>
						<p style='margin-bottom: 10px'><?php echo Yii::t('app','There is an alot of products and friends waiting for you and your products. Most of the People are there to Buy and Exchange you Products.'); ?></p>
						
						<!-- <p style='margin-bottom: 10px'>You have options to follow back. </p> -->
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
