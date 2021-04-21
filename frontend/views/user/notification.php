<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\Exchanges;
$user->userId = $model['userId'];
$user->name = $model['name'];
$user->userImage = $model['userImage'];
$user->mobile_status = $model['mobile_status'];
$user->facebookId = $model['facebookId'];
?>
<?= Html::csrfMetaTags() ?>
<?php
if(count($logModel) == '0'){
	$empty_tap = " empty-tap ";
}else{
	$empty_tap = "";
} ?>
<script type="text/javascript">
	var notifyOffset = 32;
	var notifyLimit = 32;
</script>
<!--Notifications-->
<div class="container">
	<div class="row">
		<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<ol class="breadcrumb">
				<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
				<li><a href="#"><?php echo Yii::t('app','Notification'); ?></a></li>
			</ol>
		</div>
	</div>
	<div class="row page-container">
		<div class="container exchange-property-container profile-vertical-tab-section">
			<?=$this->render('//user/sidebar',['user'=>$user])?> 
			<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
				<div id="notifications" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in <?php echo $empty_tap; ?>">
					<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding textleft">
						<?php echo Yii::t('app','Notifications'); ?>
					</div>
					<div class="notification-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<?php  
						Yii::$app->controller->renderPartial('notificationloadmore',['logModel'=>$logModel]); ?>
						<!-- start code -->
						<?php if(empty($exchanges)){
							$empty_tap = " empty-tap ";
						}else{
							$empty_tap = "";
						} ?>
						<?php if(count($logModel) != '0') {
							foreach ($logModel as $log){
								$productModel = array();
								if($log->itemid != 0){
									if($log->type == 'exchange')
									{
										$getExchange = Exchanges::find()->where(['id'=>$log->sourceid])->one();
										$product_id = $getExchange->mainProductId;
									}else{
										$product_id = $log->itemid;
									}
									$productModel = yii::$app->Myclass->getProductDetails( $product_id );
								}
								$userModel = yii::$app->Myclass->getUserDetailss($log->userid);
								if(!empty($userModel->userImage)){
									$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userModel->userImage);
								}else{
									$userImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
								}
								$createdDate = date('jS M Y', $log->createddate);
								$createdDate = $log->createddate;
								?>
								<div class="notification-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="notification-pro-pic-cnt">
										<?php if ($log->type != 'admin' && $log->type != "adminpayment"){ ?>
											<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profiles', 'id' => yii::$app->Myclass->safe_b64encode($userModel->userId.'-'.rand(0,999))]); ?>">
												<div class="notification-prof-pic" id="notif-prof-1" style="background-image: url('<?php echo $userImage; ?>');"></div>
											</a>
											<?php }else{ ?><a href="javascript:void(0);">
												<div class="notification-prof-pic" id="notif-prof-1" style="background-image: url('<?php echo $userImage; ?>');"></div>
											</a>
										<?php } ?>
									</div>
									<div class="notification-message-cnt">
										<div class="notification-message">
											<?php if ($log->type != 'admin' && $log->type != "adminpayment"){ ?>
												<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profiles',
												'id'=>yii::$app->Myclass->safe_b64encode($userModel->userId.'-'.rand(0,999))]); ?>"  title="_blank"
												title="<?php echo $userModel->username; ?>">
												<?php echo $userModel->name; ?>
											</a>
											<?php
											if($log->type == 'order')
											{
												$string = $log->notifymessage;
												$value = explode(" Order Id :", $string);
												if (strpos($string, 'Order Id :') !== false) {
													echo Yii::t("app", $value[0])." ".Yii::t("app", 'Order Id :').$log->sourceid;
												}
												else
												{	
													echo Yii::t("app", $log->notifymessage);
												}
											}
											elseif($log->type == 'myoffer')
											{
												$string = $log->notifymessage;
												$value = explode("sent offer request", $string);
												$value1 = explode("accepted your offer request", $string);
												if($value[0] != "contacted you on your product")
												{
													$split_point = "on your product";
													if (isset($value[1])) {
														$strings = $value[1];
													}
													else
													{
														$strings = $value[0];
													}
													$rev = array_reverse(explode($split_point, $strings));
													if (isset($rev[1])) {
														echo Yii::t("app", "sent offer request")." ".$rev[1]." ".Yii::t("app", "on your product");
													}
													else
													{
														$value1 = explode("accepted your offer request", $string);
														$value2 = explode("declined your offer request", $string);
														if (isset($value1[1])) {
															echo Yii::t("app", "accepted your offer request").$value1[1];
														}
														else if (isset($value2[1]))
														{
															echo Yii::t("app", "declined your offer request").$value2[1];
														}
														else
														{
															echo $rev[0];
														}						
													}
												}
												else
												{
													echo Yii::t("app", "contacted you on your product");
												}
											}
											else
											{
												echo Yii::t("app", $log->notifymessage);
											}
											?>
											<?php if (!empty($productModel)){ ?>
												<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($productModel->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($productModel->name)?>" class="notification-product-name" target="_blank">
													<?php echo $productModel->name; ?>
												</a>
											<?php } ?>
										<?php }else if($log->type=="adminpayment")
										{
											$string = $log->notifymessage;
											if($string == "Still You didn't add the stripe credentials. Please add it for getting the amount.")
												echo Yii::t('app',$string);
											else{
												$value = explode("Order Id", $string);
												if ($value[0] == "paid the amount for your order. ") { ?>
													<a href="javascript:void(0);">
														<?php echo yii::$app->Myclass->getSiteName()." ";
														?>
														</a> <?php echo Yii::t("app", "paid the amount for your order").' '.Yii::t("app",'Order Id').' '.$value[1]; ?>
													<?php	}
													else if ($value[0] == "refunded the amount for your order. ") {
														?>
														<a href="javascript:void(0);">
															<?php echo yii::$app->Myclass->getSiteName()." ";
															?>
															</a> <?php echo Yii::t("app", "refunded the amount for your order").' '.Yii::t("app",'Order Id').' '.$value[1]; ?>
														<?php	}
														?>
													<?php }}
													else{ ?>
														<a href="javascript:void(0);">
															<?php echo yii::$app->Myclass->getSiteName()." "; ?>
															</a> <?php echo Yii::t("app", $log->notifymessage)." '".$log->message."'"; ?>
														<?php } ?>
													</div>
													<div class="notification-date">
														<?php echo date('M j,Y', $createdDate); ?>
													</div>
												</div>
											</div>
										<?php }
									} else { ?>
										<div class="modal-dialog modal-dialog-width">
											<div class="col-xs-8 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="payment-decline-status-info-txt" style="margin: 8% auto 0;">
														<img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>">
														</br><span class="payment-red"><?php echo Yii::t('app','Sorry...');?></span> <?php echo Yii::t('app','You have no notification');?><?php echo ".";?>
													</div>
												</div>
											</div>
										</div>
									<?php  } ?>
									<!--offer modal-->
									<div class="modal fade" id="count_modal" role="dialog" tabindex='-1'>
										<div class="modal-dialog modal-dialog-width">
											<div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<h2 class="login-header-text"><?=Yii::t('app','Counter')?></h2>
													<button data-dismiss="modal" class="close login-close" type="button">×</button>
												</div>
												<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
												<div class="login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
													<div class="counter-price-text clearfix">
														<div class="right-borer col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<div class="price-text"><?=Yii::t('app','Asking Price')?></div>
															<div class="price-rate">$ 90.00</div>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<div class="price-text"><?=Yii::t('app','Offer Price')?></div>
															<div class="price-rate">$ 60.00</div>
														</div>
													</div>
													<div class="offer-price-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?=Yii::t('app','Your Counter Price')?> :</label>
														<div class="offer-price-txt-field-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="offer-text-field-label col-xs-1 col-sm-1 col-md-1 col-lg-1 no-hor-padding">$</div>
															<div class="offer-text-field col-xs-11 col-sm-11 col-md-11 col-lg-11 no-hor-padding">
																<input type="text" class="my-offer-rate" maxlength="9" id="MyOfferForm_offer_rate" placeholder="<?=Yii::t('app','Enter your price')?>">
																<div class="message-error" style="color: red;"></div>
															</div>
														</div>
														<div class="send-btn-container col-xs-12 col-sm-3 col-md-3 col-lg-3 no-hor-padding pull-right">
															<a href="javascript:;" onClick="myoffer()">
																<div class="send-btn primary-bg-color txt-white-color text-align-center offer-send-btn"><?=Yii::t('app','Send')?></div>
															</a>
														</div>
														<div id="errorMessage" style="color: red"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--E O offer modal-->
									<!-- end code -->
								</div>
								<?php Pjax::begin(); ?>
								<?php if (count($logModel) >= 32){ ?>
									<?=Html::csrfMetaTags()?>
									<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
										<a class="loadmorenow load">
											<div class="load-more-icon" onclick="load_more()"></div>
											<div class="load-more-txt"><?=Yii::t('app','Load More')?></div>
										</div>
										<div class="classified-loader">
											<div class="cssload-loader"></div>
										</div>
									<?php } ?>
									<?php Pjax::end(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<style type="text/css">
					.textleft
					{
						text-align: left;
					}
				</style>
				<script type="text/javascript">
					function load_more()
					{
						$.ajax({
							url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/notificationloadmore/',
							type: "GET",
							dataType : "html",
							data: {
								"notifyLimit": notifyLimit, "notifyOffset": notifyOffset
							},
							beforeSend: function(data){
								$(".load-more-cnt").hide();
								$(".classified-loader").show();
							},
							success: function (response) {
								$(".load-more-cnt").show();$(".classified-loader").hide();
								var output = response.trim();
								if (output != 0) {
									notifyOffset = notifyOffset + notifyLimit;
										//$("#products").append(output);
										$(".notification-cnt").append($.trim(output));
									} else {
										$(".classified-loader").hide();
										$(".load-more-cnt").hide();
									}
								},
							});
					}
				</script>