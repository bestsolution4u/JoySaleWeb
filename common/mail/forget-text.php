<?php
use yii\helpers\Html;
?>
<?php require_once 'emailheader.php';//$this->element('emailHeader'); ?>  
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="font-family: Georgia, serif; background: #fff;" bgcolor="#ffffff">
			      <tr>
			        <td width="14" style="font-size: 0px;" bgcolor="#ffffff">&nbsp;</td>
					<td width="100%" valign="top" align="left" bgcolor="#ffffff"style="font-family: Georgia, serif; background: #fff;">
						<table cellpadding="0" cellspacing="0" border="0"  style="color: #333333; font: normal 13px Arial; margin: 0; padding: 0;" width="100%" class="content">
						
						<tr>
							<td style="padding: 18px 0 0;" align="left">			
								<h2 style=" font-weight: normal; margin: 0; padding: 0 0 12px; font-style: inherit; line-height: 30px; font-size: 25px; 
									font-family: Trebuchet MS; border-bottom: 1px solid #333333; ">  <?php echo Yii::t('app','Hello')." ".$name; ?>, 
                                <?php echo Yii::t('app','Your forget password request processed successfully.'); ?></h2>
							</td>
						</tr>
						
							<tr>
								<td style="padding: 15px 0;"  valign="top">
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','Hello'); ?> <?php echo $name; ?>,
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','We have received a request on your old password reset. The password reset link is provided along with this email. If you did not initiate this request, kindly ignore this email and change your password manually for security purpose.'); ?>
									</p>
									<p style='margin-bottom: 10px'>
										<?php echo Yii::t('app','Here is your password reset link:'); ?>
										<a href="<?php echo $uniquecode_pass; ?>" title="reset password">
											<?php echo $uniquecode_pass; ?>
										</a>
									</p>
									<p style='margin-bottom: 10px'>
										 <?php echo Yii::t('app','If you still have any problem in accessing your account, we would like you to write your problems to'); ?>
										<?php echo $siteSettings->sitename; ?>.
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
				  <?php require_once 'emailfooter.php';//$this->element('emailFooter'); ?>  
		  	</td>
		</tr>
    </table>
  </body>
</html>
<?php //die; ?>
