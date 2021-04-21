<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
error_reporting(0);
if (!isset($offset)) {
	$offset=15;
}
?>
<?= Html::csrfMetaTags() ?>
<script>
	var offset = 15;
	var limit = 15;
</script>
<?php
if(count($products) == 0)
	$empty_tap = " empty-tap ";
else
	$empty_tap = "";
if(empty($followerlist))
	$fempty_tap = " empty-tap ";
else
	$fempty_tap = "";
?>
<div class="container profile-page-dev">
	<div class="row">
		<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<ol class="breadcrumb">
				<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
				<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>
			</ol> 
		</div>
	</div>
	<div class="row">
		<div class="profile-vertical-tab-section col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php echo $this->render('//user/sidebar',['user'=>$user,'followerIds'=>$followerIds])?> 
			<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
				<?php  if(Yii::$app->session->hasFlash('success')): ?>
					<?=ToastrWidget::widget(['type' => 'success', 'message'=>Yii::$app->session->getFlash('success'),
						"closeButton" => true,
						"debug" => false,
						"newestOnTop" => false,
						"progressBar" => false,
						"positionClass" => ToastrWidget::POSITION_TOP_RIGHT,
						"preventDuplicates" => false,
						"onclick" => null,
						"showDuration" => "300",
						"hideDuration" => "1000",
						"timeOut" => "5000",
						"extendedTimeOut" => "1000",
						"showEasing" => "swing",
						"hideEasing" => "linear",
						"showMethod" => "fadeIn",
						"hideMethod" => "fadeOut"
					]);?>
				<?php  endif; ?>
				<?php if(Yii::$app->session->hasFlash('error')): ?>
					<?=ToastrWidget::widget(['type' => 'error', 'message'=>Yii::$app->session->getFlash('error'),
						"closeButton" => true,
						"debug" => false,
						"newestOnTop" => false,
						"progressBar" => false,
						"positionClass" => ToastrWidget::POSITION_TOP_RIGHT,
						"preventDuplicates" => false,
						"onclick" => null,
						"showDuration" => "300",
						"hideDuration" => "1000",
						"timeOut" => "5000",
						"extendedTimeOut" => "1000",
						"showEasing" => "swing",
						"hideEasing" => "linear",
						"showMethod" => "fadeIn",
						"hideMethod" => "fadeOut"
					]);?>
				<?php endif; ?>
				<?php if(Yii::$app->controller->action->id == 'profiles') { ?>
					<!--Listing-->
					<div id="listing" class=" profile-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $empty_tap; ?>">
						<?php   if(Yii::$app->user->id == $user->userId) {  ?>
							<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 textleft">
								<?php echo Yii::t('app','My Listing'); ?>
							</div>
							<?php 
							if(count($products) != 0) { ?>
								<div id="products" class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<!-- Start load result page -->
									<?php  $i=0;
									$siteSettings = yii::$app->Myclass->getSitesettings();
									$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82',
										'5e82de');
									foreach($products as $product):
										$soldData = '';
										$randKey = array_rand($colorArray);
										$colorvalue = "#".$colorArray[$randKey];
										$productId = $product->productId;
										$image = yii::$app->Myclass->getProductImage($productId);
										if(!empty($image)) {
											$img = $product->productId.'/'.$image;
											$img = Yii::$app->urlManager->createAbsoluteUrl('/media/item/'.$img);
											$imageSize = getimagesize($img);
											$imageWidth = $imageSize[0];
											$imageHeigth = $imageSize[1];
											if ($imageWidth > 300 && $imageHeigth > 300){
												$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$product->productId.'/'.$image);
											}
											$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$product->productId.'/'.$image);
											if(file_exists($mediapath)) {
												$img = $img;
											} else {
												$img =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
											}
										} 
										else {
											$img = $siteSettings->default_productimage;
											$img = Yii::$app->urlManager->createAbsoluteUrl('/media/item/'.$img);
										}
										if(isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '2'){
											$promotions='1';
											$soldData = '<span class="item-urgent">'.Yii::t('app','Urgent').'</span>';
										}elseif (isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '1') {
											$promotions='1';
											$soldData = '<span class="item-ad">'.Yii::t('app','Ad').'</span>';
										}elseif (isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '3') {
											$promotions='0';
										}
										if ($product->quantity == 0 || $product->soldItem == 1){
											$soldData = '<div class="sold-out list abs-sold-out"> '.Yii::t('app','Sold Out').'</div>';
											$promotions='1';
										}
										?>
										<div class="profile-listing-product product-padding col-xs-12 col-sm-6 col-md-4 col-lg-4">
											<div onclick="" class="image-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="prod-1" dataid="<?php echo $productId; ?>" style="background:url('<?php echo $img; ?>') no-repeat center center;background-size: cover;background-color:<?php echo $colorvalue; ?>;border-radius: 6px;">
												<div class="imghoverproductlist"> <!--profile-listing-product-hover col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding-->
													<div class="profile-listing-opacity-bg col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
													<div class="product_view">
														<a style="text-decoration: none;" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name); ?>">
															<div class="productimage" style="background-image: url('<?php echo $img; ?>');background-color:<?php echo $colorvalue; ?>;">
																<?php echo $soldData; ?>
															</div>
														</a>
														<div class="listingProducts">
															<span><?=ucfirst($product->name)?></span>
														</div>
														<div class="listingDotsPosition"  data-toggle="dropdown" aria-expanded="false">
															<div class="listingDotsHolder">										
																<div>
																	<div class="listingDots"></div>
																	<div class="listingDots"></div>
																	<div class="listingDots"></div>
																</div>
															</div>
														</div>
														<ul class="dropdown-menu msg_dropdown dropdownf-position resp-disp-popup ">			
															<?php if(Yii::$app->user->id == $product->userId) { ?>
																<?php  if($promotions == '1'){ ?>
																	<li class="user_pactive">
																		<a href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name)?>><?php echo Yii::t('app','View Listing');  ?>	</a>
																	</li>
																	<li>
																		<a   href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/update').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Edit Listing');  ?></a>
																	</li>
																	<li>
																		<a   href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/insights?id=').yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Insights');  ?></a>
																	</li>
																<?php }else{  ?> 
																	<li class="user_pactive">
																		<a   href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name)?>><?php echo Yii::t('app','View Listing');  ?>	</a>
																	</li>
																	<li>
																		<a   href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/update').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Edit Listing');  ?></a>
																	</li>
																	<li>
																		<a   href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/insights?id=').yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Insights');  ?></a>
																	</li>
																	<?php
																	if(isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1")
																	{
																		?>
																		<li><a href="javascript:void(0);" data-toggle="modal" data-target="#modal1" onclick = "showListingPromotion('<?php echo $product->productId; ?>')">
																			<?php echo Yii::t('app','Promote');  ?>
																		</a> </li>          		
																	<?php	} ?>
																<?php }  }else{ ?>
																	<li> 
																		<a href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name)?>>
																			<?php echo Yii::t('app','View Listing');  ?>
																		</a></li>
																	<?php } ?>
																</ul>
															</div>
														</div>
													</div>
												</div>
												<?php $i++;
											endforeach; ?>
										</div>
									<?php }else{ ?>
										<div class="modal-dialog modal-dialog-width">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="payment-decline-status-info-txt decline-center"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','You have not added any stuff.'); ?></div>
													<div class="text-align-center col-lg-12 no-hor-padding"><a class="center-btn payment-promote-btn login-btn" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/products/create'); ?>"><?php echo Yii::t('app','Go to add your stuff'); ?></a></div>
												</div>
											</div>
										</div>
										<?php
										echo '<style type="text/css">
										.profile-tab-content
										{
											max-height: 508px;
										}
										</style>';
									} ?>
									<?php 
									if(count($products) >= 15) {  ?>
										<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<div class="classified-loader" style="width: 60px;">
												<div class="cssload-loader"></div>
											</div>
											<a class="loadmorenow load">
												<div class="load-more-icon" onclick="load_more('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)) ?>')"></div>
												<div class="load-more-txt"><?php echo Yii::t('app','Load More'); ?></div>
											</a>
										</div>
									<?php } }else{ ?>
										<?php 
                   						// Listing PRoduct with condition
										if(count($products) != 0){  
											?>
											<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<?php echo Yii::t('app','Listing'); ?>
											</div>
											<div id="products" class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 <?php echo $empty_tap; ?>">
												<?php echo  $this->render('loadresults',['products'=>$products]); ?>
											</div>
											<?php if(count($products) >= 15) {
												if(Yii::$app->controller->action->id == 'profiles') { ?>
													<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
														<a class="loadmorenow load">
															<div class="load-more-icon" onclick="load_more('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)) ?>')"></div>
															<div class="load-more-txt"><?php echo Yii::t('app','Load More'); ?></div>
														</a>
													</div>
												<?php } }?>
											<?php }else{  ?>
												<div class="modal-dialog modal-dialog-width">
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="payment-decline-status-info-txt decline-center"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','User is not added any stuff.'); ?></div>
														</div>
													</div>
												</div>
											<?php } } ?>
										</div>
									<?php }?>
									<?php if(Yii::$app->controller->action->id == 'liked')
									$lactive = 'active';
									else
										$lactive = ''; ?>
									<?php if(Yii::$app->controller->action->id == 'follower')
									$factive = 'active';
									else
										$factive = ''; ?>
									<?php if(Yii::$app->controller->action->id == 'following')
									$f1active = 'active';
									else
										$f1active = ''; ?>
									<?php if(Yii::$app->controller->action->id == 'liked' || Yii::$app->controller->action->id == 'follower' || Yii::$app->controller->action->id == 'following') { ?>
										<div class="classified-loader showLoad" id="exc-loader" style="width: 60px;"><div class="cssload-loader"></div></div>	
										<div id="recent_content" class="hideAll">
											<?php echo $this->render('loadliked',['user'=>$user,'products'=>$products]); ?>
										</div>
									<?php	}
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="paypal-form-container"></div>
					<div id="modal1" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
						<div class="modal-dialog post-list-modal-width">
							<div class="post-list-modal-content login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="post-list-header login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="modal-header-text"><p class="login-header-text"><?php echo Yii::t('app','Promote the listing'); ?></p></div>
									<button data-dismiss="modal" class="close login-close" type="button" id="white">×</button> 
								</div>
								<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
								<?php 
								$sitesetting = yii::$app->Myclass->getSitesettings();
								?>
								<div class="post-list-cnt login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
									<div class="login-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<div class="login-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<div class="post-list-modal-heading"><?php echo Yii::t('app','Highlight your listing?'); ?></div>
											<div class="post-list-content">
												<?php echo $sitesetting->sitename." ".Yii::t('app','allows you to highlight your listing with two different options to reach more number of buyers. You can choose the appropriate option for your listings. Urgent listings gets more leads from buyers and featured listings shows at various places of the website to reach more buyers.'); ?>
											</div>
										</div>
										<div class="post-list-tab-cnt">
											<ul class="post-list-modal-tab nav nav-tabs">
												<li class="active"><a data-toggle="tab" href="#urgent"><?php echo Yii::t('app','Urgent'); ?></a></li>
												<li><a data-toggle="tab" href="#promote"><?php echo Yii::t('app','Ad'); ?></a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="post-list-tab-content  tab-content">
									<div id="urgent" class="tab-pane fade in active">
										<p> <?php echo Yii::t('app','To make your ads instantly viewable you can go for Urgent ads, which gets highlighted at the top.'); ?></p>
										<?php  if (isset($urgentPrice)) {
											$promoteCurrency = explode("-", $promotionCurrency);
											echo "<p align='center'>";
											if (isset($_SESSION['language'])  && $_SESSION['language'] == 'ar'){
												echo yii::$app->Myclass->convertArabicPopupFormattingCurrency($promoteCurrency[0],$urgentPrice); 
											}
											else{
												echo yii::$app->Myclass->convertFormattingCurrency($promoteCurrency[0],$urgentPrice); 
											}
											echo "</p>";
										} ?>
										<div class="urgent-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
											<ul><div class="urgent-tab-heading"><?php echo Yii::t('app','Urgent tag Features:'); ?></div>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','More opportunities for your buyers to see your product'); ?></span></li>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Higher frequency of listing placements'); ?></span></li>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Highlight your listing to stand out'); ?></span></li>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Use for Make fast sale for seller and Make buyer to do purchase as Urgent'); ?></span></li>
												<li class="stuff-post">
													<?php 
													$sitesetting = yii::$app->Myclass->getSitesettings();
													$paymenttype = json_decode($sitesetting->sitepaymentmodes, true); 
													$bannerpaymenttype =  $paymenttype['bannerPaymenttype'];
													if($bannerpaymenttype == "stripe"){
														?>
														<?php $form = ActiveForm::begin(['id'=>'promotionstripeform',
														'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionstripepaymentprocess'),'options' => ['enctype' => 'multipart/form-data']]); ?>
														<input type="hidden" name="BPromotionType" value='urgent' />
														<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">
														<button class="btn post-btn" id="customButton"><?php echo Yii::t('app','Highlight with stripe'); ?></button>
														<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
														<div class="urgent-promote-error delete-btn"></div>
														<input type="hidden" id="itemids" name="itemids">
														<?php 
														$userId = Yii::$app->user->id;
														$sitesetting = yii::$app->Myclass->getSitesettings();
														$stripeSetting = json_decode($sitesetting->stripe_settings, true); 
														$stripe_key = $stripeSetting['stripePublicKey'];
														$total_price = ($sitesetting->urgentPrice)*100;
														$currency =  explode('-', $sitesetting->promotionCurrency);	
														$promotionType = "urgent";
														$customField = $promotionType."-_-".$currency[0]."-_-0-_-".$total_price."-_-".$userId;
														$customField = yii::$app->Myclass->cart_encrypt($customField, "pr0m0tion-det@ils");
														?>
														<input type="hidden" value="<?php echo $total_price; ?>" id="price" >
														<input type="hidden" value="<?php echo $promoteCurrency[1]; ?>" id="displaycurrency" >
														<input type="hidden" value="" id="promotiontype" name="promotiontype">
														<input type="hidden" value="<?php echo $stripe_key; ?>" id="stripekey" >
														<input type="hidden" value="" id="totalprice" name="totalPrice">
														<div id="shiperr-stripe" style="font-size:13px;color:red;margin-left: 20px;"></div>
														<input type="hidden" value="<?php echo $customField; ?>" id="customField1" name="customField1">
														<input type="hidden" value="" id="customField" name="customField">
														<input type="hidden" name="BPromotionType" value='urgent' />
														<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">
														<input type="hidden" name="currency" value="<?php echo $currency[0]; ?>"/>
														<?php ActiveForm::end(); ?>
													<?php }else{ ?> 
														<?php $form = ActiveForm::begin(['id'=>'promotionbraintreeform',
														'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionpaymentprocess'),'options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return promotionUpdate("urgent")']]); ?>
														<input type="hidden" name="BPromotionType" value='urgent' />
														<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">
														<button class="btn post-btn" href="javascript:void(0);" onclick='return promotionUpdate("urgent")' type="submit"><?php echo Yii::t('app','Highlight with braintree'); ?></button>
														<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
														<div class="urgent-promote-error delete-btn"></div>
														<?php ActiveForm::end(); ?>
													<?php } ?>
												</li>
											</ul>
										</div>
										<div class="urgent-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
											<div class="urgent-right-circle-icon"><span class="item-urgent-1"><?php echo Yii::t('app','Urgent'); ?></span></div>
										</div>
									</div>
									<div id="promote" class="tab-pane fade">
										<p><?php echo Yii::t('app','Promote your listings to reach more users than normal listings. The promoted listings will be shown at various places to attract the buyers easily.'); ?></p>
										<div class="tab-radio-button-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<?php
											$promotionCurrencyDetails = explode('-', $promotionCurrency);
											foreach ($promotionDetails as $promotion){ ?>
												<div class="tab-radio-button col-xs-6 col-sm-6 col-md-3 col-lg-3 no-hor-padding">
													<div class="tab-radio-content"> 
														<label><input type="radio" name="optradio" onclick="updatePromotion(<?php echo $promotion->id.",".$promotion->price; ?>)"></label>
														<div class="radio-tab-period"><?php echo $promotion->name; ?></div>
														<div class="radio-tab-price col-xs-offset-3 col-sm-offset-5 col-md-offset-4 col-lg-offset-4">
															<?php
															if (isset($_SESSION['language'])  && $_SESSION['language']== 'ar')
																echo yii::$app->Myclass->convertArabicFormattingCurrency($promotionCurrencyDetails[0],$promotion->price); 
															else{
																echo yii::$app->Myclass->convertFormattingCurrency($promotionCurrencyDetails[0],$promotion->price); 
															} ?>
														</div>
														<div class="radio-tab-days"><?php echo $promotion->days; ?> <?php echo Yii::t('app','days'); ?></div>
													</div>
												</div>
											<?php }  ?>
										</div>
										<div class="promote-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
											<ul><div class="promote-tab-heading"><?php echo Yii::t('app','promote tag Features:'); ?></div>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','View-able with highlight for all users on desktop and mobile'); ?></span></li>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Displayed at the top of the page in search results'); ?></span></li>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Higher visibility in search results means more buyers'); ?></span></li>
												<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Listing stands out from the regular posts'); ?></span></li>
												<li class="stuff-post">
													<?php
													if($bannerpaymenttype == "stripe"){
														$form = ActiveForm::begin(['id'=>'adpromotionstripeform',
														'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionstripepaymentprocess'),'options' => ['enctype' => 'multipart/form-data']]); ?>
														<button class="btn post-btn" id="customButton1"><?php echo Yii::t('app','Promote with Stripe');?></button>
														<input type="hidden" id="promotionids" name="promotionids">
														<?php 
														$userId = Yii::$app->user->id; 
														$sitesetting = yii::$app->Myclass->getSitesettings();
														$stripeSetting = json_decode($sitesetting->stripe_settings, true); 
														$stripe_key = $stripeSetting['stripePublicKey'];
														$currency =  explode('-', $sitesetting->promotionCurrency);
														$promotionTypes = "adds";
														$total_pricess = $promotion->price;
														$customFieldd = $promotionTypes."-_-".$currency[0]."-_-0-_-".$total_pricess."-_-".$userId;
														$customFieldd = yii::$app->Myclass->cart_encrypt($customFieldd, "pr0m0tion-det@ils"); 
														?>
														<input type="hidden" value="<?php echo $promotionTypes; ?>" id="promotiontypee" name="promotiontypee">
														<input type="hidden" value="" id="itemide" name="itemide" >
														<input type="hidden" value="<?php echo $stripe_key; ?>" id="stripekeyy" >
														<input type="hidden"  value="<?php echo $total_pricess; ?>" id="totalpricee" >
														<div id="shiperr-stripe" style="font-size:13px;color:red;margin-left: 20px;display:none;"></div>
														<input type="hidden" value="<?php echo $customFieldd; ?>" id="customFieldd" name="customFieldd">
														<input type="hidden" id ="currencyy" name="currencyy" value="<?php echo $currency[0]; ?>"/>
														<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
														<div class="adds-promote-error delete-btn">
															<?php ActiveForm::end(); } else{?>
																<?php $form = ActiveForm::begin(['id'=>'promotionbraintreeform','action'  => Yii::$app->urlManager->createAbsoluteUrl('products/promotionpaymentprocess'),'options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return promotionUpdate("adds")']]); ?>
																<form name="promotionbraintreeform" method="post" action="<?php // Yii::$app->urlManager->createAbsoluteUrl('products/promotionpaymentprocess'); ?>" >
																	<!-- validateOnSubmit="return promotionUpdate('adds')" -->
																	<input type="hidden" name="BPromotionType" value='adds' />
																	<input type="hidden" name="BPromotionProductid" id="ADPromotionProductid" value="">
																	<input type="hidden" name="BPromotionid" id="ADPromotionid" value="promotionUpdate('adds')">
																	<button class="post-btn btn" onclick="promotionUpdate('adds')" ><?php echo Yii::t('app','Promote with braintree'); ?></button>
																	<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
																	<div class="adds-promote-error delete-btn">
																		<?php ActiveForm::end(); } ?>
																	</form>
																</div>
															</li>
														</ul>
													</div>
													<div class="promote-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
														<div class="promote-right-circle-icon"><span class="item-ad-1"><?php echo Yii::t('app','Ad'); ?></span></div>
													</div>
												</div>
											</div>
										</div> 
									</div>
									<input type="hidden" class="promotion-product-id" value="">
									<input type="hidden" name="Products[promotion][type]" value="" id="promotion-type">
									<input type="hidden" name="Products[promotion][addtype]" value="" id="promotion-addtype">
								</div>
								<script type="text/javascript">
									$("#close1_open2").click(function() {
										var sum = parseInt($('#fnum').val(), 10)+parseInt($('#fnum').val(), 10);
										$("#sum").text(sum);
										$("#modal1").modal('hide');
										$("#modal2").modal('show');
									});
								</script>
								<script>jQuery.noConflict();</script>
								<style type="text/css">
									.footer {
										margin-top: 0px !important;
									}
									.textleft
									{
										text-align: left;
									}
									.hideAll  {
										visibility:hidden;
									}
									.showLoad{
										display: block;
									}
									.resp-disp-popup
									{
										z-index: 998;
									}
								</style>
								<script type="text/javascript">
									function load_more(id)
									{
										$.ajax({
											url: '<?= Yii::$app->getUrlManager()->getBaseUrl()?>/user/profiles/',
											type: "POST",
											dataType : "html",
											data: {
												"limit": limit, "offset": offset, "id" : id
											},
											beforeSend: function(data){
												$(".classified-loader").show();
												$(".load").hide();
											},
											success: function (res) {
												$(".classified-loader").hide();
												$(".load").show();
												var output = res.trim();
												if (output) {
													offset = offset + limit;
													$("#products").append(output);
												} else {
													$(".load").html(yii.t('app',"No More Products"));
												}
											},
										});
									}
								</script>
								<script type="text/javascript">
									window.onload = function() {
										var loadedUrl = window.location.href;
										var splittedurl = loadedUrl.split("user/");
										var followerurl = splittedurl[1].split("?");
										if(followerurl[0]=="follower")
										{
											setTimeout(function() {
												$("#followerclk").trigger('click');
											},10);
										}
										if(followerurl[0]=="following")
										{
											setTimeout(function() {
												$("#followingclk").trigger('click');
											},10);
										}
										$("#recent_content").removeClass("hideAll");
										$("#exc-loader").removeClass("showLoad");
									};
									function getfollower(id) {
										$.ajax({
											url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/morefollower/',
											type: "POST",
											dataType : "html",
											data: {
												"id" : id
											},
											beforeSend: function(data){	
												$("#recent_content").hide();			
												$(".classified-loader").show();
											},
											success: function (res) {
												$("#recent_content").show();
												$(".classified-loader").hide();
												$('#recent_content').html(res);
											},
										});
									}
									function getliked(id) {
										$.ajax({
											url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/moreliked/',
											type: "POST",
											dataType : "html",
											data: {
												"id" : id
											},
											beforeSend: function(data){	
												$("#recent_content").hide();			
												$(".classified-loader").show();
											},
											success: function (res) {
												$("#recent_content").show();
												$(".classified-loader").hide();
												$('#recent_content').html(res);
											},
										});
									}
									function getfollowing(id) {
										$.ajax({
											url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/morefollowing/',
											type: "POST",
											dataType : "html",
											data: {
												"id" : id
											},
											beforeSend: function(data){	
												$("#recent_content").hide();			
												$(".classified-loader").show();
											},
											success: function (res) {
												$("#recent_content").show();
												$(".classified-loader").hide();
												$('#recent_content').html(res);
											},
										});
									}
								</script>
								<script src="https://checkout.stripe.com/checkout.js"></script>
								<script src="https://code.jquery.com/jquery-latest.min.js"></script> 
								<script>
									$('#customButton').click(function(){
										if(this.disabled = true){
											var stripekey = $('#stripekey').val();
											var x='<?php echo $promoteCurrency[1]; ?>';
											var promotionType = $('#promotiontype').val('urgent');
											var totalprice = $('#price').val();
											var customField1 = $('#customField1').val();
											$('#customField').val(customField1);
											$('#totalprice').val(totalprice);
											$('#customButtonn').attr('disabled', 'disabled');
											$id = $('.promotion-product-id').val();
											$('#itemids').val($id);
											var token = function(res){
												var $input = $('<input type=hidden name=stripeToken class=stripee />').val(res.id);
												$('#braintreeurgentt').attr('disabled', 'disabled');
												$('#customButton').attr('disabled', 'disabled');
												$('#promotionstripeform').append($input).submit();
											};
											StripeCheckout.open({
												key:         stripekey,
												address:     false,
												amount:      totalprice,
												currency:    '<?php echo trim(strtolower($currency[0])); ?>',
												name:        '<?php echo $sitesetting->sitename; ?>',
												panelLabel:  'Checkout',
												token:       token,
												closed : function() {
													var stripeee = $('.stripee').val();
													if(typeof(stripeee) === "undefined"){
														$("#braintreeurgentt").removeAttr('disabled');
														$("#customButton").removeAttr('disabled');
													}else{
														$('#braintreeurgentt').attr('disabled', 'disabled');
														$('#customButton').attr('disabled', 'disabled');
													}
												}
											});
											return false;
										}
									});
									$('#customButton1').click(function(){
										var stripekey = $('#stripekeyy').val();
										var promotionTypes = $('#promotiontypee').val();
										$id = $('.promotion-product-id').val();
										$('#itemide').val($id);
										var totalpricee = $('#totalpricee').val();
										var  totalprice =  totalpricee * 100;
										var errorSelector = ".adds-promote-error";	
										var promotionId = $('#promotion-addtype').val();
										$('#customButtonn1').attr('disabled', 'disabled');
										if(promotionId == ""){
											$(errorSelector).html(yii.t('app', 'Select a Promotion'));
											$(errorSelector).show();
											setTimeout(function() {
												$(errorSelector).html('');
												$(errorSelector).hide();
											}, 1500);
											return false;
										}
										else {
											if(this.disabled = true){
												var token = function(res){
													var $input = $('<input type=hidden name=stripeToken class=stripee />').val(res.id);
													$('#braintreeaddss').attr('disabled', 'disabled');
													$('#customButton1').attr('disabled', 'disabled');
													$('#adpromotionstripeform').append($input).submit();
												};
												StripeCheckout.open({
													key:         stripekey,
													address:     false,
													amount:      totalprice,
													currency:    '<?php echo trim(strtolower($currency[0])); ?>',
													name:        '<?php echo $sitesetting->sitename; ?>',
													panelLabel:  'Checkout',
													token:       token,
													closed : function() {
														var stripeee = $('.stripee').val();
														if(typeof(stripeee) === "undefined"){
															$("#braintreeaddss").removeAttr('disabled');
															$("#customButton1").removeAttr('disabled');
														}else{
															$('#braintreeaddss').attr('disabled', 'disabled');
															$('#customButton1').attr('disabled', 'disabled');
														}
													}
												});
											}}
											return false;
										});
									</script>