<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="line-height: 10px;" bgcolor="#d1d1d1" class="footer">
      <tr>
        <td bgcolor="#d1d1d1"  align="center" style="padding: 6px 20px 6px; margin: 0;" valign="top">
			<!-- p style="padding: 0; font-size: 11px; color:#fff; margin: 0; font-family: Georgia, serif;">You're receiving this newsletter because you bought widgets from us.</p>
			<p style="padding: 0; font-size: 11px; color:#fff; margin: 0 0 8px 0; font-family: Georgia, serif;">Having trouble reading this? <webversion style="color: #f7a766; text-decoration: none;">View it in your browser</webversion>. Not interested anymore? <unsubscribe style="color: #f7a766; text-decoration: none;">Unsubscribe</unsubscribe> instantly.</p-->
			<p style='font-size: 10px;font-family:Arial;color:#333333;text-align: left;'>
				<?php echo $siteSettings->sitename; ?> <?php echo Yii::t('app','sent this email to you.'); ?><br><br>
				<?php echo $siteSettings->sitename; ?>  <?php echo Yii::t('app','is committed to your privacy. Read more about'); ?>
				<a href='<?php echo Yii::$app->urlManager->createAbsoluteUrl('message/help?details='.yii::$app->Myclass->getTermsSlug()); ?>' style='color:rgb(109, 158, 235);'>
					<?php echo Yii::t('app','Terms & Privacy policy'); ?>
				</a>.
			</p>
		</td>
      </tr> 
</table><!-- footer-->
			
			</td>
		</tr>
    </table>
  </body>
</html>