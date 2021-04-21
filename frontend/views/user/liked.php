<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
?>
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
					<?=$this->render('//user/sidebar',['user'=>$user,'followerIds'=>$followerIds])?> 

					<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">

					<?php if(Yii::$app->controller->action->id == 'profiles') { ?>

						<div id="listing" class=" profile-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $empty_tap; ?>">

						<?php if(Yii::$app->user->id == $user->userId) { ?>
							<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 textleft">
								<?php echo Yii::t('app','My Listing'); ?>
							</div>
							<?php if(count($products) != 0) { ?>
							<div id="products" class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php echo  $this->render('loadresults',compact('products')); ?>
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
							<?php if(count($products) >= 15) { ?>
							<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
									<a class="loadmorenow load">
									<div class="load-more-icon" onclick="load_more('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)) ?>')"></div>
									<div class="load-more-txt"><?=Yii::t('app','Load More')?></div>

								
							</a>
						</div>


						<?php } }else{ ?>
								<?php if(count($products) != 0){ ?>
											<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12">
												 <?php echo Yii::t('app','Listing'); ?>
											</div>
											<div id="products" class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 <?php echo $empty_tap; ?>">
												<?php $this->renderPartial('loadresults',compact('products')); ?>
												</div>
												<?php if(count($products) >= 15) {
												if(Yii::$app->controller->action->id == 'profiles') { ?>
													<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
													<a class="loadmorenow load">
													<div class="load-more-icon"></div>
												<?php echo CHtml::ajaxButton(Yii::t('app','Load More'), array('profiles','id' =>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),
												array(
												'data'=> 'js:{"limit": limit, "offset": offset }',
												'beforeSend'=>'js:function(data){
												$(".classified-loader").show();
												$(".load").hide();
												}',
												'success' => 'js:function(response){
												$(".classified-loader").hide();
												$(".load").show();
										        var output = response.trim();
														if (output) {
															offset = offset + limit;
												 $("#products").append(output);
														} else {
												        $(".load").html(Yii.t("app","No More Products"));
												        //$(".load").hide();

														}
												 }',
												)
												); ?>
												</a>
										</div>
												<?php } }?>
											<?php }else{ ?>
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
					<!--Recent activity-->
						<div id="recent-activity" class="profile-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $empty_tap; ?><?php echo $fempty_tap; ?>">

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<ul class="recent-activities-tab nav nav-tabs">
		  <li class="active"><?=Html::a('Liked', ['user/liked'])?></li>
		  <li class=""><?=Html::a('Followers', ['user/follower'])?></li>
		  <li class=""><?=Html::a('Followings', ['user/following'])?></li>
		</ul>


							</div>


							
							<div class="recent-activities-tab-content tab-content">
							<!--Liked-->
								<?php if(Yii::$app->controller->action->id == 'liked'){ ?>

								<div id="liked" class="tab-pane fade in active">
									<div class="center profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<?php if(Yii::$app->user->id == $user->userId) { ?>
											<?php if (count($products) == 0){ ?>
											<div class="modal-dialog modal-dialog-width">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="payment-decline-status-info-txt decline-center" ><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','You have not liked any products.'); ?></div>
													<div class="text-align-center col-lg-12 no-hor-padding"><a class="center-btn payment-promote-btn login-btn" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Go to home'); ?></a></div>
												</div>
											</div>
											</div>
											<?php }else{ ?>

												<div id="products" style="margin-top:0px !important;">
												<?php echo $this->render('loadliked',['products'=>$products]); ?>
												</div>


									<!-- <div class="load-more-txt">More Users</div> -->

								<?php if(count($products) >= 15) {
								if(Yii::$app->controller->action->id == 'liked') { ?>
									<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
									<a class="loadmorenow load">

									<div class="load-more-icon"></div>
								<?php echo CHtml::ajaxButton(Yii::t('app','Load More'), array('liked','id' =>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),
								array(
								'data'=> 'js:{"limit": limit, "offset": offset}',
								'beforeSend'=>'js:function(data){
								$(".classified-loader").show();
								$(".load").hide();
								}',
								'success' => 'js:function(response){
								$(".classified-loader").hide();
								$(".load").show();
						         var output = response.trim();
										if (output) {
											offset = offset + limit;
								 $("#products").append(output);
										} else {
								        $(".load").html(Yii.t("app","No More Products"));
								        //$(".load").hide();

										}
								 }',
								)
								); ?>
								</a>
						</div>
								<?php } } } ?>

										<?php }else{ ?>
											<?php if(count($products) != 0){ ?>
											<div id="products" style="margin-top:0px !important;">
												<?php echo $this->renderPartial('loadliked',compact('products')); ?>
												</div>
												<?php if(count($products) >= 15) {
												if(Yii::$app->controller->action->id == 'liked') { ?>
													<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
													<a class="loadmorenow load">
													<div class="load-more-icon"></div>
												<?php echo CHtml::ajaxButton(Yii::t('app','Load More'), array('profiles','id' =>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),
												array(
												'data'=> 'js:{"limit": limit, "offset": offset}',
												'beforeSend'=>'js:function(data){
												$(".classified-loader").show();
												$(".load").hide();
												}',
												'success' => 'js:function(response){
												$(".classified-loader").hide();
												$(".load").show();
										         var output = response.trim();
														if (output) {
															offset = offset + limit;
												 $("#products").append(output);
														} else {
												        $(".load").html(Yii.t("app","No More Products"));
												        //$(".load").hide();

														}
												 }',
												)
												); ?>
												</a>
										</div>
												<?php } }?>
											<?php }else{ ?>
												<div class="modal-dialog modal-dialog-width">
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="payment-decline-status-info-txt decline-center" ><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','User is not liked any products.'); ?></div>
														</div>
													</div>
												</div>

										<?php } } ?>

									</div>
								</div>
								<?php } ?>

								<!--Followers-->
								<?php if( Yii::$app->controller->action->id == 'follower') { ?>
								<div id="followersss" class="tab-pane fade in active">
									<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

										<?php  if(!empty($followerlist)) { ?>
										<div id="follower" style="margin-top:0px !important;">
											<?php $this->renderPartial('follower',compact('followerlist','followerIds')); ?>
										</div>
										<div class="no-more"></div>
									<?php if(count($followerlist) >= 15) {
										if(Yii::$app->controller->action->id == 'follower') { ?>
									<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
										<?php echo CHtml::ajaxLink('<div class="load"><div class="load-more-icon"></div>
							<div class="load-more-txt">'.Yii::t('app','Load more').'</div></div>', array('follower','id' =>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),
										array(
										'data'=> 'js:{"limit": limit, "offset": offset}',
										'beforeSend'=>'js:function(data){
										$(".classified-loader").show();
										$(".load").hide();
										}',
										'success' => 'js:function(response){
										$(".classified-loader").hide();
										$(".load").show();
								         var output = response.trim();
												if (output) {
													offset = offset + limit;
										 $("#follower").append(output);
												} else {
										        $(".load-more-cnt").html(Yii.t("app","No More Followers"));
										        //$(".load-more-cnt").hide();

												}
										 }',
										)
										); ?>
									</div>
										<?php } ?>
										<?php  } ?>
										<?php }else {?>
											<div class="modal-dialog modal-dialog-width">
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="payment-decline-status-info-txt decline-center" ><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','Yet no follower are here'); echo "."; ?></div>
														</div>
													</div>
												</div>
										<?php } ?>
									</div>
								</div>
								<?php } ?>

								<!--Followings-->
								<?php if( Yii::$app->controller->action->id == 'following') { ?>
								<div id="followings" class="tab-pane fade in active">
									<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

											<?php  if(!empty($followerlist)) { ?>
											<div id="following" style="margin-top:0px !important;">
												<?php  $this->renderPartial('following',compact('followerlist','followerIds')); ?>
											</div>
											<div class="no-more"></div>
										<?php if (count($followerlist) >= 15){
											if(Yii::$app->controller->action->id == 'following') { ?>
				<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
					<?php echo CHtml::ajaxLink('<div class="load"><div class="load-more-icon"></div>
							<div class="load-more-txt">'.Yii::t('app','Load more').'</div></div>', array('following','id' =>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),
											array(
											'data'=> 'js:{"limit": limit, "offset": offset}',
											'beforeSend'=>'js:function(data){
											$(".classified-loader").show();
											$(".load").hide();
											}',
											'success' => 'js:function(response){
											$(".classified-loader").hide();
											$(".load").show();
									         var output = response.trim();
													if (output) {
														offset = offset + limit;
											 $("#following").append(output);
													} else {
											        $(".load-more-cnt").html(Yii.t("app","No More Followings"));
											        //$(".load-more-cnt").hide();

													}
											 }',
											)
											); ?>
				</div>
									<?php }

									} ?>



									<!--	<div class="btn-block load">
										<?php if(count($followerlist) >= 15) {
											if(Yii::$app->controller->action->id == 'following') { ?>
											<?php echo CHtml::ajaxButton(Yii::t('app','Load more'), array('following','id' =>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),
											array(
											'data'=> 'js:{"limit": limit, "offset": offset}',
											'beforeSend'=>'js:function(data){
											$(".classified-loader").show();
											$(".load").hide();
											}',
											'success' => 'js:function(response){
											$(".classified-loader").hide();
											$(".load").show();
									         var output = response.trim();
													if (output) {
														offset = offset + limit;
											 $("#following").append(output);
													} else {
											        $(".load").html(Yii.t("app","No More Followers"));
											        $(".load").hide();

													}
											 }',
											)
											); ?>
											<?php } ?>
											<?php  } ?>
										</div>-->
											<?php }else {?>
											<div class="modal-dialog modal-dialog-width">
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="payment-decline-status-info-txt decline-center" ><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','Yet no following are here.'); ?></div>
														</div>
													</div>
												</div>

											<?php } ?>

									</div>
								</div>
								<?php } ?>
						
							</div>
						</div>
			<?php } ?>


						</div>
					</div>
				</div>
			</div>



</div>
<div class="paypal-form-container"></div>

	<!--Add popup modal-->

		<div class="modal fade" id="post-your-list" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog post-list-modal-width">
				<div class="post-list-modal-content login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="post-list-header login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="modal-header-text"><p class="login-header-text"><?php echo Yii::t('app','Promote the listing'); ?></p></div>
								<button data-dismiss="modal" class="close login-close" type="button" id="white">×</button> 
						</div>

						<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
						<?php $sitesetting = yii::$app->Myclass->getSitesettings(); ?>
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
							<div class="urgent-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
								<ul><div class="urgent-tab-heading"><?php echo Yii::t('app','Urgent tag Features:'); ?></div>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','More opportunities for your buyers to see your product'); ?></span></li>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Higher frequency of listing placements'); ?></span></li>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Highlight your listing to stand out'); ?></span></li>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Use for Make fast sale for seller and Make buyer to do purchase as Urgent'); ?></span></li>
									<li class="stuff-post">
										<form name="promotionbraintreeform" method="post" action="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/item/products/promotionpaymentprocess'); ?>" onsubmit="return promotionUpdate('urgent')">
											<input type="hidden" name="BPromotionType" value='urgent' />
											<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">

											<button class="btn post-btn" href="javascript:void(0);" onclick="" type="submit"><?php echo Yii::t('app','Highlight now'); ?></button>
											<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
											<div class="urgent-promote-error delete-btn"></div>
										</form>
									</li>
								</ul>
							</div>
							<div class="urgent-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
								<div class="urgent-right-circle-icon"><span class="item-urgent-1">Urgent</span></div>
							</div>
						  </div>
						  <div id="promote" class="tab-pane fade">
							<p><?php echo Yii::t('app','Promote your listings to reach more users than normal listings. The promoted listings will be shown at various places to attract the buyers easily.'); ?></p>
							<div class="tab-radio-button-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							
							</div>
							<div class="promote-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
								<ul><div class="promote-tab-heading"><?php echo Yii::t('app','promote tag Features:'); ?></div>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','View-able with highlight for all users on desktop and mobile'); ?></span></li>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Displayed at the top of the page in search results'); ?></span></li>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Higher visibility in search results means more buyers'); ?></span></li>
									<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Listing stands out from the regular posts'); ?></span></li>
									<li class="stuff-post">
										<form name="promotionbraintreeform" method="post" action="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/item/products/promotionpaymentprocess'); ?>" onsubmit="return promotionUpdate('adds')">
											<input type="hidden" name="BPromotionType" value='adds' />
											<input type="hidden" name="BPromotionProductid" id="ADPromotionProductid" value="">
											<input type="hidden" name="BPromotionid" id="ADPromotionid" value="">

										<button class="post-btn btn" href="javascript:void(0);" onclick=""><?php echo Yii::t('app','Promote now'); ?></button>
										<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
										<div class="adds-promote-error delete-btn"></div>
										</form>
									</li>
								</ul>
							</div>
							<div class="promote-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
								<div class="promote-right-circle-icon"><span class="item-ad-1">Ad</span></div>
							</div>
						  </div>
						</div>
				</div>
			</div>
			<input type="hidden" class="promotion-product-id" value="">
			<input type="hidden" name="Products[promotion][type]" value="" id="promotion-type">
			<input type="hidden" name="Products[promotion][addtype]" value="" id="promotion-addtype">
		</div>

<style type="text/css">
.footer {
    margin-top: 0px !important;
}

.textleft
{
	text-align: left;
}
</style>
<script type="text/javascript">

	
function load_more(id)
{

	$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/liked/',
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
				        $(".load").html(yii.t("app","No More Products"));
				   	}
        },
    });
    
}
</script>