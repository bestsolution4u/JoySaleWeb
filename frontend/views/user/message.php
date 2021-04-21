<?php
use yii\helpers\Url;
use common\models\Photos;
use yii\helpers\Json;
use yii\helpers\Html;
Html::csrfMetaTags();
?>

<div class="message-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="live-msg-container">
	<ol
		class="live-messages-ol-<?php echo $currentUserDetails->username; ?>-<?php echo $chatUser->username; ?>-<?php echo $sourceId; ?>">
		<?php 
		$siteSettings = yii::$app->Myclass->getSitesettings();
	$apikey=$siteSettings->googleapikey;
		if(!empty($messageModel)) {
		 foreach ($messageModel as $message){

		 	$sender = $message->senderId;
		 	$gridAlign = "user-conv";
		 	$messageContainerAlign = "message-conversation-right-cnt";
		 	$messageContainer = "message-conversation";
		 	$gridArrowAlign = "arrow-right";
		 	$userImageAlign = "id='user'";
		 	$chatGirdImage = $currentUserImage;
		 	if ($sender != $currentUserDetails->userId){
		 		$gridAlign = "";
		 		$messageContainerAlign = "message-conversation-left-cnt";
		 		$messageContainer = "exchange-message-conversation";
		 		$gridArrowAlign = "exchange-arrow-left";
		 		$userImageAlign = "";
		 		$chatGirdImage = $currentChatUserImage;
		 		$receiverId = $sender;
		 	}
		 	$chatTime = $message->createdDate;
		 	$chatMessage = $message->message;
		 	$chatId = $message->chatId;
		 	$chatMessageContent=$message->messageContent;

			?>

		<li>
			<div class="<?php echo $gridAlign; ?> message-conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<div <?php echo $userImageAlign; ?> class="conversation-prof-pic no-hor-padding">
					<div class="message-prof-pic" style="background-image: url('<?php echo $chatGirdImage; ?>')"></div>
				</div>
				<div class="<?php echo $messageContainerAlign; ?> col-xs-9 col-sm-9 col-md-9 col-lg-7 no-hor-padding">
					<div class="<?php echo $gridArrowAlign; ?>"></div>
					<div class="<?php echo $messageContainer; ?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<div class="conversation-bargain-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<!-- working chat section -->
															<?php if($chatMessageContent==1){?>	
																<div class="conversation-text"><?php echo urldecode($chatMessage); ?>
																	
																</div>
															<?php } 
															if ($chatMessageContent==2) {?>
																<div class="conversation-text">
																<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/message/'.$chatMessage);?>" target="_blank">
																<img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/message/'.$chatMessage);?>" alt="<?php echo $chatMessage;?>">
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
															<img src="<?php echo $mapSrc; ?>">
														
															</a></div>
															<?php }?>
													
							</div>
						</div>
					</div>
					<div class="conversation-date col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<?php 
						$date=date_create(date('Y-m-d', $chatTime));
					echo date_format($date, "dS M Y");
					?>
											
					</div>
				</div>
			</div>
		</li>

		<?php }
		}  ?>
	</ol>
</div>
<?php $disable = '';
			if($chatUser->userstatus == 0)
				$disable = "disabled = 'disabled'";
			?>
<div class="message-type-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	<div class="live-messages-typing typing-status col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<?php echo $chatUser->username." ".Yii::t('app','Typing'); ?>...
	</div>
	<div class="chat-message-type col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<form class="form-inline" id="messageForm" enctype="multipart/form-data" onsubmit="sendMessage1()">
			<div class="form-group col-xs-12 col-sm-10 col-md-10 col-lg-11 no-hor-padding">
			  <textarea id="messageInput" class="exchange-comment-area comment-text-area form-control message_area_padding" rows="5"
			  	placeholder="<?php echo Yii::t('app','Message'); ?>" <?php echo $disable;?> style="background: #efefef ! important;    margin-bottom: 2px;"></textarea>
			  		<!-- design changes -->
										  	<a href="javascript:void(0);" >
												<div><label for="file-input"><div class="attach_file attach_pos" id="chtShareImage"></div><span style="color:red;font-weight: 100" id="imageError"></span></label><input type="file" accept="image/gif, image/jpeg, image/png" name="file" id="file-input" style="display: none;"></div>
												</a>
												
												<!-- <a onclick="sharelocation();" id="chatShareLocation">
												<div class="share_loction share_pos" id="chtShareLocation"></div>
											</a> -->

											<a data-toggle="modal" data-target="#shareloc" id="chatShareLocation">
												<div class="share_loction share_pos" id="chtShareLocation"></div>
											</a>

												<!-- end design changes -->
			</div>
			<input id="sourcce" name="sourcce" type="hidden" value="<?php echo $chatId; ?>" />
			<input id="sendingsource" name="sendingsource" type="hidden" value="<?php echo $currentUserDetails->userId; ?>" />
			<input id="appendinggsource" name="appendinggsource" type="hidden" value="<?php echo $currentUserDetails->username; ?>" />
			<input id="receiveingsource" name="receiveingsource" type="hidden" value="<?php echo $chatUser->username; ?>" />
			<input id="sourccetype" name="sourccetype" type="hidden" value="exchange" />
			<input id="chatsourcce" name="chatsourcce" type="hidden" value="0" />
			<input id="sourceId" name="sourceId" type="hidden" value="<?php echo $sourceId; ?>" />
			<input id="shareMap" name="shareMap" type="hidden" value=""/>
			<input id="staticMapApiKey" name="staticMapApiKey" type="hidden" value="<?php echo $siteSettings->staticMapApiKey;?>"/>
			<!-- new for offer instant chat -->
										<input id="offerADId" name="offerADId" type="hidden" value=""/>
										<input id="offerADType" name="offerADType" type="hidden" value=""/>
										<input id="messageInputOffer" name="messageInputOffer" type="hidden" value=""/>

										<!-- new for offer instant chat -->

			<div class="message-send col-xs-12 col-sm-2 col-md-2 col-lg-1 no-hor-padding">
				<a href="javascript:void(0);" id="sendform" <?php echo $disable;?> onclick="sendMessage1();">
					<div class="send-btn primary-bg-color text-align-center">
						<span><?php echo Yii::t('app','Send'); ?></span>
						<img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/send-icon.png');?>" alt="send-icon"></div>
				</a>
			</div>
		</form>
		<div class="message-limit" style="color:red;"></div>
	</div>
</div>


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

<script
src="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/node_modules/socket.io-client/dist/socket.io.js"></script>
<script
src="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/server/nodeClient.js"></script>

			<?php 
			$user = Yii::$app->user;
			?>
<script type="text/javascript">
	$(document).ready(function(){
		socket.emit( 'exchangejoin', { joinid: '<?php echo $currentUserDetails->username; ?>' } );
	});

	var objDiv = document.getElementById("live-msg-container");
	objDiv.scrollTop = objDiv.scrollHeight;
	$('.live-messages ol').css({'opacity':'1'});
</script>
		

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
		socket.emit( 'join', { joinid: '<?php echo $currentUserDetails->username ?>' } );
		<?php } ?>
	});
</script>
<script type="text/javascript">
$( document ).ready(function() {
	$( "#file-input").change(function(e) {
		
		var fileName = e.target.files[0].name;
		var fileExtension = fileName.replace(/^.*\./, '');
         if(fileExtension=='jpg' || fileExtension=='jpeg' || fileExtension=='png' || fileExtension=='gif' )
         {	
         	$('#messageForm').submit();
         	
         }
         else
         {
         	$("#imageError").html("Invalid file type.allow jpg,jpeg,png,gif only.");
         	setTimeout(function() { $("#imageError").html(""); }, 3000);
         	 $("#file-input").val("");
     	 return false;
         }
         
		
	});
	
	$("form").on("submit", function (e) {
		e.preventDefault();
		
	});
});



function sendMessage1() {

	
	var fd = new FormData(document.getElementById("messageForm"));
	
	fd.append("label", "WEBUPLOAD");
    var chatId = $("#sourcce").val();
    /* new for offer section */
     var offerADId = $("#offerADId").val();
      var offerADType = $("#offerADType").val();
    
     /* end offer section */
    

    if($("#file-input").val()!=""){
    	$("#chtShareImage").removeClass("attach_file");
    	$("#chtShareImage").addClass("attach_file_loader");
		var message = $("#file-input").val();
		var messageContent=2;
	 }
    else if(($("#shareMap").val())!=""){
    	$("#chtShareLocation").removeClass("share_loction");
    	$("#chtShareLocation").addClass("share_loction_loader");
		var message = $("#shareMap").val();
		var latlon = message.split("@#@");
		var lat=latlon[0];
		var lon=latlon[1];
		var messageContent=3;
	}
	else if(offerADId!="" && offerADType!=""){
		var messageContent=1;
	}
	else if(typeof (elem) != 'undefined'){
		var message = $.trim($(elem).html());
		var messageContent=1;
	}
	else if(($("#messageInput").val())!=""){
		var message = $("#messageInput").val();
		var messageContent=1;
	}
	else{
		$("#imageError").html(yii.t('app', "Enter some text."));
		setTimeout(function() { $("#imageError").html(""); }, 3000);

	}
	var senderId = $('#sendingsource').val();//sender id
	var messageType = $('#sourccetype').val(); //normal
	var source = $('#chatsourcce').val();
	var receiveId = $('#receiveingsource').val();
	var appendId = $('#appendinggsource').val();
	var sourceId = $('#sourceId').val();
	var zero=0;

    if(($("#file-input").val())!=""){
	    
    	$("#file-input").val("");
    	fd.append("messageContent", "2");
    	fd.append("chatId", chatId);

	    $.ajax({
		    url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/postmessage/',
		    type: "POST",
		    data: fd,
		  	processData: false,  
			contentType: false,   
			success : function(data) {
				data = data.trim();
				var data = data.split("~#~");
				if (data[0] != "blocked"){
					if (data != ""){
						data = JSON.parse(data);
						
						var sendData = "<li><div class='msg-grid-left'>" + data
						+ "</div></li>";
						var appendData = "<li><div class='msg-grid-right'>" + data
						+ "</div></li>";
						if (sourceId != '') {
							var appendData = constructData('right', data, 'exmessage');
							socket.emit('exmessage', {
								receiverId : appendId,
								senderId : receiveId,
								message : data,
								sourceId : sourceId
								
							});
							var appendlabel = ".live-messages-ol-" + appendId + "-"
									+ receiveId + "-" + sourceId;
							var msgContainer = "#live-msg-container";
						} else {
							var appendData = constructData('right', data, 'message');
							socket.emit('message', {
								receiverId : appendId,
								senderId : receiveId,
								message : data,
								offerId : zero
								
							});
							var appendlabel = ".live-messages-ol-" + appendId + "-"
									+ receiveId;
							var msgContainer = "#live-msg-container";
						}
					
						$(appendlabel).append(appendData);

						var currentScrollHeight = $(msgContainer)[0].scrollHeight;
						var currentScrollPosition = $(msgContainer).scrollTop();
						var currentInnerHeight = $(msgContainer).innerHeight();
						var newMessageInnerHeight = $("#newMessage").innerHeight();
						var newHth=currentScrollHeight+newMessageInnerHeight;
						$("#live-msg-container").scrollTop(newHth);
		
					}else{
						$(".message-limit").html("Enter some message without html");
						$(".message-limit").fadeIn();
						setTimeout(function() {
							$('#messageInput').removeClass('has-error');
							$(".message-limit").fadeOut();
						}, 3000);
					}
					
					$("#chtShareImage").removeClass("attach_file_loader");
	    			$("#chtShareImage").addClass("attach_file");
	    		} else {
	    			if(data[1]=="defined"){
						$('#user_pb').attr({style: 'display:none;'});
						$('.message-block-container > span').html('You are blocked');	
					} else if(data[1]=="undefined"){
						$('.message-block-container > span').html('You have blocked this user');	
					}
					$('.message-block-container').attr({style: 'display:block;'});
				
					$("#chtShareImage").removeClass("attach_file_loader");
	    			$("#chtShareImage").addClass("attach_file");
	    		}
	    	

			},
			error : function() {
			console.log(9);
			}
		});
	}
	else if(($("#shareMap").val())!=""){
		/* new share map section */
		$("#file-input").val("");
		//$("#messageInput").val("");
		$("#shareMap").val("");

		$.ajax({
			url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/postmessage/',
			type : "POST",
			data : {
				chatId : chatId,
				message : message,
				senderId : senderId,
				messageType : messageType,
				source : source,
				sourceId : sourceId,
				messageContent : messageContent,
				offerId:0
			},
			success : function(data) {
data = data.trim();
				var data = data.split("~#~");
				if (data[0] != "blocked"){
					if (data != ""){
						data = JSON.parse(data);
						
					
						if (sourceId != '') {
							var appendData = constructData('right', data, 'exmessage');
							socket.emit('exmessage', {
								receiverId : appendId,
								senderId : receiveId,
								message : data,
								sourceId : sourceId
								
							});

							var appendlabel = ".live-messages-ol-" + appendId + "-"
									+ receiveId + "-" + sourceId;
							var msgContainer = "#live-msg-container";
						} else {
							var appendData = constructData('right', data, 'message');
							socket.emit('message', {
								receiverId : appendId,
								senderId : receiveId,
								message : data,
								offerId : zero
								
							});
						//	console.log('senderId: '+senderId+' receiverId: '+receiverId);
							var appendlabel = ".live-messages-ol-" + appendId + "-"
									+ receiveId;
							var msgContainer = "#live-msg-container";
						}
						$("#messageInput").val("");
						var currentScrollHeight = $(msgContainer)[0].scrollHeight;
						var currentScrollPosition = $(msgContainer).scrollTop();
						var currentInnerHeight = $(msgContainer).innerHeight();
						$(appendlabel).append(appendData);
						if((currentScrollPosition + currentInnerHeight) == currentScrollHeight){
							$(msgContainer).scrollTop(
									$(msgContainer)[0].scrollHeight);
						}
						//$("#live-messages").scrollTop(
						//		$("#live-messages")[0].scrollHeight);
					}else{
						$(".message-limit").html("Enter some message without html");
						
						$(".message-limit").fadeIn();
						setTimeout(function() {
							$('#messageInput').removeClass('has-error');
							$(".message-limit").fadeOut();
						}, 3000);
					}
				} else {
					if(data[1]=="defined"){
						$('#user_pb').attr({style: 'display:none;'});
						$('.message-block-container > span').html('You are blocked');	
					} else if(data[1]=="undefined"){
						$('.message-block-container > span').html('You have blocked this user');	
					}
					$('.message-block-container').attr({style: 'display:block;'});
					
				}
			},
		});
	}
	/* offer section */
	if(offerADId!="" && offerADType!=""){
		//alert(offerADId);
		$("#file-input").val("");
		$("#messageInput").val("");
		$("#offerADId").val("");
		$("#offerADType").val("");
		var em="";
		
		
		$.ajax({
			url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/postmessage/',
			type : "POST",
			data : {
				chatId : chatId,
				message : em,
				senderId : senderId,
				messageType : messageType,
				source : source,
				sourceId : sourceId,
				messageContent : messageContent,
				offerId:offerADId,
				offerADType:offerADType
			},
			success : function(data) {
			
				data = data.trim();
				var data = data.split("~#~");
				if (data[0] != "blocked"){
					if (data != ""){
						data = JSON.parse(data);
						
						var appendlabel = ".live-messages-ol-" + appendId + "-"
									+ receiveId;
							var msgContainer = "#live-msg-container";
							var appendData = constructData('center', data, 'message');
							socket.emit('message', {
								receiverId : appendId,
								senderId : receiveId,
								message : data,
								offerId : offerADId
								
							});
						//	alert(appendData);
						
						$("#messageInput").val("");
						var currentScrollHeight = $(msgContainer)[0].scrollHeight;
						var currentScrollPosition = $(msgContainer).scrollTop();
						var currentInnerHeight = $(msgContainer).innerHeight();
						$(appendlabel).append(appendData);
						if((currentScrollPosition + currentInnerHeight) == currentScrollHeight){
							$(msgContainer).scrollTop(
									$(msgContainer)[0].scrollHeight);
						}
					
					}else{
						$(".message-limit").html("Enter some message without html");
						//$('#messageInput').addClass('has-error');
						$(".message-limit").fadeIn();
						setTimeout(function() {
							$('#messageInput').removeClass('has-error');
							$(".message-limit").fadeOut();
						}, 3000);
					}
				} else {
					if(data[1]=="defined"){
						$('#user_pb').attr({style: 'display:none;'});
						$('.message-block-container > span').html('You are blocked');	
					} else if(data[1]=="undefined"){
						$('.message-block-container > span').html('You have blocked this user');	
					}
					$('.message-block-container').attr({style: 'display:block;'});
					
				}
			}
		});
		 }
	/* end offer section */
	else if(($("#messageInput").val())!="" || typeof (elem)!= 'undefined'){
		if ($("#messageInput").val().length > 500) {
		var textValue = $("#messageInput").val().substring(0, 500);
		$("#messageInput").val(textValue);
		$('#messageInput').addClass('has-error');
		$(".message-limit").html("Maximum Character limit" + " 500");
		$(".message-limit").fadeIn();
		setTimeout(function() {
			$('#messageInput').removeClass('has-error');
			$(".message-limit").fadeOut();
		}, 3000);
	}
	else {
		$("#file-input").val("");
		$("#messageInput").val("");
		$("#messageInputOffer").val("");
		$("#offerADId").val("");
		$("#offerADType").val("");
		var em="";
		//alert(messageType);
		$.ajax({
			url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/postmessage/',
			type : "POST",
			ContentType :'text/javascript',
			dataType: "text",
			data : {
				chatId : chatId,
				message : message,
				senderId : senderId,
				messageType : messageType,
				source : source,
				sourceId : sourceId,
				messageContent : messageContent,
				offerId:0,
				offerADType:em
			},
			
			success : function(data) {
		
				data = data.trim();
				var data = data.split("~#~");
				if (data[0] != "blocked"){
					if (data != ""){
						data = JSON.parse(data);
						
						if (sourceId != '') {
							var appendData = constructData('right', data, 'exmessage');
							socket.emit('exmessage', {
								receiverId : appendId,
								senderId : receiveId,
								message : data,
								sourceId : sourceId
								
							});

							var appendlabel = ".live-messages-ol-" + appendId + "-"
									+ receiveId + "-" + sourceId;
							var msgContainer = "#live-msg-container";
						} else {
							var appendData = constructData('right', data, 'message');
							socket.emit('message', {
								receiverId : appendId,
								senderId : receiveId,
								message : data,
								offerId : zero
								
							});
						//	console.log('senderId: '+senderId+' receiverId: '+receiverId);
							var appendlabel = ".live-messages-ol-" + appendId + "-"
									+ receiveId;
							var msgContainer = "#live-msg-container";
						}
						$("#messageInput").val("");
						var currentScrollHeight = $(msgContainer)[0].scrollHeight;
						var currentScrollPosition = $(msgContainer).scrollTop();
						var currentInnerHeight = $(msgContainer).innerHeight();
						$(appendlabel).append(appendData);
						if((currentScrollPosition + currentInnerHeight) == currentScrollHeight){
							$(msgContainer).scrollTop(
									$(msgContainer)[0].scrollHeight);
						}
						//$("#live-messages").scrollTop(
						//		$("#live-messages")[0].scrollHeight);
					}else{
						$(".message-limit").html("Enter some message without html");
						//$('#messageInput').addClass('has-error');
						$(".message-limit").fadeIn();
						setTimeout(function() {
							$('#messageInput').removeClass('has-error');
							$(".message-limit").fadeOut();
						}, 3000);
					}
				} else {
					if(data[1]=="defined"){
						$('#user_pb').attr({style: 'display:none;'});
						$('.message-block-container > span').html('You are blocked');	
					} else if(data[1]=="undefined"){
						$('.message-block-container > span').html('You have blocked this user');	
					}
					$('.message-block-container').attr({style: 'display:block;'});
					
				}
			},
			error: function(){
				console.log("error");
			}

		});
		 }
	}
	else {
		$("#messageInput").val("");
		$('#messageInput').addClass('has-error');
		//$(".message-limit").html(Yii.t('app', "Message Cannot be Empty"));
		$(".message-limit").fadeIn();
		setTimeout(function() {
			$('#messageInput').removeClass('has-error');
			$(".message-limit").fadeOut();
		}, 3000);
	}
}


function sharelocation(initialLoad) {

	$('#chatShareLocation').removeAttr("onclick");
	initialLoad = typeof initialLoad !== 'undefined' ? initialLoad : 0;
	var baseurl = '<?=Yii::$app->getUrlManager()->getBaseUrl()?>';
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
    function initMap() {
       
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

<script>

var sessionLat = "<?=Yii::$app->session['curr_latitude']?>";
 var sessionLog = "<?=Yii::$app->session['curr_longitude']; ?>";
 var sessionPlace = "<?=Yii::$app->session['curr_place1']; ?>";
  sessionLat = sessionLat;
 sessionLog = sessionLog;
 var sessionData = 0;
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



var map;
    function initMap() {
      map = new google.maps.Map(document.getElementById('googleMap'), {
        center: {lat:  Number(sessionLat), lng: Number(sessionLog)},
        zoom: 8
      });
      //findmylocation();
    }
    $(document).ready(function(){
       $("#chatShareLocation").click(function(){
       //	findmylocation();
        $("#mapc").html('<div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding "><div class="signup-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><div class="location-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><div class="mapcontrol-holder"><input class="classified-search-icon map-input-boxs col-xs-12 col-sm-10 col-md-9 col-lg-10 no-hor-padding" type="text" id="pac-input1" placeholder="Search Location"></div></div><a href="javascript:void(0);" class="map-mylocation-button" data-toggle="tooltip" title="" onclick="initAutocomplete()" data-original-title="Find my location!"><img alt="find my location" src="https://owanbe.ng/images/gps.png"></a><div id="googleMap" class="google-Map col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="position: relative; overflow: hidden;"></div></div></div>');
        initAutocomplete(); 
        });

     });

     function initAutocomplete() {

      	if(reload==0)
     	{
     		findmylocation();
     	}
        var map = new google.maps.Map(document.getElementById('googleMap'), {
          center: {lat:  Number(sessionLat), lng: Number(sessionLog)},
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

        var LatLng = new google.maps.LatLng( Number(sessionLat), Number(sessionLog));

        var marker = new google.maps.Marker({
      position: LatLng,
      map: map,
      title: 'Drag Me!',
      draggable: true,
	  icon:'<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/map_pointer.png");?>',
             anchorPoint: new google.maps.Point(0, -29),
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
    var geocoder = new google.maps.Geocoder;
    var latlng = new google.maps.LatLng(Number(lat), Number(lng));

//	console.log(lat);
    //placeMarker(event.latLng);
    marker.setPosition(latlng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[1]) {
			

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
           // title: "<div style = 'height:60px;width:200px' id='lattilong'><b>Your location:</b><br />Latitude: " + place.geometry.location.lat() + "<br />Longitude: " + place.geometry.location.lng()
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
    var latlng = new google.maps.LatLng(Number(lat), Number(lng));

	
    //placeMarker(event.latLng);
    marker.setPosition(latlng);
    var geocoder = new google.maps.Geocoder;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
       
          if (results[1]) {
			

               document.getElementById("pac-input1").value = results[0].formatted_address;
               // document.getElementById("map-latitude").value = lat;
               // document.getElementById("map-longitude").value = lng;
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


          });
          map.fitBounds(bounds);
        });



        var marker = new google.maps.Marker({
            position: LatLng,
            map: map,
            draggable: true,
			icon:'<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/map_pointer.png");?>',
             anchorPoint: new google.maps.Point(0, -29),
           // title: "<div style = 'height:60px;width:200px' id='lattilong'><b>Your location:</b><br />Latitude: " + p.coords.latitude + "<br />Longitude: " + p.coords.longitude
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
    var latlng = new google.maps.LatLng(Number(lat), Number(lng));

	//console.log(lat);
    //placeMarker(event.latLng);
    marker.setPosition(latlng);
    var geocoder = new google.maps.Geocoder;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[1]) {
			console.log(results[1].formatted_address)

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


    
        google.maps.event.addListener(marker, "click", function (e) {
            var infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(marker.title);
            infoWindow.open(map, marker);
        });
    },
    function error(error) {
    	$('#errmsg').show();
    		$('#map_button').addClass('map_but');
			$('#errmsg').html('Please allow location access in browser settings');
			setTimeout(function () {
				$('#errmsg').hide();
				$('#map_button').removeClass('map_but');
			}, 5000);
		return false;
    },
	{            maximumAge:Infinity,            timeout:5000        }
	);

} 
  }
  var $latitude = document.getElementById('map_latitude');
   var $longitude = document.getElementById('map_longitude');
  </script>


<style type="text/css">
	.message_area_padding {
    padding-right: 85px!important;
}

	.gm-fullscreen-control {
  display: none !important;
}  
  .map_but { padding: 10px 10px 3px 10px!important; } 
</style>

<?php  if(isset($_SESSION['reload'])) { ?>
<script>
	var reload = <?php echo $_SESSION['reload']; ?>;
</script>
<?php } ?>