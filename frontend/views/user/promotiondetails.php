<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	<span><?=Yii::t("app", 'Promotion Details')?></span>
	<div class="exchange-back-link pull-right col-xs-3 col-sm-3 col-md-3 col-lg-3 no-hor-padding"><a href="#" onclick="switchVisible_promotionback();" id="element1"><?php echo yii::t('app','Back');?></a></div>
</div>
<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	<!--row 1-->
	<div class="promotions-details-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	</div>
</div>
<?php
	$image = yii::$app->Myclass->getProductImage($product_detail->productId);
	$pdtURL = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$product_detail->productId."/".$image);
?>


<div class="promotions-content-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	<div class="promotions-left-content col-xs-12 col-sm-12 col-md-4 col-lg-4 no-hor-padding">
		<div class="promotions-prod-pic-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="background: rgba(0, 0, 0, 0) url('<?php echo $pdtURL; ?>') no-repeat scroll center center / cover ;">
			<div class="promotion-detail-prod-pic"></div>
		</div>
	</div>
	<div class="promotion-details-cnt col-xs-12 col-sm-12 col-md-8 col-lg-8 no-hor-padding">
	<div class="promotions-prod-name-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<?php echo $product_detail->name; ?>
	</div>
	<div class="promotions-details-content col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="promotions-type-cnt col-xs-12 col-sm-12 col-md-3 col-lg-3 no-hor-padding">
		<div class="promotions-type col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Promotion Type');echo ":"; ?></div>
		<div class="promotions-type-txt txt-pink-color col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app',$promot_detail->promotionName); ?></div>
	</div>   

	<div class="vertical-divider"></div>
	<div class="paid-amt-cnt col-xs-12 col-sm-12 col-md-3 col-lg-3 no-hor-padding">
		<div class="paid-amt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Paid Amount');echo ":"; ?></div>
			<div class="paid-amt-txt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<?php

			$siteSettingsModel = yii::$app->Myclass->getSitesettings();
			$promotionCurrencyCode = explode('-', $siteSettingsModel->promotionCurrency);
			if($_SESSION['language'] == 'ar'){
				 echo '<div style="direction:ltr;"">'.  yii::$app->Myclass->convertArabicFormattingCurrency($promot_detail->promotionCurrency,$promot_detail->promotionPrice).'</div>';
			}
			else
				echo yii::$app->Myclass->convertFormattingCurrency($promot_detail->promotionCurrency,$promot_detail->promotionPrice);
				?>

				</div>
	</div>
													<?php if($promot_detail->promotionName != 'urgent'){ ?>
													<div class="upto-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<div class="upto-txt-cnt col-xs-12 col-sm-12 col-md-6 col-lg-6 no-hor-padding">
															<div class="upto col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Up to'); ?></div>
															<?php
																$start_date = date("M d Y",$promot_detail->createdDate);

																$end_date =
date("M d Y",strtotime("+".$promot_detail->promotionTime."  days" , $promot_detail->createdDate));
															?>

											<?php
						$startdate=date('Y-m-d', $promot_detail->createdDate);
						$enddate = date('Y-m-d', strtotime("+".$promot_detail->promotionTime."  days" , $promot_detail->createdDate));
						// $start_date=Yii::app()->dateFormatter->formatDateTime(CDateTimeParser::parse($startdate, 'yyyy-MM-dd'),'medium',null);
						// $end_date = Yii::app()->dateFormatter->formatDateTime(CDateTimeParser::parse($enddate, 'yyyy-MM-dd'),'medium',null);

											//echo Yii::app()->dateFormatter->formatDateTime(date('M jS Y', $chatDate)).'<br/>';
											?>

															<div class="upto-txt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo $start_date.' - '.$end_date; ?></div>
														</div>
														<?php if($product_detail->promotionType == '1' && $promot_detail->status == 'Expired') { ?>
														<div class="repromote-btn-cnt col-xs-12 col-sm-12 col-md-6 col-lg-6 no-hor-padding">
															<a href="javascript:;" onclick = "showListingPromotion('<?php echo $product_detail->productId; ?>')" class="repromote-btn pull-right"><?php echo Yii::t('app','Promote the listing'); ?></a>
														</div>
														<?php } ?>
													</div>
													<?php } ?>

													<div class="transaction-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<div class="transaction-id col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Transaction Id');echo ":"; ?></div>
														<div class="transaction-id-txt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo $promot_detail->tranxId; ?></div>
													</div>
													<?php if($product_detail->promotionType == '1') { ?>
													<div class="status-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<div class="status col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Status :'); ?></div>
														<div class="status-txt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo $promot_detail->status; ?></div>
													</div>
													<?php } ?>
													<?php if($product_detail->promotionType == '1' && $promot_detail->status == 'Expired') { ?>
													<div class="repromote-btn-mob-cnt col-xs-12 col-sm-12 col-md-6 col-lg-6 no-hor-padding">
														<a href="javascript:;" onclick = "showListingPromotion('<?php echo $product_detail->productId; ?>')" class="repromote-btn pull-right"><?php echo Yii::t('app','Promote the listing'); ?></a> 
													</div>
													<?php } ?>
												</div>
											</div>
										</div>

