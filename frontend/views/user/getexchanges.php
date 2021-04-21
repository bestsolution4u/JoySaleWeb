<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 use common\models\Photos;
use yii\widgets\LinkPager;
use conquer\toastr\ToastrWidget;
$siteSettings = yii::$app->Myclass->getSitesettings();
 ?>
 <?php if(empty($exchanges)){ 
	$empty_tap = " empty-tap ";
}else{
	$empty_tap = "";
	} ?>
<div id="exchange" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in <?php echo $empty_tap; ?>">
							<div class="exchange-rows col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="exchange-rows">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<ul class="recent-activities-tab nav nav-tabs">
									 
								   <li class="<?php if($type == 'incoming') echo 'active'; ?>">
									  	 <a href="javascript:void(0)" onclick="return getincoming()"><i class="fa fa-arrow-down"> </i> <?php echo Yii::t('app','Incoming Requests'); ?> </a>
									  </li>
									  <li class="<?php if($type == 'outgoing') echo 'active'; ?>">
									  	 <a href="javascript:void(0)" onclick="return getoutgoing()"><i class="fa fa-arrow-up"> </i> <?php echo Yii::t('app','Outgoing Requests'); ?> </a>
									  </li>
									   <li class="<?php if($type == 'success') echo 'active'; ?>">
									  	 <a href="javascript:void(0)" onclick="return getsuccess()"><i class="fa fa-check-circle"> </i> <?php echo Yii::t('app','Successful Exchanges'); ?> </a>
									  </li>
									   <li class="<?php if($type == 'failed') echo 'active'; ?>">
									  	 <a href="javascript:void(0)" onclick="return getfailed()"><i class="fa fa-times-circle"> </i> <?php echo Yii::t('app','Failed Exchanges'); ?> </a>
									  </li>
									 
									
									</ul>
								</div>


			<?php
	
			 if(!empty($exchanges)) {?>
			<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="testing">
			<?php
		
			 foreach($exchanges as $exchange): ?>
			<div class="exchange-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="">
						<div class="exchange-status-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="exchange-status"><?php echo Yii::t('app','Current status :'); ?> 
						<?php if($exchange['status'] == 1) { ?>
								<span class="status-txt" id="status-accepted">
									<?php echo Yii::t('app','ACCEPTED'); ?>
								</span>
								<?php } elseif($exchange['status'] == 2) {?>
								<span class="status-txt">
									<?php echo Yii::t('app','DECLINED'); ?>
								</span>
								<?php } elseif($exchange['status'] == 3) {?>
								<span class="status-txt">
									<?php echo Yii::t('app','CANCELLED'); ?>
								</span>
								<?php } elseif($exchange['status'] == 4) { ?>
								<span class="status-txt" id="status-accepted">
									<?php echo Yii::t('app','SUCCESS'); ?>
								</span>
								<?php } elseif($exchange['status'] == 5) { ?>
								<span class="status-txt">
									<?php echo Yii::t('app','FAILED'); ?>
								</span>
								<?php } elseif($exchange['status'] == 6) { ?>
								<span class="status-txt" id="status-pending">
									<?php echo Yii::t('app','SOLD OUT'); ?>
								</span>
								<?php } else { ?>
								<span class="status-txt" id="status-pending">
									<?php echo Yii::t('app','PENDING'); ?>
								</span>
								<?php } ?>
						</div>

						<?php if($type != 'success' && $type != 'failed') {?>
							<div class="view-exchange pull-right"><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/exchangeview').'/'.$exchange['slug']; ?>" onclick="switchVisible_exchange();" ><?php  echo Yii::t('app','View Exchange'); ?>
							<span class="viewmore-arrow">&gt;</span>
							</a>
							</div>
							<?php } ?>
						</div>
						<div class="exchange-content-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							
							<div class="exchange-left-content col-xs-12 col-sm-12 col-md-5 col-lg-5 no-hor-padding">
								<?php 
    						  $productDetails = yii::$app->Myclass->getProductDetails($exchange['mainProductId']);
					          $productImage = $exchange->mainProduct->photos;
								if(!empty($productImage)) {
									$image = $productImage[0]->name;
								}
								$userDetails = $exchange->requestTo;
								$userDetails=yii::$app->Myclass->getUserdetailss($userDetails);

								 ?>
									<div class="prof-pic-container col-xs-12 col-sm-5 col-md-5 col-lg-5 no-hor-padding">
								<a style="text-decoration: none;" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view')
								.'/'.yii::$app->Myclass->safe_b64encode($productDetails->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($productDetails->name); ?>" target="_blank">
									<?php $imgurl = Yii::$app->urlManager->createAbsoluteUrl('media/item/'.$exchange->mainProductId.'/'.$image);

											$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$exchange->mainProductId . '/' . $image);

									if (!file_exists($mediapath)) {
										$imgurl=Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage);
										

									}


								  ?>
									<?php if((!empty($image)) && (getimagesize($imgurl) !== false)){ ?>
										<img class="exchange-prof-pic" id="exchange-pic-1"
											src="<?php echo $imgurl;?>"
											alt="<?php echo $productDetails->name; ?>" />
									<?php } else { ?>
										<img class="exchange-prof-pic" id="exchange-pic-1"
											src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage); ?>"
											alt="<?php echo $productDetails->name; ?>" />
									<?php }?>
									</a> 
									<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profiles',
					'id'=>yii::$app->Myclass->safe_b64encode($userDetails['userId'].'-'.rand(0,999))]); ?>" target="_blank">
										<div class="exchange-prof-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<?php echo $userDetails['name']; ?>
										</div>
									</a> 
									</div>
									
									<div class="exchange-content col-xs-12 col-sm-7 col-md-7 col-lg-7 no-hor-padding">
										<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($productDetails->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($productDetails->name); ?>" target="_blank">
											<div class="exchange-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php echo $productDetails['name']; ?>
											</div>
										</a>

										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<?php if($productDetails['productCondition'] != '' && $productDetails['productCondition'] != '0'){ ?>
											<div class="col-xs-offset-4 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 used-status">
												<?php echo $productDetails['productCondition']; ?>
											</div>
											<?php } ?>
											<div class="exchange-place col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php echo $productDetails['location']; ?>
											</div>
										</div>
									</div>
								</div>

								<div class="exchange-arrow-cnt col-xs-12 col-sm-12 col-md-2 col-lg-2 no-hor-padding">
									<div class="exchange-arrow"></div>
								</div>

								<div class="exchange-right-content col-xs-12 col-sm-12 col-md-5 col-lg-5 no-hor-padding">
								<?php $productDetails = $exchange->exchangeProduct;
								$productImage = $exchange->exchangeProduct->photos;
								if(!empty($productImage)) {
									$image = $productImage[0]->name;
								}
								$userDetails = $exchange->requestFrom;
								$userDetails=yii::$app->Myclass->getUserdetailss($userDetails); ?>
									<div class="prof-pic-container col-xs-12 col-sm-5 col-md-5 col-lg-5 no-hor-padding">
									<a style="text-decoration: none;" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($productDetails->productId.'-'.rand(0,999))
									.'/'.yii::$app->Myclass->productSlug($productDetails->name); ?>" target="_blank">

										<?php $imgurl = Yii::$app->urlManager->createAbsoluteUrl('media/item/'.$productDetails->productId.'/'.$image);

										$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$productDetails->productId . '/' . $image);

										if (!file_exists($mediapath)) {
										$imgurl=Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage);
										

									}

									?>
										<?php if((!empty($image)) && (getimagesize($imgurl) !== false)){ ?>
											
										
										<img class="exchange-prof-pic" id="exchange-pic-1"
											src="<?php echo $imgurl; ?>"
											alt="<?php echo $productDetails->name; ?>" />
										<?php } else { ?>
										<img class="exchange-prof-pic" id="exchange-pic-1"
											src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage); ?>"
											alt="<?php echo $productDetails->name; ?>" />
										<?php }?>
										</a>


										<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profiles',
					'id'=>yii::$app->Myclass->safe_b64encode($userDetails['userId'].'-'.rand(0,999))]); ?>" target="_blank">
											<div class="exchange-prof-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php echo $userDetails['name']; ?>
											</div>
										</a>
									</div>
									<div class="exchange-content col-xs-12 col-sm-7 col-md-7 col-lg-7 no-hor-padding">
										<a style="text-decoration: none;" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($productDetails->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($productDetails->name); ?>" target="_blank">
											<div class="exchange-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php echo $productDetails['name']; ?>
											</div>
										</a>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<?php if($productDetails['productCondition'] != '' && $productDetails['productCondition'] != "0"){ ?>
										<div class="col-xs-offset-4 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 used-status">
											<?php echo $productDetails['productCondition']; ?>
										</div>
										<?php } ?>
										<div class="exchange-place col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<?php echo $productDetails['location']; ?>
										</div>
										</div>
										
									</div>
								</div>
							<!-- </div> -->
						</div>
						<div class="exchange-info col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" >
						<div class="exchange-initiate-date">
								<?php echo Yii::t('app','Exchange Initiated on'); ?> :
																						<?php
						$date=date('Y-m-d', $exchange->date);
						

											echo $date.'<br/>';
											?>
						</div>
						<div class="exchange-btn-cnt pull-right exchange-action-<?php echo $exchange->id; ?>">
							
									

							<?php 

							 if ($exchange->requestFrom == $userId  && $exchange->reviewFlagSender == 1 && $type == 'success'){
									
							
								$userDetails = $exchange->requestTo;
								$userDetails=yii::$app->Myclass->getUserdetailss($userDetails);
								?>
									<div style="display: none;" onclick="showreviewpopup('<?php echo $exchange->id; ?>','<?php echo $userDetails->userId; ?>')" class="review-btn review-btn<?php echo $exchange->id; ?> buy-button"> 
										<?php echo Yii::t('app','Write Review');?></div>
							<?php } ?>
							
							<?php if ($exchange->requestTo == $userId  && $exchange->reviewFlagReceiver == 1 && $type == 'success'){
								
							$userDetails = $exchange->requestFrom;
							$userDetails=yii::$app->Myclass->getUserdetailss($userDetails);
							?>
									<div style="display: none;" onclick="showreviewpopup('<?php echo $exchange->id; ?>','<?php echo $userDetails['userId']; ?>')" class="review-btn review-btn<?php echo $exchange->id; ?> buy-button"> 
										<?php echo Yii::t('app','Write Review');?></div>
							<?php } ?>
							
							<?php if($exchange->requestFrom == $userId && $type == 'outgoing') {
								$mCheck = yii::$app->Myclass->checkWhetherProductSold($exchange->mainProductId);
								$exCheck = yii::$app->Myclass->checkWhetherProductSold($exchange->exchangeProductId);
								if(!empty($mCheck) || !empty($exCheck)) { ?>
							<p class="sold-status">
								<label class="label-lg label-default"><?php echo Yii::t('app','ONE OF THE PRODUCTS IS SOLD'); ?>
								</label>
							</p>
							
								  <?php
							 echo Html::a(
								Yii::t('app','OK'), 
								['/user/sold', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #e40046;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>
								<?php } else {
									if($exchange->status == 1) { ?>

												    <?php
							 echo Html::a(
								Yii::t('app','SUCCESS'), 
								['/user/success', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #008000;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>

								<?php
							 echo Html::a(
								Yii::t('app','FAILED'), 
								['/user/failed', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #e40046;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>


							<?php } else { ?>

							    <?php
							 echo Html::a(
								Yii::t('app','CANCEL'), 
								['/user/cancel', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #464e55;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>
						
								<?php }
								}
						
							
							} ?>


							<?php if($exchange->requestTo == $userId && $type == 'incoming') {?>
							<?php $mCheck = yii::$app->Myclass->checkWhetherProductSold($exchange->mainProductId);
							$exCheck = yii::$app->Myclass->checkWhetherProductSold($exchange->exchangeProductId);
							if(!empty($mCheck) || !empty($exCheck)) { ?>
							<p class="sold-status">
								<label class="label-lg label-default"><?php echo Yii::t('app','ONE OF THE PRODUCTS IS SOLD'); ?></label>
							</p>
							  <?php
							 echo Html::a(
								Yii::t('app','OK'), 
								['/user/sold', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #e40046;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>
								<?php } else {
									if($exchange->status == 1) { ?>

									    <?php
							 echo Html::a(
								Yii::t('app','SUCCESS'), 
								['/user/success', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #e40046;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>


						
							<?php
							 echo Html::a(
								Yii::t('app','FAILED'), 
								['/user/failed', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #464e55;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>

						
							<?php } else { ?>
                             <?php
							 echo Html::a(
								Yii::t('app','ACCEPT'), 
								['/user/accept', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #e40046;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>
							
						

								      <?php
							 echo Html::a(
								Yii::t('app','DECLINE'), 
								['/user/decline', 'id' => $exchange->id], 
								['class' => 'exchange-btn','style' => 'font-size: 13px; float: left;text-decoration:none;background-color: #464e55;'],
								[
									'data-confirm' => ' Are you sure you want to proceed ?',
									'data-method' => 'post',
									
								]
							
							);
							?>


						 
								<?php } } 
							} ?>


							<?php if($type == 'failed' && $exchange->requestTo == $userId) { ?>
							<p class="no-more-exchanges no-more-<?php echo $exchange->id; ?>">
							<?php if($exchange->blockExchange == '0') { ?>
								<span class="exchange-btn" id="exc-failed"
									onClick='cancelExchange("<?php echo $exchange->id; ?>")'>	
									<?php echo Yii::t('app','Block Exchanges'); ?></span>

									<?php } else { ?>
								<span class="exchange-btn" id="exc-success"
									onclick='allowExchange("<?php echo $exchange->id; ?>");'>
									<?php echo Yii::t('app','Allow Exchanges'); ?></span>
									<?php } ?>
							</p>
							<?php }?>

						</div>
						
						</div>
					</div>
				</div>
				<?php  endforeach;  ?>

				<div class="clear urgent-tab-right">
				
				<?php	echo LinkPager::widget([
    'pagination' => $pages,
]);
		
					?>
					</div>

			</div>
		</div>
	</div>
	<?php

	?>
	<?php } else { ?>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="payment-decline-status-info-txt decline-center"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','No Exchanges Found.'); ?></div>
					</div>
				</div>
	<?php } ?>
	</div>