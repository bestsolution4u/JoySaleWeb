<div class="advrtz-section">
	<div class="advrtz-banner-top">
		<img class="img-responsive" src="<?=Yii::$app->urlManager->createAbsoluteUrl("/images/advtz.png");?>">
	</div>
	<div class="container">		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<h2 class="advrtz-title bold"><?= Yii::t('app',$models['ad_title'])?></h2>
				<div class="adcrt-baner-view"> 
					<img class="img-responsive" src="<?=Yii::$app->urlManager->createAbsoluteUrl("/media/logo/").'/'.$models->ad_image;?>" >
				</div>
				<div class="advtrz-content margin-top-20">
					<?php echo $ad_desc; ?>
				</div> 
				<div class="margin-top-20">
					<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/addbanner'); ?>"
						class="post-btn btnUpdate btn advt-btn"><?php echo Yii::t('app','Banner Advertise'); ?>
					</a>				
				</div>
			</div>
		</div>
	</div>
</div>