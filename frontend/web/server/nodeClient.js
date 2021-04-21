//To use nodejs with http enable the below line by removing the // at the start of the line
//var socket = io.connect('', {secure: false});

//To use nodejs with http command the below line by adding a // at the start of the line
var socket = io.connect('', {secure: true});

var typingTrack = 0;    
var timerId;
var livenotifytimer;

$(document).on('keydown', '#messageInput', function (e) {
	var keycode = e.keyCode;
	var sourceId = $('#sourceId').val();
	var keyPress = e;
	var message = $('#messageInput').val();
	
	//alert(messageLang);
	var messageLength = message.length;
	/*if ( keyPress &&
	        ( ( ( keyPress.which >= 32 // not a control character
	              //|| keyPress.which == 8  || // \b
	              //|| keyPress.which == 9  || // \t
	              //|| keyPress.which == 10 || // \n
	              //|| keyPress.which == 13    // \r
	              ) &&
	            !( keyPress.which >= 63232 && keyPress.which <= 63247 ) && // not special character in WebKit < 525
	            !( keyPress.which == 63273 )                            && //
	            !( keyPress.which >= 63275 && keyPress.which <= 63277 ) && //
	            !( keyPress.which === event.keyCode && // not End / Home / Insert / Delete (i.e. in Opera < 10.50)
	               ( keyPress.which == 35  || // End
	                 keyPress.which == 36  || // Home
	                 keyPress.which == 45  || // Insert
	                 keyPress.which == 46  || // Delete
	                 keyPress.which == 144    // Num Lock
	                 )
	               )
	            ) ||
	          keyPress.which === undefined // normal character in IE < 9.0
	          ) &&
	        keyPress.charCode !== 0 // not special character in Konqueror 4.3
	        ) {*/
	if (typingTrack == 0 && keycode != 13 && messageLength < 500) {
		var senderId = $('#receiveingsource').val();
		var receiverId = $('#appendinggsource').val();
		//console.log("id" + receiverId);
		//console.log('senderId: '+senderId+' receiverId: '+receiverId);
		if (sourceId != '') {
			socket.emit('exmessageTyping', {
				senderId: senderId,
				receiverId: receiverId,
				sourceId: sourceId,
				message: "type"
			});
		} else {

			//	var type={type : "type"};
			socket.emit('messageTyping', {
				receiverId: receiverId,
				senderId: senderId,
				message: "type"
			});
		}
		typingTrack = 1;
	}
	//}

	if (keycode == 13) {
		sendMessage1();
		return false;
	}
	if (typeof timerId != 'undefined') {
		clearInterval(timerId);
	}
	timerId = setInterval(function () {
		typingTrack = 0;
		var senderId = $('#receiveingsource').val();
		var receiverId = $('#appendinggsource').val();
		console.log('senderId: ' + senderId + ' receiverId: ' + receiverId);
		if (sourceId != '') {
			socket.emit('exmessageTyping', {
				senderId: senderId,
				receiverId: receiverId,
				sourceId: sourceId,
				message: "untype"
			});
		} else {
			//var type={type : "untype"};
			socket.emit('messageTyping', {
				senderId: senderId,
				receiverId: receiverId,
				message: "untype"
			});
		}
		clearInterval(timerId);
	}, 1000);
});

/*if (typeof typetimerId != 'undefined'){
	clearInterval(typetimerId);
}
var typetimerId = setInterval(function() {
	if(typingTrack == 0){
		var senderId = $('#receiveingsource').val();
		socket.emit('messageTyping', {
			senderId : senderId,
			message : "untype"
		});
	}
},1000);*/

$(document).on('click', ".submit", function () {
	sendMessage1();
	return false;
});

socket.on('messageTyping', function (data) {

	var accessId = ".live-messages-typing";
	var receivingSource = $('#receiveingsource').val();
	var myid = $('#appendinggsource').val();


	if (receivingSource == data.receiver) {


		if (data.message == "untype") {
			if (myid == data.senderId) {
				$(accessId).css('opacity', "0");
			}
		} else {
			if (myid == data.senderId) {
				$(accessId).css('opacity', "1");
			}
		}
	}
});

socket.on('message', function (data) {

	var appendId = $('#receiveingsource').val();
	var myid = $('#myid').val();

	if (appendId == data.receiver) {
		if (myid == data.sender) {
			//console.log("sid "+data.sender);
			var accessId = ".live-messages-ol-" + data.sender + "-" + data.receiver;
			if (data.offerId == 0) {
				var newMsgContent = constructData('left', data.message, 'message'); // data.message;
			} else {
				var newMsgContent = constructData('center', data.message, 'message'); // data.message;
			}
			$(accessId).append(newMsgContent);
			var currentScrollHeight = $("#live-msg-container")[0].scrollHeight;
			var currentScrollPosition = $("#live-msg-container").scrollTop();
			var currentInnerHeight = $("#live-msg-container").innerHeight();
			var newMessageInnerHeight = $("#newMessage").innerHeight();
			//console.log(newMessageInnerHeight);
			var newHth = currentScrollHeight + newMessageInnerHeight;
			$("#live-msg-container").scrollTop(newHth);
			/*if((currentScrollPosition + currentInnerHeight) == currentScrollHeight){
				$("#live-msg-container").scrollTop(
						$("#live-msg-container")[0].scrollHeight);
						$("#live-msg-container").scrollTop(newHth);
			}*/

			$.ajax({
				url: baseUrl + '/message/updatechat/',
				type: "POST",
				data: {
					type: "markread",
					sender: data.sender,
					receiver: data.receiver
				},
				success: function (data) {
						console.log("nodeclient js"+data.message);
				}
			});
		}
	} else if (appendId == data.sender) {
		if (myid == data.sender) {
			//console.log("sid "+data.sender);
			var accessId = ".live-messages-ol-" + data.sender + "-" + data.receiver;
			if (data.offerId == 0) {
				var newMsgContent = constructData('left', data.message, 'message'); // data.message;
			} else {
				var newMsgContent = constructData('center', data.message, 'message'); // data.message;
			}
			$(accessId).append(newMsgContent);
			var currentScrollHeight = $("#live-msg-container")[0].scrollHeight;
			var currentScrollPosition = $("#live-msg-container").scrollTop();
			var currentInnerHeight = $("#live-msg-container").innerHeight();
			var newMessageInnerHeight = $("#newMessage").innerHeight();
			var newHth = currentScrollHeight + newMessageInnerHeight;
			$("#live-msg-container").scrollTop(newHth);
			/*if((currentScrollPosition + currentInnerHeight) == currentScrollHeight){
				$("#live-msg-container").scrollTop(
						$("#live-msg-container")[0].scrollHeight);
						$("#live-msg-container").scrollTop(newHth);
			}*/

			$.ajax({
				url: baseUrl + '/message/updatechat/',
				type: "POST",
				data: {
					type: "markread",
					sender: data.sender,
					receiver: data.receiver
				},
				success: function (data) {
						console.log("nodeclient js"+data.message);
				}
			});
		}
	} else {
		if (myid == data.sender) {
			var notifyContent = constructLiveNotify(data.message);
			var chatlistSelector = ".chatlist-" + data.receiver;
			var messageContainer = chatlistSelector + " .short-message";
			var messageUnreadMarker = chatlistSelector + " .message-prof-pic";
			var readCount = chatlistSelector + " .userNameLink";
			var totalData = data.message;
			var newChatStatus = $(readCount).data("userread");
			if (totalData.messageContent == 2) {
				var liveMessage = 'shared an image';
			} else if (totalData.messageContent == 3) {
				var liveMessage = 'shared a location';
			} else {
				var liveMessage = totalData.message;
			}

			//console.log('li: '+chatlistSelector+" div: "+messageContainer+" data: "+liveMessage);
			$(readCount).attr("data-userread", "1");
			$(chatlistSelector).addClass('instant-notify');
			$(messageContainer).html(liveMessage);
			$(messageUnreadMarker).html('<div class="message-unread-count"></div>');


			$('.chatnotify-container').html(notifyContent);
			$('.chatnotify-container').show();
			if (typeof livenotifytimer != 'undefined') {
				clearInterval(livenotifytimer);
			}
			livenotifytimer = setInterval(function () {
				$('.chatnotify-container').hide();
				clearInterval(livenotifytimer);
			}, 5000);

			/*var data = parseInt($('.message-count ').html());
			if(newChatStatus == '0'){
				data += 1;
				$('.message-count').html(data);
				$(readCount).data("userread", 1);
				$('.message-count').removeClass('message-hide');
			}*/

			$.ajax({
				url: baseUrl + '/message/updatechat/',
				type: "POST",
				data: {
					type: "getcount",
					userName: data.sender
				},
				success: function (data) {
					console.log("hi");
					if (data != '0' && liveCount != data) {
						liveCount = data;
						$('.message-count').html(data);
						$(readCount).data("userread", 1);
						$('.message-count').removeClass('message-hide');
					}
				}
			});
		}
	}
});



socket.on('exmessage', function (data) {
	//alert("soket on clien exmessage"+data);


	// data.sender=data.message.senderId;
	// data.receiver=data.message.receiverId;
	// data.sourceId=data.message.sourceId;
	console.log('Ex Message Chat - appendId: ' + data.sender + " receiver id: " + data.receiver + " data: " + data.message);
	var appendId = $('#receiveingsource').val();
	var sourceId = $('#sourceId').val();
	var accessId = ".live-messages-ol-" + data.sender + "-" + data.receiver + "-" + data.sourceId;
	var newMsgContent = constructData('left', data.message, 'exmessage'); // data.message;
	var currentScrollHeight = $("#live-msg-container")[0].scrollHeight;
	var currentScrollPosition = $("#live-msg-container").scrollTop();
	var currentInnerHeight = $("#live-msg-container").innerHeight();
	$(accessId).append(newMsgContent);
	console.log(".live-messages-ol-" + data.sender + "-" + data.receiver +
		"-" + data.sourceId + "Message:" + newMsgContent);
	if ((currentScrollPosition + currentInnerHeight) == currentScrollHeight) {
		$("#live-msg-container").scrollTop(
			$("#live-msg-container")[0].scrollHeight);
	}
});

socket.on('exmessageTyping', function (data) {
	var accessId = ".live-messages-typing";
	var sourceId = $('#sourceId').val();
	console.log("Type message: " + data.message + " receiverId: " + data.receiverId);
	if (sourceId == data.sourceId) {
		if (data.message == "untype")
			$(accessId).css('opacity', "0");
		else
			$(accessId).css('opacity', "1");
	}
});

function english_ordinal_suffix(dt) {
	return dt.getDate() + (dt.getDate() % 10 == 1 && dt.getDate() != 11 ? 'st' : (dt.getDate() % 10 == 2 && dt.getDate() != 12 ? 'nd' : (dt.getDate() % 10 == 3 && dt.getDate() != 13 ? 'rd' : 'th')));
}

function converttimestamp(UNIX_timestamp) {
	var a = new Date(UNIX_timestamp * 1000);
	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var year = a.getFullYear();
	var month = months[a.getMonth()];
	var date = a.getDate();
	var hour = a.getHours();
	var min = a.getMinutes();
	var sec = a.getSeconds();
	var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
	//console.log(time);
	time = new Date(time);
	return english_ordinal_suffix(time) + " " + month + " " + year;
}

function constructData(align, data, type) {
	if (align == 'center') {
		var appendId = $('#receiveingsource').val();
		var messageLang = $('#messageLang').val();
		//alert(messageLang);
		if (data.offer_type == 'accept') {
			var acceptDecline1 = "accepted";
			var acceptDecline2 = "";
			var acceptDecline3 = "accept_txt";
			if (data.seller_name == appendId) {
				var content = yii.t('app', 'Your offer accepted');
			} else {
				var content = yii.t('app', 'You have accepted this offer');
			}

		} else {
			var acceptDecline1 = "decline";
			var acceptDecline2 = "decline_offer";
			var acceptDecline3 = "decline_txt";
			if (data.seller_name == appendId) {
				var content = yii.t('app', 'Your offer declined');
			} else {
				var content = yii.t('app', 'You have declined this offer');
			}

		}
		var item_img_url = data.item_image;
		var buynow_section = "";
		//alert("sellername"+data.seller_name);

		//alert("receivername"+appendId);

		if (data.offer_type == 'accept' && data.buynow_status == 0 && data.seller_name == appendId && data.instant_buy == 1 && data.site_buynowPaymentMode == 1) {
			var buy_url = data.buynow_url;
			buynow_section = '<div class="buy_now_btn" id="accept_btn_buynow"><a class="btn btn_buynow" href="' + buy_url + '">' + 'Buy Now' + '</a></div>';
		}

		if(data.sender_lang == 'ar'){
			if(data.offer_currency_position == 'postfix'){
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_price + ' ' + data.offer_currency + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}else
			{
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_currency + ' ' + data.offer_price + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}
			
		}else
		{
			if(data.offer_currency_position == 'postfix'){
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_price + ' ' + data.offer_currency + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}else
			{
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_currency + ' ' + data.offer_price + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}
			
		}

		if(data.receiver_lang == 'ar'){
			if(data.offer_currency_position == 'postfix'){
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_price + ' ' + data.offer_currency + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}else
			{
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_currency + ' ' + data.offer_price + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}
			
		}else
		{
			if(data.offer_currency_position == 'postfix'){
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_price + ' ' + data.offer_currency + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}else
			{
				outputData = '<li><div class="offer-' + acceptDecline1 + '-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage"><div class="offer_container col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 border-radius-5 col-xs-6 col-sm-6 col-md-6 col-lg-6 no-hor-padding"><div class="conversation-product-pic-container col-xs-offset-3 col-sm-offset-0"><div style="background-image: url(' + item_img_url + ');display:block;" class="conversation-product-pic offer_product"></div></div><div class="conversation-bargain-container offer_accept_decline_container margin_left10"><!-- this  add class decline_offer--><div class="offer_accepted ' + acceptDecline2 + '"></div><!-- this decline_txt--><span class="margin_left5 ' + acceptDecline3 + '">' + content + '.</span><a href=""><div class="offer_txt extra_text_hide">Product Name</div></a><div class="conversation-rate-container"><div class="sent_rate"><h4><span class="offer_price">' + data.offer_currency + ' ' + data.offer_price + '</span></h4></div><span class="offer_date">' + converttimestamp(data.chatTime) + '</span></div>' + buynow_section + '</div></div></div></li>';
			}
			
		}


		
		return outputData;
	} else {
		if (data.messageContent == "1") {
			var output = '<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage">' +
				'<div class="conversation-bargain-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' +
				'<div class="conversation-text">' + data.message + '</div></div></div>'
		} else if (data.messageContent == "2") //image share
		{
			var output = '<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage">' +
				'<div class="conversation-bargain-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' +
				"<div class='conversation-text'>" + "<a href='" + data.view_url + "' target='_blank'><img src='" + data.view_url + "' alt='Loding...'></a>" + "</div></div></div>";
		} else if (data.messageContent == "3") //location share
		{
			var lat = data.lat;
			var lon = data.lon;


			var map1 = 'https://maps.googleapis.com/maps/api/staticmap?center=';
			var map2 = '&zoom=16&size=400x200&sensor=false&maptype=roadmap&markers=color:red%7Clabel:S%7C';
			var map3 = '&key=';
			var map4 = $('#staticMapApiKey').val();
			var com = ',';
			//var mapSrc=map1+lat+com+lon+map2+lat+com+lon;
			var mapSrc = map1 + lat + com + lon + map2 + lat + com + lon + map3 + map4;

			//alert(mapSrc);

			var output = '<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage">' +
				'<div class="conversation-bargain-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' +
				'<div class="conversation-text">' + '<a class="viewShared" href="https://www.google.com/maps?daddr=' + lat + ',' + lon + '" target="_blank"><img src="' + mapSrc + '" style="width:400px;height:200px;"></a>' + '</div></div></div>';

			/*var output = '<div class="conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">'
			+'<div class="conversation-bargain-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">'
			+'<div class="conversation-text">'+ '<a class="viewShared" href="https://www.google.com/maps?daddr='+lat+','+lon+'" target="_blank"><img src="/images/location.png" >&nbsp;'+Yii.t('app','View Shared Location')+'.</a>' + '</div></div></div>';*/
		}

		var outputData = "";
		if (align == "right") {
			var gridAlign = "user-conv";
			var messageContainerAlign = "message-conversation-right-cnt";
			var gridArrowAlign = "arrow-right";
			var userImageAlign = "id='user'";

			outputData = '<li><div class="' + gridAlign + ' message-conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' +
				'<div ' + userImageAlign + ' class="conversation-prof-pic no-hor-padding">' +
				'<div class="message-prof-pic" style="background-image: url(\'' + data.userImage + '\')"></div></div>' +
				'<div class="' + messageContainerAlign + ' col-xs-9 col-sm-9 col-md-9 col-lg-7 no-hor-padding">' +
				'<div class="' + gridArrowAlign + '"></div>' +
				'<div class="message-conversation col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage">' +
				output + '</div><div class="conversation-date col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' +
				converttimestamp(data.chatTime) +
				'</div></div></div></li>';
			//Old------------>
			/*+"<li><div class='msg-grid-right'>" + output
				+ "</div></li>";*/
		} else {
			var gridAlign = "";
			var messageContainerAlign = "message-conversation-left-cnt";
			if (type == "exmessage") {
				var gridArrowAlign = "exchange-arrow-left";
				var messageContainer = "exchange-message-conversation";
			} else {
				var gridArrowAlign = "arrow-left";
				var messageContainer = "message-conversation";
			}
			var userImageAlign = "";

			outputData = '<li><div class="' + gridAlign + ' message-conversation-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' +
				'<div ' + userImageAlign + ' class="conversation-prof-pic no-hor-padding">' +
				'<div class="message-prof-pic" style="background-image: url(\'' + data.userImage + '\')"></div></div>' +
				'<div class="' + messageContainerAlign + ' col-xs-9 col-sm-9 col-md-9 col-lg-7 no-hor-padding">' +
				'<div class="' + gridArrowAlign + '"></div>' +
				'<div class="' + messageContainer + ' col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="newMessage">' +
				output + '</div><div class="conversation-date col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' +
				converttimestamp(data.chatTime) +
				'</div></div></div></li>';
			//outputData = "<li><div class='msg-grid-left'>" + output + "</div></li>";
		}
		return outputData;
	}

}

function constructLiveNotify(data) {
	//console.log(data);
	if (data.messageContent == 2) {
		var liveMessage = yii.t('app', 'shared an image');
	} else if (data.messageContent == 3) {
		var liveMessage = yii.t('app', 'shared a location');
	} else {
		var liveMessage = data.message;

		if (data.offer_type == 'accept') {
			var liveMessage = yii.t('app', 'Your offer accepted');
		} else if (data.offer_type == 'decline') {
			var liveMessage = yii.t('app', 'Your offer declined');
		}
	}

	// if (data.offer_type=='accept'){
	// 	var liveMessage = "Your offer accepted";
	// }
	// else if(data.offer_type=='decline'){
	// 	var liveMessage = "Your offer declined";
	// }
	var output = '<a href="' + data.chatURL + '" target="_blank" title="' + data.userName + '" >' +
		'<div class="message-floating-div-cnt col-xs-12 col-sm-4 col-md-3 col-lg-3 no-hor-padding">' +
		'<div class="floating-div no-hor-padding pull-right">' +
		'<div class="message-icon no-hor-padding">' +
		'<div class="message-user-prof-pic" id="floating-div-pic" style="background-image:url(\'' + data.userImage + '\');"></div>' +
		'</div>' +
		'<div class="message-user-info-cnt no-hor-padding">' +
		'<div class="message-user-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">' + data.userName + '</div>' +
		'<div class="message-user-info">' + liveMessage.slice(0,35) + '</div>' +
		'</div></div></div></a>';
	return output;
}


function sendMessage1(elem) {


	var fd = new FormData(document.getElementById("messageForm"));

	fd.append("label", "WEBUPLOAD");
	var chatId = $("#sourcce").val();
	/* new for offer section */
	var offerADId = $("#offerADId").val();
	var offerADType = $("#offerADType").val();
	//console.log($("#file-input").val());

	/* end offer section */


	if ($("#file-input").val() != "") {
		$("#chtShareImage").removeClass("attach_file");
		$("#chtShareImage").addClass("attach_file_loader");
		var message = $("#file-input").val();
		var messageContent = 2;

		//alert(message);alert(messageContent);

	} else if (($("#shareMap").val()) != "") {
		$("#chtShareLocation").removeClass("share_loction");
		$("#chtShareLocation").addClass("share_loction_loader");
		var message = $("#shareMap").val();
		var latlon = message.split("@#@");
		var lat = latlon[0];
		var lon = latlon[1];
		var messageContent = 3;
	} else if (offerADId != "" && offerADType != "") {
		//var message = messageInputOffer;
		var messageContent = 1;
	} else if (typeof (elem) != 'undefined') {
		var message = $.trim($(elem).html());
		var messageContent = 1;
	} else if (($("#messageInput").val()) != "") {
		var message = $("#messageInput").val();
		var messageContent = 1;
	} else {
		//$("#imageError").html(yii.t('app', "Enter some text."));
		// setTimeout(function () {
		// 	$("#imageError").html("");
		// }, 3000);

	}
	//	alert(messageContent);
	var senderId = $('#sendingsource').val(); //sender id
	var messageType = $('#sourccetype').val(); //normal
	var source = $('#chatsourcce').val();
	var receiveId = $('#receiveingsource').val();
	var appendId = $('#appendinggsource').val();
	var sourceId = $('#sourceId').val();
	var zero = 0;

	if (($("#file-input").val()) != "") {
		/* image share section */
		$("#file-input").val("");
		fd.append("messageContent", "2");
		fd.append("chatId", chatId);

		$.ajax({
			url: baseUrl + '/message/postmessage',
			type: "POST",
			data: fd,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			success: function (data) {
				//alert(data);
				data = data.trim();
				//	console.log(data);
				var data = data.split("~#~");
				if (data[0] != "blocked") {
					if (data != "") {
						data = JSON.parse(data);
						// data['receiverId']=appendId;
						// data['senderId']=receiveId;


						var sendData = "<li><div class='msg-grid-left'>" + data +
							"</div></li>";
						var appendData = "<li><div class='msg-grid-right'>" + data +
							"</div></li>";
						if (sourceId != '') {
							//data['sourceId']=sourceId;
							var appendData = constructData('right', data, 'exmessage');
							socket.emit('exmessage', {
								receiverId: appendId,
								senderId: receiveId,
								message: data,
								sourceId: sourceId

							});
							var appendlabel = ".live-messages-ol-" + appendId + "-" +
								receiveId + "-" + sourceId;
							var msgContainer = "#live-msg-container";
						} else {
							//data['offerId']=zero;
							var appendData = constructData('right', data, 'message');
							socket.emit('message', {
								receiverId: appendId,
								senderId: receiveId,
								message: data,
								offerId: zero

							});
							var appendlabel = ".live-messages-ol-" + appendId + "-" +
								receiveId;
							var msgContainer = "#live-msg-container";
						}
						/*$("#messageInput").val("");*/
						$(appendlabel).append(appendData);

						var currentScrollHeight = $(msgContainer)[0].scrollHeight;
						var currentScrollPosition = $(msgContainer).scrollTop();
						var currentInnerHeight = $(msgContainer).innerHeight();
						var newMessageInnerHeight = $("#newMessage").innerHeight();
						var newHth = currentScrollHeight + newMessageInnerHeight;
						$("#live-msg-container").scrollTop(newHth);


						// if((currentScrollPosition + currentInnerHeight) == currentScrollHeight){
						// 	$("#live-msg-container").scrollTop(
						// 			$("#live-msg-container")[0].scrollHeight);

						// }
						// $("#live-messages").scrollTop(
						// 						$("#live-messages")[0].scrollHeight);
					} else {
						$(".message-limit").html("Enter some message without html");
						//$('#messageInput').addClass('has-error');
						$(".message-limit").fadeIn();
						setTimeout(function () {
							$('#messageInput').removeClass('has-error');
							$(".message-limit").fadeOut();
						}, 3000);
					}
					//place loader
					$("#chtShareImage").removeClass("attach_file_loader");
					$("#chtShareImage").addClass("attach_file");
				} else {
					if (data[1] == "defined") {
						$('#user_pb').attr({
							style: 'display:none;'
						});
						$('.message-block-container > span').html('You are blocked');
					} else if (data[1] == "undefined") {
						$('.message-block-container > span').html('You have blocked this user');
					}
					$('.message-block-container').attr({
						style: 'display:block;'
					});
					//place loader
					$("#chtShareImage").removeClass("attach_file_loader");
					$("#chtShareImage").addClass("attach_file");
				}
				//eswar();

			},
			error: function () {
				console.log(9);
			}
		});
	} else if (($("#shareMap").val()) != "") {
		/* new share map section */
		$("#file-input").val("");
		//$("#messageInput").val("");
		$("#shareMap").val("");

		$.ajax({
			url: baseUrl + '/message/postmessage',
			type: "POST",
			data: {
				chatId: chatId,
				message: message,
				senderId: senderId,
				messageType: messageType,
				source: source,
				sourceId: sourceId,
				messageContent: messageContent,
				offerId: 0
			},
			success: function (data) {
				data = data.trim();
				var data = data.split("~#~");
				if (data[0] != "blocked") {
					if (data != "") {
						data = JSON.parse(data);
						// data['receiverId']=appendId;
						// data['senderId']=receiveId;


						// var sendData = "<li><div class='msg-grid-left'>" + data
						// + "</div></li>";
						// var appendData = "<li><div class='msg-grid-right'>" + data
						// + "</div></li>";
						if (sourceId != '') {
							//data['sourceId']=sourceId;
							var appendData = constructData('right', data, 'exmessage');
							socket.emit('exmessage', {
								receiverId: appendId,
								senderId: receiveId,
								message: data,
								sourceId: sourceId

							});

							var appendlabel = ".live-messages-ol-" + appendId + "-" +
								receiveId + "-" + sourceId;
							var msgContainer = "#live-msg-container";
						} else {
							//data['offerId']=zero;
							var appendData = constructData('right', data, 'message');
							socket.emit('message', {
								receiverId: appendId,
								senderId: receiveId,
								message: data,
								offerId: zero

							});
							//	console.log('senderId: '+senderId+' receiverId: '+receiverId);
							var appendlabel = ".live-messages-ol-" + appendId + "-" +
								receiveId;
							var msgContainer = "#live-msg-container";
						}
						$("#messageInput").val("");
						var currentScrollHeight = $(msgContainer)[0].scrollHeight;
						var currentScrollPosition = $(msgContainer).scrollTop();
						var currentInnerHeight = $(msgContainer).innerHeight();
						$(appendlabel).append(appendData);
						if ((currentScrollPosition + currentInnerHeight) == currentScrollHeight) {
							$(msgContainer).scrollTop(
								$(msgContainer)[0].scrollHeight);
						}
						//$("#live-messages").scrollTop(
						//		$("#live-messages")[0].scrollHeight);
					} else {
						$(".message-limit").html("Enter some message without html");
						//$('#messageInput').addClass('has-error');
						$(".message-limit").fadeIn();
						setTimeout(function () {
							$('#messageInput').removeClass('has-error');
							$(".message-limit").fadeOut();
						}, 3000);
					}
				} else {
					if (data[1] == "defined") {
						$('#user_pb').attr({
							style: 'display:none;'
						});
						$('.message-block-container > span').html('You are blocked');
					} else if (data[1] == "undefined") {
						$('.message-block-container > span').html('You have blocked this user');
					}
					$('.message-block-container').attr({
						style: 'display:block;'
					});

				}
			},
		});
	}
	/* offer section */
	if (offerADId != "" && offerADType != "") {
		//alert(offerADId);
		$("#file-input").val("");
		$("#messageInput").val("");
		$("#offerADId").val("");
		$("#offerADType").val("");
		var em = "";


		$.ajax({
			url: baseUrl + '/message/postmessage',
			type: "POST",
			data: {
				chatId: chatId,
				message: em,
				senderId: senderId,
				messageType: messageType,
				source: source,
				sourceId: sourceId,
				messageContent: messageContent,
				offerId: offerADId,
				offerADType: offerADType
			},
			success: function (data) {

				data = data.trim();
				var data = data.split("~#~");
				if (data[0] != "blocked") {
					if (data != "") {
						data = JSON.parse(data);
						// data['receiverId']=appendId;
						// data['senderId']=receiveId;
						// data['offerId']=offerADId;

						// var sendData = "<li><div class='msg-grid-left'>" + data
						// + "</div></li>";
						// var appendData = "<li><div class='msg-grid-right'>" + data
						// + "</div></li>";


						var appendData = constructData('center', data, 'message');
						socket.emit('message', {
							receiverId: appendId,
							senderId: receiveId,
							message: data,
							offerId: offerADId

						});

						var appendlabel = ".live-messages-ol-" + appendId + "-" +
							receiveId;
						var msgContainer = "#live-msg-container";
						//	alert(appendData);

						$("#messageInput").val("");
						var currentScrollHeight = $(msgContainer)[0].scrollHeight;
						var currentScrollPosition = $(msgContainer).scrollTop();
						var currentInnerHeight = $(msgContainer).innerHeight();
						$(appendlabel).append(appendData);
						if ((currentScrollPosition + currentInnerHeight) == currentScrollHeight) {
							$(msgContainer).scrollTop(
								$(msgContainer)[0].scrollHeight);
						}

					} else {
						$(".message-limit").html("Enter some message without html");
						//$('#messageInput').addClass('has-error');
						$(".message-limit").fadeIn();
						setTimeout(function () {
							$('#messageInput').removeClass('has-error');
							$(".message-limit").fadeOut();
						}, 3000);
					}
				} else {
					if (data[1] == "defined") {
						$('#user_pb').attr({
							style: 'display:none;'
						});
						$('.message-block-container > span').html('You are blocked');
					} else if (data[1] == "undefined") {
						$('.message-block-container > span').html('You have blocked this user');
					}
					$('.message-block-container').attr({
						style: 'display:block;'
					});

				}
			}
		});
	}
	/* end offer section */
	else if (($("#messageInput").val()) != "" || typeof (elem) != 'undefined') {
		if ($("#messageInput").val().length > 500) {
			var textValue = $("#messageInput").val().substring(0, 500);
			$("#messageInput").val(textValue);
			$('#messageInput').addClass('has-error');
			$(".message-limit").html("Maximum Character limit" + " 500");
			$(".message-limit").fadeIn();
			setTimeout(function () {
				$('#messageInput').removeClass('has-error');
				$(".message-limit").fadeOut();
			}, 3000);
		} else {
			$("#file-input").val("");
			$("#messageInput").val("");
			$("#messageInputOffer").val("");
			$("#offerADId").val("");
			$("#offerADType").val("");
			var em = "";
			//alert(messageType);
			$.ajax({
				url: baseUrl + '/message/postmessage',
				type: "POST",
				ContentType: 'text/javascript',
				dataType: "text",
				data: {
					chatId: chatId,
					message: message,
					senderId: senderId,
					messageType: messageType,
					source: source,
					sourceId: sourceId,
					messageContent: messageContent,
					offerId: 0,
					offerADType: em
				},

				success: function (data) {
					//alert(data);

					data = data.trim();
					var data = data.split("~#~");
					//alert(data[1]);
					if (data[0] != "blocked") {
						if (data != "") {
							data = JSON.parse(data);

							//alert(data);
							// var sendData = "<li><div class='msg-grid-left'>" + data
							// + "</div></li>";
							// var appendData = "<li><div class='msg-grid-right'>" + data
							// + "</div></li>";
							if (sourceId != '') {
								// 		data['receiverId']=appendId;
								// data['senderId']=receiveId;
								// data['sourceId']=sourceId;
								var appendData = constructData('right', data, 'exmessage');
								socket.emit('exmessage', {
									receiverId: appendId,
									senderId: receiveId,
									message: data,
									sourceId: sourceId

								});

								var appendlabel = ".live-messages-ol-" + appendId + "-" +
									receiveId + "-" + sourceId;
								var msgContainer = "#live-msg-container";
							} else {



								var appendData = constructData('right', data, 'message');
								socket.emit('message', {
									receiverId: appendId,
									senderId: receiveId,
									message: data,
									offerId: zero

								});

								var appendlabel = ".live-messages-ol-" + appendId + "-" +
									receiveId;
								var msgContainer = "#live-msg-container";
							}
							$("#messageInput").val("");
							var currentScrollHeight = $(msgContainer)[0].scrollHeight;
							var currentScrollPosition = $(msgContainer).scrollTop();
							var currentInnerHeight = $(msgContainer).innerHeight();
							$(appendlabel).append(appendData);
							if ((currentScrollPosition + currentInnerHeight) == currentScrollHeight) {
								$(msgContainer).scrollTop(
									$(msgContainer)[0].scrollHeight);
							}

							//$("#live-messages").scrollTop(
							//		$("#live-messages")[0].scrollHeight);
						} else {
							$(".message-limit").html("Enter some message without html");
							//$('#messageInput').addClass('has-error');
							$(".message-limit").fadeIn();
							setTimeout(function () {
								$('#messageInput').removeClass('has-error');
								$(".message-limit").fadeOut();
							}, 3000);
						}
					} else {
						if (data[1] == "defined") {
							$('#user_pb').attr({
								style: 'display:none;'
							});
							$('.message-block-container > span').html('You are blocked');
						} else if (data[1] == "undefined") {
							$('.message-block-container > span').html('You have blocked this user');
						}
						$('.message-block-container').attr({
							style: 'display:block;'
						});

					}
				},
				error: function () {
					console.log("error");
				}

			});
		}
	} else {
		$("#messageInput").val("");
		$('#messageInput').addClass('has-error');
		//$(".message-limit").html(Yii.t('app', "Message Cannot be Empty"));
		$(".message-limit").fadeIn();
		setTimeout(function () {
			$('#messageInput').removeClass('has-error');
			$(".message-limit").fadeOut();
		}, 3000);
	}
}