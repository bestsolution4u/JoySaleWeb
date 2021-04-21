<?php
use yii\helpers\Url;
use common\models\Photos;
use yii\helpers\Json;
use yii\helpers\Html;
Html::csrfMetaTags();
?>

<div class="container-fluid no-hor-padding chatnotify-container" style="display: none;"></div>
<div class="container">
<?php $siteSettings =  yii::$app->Myclass->getSitesettings();
$sitePaymentModes =  yii::$app->Myclass->getSitePaymentModes();
$me=yii::$app->Myclass->getUserDetailss(Yii::$app->user->id);


?>
<script
src="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/node_modules/socket.io-client/dist/socket.io.js"></script>
<script
src="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/server/nodeClient.js"></script>
		<div class="row">
				<div class="add-product-heading col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:25px">
					<h2 class="top-heading-text"><?php echo Yii::t('app','Messages'); ?></h2>
				</div>
		</div>


		<?php if(!empty($chattingUsers)){   ?>
            
		<div class="row">
			<div class="message-vertical-tab-section col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<ul class="message-vertical-tab-container nav nav-tabs col-xs-12 col-sm-3 col-md-3 col-lg-3 no-hor-padding">
				<?php foreach ($chattingUsers as $chattingUser){
					// print_r($chattingUser);
					// exit;
						$active = "";
						$userDetails = $chatUser[$chattingUser];
					//	$userDetails = $chattingUser;
						if(!empty($userDetails->userImage)) {
							$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.
									$userDetails->userImage);
						} else {
							$userImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.
									yii::$app->Myclass->getDefaultUser());
						}
						if($currentChatUser != "")
						{
						if ($currentChatUser == $userDetails->userId){
							$active = "active";
							$disp ='display:block';
						}
					}
						$userName = $userDetails->name;
						$latestMessage = $lastMessages[$chattingUser]['message'];
						$lastTime = yii::$app->Myclass->getElapsedTime($lastMessages[$chattingUser]['time'])." ".Yii::t('app',"ago");
						if ($lastMessages[$chattingUser]['messaggeMarker'] == '<div class="message-unread-count"></div>'){
							$unread = 1;
						}else{
							$unread = 0;
						} ?>
						<li class="<?php echo $active; ?>  chatlist-<?php echo $userDetails->username; ?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<a class="chat-link userNameLink" href="<?php echo yii::$app->urlManagerfrontEnd->baseUrl."/message/".yii::$app->Myclass->safe_b64encode(
									$userDetails->userId.'-0'); ?>" data-userid="<?php echo yii::$app->Myclass->safe_b64encode($userDetails->userId.'-0'); ?>"
									data-userread = "<?php echo $unread; ?>">
								<div class="message-icon col-xs-4 col-sm-12 col-md-4 col-lg-4 no-hor-padding">
									<div class="message-prof-pic col-sm-offset-3 col-md-offset-0 col-lg-offset-0"
										style="background-image:url('<?php echo $userImage; ?>');">
										<?php echo $lastMessages[$chattingUser]['messaggeMarker']; ?>
									</div>
								</div>
								<div class="message-details col-xs-8 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
									<div class="message-prof-name"><?php echo $userName; ?></div>
									<div class="short-message"><?php echo $lastMessages[$chattingUser]['messaggeReplyMarker'].urldecode($latestMessage); ?></div>
									<div class="message-time"><?php echo $lastTime; ?></div>
								</div>
							</a>
						</li>
					<?php } ?>
					</ul>

				<div class="chat-message-container tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9 no-hor-padding">

					<!--classified version 3 updates starts - AK -->
					<div class="user_profile col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="user_img_msg col-xs-3 col-sm-3 col-md-3 col-lg-3" style="background-image: url(<?php echo $currentChatUserImage; ?>);"></div>
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							<div class="user_name"><?php $userDetails = $chatUser[$currentChatUser]; echo $userDetails['name']; ?></div>
						</div>
						
									
					<?php
					$block_userid = $lastMessages[$currentChatUser]['blockedUser'];
					if($block_userid !=0 && $block_userid != "") {
						if($currentUserDetails->userId != $block_userid && $currentChatUser == $block_userid) { ?>
							<div class="msg_menu_pos dropdown">
								<div class="dropdown-toggle msg_menu" data-toggle="dropdown"></div>
									<ul class="dropdown-menu msg_dropdown">
										<li class="user_pactive">
											<a href="javascript:void(0);" onclick="userPermission('ZFc1aWJHOWphdz09');" id="user_pub" class="user_Permission">
											<?=Yii::t('app','Unblock User');?>
											</a>
										</li>
										<?php if(!empty($safetytipsModel) && $safetytipsModel->id == 3) { ?>
											<li class="">
												<?=Html::a(Yii::t('app','Safety Tips'),array('message/help/','details' => $safetytipsModel->slug), ['target' => '_blank'
            ]); ?>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						<?php	} else {
							if(!empty($safetytipsModel) && $safetytipsModel->id == 3) { ?>
								<div class="msg_menu_pos dropdown">
									<div class="dropdown-toggle msg_menu" data-toggle="dropdown"></div>
										<ul class="dropdown-menu msg_dropdown">
											<li class="">
												<?=Html::a(Yii::t('app','Safety Tips'),array('message/help/','details' => $safetytipsModel->slug), ['target' => '_blank'
            ]); ?>
											</li>
										</ul>
									</div>
								</div>
							<?php }
						}
					} else { ?>
					<div class="msg_menu_pos dropdown">
						<div class="dropdown-toggle msg_menu" data-toggle="dropdown"></div>
							<ul class="dropdown-menu msg_dropdown">
								<li class="user_pactive">
									<a href="javascript:void(0);" onClick="userPermission('WW14dlkycz0=')" id="user_pb" class="user_Permission">
										<?=Yii::t('app','Block User')?>
									</a>
								</li>
								<?php if(!empty($safetytipsModel) && $safetytipsModel->id == 3) { ?>
									<li class="">
									<?=Html::a(Yii::t('app','Safety Tips'),array('message/help/','details' => $safetytipsModel->slug), ['target' => '_blank'
            ]); ?>

							
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<?php }   ?>
							
									
							
					<!--classified version 3 updates ends - AK -->
				<div id="home" class="message-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="message-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="live-msg-container">
						<ol
							class="live-messages-ol-<?php echo $currentUserDetails->username; ?>-<?php echo $chatUser[$currentChatUser]->username; ?>">
							<?php
							$receiverId = $currentChatUser;
							if(!empty($messageChatId)) {
								$chatId = $messageChatId;
							}

							
							foreach ($messageModel as $message){

								$sender = $message->senderId;
								$gridAlign = "user-conv";
								$position='right';
								$messageContainerAlign = "message-conversation-right-cnt";
								$gridArrowAlign = "arrow-right";
								$userImageAlign = "id='user'";
								$chatGirdImage = $currentUserImage;
								if ($sender != $currentUserDetails->userId){
									$position='left';
									$gridAlign = "";
									$messageContainerAlign = "message-conversation-left-cnt";
									$gridArrowAlign = "arrow-left";
									$userImageAlign = "";
									$chatGirdImage = $currentChatUserImage;
									$receiverId = $sender;
								}
								$chatDate = $message->createdDate;
								$chatMessage = $message->message;
								$chatMessageContent=$message->messageContent;
								$chatId = $message->chatId;
								if($message->sourceId != 0)
								{
									$item_detail = yii::$app->Myclass->getProductDetails($message->sourceId);
									$item_detail_count = count($item_detail);
								}
								else if($message->sourceId == 0)
								{
									$item_detail_count = "1";
								}
								?>
						<?php if($item_detail_count > 0) { ?>
							<!-- make on offer -->
								<?php  
								if ($message->messageType == 'offer'){
								$chatMessage = Json::decode($chatMessage, true);
							}
							//	if ($message->messageType == 'offer' && $chatMessage['type']=='sendreceive'){ ?>

								<?php if ($message->messageType == 'offer' && ($chatMessage['type']=='accept' || $chatMessage['type']=='decline')){
									$loginUserId = Yii::$app->user->id; 
		if ($chatMessage['type']=='accept'){
			$acceptDecline1="accepted";
			$acceptDecline2="";
			$acceptDecline3="accept_txt";
			if ($message->senderId==$loginUserId){
			$offerStatus=Yii::t('app','Your offer accepted');

		}
		else{
			$offerStatus=Yii::t('app','You have accepted this offer');

		}

		}
		else
		{
			$acceptDecline1="decline";
			$acceptDecline2="decline_offer";
			$acceptDecline3="decline_txt";
			if ($message->senderId==$loginUserId){
			$offerStatus=Yii::t('app','Your offer declined');
		}
		else{
			$offerStatus=Yii::t('app','You have declined this offer');
		}
			
		}
	  ?>
	<!-- offer accept decline section -->
	<?php 
			$chatSourceItemOffer = yii::$app->Myclass->getProductDetails($message->sourceId);
										if(!empty($chatSourceItemOffer)){
											if(isset($chatSourceItemOffer->photos[0])){
												$productImage = Yii::$app->urlManager->createAbsoluteUrl(
														'/media/item/'.$chatSourceItemOffer->productId.
														'/'.$chatSourceItemOffer->photos[0]->name);
											}else{
												$productImage = Yii::$app->urlManager->createAbsoluteUrl('/media/item/default.jpg');
											}
										}
	 ?>
	<li>
<div class="offer-<?php echo $acceptDecline1;?>-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	<div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding">
		<div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0">
			<div class="conversation-product-pic offer_product" style="background-image: url('<?php echo $productImage; ?>');display: block;"></div>
		</div>
				<div class="conversation-bargain-container offer_accept_decline_container margin_left10">
								<!-- this  add class decline_offer-->			<div class="offer_accepted <?php echo $acceptDecline2;?>"></div>
												<!-- this decline_txt--><span class="margin_left5 <?php echo $acceptDecline3;?>"><?php echo $offerStatus;?></span>
														<a href=""><div class="offer_txt extra_text_hide"><?=Yii::t('app','Product Name')?></div></a>
														<div class="conversation-rate-container s2">
															<div class="sent_rate">
																<h4><span class="offer_price"><?php if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
											echo yii::$app->Myclass->getArabicFormattingCurrency($chatMessage['currency'],$chatMessage['price']); 
										else
										echo yii::$app->Myclass->getFormattingCurrency($chatMessage['currency'],$chatMessage['price']);?></span></h4>															</div>
																<span class="offer_date"><?php
					$date=date_create(date('Y-m-d', $chatDate));
					echo Yii::t('app',date_format($date, "dS M Y"));
				// 	$dateToLocale=Yii::$app->dateFormatter->formatDateTime(CDateTimeParser::parse($date, 'yyyy-MM-dd'),'medium',null);
				// 	$dateBackToMySQL=date('Y-m-d', CDateTimeParser::parse($dateToLocale, Yii::$app->locale->dateFormat));
				// echo $dateToLocale; ?></span>
														</div>
														<?php

							$proDetl = yii::$app->Myclass->getProductDetails($message->sourceId);
																/*echo "chat type".$chatMessage['type'];
																echo "buynowstatus".$chatMessage['buynowstatus'];
																echo "chat senderId".$message->senderId;
																echo "loginUserId".$loginUserId;
																echo "proDetlinstantBuy".$proDetl->instantBuy;
																echo "buynowPaymentMode".$sitePaymentModes['buynowPaymentMode']; */

						if ($chatMessage['type']=='accept' && $chatMessage['buynowstatus']== 0 && $message->senderId==$loginUserId && $proDetl->instantBuy == '1' && $sitePaymentModes['buynowPaymentMode'] == 1){  ?>
					<div class="buy_now_btn" id="accept_btn_buynow">
						<?php 
							$mkeOfferPrice=$chatMessage['price'];
															$cartDataURL = yii::$app->Myclass->cart_encrypt($message->sourceId."-0-".$mkeOfferPrice."-".$message->messageId, 'joy*ccart'); ?>
  <a class="btn btn_buynow" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/checkout/revieworder2/'.$cartDataURL); ?>"><?php echo Yii::t('app','Buy Now');?></a>
  <input type="hidden" value="<?php echo $cartDataURL;?>">
</div>
<?php } ?>
													
				</div>
	</div>
</div>
</li>

	<!-- end offer accept,decline -->
	<?php } else {   ?>

<!-- make on offer -->
						
			<li>
<div class="<?php echo $gridAlign; ?> message-conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div <?php echo $userImageAlign; ?> class="conversation-prof-pic no-hor-padding">
<div class="message-prof-pic" style="background-image: url('<?php echo $chatGirdImage; ?>')"></div>
</div>
<div class="<?php echo $messageContainerAlign; ?> col-xs-9 col-sm-9 col-md-9 col-lg-7 no-hor-padding">
<div class="<?php echo $gridArrowAlign;?> <?php if($message->messageType == 'offer') { echo "offer_sent_arrow";}?>"></div>
<div class="message-conversation <?php if($message->messageType == 'offer') { echo "offer_sent";}?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<?php
									if ($message->messageType == 'offer' && $chatMessage['type']=='sendreceive'){

										

										$chatSourceItem = yii::$app->Myclass->getProductDetails($message->sourceId);
										 if(!empty($chatSourceItem)){
											if(isset($chatSourceItem->photos[0])){
												$productImage = Yii::$app->urlManager->createAbsoluteUrl(
														'/media/item/'.$chatSourceItem->productId.
														'/'.$chatSourceItem->photos[0]->name);
											}else{
												$productImage = Yii::$app->urlManager->createAbsoluteUrl('/media/item/default.jpg');
											}
									$productTitle = $chatSourceItem->name;
									//$chatMessage = Json::decode($chatMessage, true);
									$offerCurrency = explode("-", $chatMessage['currency']);
									$productLink = Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($chatSourceItem->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($chatSourceItem->name);

											?>
			<div class="offer_view col-xs-12 col-sm-12 col-md-12 col-lg-12"> <!-- hide-->
				<div class="conversation-topic col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="offer_accepted white_offer"></div> <span class="margin_left5"><?php if($position=='left') { echo Yii::t('app','Received offer request on'); } else { echo Yii::t('app','Sent offer request on'); } ?></span> <a class="message-conversation-item-name txt-white-color bold" target="_blank" href="<?php echo $productLink; ?>"><?php echo $productTitle; ?></a>
				</div>
				<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0">
						 <div class="conversation-product-pic" style="background-image: url('<?php echo $productImage; ?>');display: block;"></div> 
					</div>
					<div class="conversation-bargain-container margin_left10">
						<div class="conversation-rate-container">
							<div class="sent_rate">

										<h2><?php 	if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
											echo yii::$app->Myclass->getArabicFormattingCurrency($chatMessage['currency'],$chatMessage['price']); 
										else
										echo yii::$app->Myclass->getFormattingCurrency($chatMessage['currency'],$chatMessage['price']); 
									
									
?></h2>														</div>
							</div>
							<div class="conversation-text txt-white-color"><?php echo $chatMessage['message'];?></div>
						</div>
					</div>
				</div> <!-- hide-->
				<?php if($position=='left' && $chatMessage['type']=='sendreceive' && $chatMessage['offerstatus']== 0 ){
						$btn_hide_show=rand(0,999);
					?>
				<div class="accept_decline btn-process-<?php echo $btn_hide_show;?> col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-6 text-center btn_border acc-dec-btn">
					<?php $sendValue=$message->messageId.'@#@accept@#@'.$btn_hide_show;$enc1=base64_encode($sendValue);$enc2=base64_encode($enc1);

					?>
						<button class="btn_accept accept_txt" id="offerBtnAccept" onClick="offerStatus('<?php echo $enc2;?>')"><?php echo Yii::t('app','Accept')?></button>
					</div>
					<div class="col-xs-6 text-center acc-dec-btn">
					<?php $sendValue=$message->messageId.'@#@decline@#@'.$btn_hide_show;$enc1=base64_encode($sendValue);$enc2=base64_encode($enc1);?>
						<button class="btn_accept" id="offerBtnDecline" onClick="offerStatus('<?php echo $enc2;?>')"><?php echo Yii::t('app','Decline');?></button>
					</div>
					 <span style="color:red;" id="makeoffer_error_msg"></span>
					
				</div><!-- hide-->
				<?php } ?>
						<?php }?>
							
								<?php 	} else{
										if($message->messageType == 'normal' && $message->sourceId != 0){
											$chatSourceItem = yii::$app->Myclass->getProductDetails($message->sourceId);
											if(!empty($chatSourceItem)){
												if(isset($chatSourceItem->photos[0])){
													$productImage = Yii::$app->urlManager->createAbsoluteUrl(
														'/media/item/'.$chatSourceItem->productId.
															'/'.$chatSourceItem->photos[0]->name);
													$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$chatSourceItem->productId .
													'/' . $chatSourceItem->photos[0]->name);

														if(file_exists($mediapath)) {
														
															$productImage = $productImage;
														} else {
														
															$productImage =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
														}
											}
											else
											{
												$productImage = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
											}
												$productTitle = $chatSourceItem->name;
												$productLink = Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($chatSourceItem->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($chatSourceItem->name);
												?>
											<div class="conversation-topic col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php echo Yii::t('app','About'); ?> <a href="<?php echo $productLink; ?>" target="_blank" class="message-conversation-item-name"><?php echo $productTitle; ?></a>
											</div>
											<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0">
													<div class="conversation-product-pic" style="background-image: url('<?php  echo $productImage; ?>');display: block;"></div> 
												</div>
												<div class="conversation-bargain-container">
													<div class="conversation-text"><?php echo urldecode($chatMessage); ?></div>
												</div>
											</div>
											<?php }
											}else{ ?>
												<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="conversation-bargain-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<!-- working chat section -->
														<?php if($chatMessageContent==1){?>	
															<div class="conversation-text"><?php echo urldecode($chatMessage); ?>
																
															</div>
														<?php } 
														if ($chatMessageContent==2) {?>
															<div class="conversation-text">
															<a href="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/frontend/web/images/message/<?php echo $chatMessage;?>" target="_blank">
															<img src="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/frontend/web/images/message/<?php echo $chatMessage;?>" alt="Loding..." >
															<!-- style="width:auto;height:200px;max-width:100%;" -->
															<!--style='width:200px;height:200px;'-->
															</a></div>
														<?php }?>
														<?php if ($chatMessageContent==3) {?>
															<div class="conversation-text">
		<?php $latLongArr=explode("@#@",$chatMessage); 
															
					$map1='https://maps.googleapis.com/maps/api/staticmap?center=';
	$map2='&zoom=16&size=400x200&sensor=false&maptype=roadmap&markers=color:red%7Clabel:S%7C';
	$map3='&key=';
	 $map4=$siteSettings->staticMapApiKey;
	 $com=',';
	$mapSrc=$map1.$latLongArr[0].$com.$latLongArr[1].$map2.$latLongArr[0].$com.$latLongArr[1];
	$mapSrc=$map1.$latLongArr[0].$com.$latLongArr[1].$map2.$latLongArr[0].$com.$latLongArr[1].$map3.$map4;
																	

															?>
															<a class="viewShared" href="https://www.google.com/maps?daddr=<?php echo $latLongArr[0];?>,<?php echo $latLongArr[1];?>" target="_blank">
															<img src="<?php echo $mapSrc;//Yii::$app->urlManager->createAbsoluteUrl('images/location.png');?>" style="width:400px;height:200px;">
															<?php //echo Yii::t('app','View Shared Location.');?>
															</a></div>
														<?php }?>
														<!-- working chat section -->
													</div>
												</div>
										<?php } ?>
										
									<?php  } ?>
			
<!-- footer -->
										</div>
			<div class="conversation-date col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<?php
					$date=date_create(date('Y-m-d', $chatDate));
					echo date_format($date, "dS M Y");
				// 	$dateToLocale=Yii::$app->dateFormatter->formatDateTime(CDateTimeParser::parse($date, 'yyyy-MM-dd'),'medium',null);
				// 	$dateBackToMySQL=date('Y-m-d', CDateTimeParser::parse($dateToLocale, Yii::$app->locale->dateFormat));
				// echo $dateToLocale; ?>
			</div>
			</div>
		</div>
	</li>
	<?php } ?>
										<!-- end footer -->
	

<!-- make on offer -->
							
							<?php } else { ?>
<!-- If product is removed show this content -->


								<li>
								<div class="<?php echo $gridAlign; ?> message-conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div <?php echo $userImageAlign; ?> class="conversation-prof-pic no-hor-padding">
										<div class="message-prof-pic" style="background-image: url('<?php echo $chatGirdImage; ?>')"></div>
									</div>
									<div class="<?php echo $messageContainerAlign; ?> col-xs-9 col-sm-9 col-md-9 col-lg-7 no-hor-padding">
										<div class="<?php echo $gridArrowAlign; ?>"></div>
										<div class="message-conversation col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"> <img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/trash-'.$messageContainerAlign.'.png');?>" height="50" width="50"> &nbsp;&nbsp;<b><?php echo Yii::t('app','Product is removed'); ?>.</b>
									<?php
									if ($message->messageType == 'offer'){
									
										$chatSourceItem = yii::$app->Myclass->getProductDetails($message->sourceId);
										if(!empty($chatSourceItem)){
											if(isset($chatSourceItem->photos[0])){
												$productImage = Yii::$app->urlManager->createAbsoluteUrl(
														'/media/item/'.$chatSourceItem->productId.
														'/'.$chatSourceItem->photos[0]->name);
												$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$chatSourceItem->productId .
												'/' . $chatSourceItem->photos[0]->name);

													if(file_exists($mediapath)) {
													
														$productImage = $productImage;
													} else {
													
														$productImage =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
													}
										}
										else
										{
											$productImage = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
										}
											$productTitle = $chatSourceItem->name;
											$chatMessage = Json::decode($chatMessage, true);
											$offerCurrency = explode("-", $chatMessage['currency']);
											$productLink = Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($chatSourceItem->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($chatSourceItem->name);
											?>
											<div class="conversation-topic col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php echo Yii::t('app','Product is removed'); ?> <a href="<?php echo $productLink; ?>" target="_blank" class="message-conversation-item-name"><?php echo $productTitle; ?></a>
											</div>
											<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0">
													 <div class="conversation-product-pic" style="background-image: url('<?php echo $productImage; ?>');display: block;"></div> 
												</div>
												<div class="conversation-bargain-container">
													<div class="conversation-rate-container s1">
														<div class="conversation-rate">
															<?php if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
											echo yii::$app->Myclass->getArabicFormattingCurrency($chatMessage['currency'],$chatMessage['price']); 
										else
										echo yii::$app->Myclass->getFormattingCurrency($chatMessage['currency'],$chatMessage['price']);


															?>
														</div>
													</div>
													<div class="conversation-text"><?php echo $chatMessage['message']?></div>
												</div>
											</div>
											<?php }
									}else{
										if($message->messageType == 'normal' && $message->sourceId != 0){
										
											$chatSourceItem = yii::$app->Myclass->getProductDetails($message->sourceId);
											if(!empty($chatSourceItem)){
												if(isset($chatSourceItem->photos[0])){
													$productImage = Yii::$app->urlManager->createAbsoluteUrl(
															'/media/item/'.$chatSourceItem->productId.
															'/'.$chatSourceItem->photos[0]->name);
												}else{
													$productImage = Yii::$app->urlManager->createAbsoluteUrl('/media/item/default.jpg');
												}
												$productTitle = $chatSourceItem->name;
												$productLink = Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($chatSourceItem->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($chatSourceItem->name);
											?>
											<div class="conversation-topic col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php echo Yii::t('app','About'); ?> <a href="<?php echo $productLink; ?>" target="_blank" class="message-conversation-item-name"><?php echo $productTitle; ?></a>
											</div>
											<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0">
													<div class="conversation-product-pic" style="background-image: url('<?php echo $productImage; ?>');display: block;"></div>
												</div>
												<div class="conversation-bargain-container">
													<div class="conversation-text"><?php echo urldecode($chatMessage); ?></div>
												</div>
											</div>
											<?php }
											}
											/*else{ ?>
												<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="conversation-bargain-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													
													</div>
												</div>
										<?php } */?>
									<?php } ?>
										</div>
										<div class="conversation-date col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<?php
					$date=date_create(date('Y-m-d', $chatDate));
					echo date_format($date, "dS M Y");
					//echo $date;
					// $dateToLocale=Yii::$app->dateFormatter->formatDateTime(CDateTimeParser::parse($date, 'yyyy-MM-dd'),'medium',null);
					// $dateBackToMySQL=date('Y-m-d', CDateTimeParser::parse($dateToLocale, Yii::$app->locale->dateFormat));


										//echo Yii::$app->dateFormatter->formatDateTime(date('M jS Y', $chatDate)).'<br/>';
										?>
											<?php //echo $dateToLocale; ?>
										</div>
									</div>
								</div>
							</li>
			
	  		<?php }?>
							<?php }?>
						</ol>
						</div>
						<?php $disable = '';

								//		print_r($chatUser[$receiverId]->userstatus);exit;

							if ($chatUser[$receiverId]->userstatus == 0)
								$disable = "disabled = 'disabled'";
						
						?>

						<?php 
						$block_userid = $lastMessages[$currentChatUser]['blockedUser'];
						$msg_view = "";
						if($block_userid > 0 && (($currentUserDetails->userId != $block_userid && $currentChatUser == $block_userid) || ($currentUserDetails->userId == $block_userid && $currentChatUser != $block_userid))) { 
								$block_view = "display: block;";
								if($block_userid != $currentUserDetails->userId)
						 			$block_msg = "You have blocked this user";
						 		else
						 			$block_msg = "You are blocked";
						 } elseif($block_userid == 0) { 
						 		$block_view = "display: none;";
						 } else {
						 		$block_view = "display: none;";
								$msg_view = "display: none;";
						 } ?>

							<div class="message-type-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="<?php echo $msg_view; ?>">
								<div class="live-messages-typing typing-status col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<?php echo $chatUser[$receiverId]->username." ".Yii::t("app",'Typing') ?>...
								</div>
								<div class="chat-message-type col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

									<!--classified version 3 updates starts - AK -->
									<div class="block_div message-block-container" style="<?php echo $block_view; ?>">
									    <span id="block_msg" style="font-size: 15px;"><?php if (isset($block_msg)) {
									    	echo $block_msg;
									    } ?></span>
									</div>


									<div class="default_msg_txt" id="default_msgs">
										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","Hi, I'd like to buy it"); ?></a>
										</div>
										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","I'm Interested"); ?></a>
										</div>
										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","Is it Still available"); ?></a>
										</div>
										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","Where we can meet up?"); ?></a>
										</div>

										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","Thank you"); ?></a>
										</div>

										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","Any queries"); ?></a>
										</div>

										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","Welcome"); ?></a>
										</div>

										<div class="default_txt primary-bg-color border-radius-5">
											<a href="javascript:void(0);" onClick="sendMessage1(this)"><?=Yii::t("app","Sorry for inconvenience"); ?></a>
										</div>


										
									</div>
									<!--classified version 3 updates ends - AK -->

									<form class="form-inline" id="messageForm" name="messageForm" enctype="multipart/form-data" onsubmit="sendMessage1()">
										<a href="javascript:void(0);">
										<div class="form-group col-xs-12 col-sm-10 col-md-10 col-lg-11 no-hor-padding">
										<textarea id="messageInput" name="messageInput" class="comment-text-area form-control message_area_padding" rows="5" maxlength="500" placeholder="<?php echo Yii::t('app','Message'); ?>" <?php echo $disable;?>></textarea>
										
										<!-- design changes onkeyup="limitMessage(500,event);" -->
										 <a href="javascript:void(0);" >
										<label for="file-input"><div class="attach_file attach_pos" id="chtShareImage"></div>
										<div style="color:red;margin-top: 5px;font-weight: 100" id="imageError"></div></label><input type="file" accept="image/gif, image/jpeg, image/png" name="file" id="file-input" style="display: none;">
												</a>
												
												<!-- <a onClick="sharelocation()" id="chatShareLocation">
												<div class="share_loction share_pos" id="chtShareLocation"></div>
											</a> -->
											<a data-toggle="modal" data-target="#shareloc" id="chatShareLocation">
												<div class="share_loction share_pos" id="chtShareLocation"></div>
											</a>

												<!-- end design changes -->
										</div>
										<?php 
										if(Yii::$app->session['language'] == 'ar'){
											$lang = Yii::$app->session['language'];
										}else{
											$lang = Yii::$app->session['language'];
										}
										?>
										<input type="hidden" id="messageLang" value="<?php echo $lang;?>">
										<input id="sourcce" name="sourcce" type="hidden" value="<?php if(!empty($chatId))echo $chatId; ?>" />
										<input id="sendingsource" name="sendingsource" type="hidden" value="<?php echo $currentUserDetails->userId; ?>" />
										<input id="myid" name="myid" type="hidden" value="<?php echo $me->username; ?>" />
										<input id="appendinggsource" name="appendinggsource" type="hidden" value="<?php echo $currentUserDetails->username; ?>" />
										<input id="receiveingsource" name="receiveingsource" type="hidden" value="<?php if(!empty($chatUser[$receiverId]->username))echo str_replace(' ', '', $chatUser[$receiverId]->username); ?>" />
										<input id="sourccetype" name="sourccetype" type="hidden" value="normal" />
										<input id="chatsourcce" name="chatsourcce" type="hidden" value="0" />
										<input id="sourceId" name="sourceId" type="hidden" value="" />
										<input id="shareMap" name="shareMap" type="hidden" value=""/>
										<input id="staticMapApiKey" name="staticMapApiKey" type="hidden" value="<?php echo $siteSettings->staticMapApiKey;?>"/>
										<!-- new for offer instant chat -->
										<input id="offerADId" name="offerADId" type="hidden" value=""/>
										<input id="offerADType" name="offerADType" type="hidden" value=""/>
										<input id="messageInputOffer" name="messageInputOffer" type="hidden" value=""/>
												
										
										<!-- new for offer instant chat -->
										<!-- new -->
											

										<!-- end -->
										<div class="message-send col-xs-12 col-sm-2 col-md-2 col-lg-1 no-hor-padding">
											<a href="javascript:void(0);" <?php echo $disable;?> id="sendform" onClick="sendMessage1()">
												<div class="send-btn primary-bg-color text-align-center" style="margin-bottom: 15px;"><span><?php echo Yii::t('app','Send'); ?></span><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/send-icon.png');?>" alt="send-icon"></div>
											</a>
										</div>

										
									</form>

									<div class="message-limit col-md-12 col-sm-12 no-hor-padding" style="color:red;"></div>
								</div>
							</div>
						
				  </div>

				</div>
			</div>
		</div>
		<?php }else{ ?>
	  		<div class="row">
	  			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding no-conversation-msg">
	  				<img alt="No Converstations Found" src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/no-conversation.jpg'); ?>">
	  				<div><?php echo Yii::t('app', 'No conversation yet')."."; ?></div>
	  			</div>
	  		</div>
	  	<?php } ?>
	
</div>
 <input id="currentUserID" type="hidden" value="<?php echo base64_encode($currentUserDetails->userId); ?>">
<input id="currentChatUserID" type="hidden" value="<?php if (isset($currentChatUser) && $currentChatUser != "") {
	echo base64_encode($currentChatUser);
}
?>">



<!-- <div class="modal fade" id="shareloc" role="dialog">
<div class="modal-dialog modal-dialog-width">
<div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div id="mapc"></div>
<input type="hidden" id="map_latitude" placeholder="latitude">
 <input type="hidden" id="map_longitude" placeholder="longitude">
<button id="btn" style="display: none;">load map</button>
<span class="button" id="close-modal" data-dismiss="modal" aria-label="Close"></span>
</div>

</div>
</div> -->



<div class="modal fade in sharelocation" id="shareloc" role="dialog" style="display: none;">
	<div class="modal-dialog modal-dialog-width">
		<div class="share-location-modal col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="signup-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<h2 class="signup-header-text">Share location</h2>
				<button data-dismiss="modal" id="close-modal" class="close login-close" type="button">×</button>
			</div>
			<div class="sigup-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
			<div id="mapc"></div>
		
	<div class="login-div-line col-xs-12 col-sm-12 col-md-12 col-lg-12">
	
	</div>
	<input type="hidden" id="map_latitude" placeholder="latitude">
 <input type="hidden" id="map_longitude" placeholder="longitude">
	
	<div style="padding: 10px 10px 10px 10px;" id="map_button" class="signup-box  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<button id="map_button" onclick="shareLocationmap()" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding login-btn">Send</button>
		
	</div><div id="errmsg" style="color: red;font-size: larger;padding: 0 10px 3px 10px;text-align: center;"></div>
	
	</div>
	</div>
	</div>

 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<!--<script src="js/bootstrap.js"></script> -->
<?php



if (isset($ajaxChat)) {

 if($ajaxChat == 0){ ?>
<!-- <script
src="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/node_modules/socket.io-client/dist/socket.io.js"></script> -->
<!-- <script
src="<?php // Yii::$app->urlManagerfrontEnd->baseUrl; ?>/server/nodeClient.js"></script> -->
		<?php
} }
		$user = Yii::$app->user;
		//echo "User: ".Yii::$app->user->id;
		//if ($user->isGuest != 1 && !isset(Yii::$app->session['firstLogin'])){ ?>
<script type="text/javascript">
	$( "#sendform").click(function(e) {
 var chat_div = document.getElementById('live-msg-container');
  chat_div.scrollTop = chat_div.scrollHeight;
});
		$( "#map_button").click(function(e) {
 var chat_div = document.getElementById('live-msg-container');
  chat_div.scrollTop = chat_div.scrollHeight;
});


$(document).ready(function(){
	setTimeout(function() {
		$("#live-msg-container").scrollTop($("#live-msg-container")[0].scrollHeight);
		$('.live-messages ol').css({'opacity':'1'});
	}, 1000);
	<?php if(isset($ajaxChat) && $ajaxChat == 0){ ?>
	socket.emit('join', { joinid: '<?php echo str_replace(' ', '', $currentUserDetails->username) ?>' } );
	<?php } ?>
});

</script>
<script type="text/javascript">

$( document ).ready(function() {

// $( "#file-input").change(function(e) {
	$(document).on('change', 'input[type="file"]' , function(e){
	var fileName = e.target.files[0].name;
	
	var fileExtension = fileName.replace(/^.*\./, '');
     if(fileExtension=='jpg' || fileExtension=='jpeg' || fileExtension=='png' || fileExtension=='gif' )
     {
    $("#sendform").click();
     $("#chtShareImage").removeClass("attach_file_loader");
	 $("#chtShareImage").addClass("attach_file");
     	
     }
     else
     {
     	$("#imageError").html("Invalid file type.allow jpg,jpeg,png,gif only.");
     	setTimeout(function() { $("#imageError").html(""); }, 3000);
     	 $("#file-input").val("");
     	 return false;
     }
     
	
});

// $("form").on("submit", function (e) {
// 	e.preventDefault();
// 	var formData = $('#file-input')[0].files[0].name
// 	alert(formData);
// });
});
</script>



<script type="text/javascript">
	

function sharelocation(initialLoad) {

	$('#chatShareLocation').removeAttr("onclick");
	initialLoad = typeof initialLoad !== 'undefined' ? initialLoad : 0;
	var baseurl = '<?=Yii::$app->getUrlManager()->getBaseUrl();?>';
	var grid = document.querySelector('#fh5co-board');
	var kilometer = 25;
	var lat;
	var lon;

	var apiKey = $('#googleapikey').val();


	if (initialLoad == 0) {
		window.google = window.google || {};
		google.maps = google.maps || {};
		(function () {

			function getScript(src) {
				var s = document.createElement('script');

				s.src = src;
				document.body.appendChild(s);
			}

			var modules = google.maps.modules = {};
			google.maps.__gjsload__ = function (name, text) {
				modules[name] = text;
			};

			google.maps.Load = function (apiLoad) {
				delete google.maps.Load;
				apiLoad([0.009999999776482582, [[["https://mts0.googleapis.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.googleapis.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026"], null, null, null, null, "m@281000000", ["https://mts0.google.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.google.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026"]], [["https://khms0.googleapis.com/kh?v=162\u0026hl=en-US\u0026", "https://khms1.googleapis.com/kh?v=162\u0026hl=en-US\u0026"], null, null, null, 1, "162", ["https://khms0.google.com/kh?v=162\u0026hl=en-US\u0026", "https://khms1.google.com/kh?v=162\u0026hl=en-US\u0026"]], [["https://mts0.googleapis.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.googleapis.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026"], null, null, null, null, "h@281000000", ["https://mts0.google.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.google.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026"]], [["https://mts0.googleapis.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.googleapis.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026"], null, null, null, null, "t@132,r@281000000", ["https://mts0.google.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.google.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026"]], null, null, [["https://cbks0.googleapis.com/cbk?", "https://cbks1.googleapis.com/cbk?"]], [["https://khms0.googleapis.com/kh?v=84\u0026hl=en-US\u0026", "https://khms1.googleapis.com/kh?v=84\u0026hl=en-US\u0026"], null, null, null, null, "84", ["https://khms0.google.com/kh?v=84\u0026hl=en-US\u0026", "https://khms1.google.com/kh?v=84\u0026hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]], [["https://mts0.googleapis.com/vt?hl=en-US\u0026", "https://mts1.googleapis.com/vt?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]]], ["en-US", "US", null, 0, null, null, "https://maps.gstatic.com/mapfiles/", "https://csi.gstatic.com", "https://maps.googleapis.com", "https://maps.googleapis.com", null, "https://maps.google.com"], ["https://maps.gstatic.com/maps-api-v3/api/js/19/2", "3.19.2"], [630100503], 1, null, null, null, null, null, "initialize", null, null, 1, "https://khms.googleapis.com/mz?v=162\u0026", null, "https://earthbuilder.googleapis.com", "https://earthbuilder.googleapis.com", null, "https://mts.googleapis.com/vt/icon", [["https://mts0.googleapis.com/vt", "https://mts1.googleapis.com/vt"], ["https://mts0.googleapis.com/vt", "https://mts1.googleapis.com/vt"], null, null, null, null, null, null, null, null, null, null, ["https://mts0.google.com/vt", "https://mts1.google.com/vt"], "/maps/vt", 281000000, 132], 2, 500, ["https://geo0.ggpht.com/cbk", "https://g0.gstatic.com/landmark/tour", "https://g0.gstatic.com/landmark/config", "", "https://www.google.com/maps/preview/log204", "", "https://static.panoramio.com.storage.googleapis.com/photos/", ["https://geo0.ggpht.com/cbk", "https://geo1.ggpht.com/cbk", "https://geo2.ggpht.com/cbk", "https://geo3.ggpht.com/cbk"]], ["https://www.google.com/maps/api/js/master?pb=!1m2!1u19!2s2!2sen-US!3sUS!4s19/2", "https://www.google.com/maps/api/js/widget?pb=!1m2!1u19!2s2!2sen-US"], 1, 0], loadScriptTime);
			};
			var loadScriptTime = (new Date).getTime();
		//	alert(loadScriptTime);

		})();
	}

	// Try HTML5 geolocation
	if (navigator.geolocation) {


		navigator.geolocation.getCurrentPosition(function (position) {

			var pos = new google.maps.LatLng(position.coords.latitude,
				position.coords.longitude);

			lat = pos.lat();
			lon = pos.lng();
			//alert(pos);

			$('#map-latitude').val(lat);
			$('#map-longitude').val(lon);

			if (initialLoad == 0) {
				var latlng = new google.maps.LatLng(lat, lon);


				geocoder.geocode({ 'latLng': latlng }, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[1]) {
							document.getElementById("map-latitude").value = lat;
							document.getElementById("map-longitude").value = lon;

						}
						else {
							alert("No results found");
						}
					}
					else {
						console.log("Geocoder failed due to: " + status);
					}
				});
			}
			else {

				var latlng = new google.maps.LatLng(lat, lon);
				document.getElementById("map-latitude").value = lat;
				document.getElementById("map-longitude").value = lon;
				//getLocationData(1);

			}


			if (lat != "" && lon != "") {
				var str = '@#@';
				var staticMap = lat + str + lon;
				//alert(staticMap);
				$('#shareMap').val(staticMap);
				$('#sendform').click();
				//$('#sendform').removeAttr("onclick");
			
				$("#chtShareLocation").addClass("share_loction");
    		$("#chtShareLocation").removeClass("share_loction_loader");
			}
			else {
				alert("Error share location");
			}


		},
			function (error) {
				console.log(error.message);
			});

	} else {
		alert("Browser not support Geo Location");
	}
	$('#chatShareLocation').attr('onclick', 'sharelocation();');

}



</script>
<script type="text/javascript">
var map;
    function initMap() {


    	map = new google.maps.Map(document.getElementById('googleMap'), {
        center: {lat: 13.0833, lng: 80.28330000000005},
        zoom: 8,

      });
       
      var autocomplete = new google.maps.places.Autocomplete((document
            .getElementById('pac-input1')), {
        types : [ 'geocode' ]
    });
     autocomplete.addListener('place_changed', function() {
        
    var place = autocomplete.getPlace();

     var latitude = place.geometry.location.lat();
     var longitude = place.geometry.location.lng();

    var placeDetails = place.address_components;
     var count = placeDetails.length;
   
    var country = "";
    while(count != 0 && country == ""){
     if(placeDetails[count-1].types[0] == "country"){
         country = placeDetails[count-1].short_name;
         $('#shippingcountry').val(country);
     }
     count--;
    }
    
    $("#map-latitude").val(latitude);
     $("#map-longitude").val(longitude);

    });

    
      }

</script>

<?php  $this->registerJsFile('js/mousewheel.js'); ?>

<script>

 var sessionLat = "<?=Yii::$app->session['curr_latitude']?>";
 var sessionLog = "<?=Yii::$app->session['curr_longitude']; ?>";
 var sessionPlace = "<?=Yii::$app->session['curr_place1']; ?>";


 var sessionData = 0;
 sessionLat = sessionLat;
 sessionLog = sessionLog;
console.log(sessionLat);

 if(sessionLat=='')
 {
 	sessionLat="-33.8688";
 	sessionData =1;
 }

if(sessionLog=='')
 {
 	sessionLog="151.2195";
 	sessionData =1;
 }



   // $(document).ready(function(){
       $("#chtShareLocation").click(function(){

      // 	findmylocation();


        $("#mapc").html('<div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding "><div class="signup-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><div class="location-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><div class="mapcontrol-holder"><input class="classified-search-icon map-input-boxs col-xs-12 col-sm-10 col-md-9 col-lg-10 no-hor-padding" type="text" id="pac-input1" placeholder="Search Location"></div></div><a href="javascript:void(0);" class="map-mylocation-button" data-toggle="tooltip" title="" onclick="initAutocomplete()" data-original-title="Find my location!"><img alt="find my location" src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('frontend/web/images/target.png');?>"></a><div id="googleMap" class="google-Map col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="position: relative; overflow: hidden;"></div></div></div>');
        initAutocomplete(); 
        });

   //  });
     function initAutocomplete() {

     	if(reload==0)
     	{
     		
     		findmylocation();
     	}
     	console.log(Number(sessionLat));
        var map = new google.maps.Map(document.getElementById('googleMap'), {
          center: {lat: Number(sessionLat), lng: Number(sessionLog)},
          zoom: 13,
          mapTypeId: 'roadmap',
		  scaleControl: false,
   // scrollwheel: false,
   navigationControl: false,
   streetViewControl: false,
   fullScreenControl:false,
   zoomControl: false,
   mapTypeControl: false,
   gestureHandling: 'greedy'
        });

        document.getElementById('pac-input1').value = sessionPlace;

        var LatLng = new google.maps.LatLng(Number(sessionLat), Number(sessionLog));

        var marker = new google.maps.Marker({
      position: LatLng,
      map: map,
      title: 'Drag Me!',
      draggable: true,
	  icon:'<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/map_pointer.png");?>',
             anchorPoint: new google.maps.Point(0, -29),
    });
    
    google.maps.event.addListener(marker, 'dragend', function(marker){
      var latLng = marker.latLng;
      $latitude.value = latLng.lat();
      $longitude.value = latLng.lng();
       var geocoder = new google.maps.Geocoder;
     geocoder.geocode({'location': latLng}, function(results, status) {          
     if (status === 'OK') {            
     if (results[0]) {              
     console.log(results[0].formatted_address);              
     document.getElementById('pac-input1').value = results[0].formatted_address;            
     }          
     }        
     });



    });

google.maps.event.addListener(map, 'click', function (e) {
    var lat = e.latLng.lat();
    var lng = e.latLng.lng();
    var latlng = new google.maps.LatLng(lat, lng);

	console.log(lat);
    // if(marker != null) {            //already set marker to clear
    //    marker.setMap(null);
    //    marker = null;
    // }
    marker.setPosition(latlng);
     var geocoder = new google.maps.Geocoder;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
       // console.log(results)
          if (results[1]) {
			console.log(results[0].formatted_address);

               document.getElementById("pac-input1").value = results[0].formatted_address;
               document.getElementById("map-latitude").value = lat;
               document.getElementById("map-longitude").value = lng;
               map.setCenter(latlng); // Set map center to marker position
               //map.setZoom(15);

          } else {
            alert("No results found");
          }
        } else {
			console.log("Geocoder failed due to: " + status);
        }
      });
});




        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input1');
        var searchBox = new google.maps.places.SearchBox(input);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();


          if (places.length == 0) {
            return;
          }

         
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }

             var marker = new google.maps.Marker({
            position: place.geometry.location,
            map: map,
             draggable: true,
			 icon:'<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/map_pointer.png");?>',
             anchorPoint: new google.maps.Point(0, -29),
          //  title: "<div style = 'height:60px;width:200px' id='lattilong'><b>Your location:</b><br />Latitude: " + place.geometry.location.lat() + "<br />Longitude: " + place.geometry.location.lng()
        });
        google.maps.event.addListener(marker, 'dragend', function(marker){

      var latLng = marker.latLng;
      $latitude.value = latLng.lat();
      $longitude.value = latLng.lng();

     // $('#lattilong').html("<div style = 'height:60px;width:200px' id='lattilong'><b>Your location:</b><br />Latitude: " + latLng.lng() + "<br />Longitude: " + latLng.lat());

       var geocoder = new google.maps.Geocoder;
     geocoder.geocode({'location': latLng}, function(results, status) {          
     if (status === 'OK') {            
     if (results[0]) {              
     console.log(results[0].formatted_address);              
     document.getElementById('pac-input1').value = results[0].formatted_address;            
     }          
     }        
     });

    });

google.maps.event.addListener(map, 'click', function (e) {
    var lat = e.latLng.lat();
    var lng = e.latLng.lng();
    var latlng = new google.maps.LatLng(lat, lng);

	console.log(lat);
     if(marker != null) {           
       marker.setMap(null);
       marker = null;
    }
    marker.setPosition(latlng)
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
       // console.log(results)
          if (results[1]) {
			console.log(results[0].formatted_address)

               document.getElementById("pac-input1").value = results[0].formatted_address;
            //    document.getElementById("map-latitude").value = lat;
            //    document.getElementById("map-longitude").value = lng;
               map.setCenter(latlng); // Set map center to marker position
               //map.setZoom(15);

          } else {
            alert("No results found");
          }
        } else {
			console.log("Geocoder failed due to: " + status);
        }
      });
});


        google.maps.event.addListener(marker, "click", function (e) {
            var infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(marker.title);
            infoWindow.open(map, marker);
        });


            });
          map.fitBounds(bounds);


        });

      }

     </script>
<script type="text/javascript">

$(document).ready(function () {
	// $.noConflict();
	$('.default_msg_txt').mousewheel(function(e, delta) {
    this.scrollLeft -= (delta * 40);
    e.preventDefault();
});
});

  

</script>
<script type="text/javascript">
 function findmylocation(){

    if (navigator.geolocation) {

    navigator.geolocation.getCurrentPosition(function (p) {
        
        var LatLng = new google.maps.LatLng(p.coords.latitude, p.coords.longitude);
        var mapOptions = {
            center: LatLng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
			scaleControl: false,
   // scrollwheel: false,
   navigationControl: false,
   streetViewControl: false,
   fullScreenControl:false,
   zoomControl: false,
   mapTypeControl: false,
   gestureHandling: 'greedy'
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);


        //$("#mapc").append('<input id="pac-input1" class="controls" type="text" placeholder="Search Box">');

        var input = document.getElementById('pac-input1');
        var searchBox = new google.maps.places.SearchBox(input);
       // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

         // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

         
    


        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }


          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {

            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));



            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }


 if (place.geometry) {
      marker.setOptions({
        title: place.name,
        position: place.geometry.location
      });
      if (place.geometry.viewport) {
        marker.getMap().fitBounds(place.geometry.viewport);
      } else {
        marker.getMap().setCenter(place.geometry.location);
      }
    }
    //otherwise
    else {
      marker.setOptions({
        title: null
      });
      alert('place not found');
    }

          });
          map.fitBounds(bounds);
        });

// drag

        var marker = new google.maps.Marker({
            position: LatLng,
            map: map,
             draggable: true,
			 icon:'<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/map_pointer.png");?>',
             anchorPoint: new google.maps.Point(0, -29),
          //  title: "<div style = 'height:60px;width:200px' id='lattilong'><b>Your location:</b><br />Latitude: " + p.coords.latitude + "<br />Longitude: " + p.coords.longitude
        });


marker.addListener('mouseover', function() {
    //infowindow.open(map, this);
    marker.setAnimation(google.maps.Animation.BOUNCE);
});

//assuming you also want to hide the infowindow when user mouses-out
marker.addListener('mouseout', function() {
    //infowindow.close();
    marker.setAnimation(null);
});


         var geocoder = new google.maps.Geocoder;
     geocoder.geocode({'location': LatLng}, function(results, status) {          
     if (status === 'OK') {            
     if (results[0]) {              
     console.log(results[0].formatted_address);              
     document.getElementById('pac-input1').value = results[0].formatted_address;

     $.ajax({
   							url: baseUrl+'/site/currentloc/',
   							type: "POST",
   							dataType: "html",
   							ContentType :'text/html',
   							data: { 'lat': p.coords.latitude, 'lon': p.coords.longitude, 'place':results[0].formatted_address },
   							success: function (responce) {

								reload = 1;
								document.getElementById('pac-input1').value = results[0].formatted_address;
								sessionPlace=results[0].formatted_address;
							},
							error: function(err){
								console.log(err);
							}
		
							});       
     }          
     }        
     });

         
        google.maps.event.addListener(marker, 'dragend', function(marker){
      var latLng = marker.latLng;
      $latitude.value = latLng.lat();
      $longitude.value = latLng.lng();
    //  $('#lattilong').html("<div style = 'height:60px;width:200px' id='lattilong'><b>Your location:</b><br />Latitude: " + latLng.lng() + "<br />Longitude: " + latLng.lat());
      var geocoder = new google.maps.Geocoder;
       geocoder.geocode({'location': latLng}, function(results, status) {          
     if (status === 'OK') {            
     if (results[0]) {              
     console.log(results[0].formatted_address);              
     document.getElementById('pac-input1').value = results[0].formatted_address;            
     }          
     }        
     });
    });


google.maps.event.addListener(map, 'click', function (e) {
    var lat = e.latLng.lat();
    var lng = e.latLng.lng();
    var latlng = new google.maps.LatLng(lat, lng);
	console.log(lat);
    //placeMarker(event.latLng);
     if(marker != null) {            //already set marker to clear
       marker.setMap(null);
       marker = null;
    }
    marker.setPosition(latlng)
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
        //console.log(results)
          if (results[1]) {
			console.log(results[0].formatted_address)

               document.getElementById("pac-input1").value = results[0].formatted_address;
            //    document.getElementById("map-latitude").value = lat;
            //    document.getElementById("map-longitude").value = lng;
               map.setCenter(latlng); // Set map center to marker position
               //map.setZoom(15);

          } else {
            alert("No results found");
          }
        } else {
			console.log("Geocoder failed due to: " + status);
        }
      });
});

    
        google.maps.event.addListener(marker, "click", function (e) {
            var infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(marker.title);
            infoWindow.open(map, marker);
        });
    },
    function error(error) {
    	console.log(error);
    	$('#errmsg').show();
    		$('#map_button').addClass('map_but');
			$('#errmsg').html('Please allow location access in browser settings');
			setTimeout(function () {
				$('#errmsg').hide();
				$('#map_button').removeClass('map_but');
			}, 5000);
		return false;
    },
    {
           enableHighAccuracy: true, timeout: 20000, maximumAge: 0
        }
    );

} 
  }
  var $latitude = document.getElementById('map_latitude');
   var $longitude = document.getElementById('map_longitude');

  </script>
	  <style>
    
            #map { width: auto;  height: 500px; } 
            .map_but { padding: 10px 10px 3px 10px!important; }   
			.gm-fullscreen-control {
  display: none !important;
}   
    </style>

<?php  if(isset($_SESSION['reload'])) { ?>
<script>
	var reload = <?php echo $_SESSION['reload']; ?>;
</script>
<?php } ?>



<style type="text/css">
	.acc-dec-btn {
     width: 50% !important; 
}

</style>
<style type="text/css">

	 @media screen and (max-width: 764px) {
	.message-vertical-tab-section > .tab-content
	{
		display: none; 
	}

}
</style>

<script type="text/javascript">
if ( $(window).width() < 764) {

	var url =window.location.href;
	var splitted_url = url.split('/message');

	if(splitted_url[1] == "" || splitted_url[1] == "/" ){
	}
	else{
	var chatdiv = document.querySelector('.message-vertical-tab-container');
	chatdiv.setAttribute('style', 'display: none');

// if( $('.chat-message-container').css('display') == 'block' ) {
	
	var div = document.querySelector('.chat-message-container');
	div.setAttribute('style', 'display: block');
// } else {

// }
}
}
</script>

<!--  -->



