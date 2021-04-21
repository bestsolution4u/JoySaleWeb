// Frontend
if (!(globalSize)) {
	var globalSize = new Array();
}
var ajaxcart = 1;
var contactAjax = 1, checkoutAjax = 1; reviewAjax = 1;
var productImage = 0, addImage = 0, generateCouponAjax = 1, addImageError = 0;
var mapClick = 1;
var offercheck = 1;
var followval = 1;
var unfollowval = 1;
var mobile_mapClick = 1;
var rating = 0, urgent = 0, ads = 0; PriceValue = "0;5000";//SliderPrice  
var lth = 0, htl = 0;//price filter
var last24hrs=0, last7days=0, last30days=0,all=0,prodcond=[];//posted within
var posted_within='';
var productcond=[];
var specials = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
var alpha = /[a-zA-Z]/gi;
var numric = /[0-9]/gi;
var mailcheck = 1;
var locationTracker = 1;
var likeAjax = 1, dislikeAjax = 1;
var messageUserScrollPosition = 0;
var mob_check = 1;
var removeimg = [];
var radioFilter=[];
var checkFilter=[];

var onLoadSearchClick = 1;

$("#offer-form").hide();

var onLocMain = document.getElementById('pac-input').value;
var onLoadLocation = (onLocMain != "") ? 1 : 0; 

var onLoadSearchData = $('input[name=search]').val();
var onLoadSearchFlag = ($.trim(onLoadSearchData) != "") ? 1 : 0; 

$(document).on('mouseover', '.action-star', function () {
	var onStar = $(this).data('star');
	var starSelector = ".star" + onStar;
	$('.action-star').removeClass('g-color');
	$('.action-star').addClass('gray');
	$(starSelector).removeClass('gray');
	$(starSelector).addClass('g-color');
});


function start_image_upload()
{
	//alert('testing');
	//return false;
	var inp = document.getElementById('image_file');

	uploadedfiles = $("#uploadedfiles").val();


	var maxsize=(inp.files[0].size);
   
    if(maxsize > 2000000) {
       $("#image_error").show();
		$("#image_error").html(yii.t('app',"Image size doesn't exceed 2MB."));
		setTimeout(function() {
			$("#image_error").slideUp();
			$('#image_error').html('');
		}, 5000);
		return false;
    };
	if(uploadedfiles!="")
	{
		uploaded = jQuery.parseJSON(uploadedfiles);
		uploadedlen = uploaded.length;
	}
	else 
	{
		uploadedlen = 0;
	}
	imagesarr = [];
	var i = 0, len = parseInt(inp.files.length,10), img, reader, file;
	j=parseInt(document.getElementById('count').value,10);

	var filePath = inp.value;

	var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
	if(!allowedExtensions.exec(filePath)){
		$("#image_error").show();
		$("#image_error").html(yii.t('app',"Upload only image file"));
		setTimeout(function() {
			$("#image_error").slideUp();
			$('#image_error').html('');
		}, 5000);
		return false;
	}


	remainfiles = parseInt(10) - parseInt(uploadedlen);
	if(len == 0 ){
		$("#image_error").show();
		$("#image_error").html(yii.t('app',"Please Select Image"));
		setTimeout(function() {
			$("#image_error").slideUp();
			$('#image_error').html('');
		}, 5000);
		return false;
	}
	let uploadfiles= j +len;
	if(uploadfiles>5 ){
		$("#image_error").show();
		$("#image_error").html(yii.t('app',"You can upload 5 images only"));
		setTimeout(function() {
			$("#image_error").slideUp();
			$('#image_error').html('');
		}, 5000);
		return false;
	}
	formdata = new FormData();
	for ( ; i < len; i++ ) {
		file = inp.files[i];

		if (!!file.type.match(/image.*/)) {
			document.getElementById('count').value=(j+len);

			if ( window.FileReader ) {
				reader = new FileReader();
				reader.onloadend = function (e) { 
                    //showUploadedItem(e.target.result, file.fileName);
                };
                reader.readAsDataURL(file);
            }
            if (formdata) {
            	


            	formdata.append("images[]", file);
            }
        }   
    }

    var d=parseInt(document.getElementById('count').value,10);

    if (formdata) {
    	$.ajax({
            url: baseUrl+'/products/startfileupload/', // point to server-side PHP script 
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
            beforeSend: function() {
            	$("#loadingimg").show();
            	$(".loading").css("display","block");
            	$("#startuploadbtn").attr("disabled","true");
            },                  
            success: function (res) {
                //alert(res);
                $("#imagenames").html("");
                $("#loadingimg").hide();
                if ($.trim(res)=="error") {
                	$(".blog_img_error").show();
                	$(".blog_img_error").html(yii.t('app',"The file is too big"));
                	setTimeout(function() {
                		$(".blog_img_error").slideUp();
                		$('.blog_img_error').html('');
                	}, 4000);
                	$("#startuploadbtn").removeAttr("disabled");
                	$("#blogfile").val("");                        
                }
                else
                {
                	result = res.split("***");
                	inputfiles = $("#uploadedfiles").val();
                	if(inputfiles=="")
                		$("#uploadedfiles").val(result[1]);
                	else
                	{
                		newfiles = result[1].replace('[','');
                		existfiles = $("#uploadedfiles").val();
                		existfiles = existfiles.replace(']','');
                		$("#uploadedfiles").val(existfiles+','+newfiles);
                	}
                	$(".blog_images").append(result[0]);
                	$("#startuploadbtn").removeAttr("disabled");
                	$("#loadingimg").hide();
                	$(".loading").css("display","none");
                	$("#uploadfile").val("");
                }
            }
        });
    }  
}

function dosearch() {

	var searchval = $('input[name=search]').val();
	searchval = searchval.trim();
	gotogetLocationData();
	if (searchval == '') {
		return false;
	}
}

$(document).on('mouseout', '.action-star', function () {
	var selectedStar = $('.ratting-stars').val();
	$('.action-star').removeClass('g-color');
	$('.action-star').addClass('gray');
	if (selectedStar != '0') {
		var starSelector = ".star" + selectedStar;
		$(starSelector).removeClass('gray');
		$(starSelector).addClass('g-color');
	}
});

$(document).on('click', '.action-star', function () {
	var onStar = $(this).data('star');
	var starSelector = ".star" + onStar;
	$('.action-star').removeClass('g-color');
	$('.action-star').addClass('gray');
	$(starSelector).removeClass('gray');
	$(starSelector).addClass('g-color');
	$('.ratting-stars').val(onStar);
});

function updateReview() {
	var reviewStars = $('.ratting-stars').val();
	var reviewTitle = $('.ratting-title').val();
	var reviewDescription = $('.ratting-textarea').val();

	if (reviewStars == '0' || reviewTitle == '' || reviewDescription == '') {
		$('.review-error').show();
		setTimeout(function () {
			$(".review-error").fadeOut();
		}, 3000);
		return;
	}

	if (reviewAjax == 1) {
		reviewAjax = 0;
		var reviewOrderId = $('.review-order-id').val();
		var reviewId = $('.review-id').val();
		$.ajax({
			url: baseUrl+'/buynow/updatereview',
			type: "POST",
			dataType: "html",
			data: {
				'reviewStars': reviewStars, 'reviewTitle': reviewTitle, 'reviewDescription': reviewDescription,
				'reviewOrderId': reviewOrderId, 'reviewId': reviewId
			},
			beforeSend: function () {
				$('.review-btn').html(yii.t('app','Please wait')+'.......');
			},
			success: function (responce) {

			//	alert(responce);

			var starsCode = generateReviewStarsCode(reviewStars);
			if (responce == '1') {
				$('.review-stars-container').remove();
				$('.order-detail-name-header').append(starsCode);
				$('.review-content-heading').html(reviewTitle);
				$('.review-content-description').html(reviewDescription);
					//$('#write-review-modal').hide();
					window.location.reload();
				} else {
					$('.order-detail-name-header').append(starsCode);
					$('.review-append-container').html(responce);
					$('.write-review-new-link').remove();
				//	$('#write-review-modal').hide();
				window.location.reload();
			}
			reviewAjax = 1;
			$('.review-btn').html('Submit');
		},
		error: function(){
			console.log("error");
		}
	});
	}
}


function generateReviewStarsCode(stars) {
	var starsCode = '';
	starsCode += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding review-stars-container">'
	+ '<div class="write-review-1">';
	$orderRatting = stars;
	for ($i = 0; $i < $orderRatting; $i++) {
		starsCode += '<span class="g-color fa fa-star"></span>';
	}
	if ($i != 5) {
		for (; $i < 5; $i++) {
			starsCode += '<span class="gray fa fa-star"></span>';
		}
	}
	starsCode += '</div></div>';

	return starsCode;
}

$(document).on('keyup', '.card-cvv, .card-number', function () {
	var $th = $(this);
	$th.val($th.val().replace(/[^0-9]/g, function (str) {
		return '';
	}));
});
$(document).on('keyup', '.card-first-name, .card-last-name', function () {
	var $th = $(this);
	$th.val($th.val().replace(/[^a-zA-Z]/g, function (str) {
		return '';
	}));
});
$(document).on('click', '.revieworder-li', function () {
	if ($('.revieworder-li').hasClass('active')) {
		return false;
	} else {
		$('.paymentdetails-li').removeClass('active');
		$('.revieworder-li').addClass('active');
		$('.payment-details').hide();
		$('.revieworder-details').fadeIn();
		return false;
	}
});
$(document).on('click', '.dropdown-toggle.profile', function () {
	if ($('.dropdown.profile-drop-li').hasClass('open')) {
		$('.dropdown.profile-drop-li').removeClass('open');
	} else {
		$('.dropdown.profile-drop-li').addClass('open');
	}
});
$(document).on('click', '#alldevice', function () {
	$.ajax({
		url: yii.urls.base + '/admin/action/cleardevicetoken/',
		type: "post",
		dataType: "html",
		data: { 'type': 'all' },
		success: function (responce) {
			$("#devicesuccess").show();
			$("#devicesuccess").html(yii.t('app',"Device token cleared successfully"));
			setTimeout(function () {
				$("#devicesuccess").fadeOut();
			}, 3000);
		}
	});
});
$(document).on('click', '#iosdevice', function () {
	$.ajax({
		url: yii.urls.base + '/admin/action/cleardevicetoken/',
		type: "post",
		dataType: "html",
		data: { 'type': 'ios' },
		success: function (responce) {
			$("#devicesuccess").show();
			$("#devicesuccess").html(yii.t('app',"Device token cleared successfully"));
			setTimeout(function () {
				$("#devicesuccess").fadeOut();
			}, 3000);
		}
	});
});
$(document).on('click', '#androiddevice', function () {
	$.ajax({
		url: yii.urls.base + '/admin/action/cleardevicetoken/',
		type: "post",
		dataType: "html",
		data: { 'type': 'android' },
		success: function (responce) {
			$("#devicesuccess").show();
			$("#devicesuccess").html(yii.t('app',"Device token cleared successfully"));
			setTimeout(function () {
				$("#devicesuccess").fadeOut();
			}, 3000);
		}
	});
});
$(document).on('click', '.smlght', function () {
	$('.smlght').removeClass('active');
	$(this).addClass('active');
	var srcToChange = $(this).data("img-src");
	// $('#fullimgtag').attr('src',srcToChange);
	$('#image').css('background-image', 'url(' + srcToChange + ')');
	return false;
});
$(document).on('click', '.payment-side-menu ul li', function () {
	var previouspage = $('.payment-side-menu ul li.active').data("page");
	$('.side-menu').removeClass('active');
	$(this).addClass('active');
	var pagenumber = $(this).data("page");
	$('.page-' + previouspage).hide();
	$('.page-' + pagenumber).show();
	return false;
});

$(document).on('click', '.new-coupon-link', function () {
	$('.new-coupon-link').hide();
	$(".coupon-code").hide();
	$('.couponValue').val('');
	$(".generate-coupon-container").fadeIn(1500);
});

$(document).on('keyup', '#Sitesettings_apiPassword', function () {
	$('#show_apipassword').val($('#Sitesettings_apiPassword').val());
});

$(document).on('keyup', '#show_apipassword', function () {
	$('#Sitesettings_apiPassword').val($('#show_apipassword').val());
});

$(document).on('keyup', '.option, .quantity, .price', function () {
	if ($('.option-add-btn').is(':disabled')) {
		$('.option-add-btn').removeAttr('disabled');
	}
});


$(document).on('change', '#Users_phonevisible', function () {
   // alert("hi");
   userid = $("#userId").val();
   if ($('#Users_phonevisible').is(':checked')) {
   	enablestatus = "1";
   }
   else {
   	enablestatus = "0";
   }
   $.ajax({
   	url: baseUrl+'/user/makephonevisible/',
   	type: "POST",
   	dataType: "html",
   	ContentType :'text/html',
   	data: { 'userid': userid, 'enablestatus': enablestatus },
   	success: function (responce) {
			//alert(responce);
		},
		error: function(err){
			console.log(err);
		}
	});
});
$('#nearmemodals').on('shown.bs.modal', function () {
	//console.log("On Modal-1");
	var currCenter = map.getCenter();
	google.maps.event.trigger(map, "resize");
	map.setCenter(currCenter);
});
$('#nearmemodals').on('shown', function () {
	//console.log("On Modal-2");
	var currCenter = map.getCenter();
	google.maps.event.trigger(map, "resize");
	map.setCenter(currCenter);
});



$(document).on('change', '#Products_category', function () {
		var productPropertyUpdate = 0;
	productId = $('#productId').val();
	var selectedCategory = $('#Products_category').val();
	var giving_away = $("#giving_away").val();

	//console.log('Products_category on change call');
//	alert(selectedCategory);
if (productPropertyUpdate == 0) {
	productPropertyUpdate = 1;
	$.ajax({
		url: baseUrl+'/products/productproperty/',
		type: "post",
		data: { 'selectedCategory': selectedCategory, 'givingAway': giving_away},
		dataType: "html",
		success: function (responce) {

			//console.log(responce);
			//return false;

			$('.instant-buy-details').fadeOut('fast');
			responce = responce.trim();
			propertyData = eval(responce);

			//alert(propertyData[3]);
			

			if (propertyData[0] == 0) {
				$('.dynamicProperty').html(propertyData[1]);
				$('.dynamic-section').hide();
			} else {
				$('.dynamicProperty').html(propertyData[1]);
				$('.dynamic-section').show();
			}

			if(propertyData[3] == 0)
			{
				myFunction();
			}

			$('#Products_subCategory').html(propertyData[2]);
			$('#Products_sub_subCategory').html(propertyData[3]);
			$('#Products_sub_subCategory_head').html(Yii.t('app','Select child category'));
			productPropertyUpdate = 0;
				//alert(propertyData[2]);
			}
		});
}
});



$(document).on('change', '#Products_category', function () {
	var productCategory = document.getElementById("Products_category").value;
	//var ProductsubCategory = document.getElementById("Products_subCategory").value;
	var productId = document.getElementById("productId").value;

	//Get instant data from the posted values.
	$.ajax({
           type: "POST",
           url: baseUrl+'/products/getfilter',
           data: { 
           		'cat':productCategory,
           		//'subcat':ProductsubCategory,
           		'productId':productId
           		}, // serializes the form's elements.
           success: function(data)
           {
						// console.log(data);
						// return false;
               	if(data==0)
				{
					$("#showsubfield").html("");
				}
				else
				{
					$("#showsubfield").html(data);
				}
				return false;
           }
    });
});

$(document).on('change', '#Products_subCategory', function () {
	//var productCategory = document.getElementById("Products_category").value;
	var ProductsubCategory = document.getElementById("Products_subCategory").value;
	var productId = document.getElementById("productId").value;

	//Get instant data from the posted values.
	$.ajax({
           type: "POST",
           url: baseUrl+'/products/getsubcategory',
           data: { 
           		//'cat':productCategory,
           		'subcat':ProductsubCategory,
           		'productId':productId
           		}, // serializes the form's elements.
           success: function(data)
           {
						 
               	if(data==0)
				{
					$("#showField").html("");
				}
				else
				{
					$("#showField").html(data);
				}
				return false;
           }
    });
});






$(document).on('change', '#Products_sub_subCategory', function () {

	var productCategory = document.getElementById("Products_category").value;
	var ProductsubCategory = document.getElementById("Products_subCategory").value;
	var Productsub_subCategory = document.getElementById("Products_sub_subCategory").value;
	var productId = document.getElementById("productId").value;

	//Get instant data from the posted values.
	$.ajax({
           type: "POST",
           url: baseUrl+'/products/getsubfilter',
           data: { 
           		'cat':productCategory,
							'subcat':ProductsubCategory,
							'sub_subcat': Productsub_subCategory,
           		'productId':productId
           		}, // serializes the form's elements.
           success: function(data)
           {
						//console.log(data);
						// return false;
						
               	if(data==0)
				{
					$("#showsubfield").html("");
				}
				else
				{
					$("#showsubfield").html(data);
				}
				return false;
           }
    });
});


$(document).on(
	'click',
	'.left-controller',
	function () {
		if (currentLeftClick > 0 && currentRightClick != 0) {
			currentPosition = currentPosition + 80;
			$('.product-figure-list').css({
				"left": currentPosition
			});
			currentLeftClick -= 1;
			currentRightClick -= 1;
		}
		console.log("Left: " + currentLeftClick + " Totalimage: "
			+ totalMoreImage);
	});

$(document).on(
	'click',
	'.right-controller',
	function () {
		if (currentRightClick < totalMoreImage) {
			currentPosition = currentPosition - 80;
			$('.product-figure-list').css({
				"left": currentPosition
			});
			currentRightClick += 1;
			currentLeftClick += 1;
		}
		console.log("Right: " + currentRightClick + " Totalimage: "
			+ totalMoreImage);
	});

$(document).keydown(function (e) {
	var keycode = e.keyCode;

	if (keycode == 27) {
		$('#popup_container').hide();
		$('#popup_container').css({
			"opacity": "0"
		});
		$('#choose-option-popup').hide();
		$('#show-exchange-popup').hide();
		$('#show-invoice-popup').hide();
		$('#show-coupon-popup').hide();
		$('#contact-me-popup').hide();
		$('body').css({
			"overflow": "auto"
		});
	}
});
$(document).on('click', '#Products_instantBuy', function () {
	if ($('#Products_instantBuy').is(':checked')) {
		$('.instant-buy-details').fadeIn('slow');
	} else {
		$('.instant-buy-details').fadeOut('fast');
	}
});
$(document).on('click', '.ly-close, .close-contact', function () {
	$('#popup_container').hide();
	$('#popup_container').css({
		"opacity": "0"
	});
	$('#choose-option-popup').hide();
	$('#show-exchange-popup').hide();
	$('#show-invoice-popup').hide();
	$('#show-coupon-popup').hide();
	$('#contact-me-popup').hide();
	$('.contact-textarea').val('');
	$('body').css({
		"overflow": "auto"
	});
});
$(document).on('keyup',
	'#Products_quantity, .quantity, .price', function () {
		var $th = $(this);
		$th.val($th.val().replace(/[^0-9]/g, function (str) {
			return '';
		}));
	});
$(document).on('mouseup', '#popup_container', function (e) {
	var container = $(".popup");

	if (!container.is(e.target) // if the target of the click isn't the
		// container...
		&& container.has(e.target).length === 0) // ... nor a descendant
	// of the container
{
	container.hide();
	$('#popup_container').hide();
	$('#popup_container').css({
		"opacity": "0"
	});
	$('#choose-option-popup').hide();
	$('#show-exchange-popup').hide();
	$('#show-coupon-popup').hide();
	$('#show-invoice-popup').hide();
	$('#contact-me-popup').hide();
	$('body').css({
		"overflow": "auto"
	});
}
});

$(document).on('click', '.chat-link.userNameLink', function (e) {
	//alert(0);
	e.preventDefault();
	var userId = $(this).data("userid");
	//alert(userId);
	var userRead = parseInt($(this).data("userread"));
	if (userId != "") {
		messageUserScrollPosition = $('.message-vertical-tab-container').scrollTop();
		console.log("messageUserScrollPosition: " + messageUserScrollPosition);
		$.ajax({
			url: baseUrl+'/message/indexx/',
			type: "post",
			dataType: "html",
			data : {'id':userId},
			ContentType :'text/html',
			success: function (responce) {
				//alert(responce);
				//console.log(responce);
				$('#content').html(responce);
				$('.message-vertical-tab-container').scrollTop(messageUserScrollPosition);
				if (userRead == 1) {
					var readCount = parseInt($('.message-count').html());
					if (readCount == 1) {
						$('.message-count').addClass('message-hide');
					} else {
						readCount -= 1;
						$('.message-count').html(readCount);
					}
					liveCount -= 1;
				}
				if (!$('.chat-message-container').is(':visible')) {
					$('.message-vertical-tab-container').hide();
					$('.chat-message-container').show();
				}
			},
			error: function(){
				console.log('error');
			}
		});
	} else {
		console.log("userId Empty");
	}
});
// function to set the height on fly
function autoHeight() {
	$('#content').css('min-height', 0);
	$('#content').css('min-height', (
		$(document).height()
		- $('.joysale-menu').height()
		- $('.footer').height()
		));
}

// onDocumentReady function bind
$(document).ready(function () {

	$('#MyOfferForm_offer_rate').bind("cut copy paste", function (e) {
		e.preventDefault();
	});

	$('#Products_price').bind("cut copy paste", function (e) {
		e.preventDefault();
	});

	$('#Sitesettings_searchList').bind("cut copy paste", function (e) {
		e.preventDefault();
	});


	autoHeight();

});

window.setInterval(function () {
	if ($('body').css('overflow') == 'auto') {
		$('body').css('overflow', 'visible');
	}
}, 1000);

// onResize bind of the function
$(window).resize(function () {
	autoHeight();
});

function loadChatDetails(userId) {
	if (userId != "") {
		$.ajax({
			url: baseUrl+'/message/' + userId,
			type: "post",
			dataType: "html",
			success: function (responce) {
				$('#content').html(responce);
			}
		});
	} else {
		console.log("userId Empty");
	}
	return false;
}


function paymentMethod() {
	$('.revieworder-head ul li').removeClass('active');
	$('.paymentdetails-li').addClass('active');
	$('.revieworder-details').hide();
	$('.payment-details').fadeIn();
}

function confirmModal(type, data, param) {

	if (type == 'method') {

		var button = '<a class="margin-bottom-0 post-btn" href="#"'
		+ 'onclick="$(\'#confirm_popup_container\').modal(\'hide\');' + data + '(\'' + param + '\');">'
		+ 'ok' + '</a>';

	} else if (type == 'link') {
		if (param == 'fullLink') {
			var callUrl = data;
		} else {
			var callUrl = baseUrl + data + param;
		}
		var button = '<a href="' + callUrl + '" ' + 'class="post-btn margin-bottom-0" >' +
		'ok' + '</button>';
	}

	$('.confirm-btn').html(button);
	$('#confirm_popup_container').modal('show')
	

}

function closeConfirm() {
	$('#confirm_popup_container').modal('hide');
}


function deleteShipping(id) {
	window.location.href = baseUrl+'/buynow/delete/' + id;
}

function deleteItem(id) {
	window.location.href = baseUrl+'/products/delete/' + id;
}

function soldItems(id, value) {
	$.ajax({
		type: 'POST',
		url: baseUrl+'/products/solditem/',
		data: {
			'id': id,
			'value': value
		},
		success: function (data) {

			var appendText = '';
			if (value == 0) {
				appendText = '<a href="javascript: void(0);" data-loading-text="Posting..." id="load" data-toggle="modal" ' + 'class="sold-btn" onclick="soldItems(\'' + id + '\', \'1\')">' +yii.t('app','Mark as sold')+ '</a>';
			} else {
				appendText = '<a href="javascript: void(0);" data-loading-text="Posting..." id="load" data-toggle="modal" ' + 'class="sold-btn sale-btn" onclick="soldItems(\'' + id + '\', \'0\')">' +yii.t('app','Back to sale')+ '</a>';
			}
			appendText += '<a data-target="#" data-toggle="modal" href="javascript:void(0);" class="delete-btn" ' + 'onclick="confirmModal(\'method\', \'deleteItem\', \'' + id + '\')">' +yii.t('app','Delete Sale')+ '</a>'
			$('.edit-btn').html(appendText);
			console.log('success');
		}
	});
}

function chatandbuy() {
	var user = $('.logindetails').val();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		$('#popup_container').show();
		$('#popup_container').css({
			"opacity": "1"
		});
		$('#contact-me-popup').show();
		$('body').css({
			"overflow": "hidden"
		});
	}
}

function addshippingContainer() {
	var shippingSelect = $('.country').val();
	if (shippingSelect != '') {
		var shipdetails = shippingSelect.split("-");
		if (shippingArray.indexOf(shipdetails[0]) < 0) {
			shippingArray.push(shipdetails[0]);
			var output = "";
			output += '<ul class="shipping-details-' + shipdetails[0] + '">';
			output += '<li>' + shipdetails[1] + '</li>';
			output += '<li><input type="text" value="" name="Products[shipping][' + shipdetails[0]
			+ ']" style="margin-left: 3px;" class="form-control ship-to-' + shipdetails[0]
			+ '" onkeypress="return isNumber(event)" maxlength="9"/></li>';
			output += '<li><p onclick="delectShipping(' + shipdetails[0]
			+ ')"><i class="fa fa-trash-o"></i></p></li>';
			output += '</ul>';
			$('.shipping-details').append(output);
		} else {

		}
	}
	$(".country option:selected").removeAttr("selected");
}

function delectShipping(shippingId) {
	var deleteSelector = ".shipping-details-" + shippingId;
	$(deleteSelector).remove();
	var deleteIndex = shippingArray.indexOf(shippingId.toString());
	console.log('shippingId: ' + shippingId + " index: " + deleteIndex);
	console.log(shippingArray);
	shippingArray.splice(deleteIndex, 1);
	$(".country option:selected").removeAttr("selected");
	console.log(shippingArray);
}

function composeOptions() {
	// Products[productCondition]
	var alpha = /[a-zA-Z]/gi;
	$('#Products_sizeOption_em_').hide();
	var size = $('.option').val().trim();
	var quantity = $('.quantity').val().trim();
	var price = $('.price').val().trim();
	var output = '';
	var quantity = quantity.replace(/\s/g, "");
	var price = price.replace(/\s/g, "");
	$('.quantity').val(quantity);
	$('.price').val(price);
	if (specials.test(size) || specials.test(quantity) || specials.test(price)) {
		$('#Products_sizeOption_em_').html(
			yii.t('admin', 'Special Characters not allowed.'));
		$('#Products_sizeOption_em_').show();
		$('.option-add-btn').attr('disabled', 'disabled');
		setTimeout(function () {
			$('#Products_sizeOption_em_').fadeOut();
		}, 5000);
		return false;
	}

	if (alpha.test(quantity) || alpha.test(price)) {
		$('#Products_sizeOption_em_').html(
			yii.t('admin', 'Only numbers are allowed for quantity and price.'));
		$('#Products_sizeOption_em_').show();
		$('.option-add-btn').attr('disabled', 'disabled');
		setTimeout(function () {
			$('#Products_sizeOption_em_').fadeOut();
		}, 5000);

		return false;
	}

	if (size == '' || quantity == '' || price == '') {
		$('#Products_sizeOption_em_').html(
			yii.t('admin', 'Variant or Quantity or Price cannot be empty'));
		$('#Products_sizeOption_em_').show();
		$('.option-add-btn').attr('disabled', 'disabled');
		setTimeout(function () {
			$('#Products_sizeOption_em_').fadeOut();
		}, 5000);
		return false;
	} else if (size.length > 80) {
		$('#Products_sizeOption_em_').html(
			yii.t('admin', 'Variant should not exceed 80 characters'));
		$('#Products_sizeOption_em_').show();
		$('.option-add-btn').attr('disabled', 'disabled');
		setTimeout(function () {
			$('#Products_sizeOption_em_').fadeOut();
		}, 5000);
		return false;
	}

	if (price < 1 || quantity < 1) {
		$('#Products_sizeOption_em_').html(
			yii.t('admin', 'Price and Quantity should be greater than zero'));
		$('#Products_sizeOption_em_').show();
		$('.option-add-btn').attr('disabled', 'disabled');
		setTimeout(function () {
			$('#Products_sizeOption_em_').fadeOut();
		}, 5000);
		return false;
	}
	if (globalSize.indexOf(size) == -1) {
		globalSize.push(size);
		var sizeClass = size.replace(/\s/g, "-");
		output += '<div class="option-' + sizeClass + '">';
		output += '<input class="disp-size" type="text" style="width: 100px; margin-right: 4px;" name="Products[productOptions]['
		+ size
		+ '][option]" value="'
		+ size
		+ '" readonly onfocus="this.blur()">';
		output += '<input class="disp-qty" type="text" style="width: 100px; margin-right: 4px;" name="Products[productOptions]['
		+ size
		+ '][quantity]" value="'
		+ quantity
		+ '" readonly onfocus="this.blur()">';
		output += '<input class="disp-price" type="text" style="width: 100px; margin-right: 4px;" name="Products[productOptions]['
		+ size
		+ '][price]" value="'
		+ price
		+ '" readonly onfocus="this.blur()">';
		output += '<span class="display-delete" style="cursor: pointer; color: rgb(255, 51, 51); font-weight: bold; margin-left: 18px;" onclick="deleteOption(\''
		+ size + '\')">X</span></div>';
		$('.added-options').append(output);
	} else {
		$('#Products_sizeOption_em_').html('Varient already exist');
		$('#Products_sizeOption_em_').show();
		setTimeout(function () {
			$('#Products_sizeOption_em_').fadeOut();
		}, 5000);
	}
	$('.option').val('');
	$('.quantity').val('');
	$('.price').val('');
	$('.option').focus();
	return false;
}

function deleteOption(size) {
	var deleteIndex = globalSize.indexOf(size);
	if (deleteIndex != -1) {
		globalSize.splice(deleteIndex, 1);
		var sizeClass = size.replace(/\s/g, "-");
		$('.option-' + sizeClass).remove();
	}
}

function postcomment() {
	var comment = $('.commenter-text').val();
	var itemId = $('.item-id').val();
	var commentCount = $("#commentCount").val();
	$('.commenter-button').attr('disaled');
	if (comment != '') {

		$.ajax({
			url: baseUrl+'/products/savecomment',
			type: "post",
			dataType: "html",
			data: {
				'comment': comment,
				'itemId': itemId
			},
			beforeSend: function () {
				$('.commenter-button').html('Posting...');
			},
			success: function (responce) {
				$('.commenter-button').html('Post');
				$('.commenter-button').removeAttr('disaled');

				var output = responce.trim();
				if (output) {
					$('.comment ol').append(output);
					$('.commenter-text').val('');
					var incCmnt = Number(commentCount) + Number(1);
					$("#commentcnt").html('(' + incCmnt + ')');
					$("#commentCount").val(incCmnt);
				} else {
					$('.comment-error').html('Please try later...');
					$(".comment-error").fadeIn();
					setTimeout(function () {
						$(".comment-error").fadeOut();
					}, 3000);
				}
			}
		});
	} else {
		$('.comment-error').html(yii.t('app','Comment cannot be empty'));
		setTimeout(function () {
			$(".comment-error").html("")
		}, 3000);
		$(".comment-error").css('display', 'inline-block');

	}
}

function selectSize() {
	var size = $(".item-qty").val();
	var price = $(".item-qty option:selected").attr("pricetag")
	$('.buy-price').html(price);

}

function selectedOptionPrice() {
	var price = $(".item-qty option:selected").attr("pricetag");
	$('.option-price').css({
		"opacity": "1"
	});
	$('.option-price-value').html(price);
}

function addToCart() {
	var itemId = $('.item-id').val();
	var option = $('.item-option').val();
	var selectedOption = "";
	$('.carterror').html('');
	if (option == 1) {
		selectedOption = $('.item-qty').val();
		if (selectedOption == '') {
			$('.carterror').html(yii.t('app','Select a option to continue'));
			return false;
		}
	}
	if (ajaxcart == 1) {
		ajaxcart = 0;
		$.ajax({
			url: yii.urls.base + '/item/products/addtocart',
			type: "post",
			dataType: "html",
			data: {
				'selectedOption': selectedOption,
				'itemId': itemId
			},
			beforeSend: function () {
				$('.add-cart').html('Adding...');
			},
			success: function (responce) {
				$('.add-cart').html('Add to cart');
				var output = responce.trim();
				if (output) {
					window.location = output;
				} else {
					$('.carterror').html('Please try later...');
				}
				ajaxcart = 1;
			}
		});
	}
}

function updatecart(merchantId, itemId) {
	var cartGrid = '.shop' + merchantId;
	var qtySelector = '.cart-qty-' + itemId;
	var selectedQty = $(qtySelector).val();

	if (typeof (qtyCart) == 'undefined') {
		qtyCart = new Array();
	}

	if (typeof (qtyCart[itemId]) == 'undefined' || qtyCart[itemId] == 0) {
		qtyCart[itemId] = 1;
		$.ajax({
			url: yii.urls.base + '/cart',
			type: "post",
			dataType: "html",
			data: {
				'selectedQty': selectedQty,
				'itemId': itemId,
				'merchantId': merchantId
			},
			beforeSend: function () {

			},
			success: function (responce) {

				var output = responce.trim();
				if (output != 'false') {
					$(cartGrid).html(output);
				}
				qtyCart[itemId] = 0;
			}
		});
	}
}


function fillInAddress() {
	var lat = (document.getElementById('latitude'));
	var place = autocomplete.getPlace();
	var latitude = place.geometry.location.lat();
	var longitude = place.geometry.location.lng();
	var placeDetails = place.address_components;
	var count = placeDetails.length;
	var country = "";
	while (count != 0 && country == "") {
		if (placeDetails[count - 1].types[0] == "country") {
			country = placeDetails[count - 1].short_name;
			$('#shippingcountry').val(country);
		}
		count--;
	}

	$("#latitude").val(latitude);
	$("#longitude").val(longitude);
}


function getLatLong(initialLoad){
	initialLoad = typeof initialLoad !== 'undefined' ? initialLoad : 0;
	var baseurl = baseUrl;
	var grid = document.querySelector('#fh5co-board');
	var geocoder = new google.maps.Geocoder();
	// console.log(grid);
	// console.log(baseurl);
	// document.getElementById("pac-input").value = "chennai";
	var kilometer = 25;
	var lat;
	var lon;

	$('.search-location').hide();
	$('.btn-worldwide').hide();
	$('.loading-btn').show();
	$('.loader-front').hide();
	$('.loader-back').show();

	if (initialLoad == 0){
		console.log("hi");
		window.google = window.google || {};
		google.maps = google.maps || {};
		(function() {

		  function getScript(src) {
		    var s = document.createElement('script');

		    s.src = src;
		    document.body.appendChild(s);
		  }

		  var modules = google.maps.modules = {};
		  google.maps.__gjsload__ = function(name, text) {
		    modules[name] = text;
		  };

		  google.maps.Load = function(apiLoad) {
		    delete google.maps.Load;
		    apiLoad([0.009999999776482582,[[["https://mts0.googleapis.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"m@281000000",["https://mts0.google.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.google.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026"]],[["https://khms0.googleapis.com/kh?v=162\u0026hl=en-US\u0026","https://khms1.googleapis.com/kh?v=162\u0026hl=en-US\u0026"],null,null,null,1,"162",["https://khms0.google.com/kh?v=162\u0026hl=en-US\u0026","https://khms1.google.com/kh?v=162\u0026hl=en-US\u0026"]],[["https://mts0.googleapis.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"h@281000000",["https://mts0.google.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.google.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026"]],[["https://mts0.googleapis.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"t@132,r@281000000",["https://mts0.google.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.google.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026"]],null,null,[["https://cbks0.googleapis.com/cbk?","https://cbks1.googleapis.com/cbk?"]],[["https://khms0.googleapis.com/kh?v=84\u0026hl=en-US\u0026","https://khms1.googleapis.com/kh?v=84\u0026hl=en-US\u0026"],null,null,null,null,"84",["https://khms0.google.com/kh?v=84\u0026hl=en-US\u0026","https://khms1.google.com/kh?v=84\u0026hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt?hl=en-US\u0026","https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]],[["https://mts0.googleapis.com/vt?hl=en-US\u0026","https://mts1.googleapis.com/vt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt?hl=en-US\u0026","https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]]],["en-US","US",null,0,null,null,"https://maps.gstatic.com/mapfiles/","https://csi.gstatic.com","https://maps.googleapis.com","https://maps.googleapis.com",null,"https://maps.google.com"],["https://maps.gstatic.com/maps-api-v3/api/js/19/2","3.19.2"],[630100503],1,null,null,null,null,null,"initialize",null,null,1,"https://khms.googleapis.com/mz?v=162\u0026",null,"https://earthbuilder.googleapis.com","https://earthbuilder.googleapis.com",null,"https://mts.googleapis.com/vt/icon",[["https://mts0.googleapis.com/vt","https://mts1.googleapis.com/vt"],["https://mts0.googleapis.com/vt","https://mts1.googleapis.com/vt"],null,null,null,null,null,null,null,null,null,null,["https://mts0.google.com/vt","https://mts1.google.com/vt"],"/maps/vt",281000000,132],2,500,["https://geo0.ggpht.com/cbk","https://g0.gstatic.com/landmark/tour","https://g0.gstatic.com/landmark/config","","https://www.google.com/maps/preview/log204","","https://static.panoramio.com.storage.googleapis.com/photos/",["https://geo0.ggpht.com/cbk","https://geo1.ggpht.com/cbk","https://geo2.ggpht.com/cbk","https://geo3.ggpht.com/cbk"]],["https://www.google.com/maps/api/js/master?pb=!1m2!1u19!2s2!2sen-US!3sUS!4s19/2","https://www.google.com/maps/api/js/widget?pb=!1m2!1u19!2s2!2sen-US"],1,0], loadScriptTime);
		  };
		  var loadScriptTime = (new Date).getTime();
		 
		})();
	} 

	  // Try HTML5 geolocation
	  if(navigator.geolocation) {
       
	    navigator.geolocation.getCurrentPosition(function(position) {

	      var pos = new google.maps.LatLng(position.coords.latitude,
	                                       position.coords.longitude);
	      	
			lat = pos.lat();
			lon = pos.lng();
			if (initialLoad == 0){
				var latlng = new google.maps.LatLng(lat, lon);				
			     
			    //marker.setPosition(latlng)
			    geocoder.geocode({'latLng': latlng}, function(results, status) {
						
			        if (status == google.maps.GeocoderStatus.OK) {
						console.log(results[0].formatted_address);
			          if (results[1]) {
						$('.loader-front').show();
						$('.loader-back').hide();

			          		document.getElementById("pac-input").value = results[0].formatted_address;
			          		document.getElementById("pac-input2").value = results[0].formatted_address;
			               //document.getElementById("pac-input1").value = results[0].formatted_address;
			               //document.getElementById("pac-input2").value = results[0].formatted_address;
			               document.getElementById("map-latitude").value = lat;
			               document.getElementById("map-longitude").value = lon;
			               //map.setCenter(latlng); // Set map center to marker position
			               //map.setZoom(10);

			          } else {
						$('.loader-front').show();
						$('.loader-back').hide();
			            alert("No results found");
			          }
			        } else {
						$('.loader-front').show();
						$('.loader-back').hide();
						console.log("Geocoder failed due to: " + status);

			        }
			      });
			}else{
				$('.loader-front').show();
				$('.loader-back').hide();
				var latlng = new google.maps.LatLng(lat, lon);
			    ////marker.setPosition(latlng);
			    //map.setCenter(latlng); // Set map center to marker position
	            //map.setZoom(10);
				document.getElementById("map-latitude").value = lat;
				document.getElementById("map-longitude").value = lon;
				getLocationData(1);

				
			}


	    },
        function (error) {
	      
	         if (error.code == error.PERMISSION_DENIED)
	         	{	
				$('.loader-front').show();
				$('.loader-back').hide();
	            console.log("you denied me :-(");
	        	}
	    });

	  }else{
		  console.log('Browser not support Geo Location');
	  }


}

// function getLatLong(initialLoad) {
// 	initialLoad = typeof initialLoad !== 'undefined' ? initialLoad : 0;
// 	var baseurl = baseUrl;
// 	var grid = document.querySelector('#fh5co-board');
// 	var kilometer = 25;
// 	var lat;
// 	var lon;

// 	$('.search-location').hide();
// 	$('.btn-worldwide').hide();
// 	$('.loading-btn').show();
// 	$('.loader-front').hide();
// 	$('.loader-back').show();

// 	if (initialLoad == 0) {
// 		window.google = window.google || {};
// 		google.maps = google.maps || {};
// 		(function () {

// 			function getScript(src) {
// 				var s = document.createElement('script');

// 				s.src = src;
// 				document.body.appendChild(s);
// 			}

// 			var modules = google.maps.modules = {};
// 			google.maps.__gjsload__ = function (name, text) {
// 				modules[name] = text;
// 			};

// 			google.maps.Load = function (apiLoad) {
// 				delete google.maps.Load;
// 				apiLoad([0.009999999776482582, [[["https://mts0.googleapis.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.googleapis.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026"], null, null, null, null, "m@281000000", ["https://mts0.google.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.google.com/vt?lyrs=m@281000000\u0026src=api\u0026hl=en-US\u0026"]], [["https://khms0.googleapis.com/kh?v=162\u0026hl=en-US\u0026", "https://khms1.googleapis.com/kh?v=162\u0026hl=en-US\u0026"], null, null, null, 1, "162", ["https://khms0.google.com/kh?v=162\u0026hl=en-US\u0026", "https://khms1.google.com/kh?v=162\u0026hl=en-US\u0026"]], [["https://mts0.googleapis.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.googleapis.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026"], null, null, null, null, "h@281000000", ["https://mts0.google.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.google.com/vt?lyrs=h@281000000\u0026src=api\u0026hl=en-US\u0026"]], [["https://mts0.googleapis.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.googleapis.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026"], null, null, null, null, "t@132,r@281000000", ["https://mts0.google.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026", "https://mts1.google.com/vt?lyrs=t@132,r@281000000\u0026src=api\u0026hl=en-US\u0026"]], null, null, [["https://cbks0.googleapis.com/cbk?", "https://cbks1.googleapis.com/cbk?"]], [["https://khms0.googleapis.com/kh?v=84\u0026hl=en-US\u0026", "https://khms1.googleapis.com/kh?v=84\u0026hl=en-US\u0026"], null, null, null, null, "84", ["https://khms0.google.com/kh?v=84\u0026hl=en-US\u0026", "https://khms1.google.com/kh?v=84\u0026hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]], [["https://mts0.googleapis.com/vt?hl=en-US\u0026", "https://mts1.googleapis.com/vt?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]], [["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026", "https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]]], ["en-US", "US", null, 0, null, null, "https://maps.gstatic.com/mapfiles/", "https://csi.gstatic.com", "https://maps.googleapis.com", "https://maps.googleapis.com", null, "https://maps.google.com"], ["https://maps.gstatic.com/maps-api-v3/api/js/19/2", "3.19.2"], [630100503], 1, null, null, null, null, null, "initialize", null, null, 1, "https://khms.googleapis.com/mz?v=162\u0026", null, "https://earthbuilder.googleapis.com", "https://earthbuilder.googleapis.com", null, "https://mts.googleapis.com/vt/icon", [["https://mts0.googleapis.com/vt", "https://mts1.googleapis.com/vt"], ["https://mts0.googleapis.com/vt", "https://mts1.googleapis.com/vt"], null, null, null, null, null, null, null, null, null, null, ["https://mts0.google.com/vt", "https://mts1.google.com/vt"], "/maps/vt", 281000000, 132], 2, 500, ["https://geo0.ggpht.com/cbk", "https://g0.gstatic.com/landmark/tour", "https://g0.gstatic.com/landmark/config", "", "https://www.google.com/maps/preview/log204", "", "https://static.panoramio.com.storage.googleapis.com/photos/", ["https://geo0.ggpht.com/cbk", "https://geo1.ggpht.com/cbk", "https://geo2.ggpht.com/cbk", "https://geo3.ggpht.com/cbk"]], ["https://www.google.com/maps/api/js/master?pb=!1m2!1u19!2s2!2sen-US!3sUS!4s19/2", "https://www.google.com/maps/api/js/widget?pb=!1m2!1u19!2s2!2sen-US"], 1, 0], loadScriptTime);
// 			};
// 			var loadScriptTime = (new Date).getTime();

// 		})();
// 	}

// 	// Try HTML5 geolocation
// 	if (navigator.geolocation) {
// 		var geocoder= new google.maps.Geocoder();
// 		navigator.geolocation.getCurrentPosition(function (position) {

// 			var pos = new google.maps.LatLng(position.coords.latitude,
// 				position.coords.longitude);

// 			lat = pos.lat();
// 			lon = pos.lng();
// 			if (initialLoad == 0) {
// 				var latlng = new google.maps.LatLng(lat, lon);

// 				//marker.setPosition(latlng)
// 				geocoder.geocode({ 'latLng': latlng }, function (results, status) {
// 					if (status == google.maps.GeocoderStatus.OK) {
// 						console.log(results[0].formatted_address)
// 						if (results[1]) {
// 							$('.loader-front').show();
// 							$('.loader-back').hide();

// 							document.getElementById("pac-input").value = results[0].formatted_address;
// 							document.getElementById("pac-input2").value = results[0].formatted_address;
// 							//document.getElementById("pac-input1").value = results[0].formatted_address;
// 							//document.getElementById("pac-input2").value = results[0].formatted_address;
// 							document.getElementById("map-latitude").value = lat;
// 							document.getElementById("map-longitude").value = lon;
// 							//map.setCenter(latlng); // Set map center to marker position
// 							//map.setZoom(10);

// 						} else {
// 							$('.loader-front').show();
// 							$('.loader-back').hide();
// 							alert("No results found");
// 						}
// 					} else {
// 						$('.loader-front').show();
// 						$('.loader-back').hide();
// 						alert("Geocoder failed due to: " + status);

// 					}
// 				});
// 			} else {
// 				$('.loader-front').show();
// 				$('.loader-back').hide();
// 				var latlng = new google.maps.LatLng(lat, lon);
// 				////marker.setPosition(latlng);
// 				//map.setCenter(latlng); // Set map center to marker position
// 				//map.setZoom(10);
// 				document.getElementById("map-latitude").value = lat;
// 				document.getElementById("map-longitude").value = lon;
// 				getLocationData(1);
// 			}


// 		},
// 		function (error) {

// 			if (error.code == error.PERMISSION_DENIED) {
// 				$('.loader-front').show();
// 				$('.loader-back').hide();
// 				console.log("you denied me :-(");
// 			}
// 		});

// 	} else {
// 		console.log('Browser not support Geo Location');
// 	}


// }


function sharelocation2() {


	var lat = 9.9252007;
	var lon = 78.11977539999998;
	var str = '@#@';
	var staticMap = lat + str + lon;
	var apiKey = $('#googleapikey').val();
	$('#shareMap').val(staticMap);
	$('#messageForm').submit();
}



function promotionsearch(searchType) {
	if (searchType == 'ads') {
		var hidetype = '.urgent';
		var lth_hidetype = '.lth';
		var htl_hidetype = '.htl'; 
	}
	if (searchType == 'urgent') {
		var hidetype = '.ads';
		var lth_hidetype = '.lth';
		var htl_hidetype = '.htl'; 
	}

	var searchTypeSelector = "." + searchType;
	if ($(searchTypeSelector).is(':checked')) {
		$(hidetype).prop('checked', false);
		$(lth_hidetype).prop('checked', false);
		$(htl_hidetype).prop('checked', false);

		$(lth_hidetype).val('0');
		$(htl_hidetype).val('0');
	} else {
		$(lth_hidetype).prop('checked', false);
		$(htl_hidetype).prop('checked', false);
		$(lth_hidetype).val('0');
		$(htl_hidetype).val('0');   
	} 


	$('.ads-filter').val('0');
	$('.urgent-filter').val('0'); 
	$('.tagfilter_lth').remove();
	$('.tagfilter_htl').remove();

	var searchTypeSelector = "." + searchType;
	var hiddenfieldSelector = searchTypeSelector + "-filter";

	if ($(searchTypeSelector).is(':checked'))
		$(hiddenfieldSelector).val('1');  // checked
	else
		$(hiddenfieldSelector).val('0');

	if(searchType == 'ads')
	{
		var tagText = 'Popular';
	}else{
		var tagText = searchType;
	}
	

	urgent = $('.urgent-filter').val();
	ads = $('.ads-filter').val();

	if(searchType == "ads") {
		$('.tagfilter_urgent').remove();
	} else if (searchType == "urgent"){
		$('.tagfilter_ads').remove();
	} else {
		$('.tagfilter_urgent').remove();
		$('.tagfilter_ads').remove();
	} 

	if($(hiddenfieldSelector).val() == '0')
	{
		$('.tagfilter_'+searchType).remove();
		getLocationData(1);
	} else {
		$('#filterTags').append('<div class="tagParent tagfilter_'+searchType+'"><span class="tagContent">'+yii.t('app',tagText)+'</span><span id="'+searchType+'" onclick="javascript:tagclose(this);" class="tagCloser">x</span></div>');
		getLocationData(1);
	}
	
	//getLocationData(1);
}


function categoriessearch(searchType, catId) {
	var searchTypeSelector = "." + searchType;
	var hiddenfieldSelector = searchTypeSelector + "-filter";

	//if ($(searchTypeSelector).is(':checked'))
	//	$(hiddenfieldSelector).val('1');  // checked
	//else
	//	$(hiddenfieldSelector).val('0');

	//urgent = $('.urgent-filter').val();
	//ads = $('.ads-filter').val();

	getLocationData(1);
}

function filtersearch(searchType, caseid) {
	var searchTypeSelector = "." + searchType;
	var hiddenfieldSelector = searchTypeSelector + "-filter";

	var favorite = [];
	var favorite1 = [];
	var favorite2 = [];
	$.each($("input[name='dropdown[]']:checked"), function(){            
                favorite.push($(this).val());
    });
    var dropdownvalues = favorite.join(",");


    $.each($("input[name='multilevel[]']:checked"), function(){            
                favorite1.push($(this).val());
    });
     var multilevelvalues = favorite1.join(",");

    $.each($("input[name='sliderhiddenattribute[]']"), function(){            
			favorite2.push($(this).val());

    });
    var sliderhiddenattribute = favorite2.join(",");

    if(dropdownvalues != '')
    {
    	var allvals = dropdownvalues+','+multilevelvalues;	
    }else{
    	var allvals = multilevelvalues;	
    }
 
    $.ajax({
   	url: baseUrl+'/site/getfiltervalues/',
   	type: "POST",
   	dataType: "html",
   	ContentType :'text/html',
   	data: { 'filterattribute': allvals },
   	success: function (responce) {

   		$('#Filter-modal').modal('hide');
   		
			$('#advancefilter').html('');
			$.each(JSON.parse(responce), function(idx, obj) {
				$('#advancefilter').append('<div class="tagParent tagfilter_'+obj.id+'"><span class="tagContent">'+yii.t('app',obj.name)+'</span><span class="tagCloser" id="'+obj.id+'" onclick="javascript:tagclosefiltersearch(this);">x</span></div>');
			});
		},
		error: function(err){
			console.log(err);
		}
	});

	//Get values of the filter attributes.
	$('#dropdownvalues').val(dropdownvalues);
	$('#multilevelvalues').val(multilevelvalues);
	$('#rangevalues').val(sliderhiddenattribute);

	getLocationData(1);
}

// price filter
function pricesearch(searchType) {
    
	if (searchType == 'lth') {
		var hidetype = '.htl';
		var ads_hidetype = '.ads';
		var urgent_hidetype = '.urgent'; 
	}
	if (searchType == 'htl') {
		var hidetype = '.lth';
		var ads_hidetype = '.ads';
		var urgent_hidetype = '.urgent'; 
	}

	var searchTypeSelector = "." + searchType;
	if ($(searchTypeSelector).is(':checked'))
	{
		$(hidetype).prop('checked', false);
		$(ads_hidetype).prop('checked', false);
		$(urgent_hidetype).prop('checked', false);

		$("." + searchType).val('1');
		$(hidetype).val('0');
		$(ads_hidetype).val('0');
		$(urgent_hidetype).val('0');
	}
	else
	{
		$("." + searchType).val('0');
		$(hidetype).val('0');
		$(ads_hidetype).val('0');
		$(urgent_hidetype).val('0');  
	}

	lth = $('.lth').val();
	htl = $('.htl').val();
	$('.ads-filter').val('0');
	$('.urgent-filter').val('0');  

	if(lth == 1 && htl ==0)
	{
		lowtohigh = "Low to High";
		$('.tagfilter_htl').remove();
		$('.tagfilter_ads').remove();
		$('.tagfilter_urgent').remove();
		htl = $('.htl').val('');
		$('#filterTags').append('<div class="tagParent tagfilter_'+searchType+'"><span class="tagContent">'+yii.t('app',lowtohigh)+'</span><span id="'+searchType+'" onclick="javascript:tagclose(this);" class="tagCloser">x</span></div>');
		getLocationData(1);
	}else if(lth == 0 && htl ==1){
		$('.tagfilter_lth').remove();
		$('.tagfilter_ads').remove();
		$('.tagfilter_urgent').remove();
		lth = $('.lth').val('');
		$('#filterTags').append('<div class="tagParent tagfilter_'+searchType+'"><span class="tagContent">'+yii.t('app','High to Low')+'</span><span id="'+searchType+'" onclick="javascript:tagclose(this);" class="tagCloser">x</span></div>');
		getLocationData(1);
	}else{
		lth = $('.lth').val('');
		htl = $('.htl').val('');
		$('.tagfilter_'+searchType).remove();
		$('.tagfilter_ads').remove();
		$('.tagfilter_urgent').remove(); 
		getLocationData(1);
	}

	
}
// product condition

function productcondn(searchType) {


	var searchTypeSelector = "." + searchType;
	if ($(searchTypeSelector).is(':checked'))
	{
		productcond.push(searchType);
		loadProductCond.push(searchType)
		$("." + searchType).val('1');
	}
	else
	{
		loadProductCond.splice( $.inArray(searchType, loadProductCond), 1 );
		productcond.splice( $.inArray(searchType, productcond), 1 );
		$(".condn").val('0');
	}


	if($(".condn").val() == '0')
	{
		$('.tagfilter_'+searchType).remove();
	}else{

		$('#filterTags').append('<div class="tagParent tagfilter_'+searchType+'"><span class="tagContent">'+yii.t('app',searchType)+'</span><span id="'+searchType+'" onclick="javascript:tagcloseproductcondn(this);" class="tagCloser">x</span></div>');
	}

	getLocationData(1);
}


// Get results by attributes.
function attributesearch(searchType) {
	var searchTypeSelector = "." + searchType;
	if ($(searchTypeSelector).is(':checked'))
	{
		productcond.push(searchType);
		$("." + searchType).val('1');
	}
	else
	{
		productcond.splice( $.inArray(searchType, productcond), 1 );;
		$(".condn").val('0');
	}
	getLocationData(1);
}

function radioSearch(searchValue){
	var obj = {};
	radioFilter.splice( $.inArray(searchValue, radioFilter), 1 );
	radioFilter.push(searchValue);
	getLocationData(1);
	}

function checkboxSearch(searchType)
{
	var searchTypeSelector = "." + searchType;
	if ($(searchTypeSelector).is(':checked'))
	{
		checkFilter.push(searchType);
	}
	else
	{
		checkFilter.splice( $.map(searchType => checkFilter[searchType]), 1 );
	}
	getLocationData(1);
}

///postwithin search
function postwithinsearch(searchType) {

	if (searchType == 'last24hrs') {
		var hidetype1 = '.last7days';
		var hidetype2 = '.last30days';
		var hidetype3 = '.all';
		var hiddenVal = 1;
	}
	if (searchType == 'last7days') {
		var hidetype1 = '.last24hrs';
		var hidetype2 = '.last30days';
		var hidetype3 = '.all';
		var hiddenVal = 2;
	}
	if (searchType == 'last30days') {
		var hidetype1 = '.last7days';
		var hidetype2 = '.last24hrs';
		var hidetype3 = '.all';
		var hiddenVal = 3;
	}
	if (searchType == 'all') {
		var hidetype1 = '.last7days';
		var hidetype2 = '.last30days';
		var hidetype3 = '.last24hrs';
	}

	var searchTypeSelector = "." + searchType;

	if ($(searchTypeSelector).is(':checked'))
	{
		$(hidetype1).prop('checked', false);
		$(hidetype2).prop('checked', false);
		$(hidetype3).prop('checked', false);
		$("." + searchType).val('1');
		$(hidetype1).val('0');
		$(hidetype2).val('0');
		$(hidetype3).val('0');

	}
	else
	{
		$("." + searchType).val('0');
		$(hidetype1).val('0');
		$(hidetype2).val('0');
		$(hidetype3).val('0');
	}

	last24hrs = $('.last24hrs').val();
	last7days = $('.last7days').val();
	last30days = $('.last30days').val();
	all = $('.all').val();




	if($("." + searchType).val() == '0')
	{
		$('.tagfilter_postedwithin').remove();
	}else{
		$('.tagfilter_postedwithin').remove();
		// alert(yii.t('app',searchType));
		$('#filterTags').append('<div class="tagParent tagfilter_postedwithin"><span class="tagContent">'+yii.t('app',searchType)+'</span><span id="'+searchType+'" onclick="javascript:tagclosepostwithinsearch(this);" class="tagCloser">x</span></div>');
	}
	getLocationData(1);
}

function gotogetLocationData() {

	var a = $("#pac-input").val();
	var b = $.trim(a);
	$("#pac-input").val(b);
	search = $("#searchval").val();
	
	getLocationDataset('search');
}
function gotogetLocationDatamobile() {
	var a = $("#pac-input2").val();
	var b = $.trim(a);
	$("#pac-input2").val(b);
	search = $("#searchvalmobile").val();
	getLocationDatamobileset('search');

}
function getLocationData(initialLoad) {


	initialLoad = typeof initialLoad !== 'undefined' ? initialLoad : 0;
	var grid = document.querySelector('#fh5co-board');
	//alert(grid);

	$('#Products_location').removeClass('warning');
		var whereto = $("#pac-input").val();
		var lat = $('#map-latitude').val();
		var lon = $('#map-longitude').val();
		var price = $('#SliderPrice').val();
		var searchval = $("#searchval").val();
		
		distanceval = $("#Sliders2").val();
		PriceValue = price;

		if (typeof distanceval == 'undefined')
		{
			distanceval = "";
			var distance = "";
		}
		else {
			distanceval = distanceval.split(";");
			var distance = distanceval[1];
		}
		var baseurl = baseUrl;
		var category = $('.category-filter').val();

	//var search = $("#searchval").val().replace(/'/g, "\\'");
	var search = $("#searchval").val();
	var subcategory = $('.subcategory-filter').val();
	var sub_subcategory = $('.sub_subcategory-filter').val();

	var urgent = $('.urgent-filter').val();
	var ads = $('.ads-filter').val();
	var catrest = $('#catrest').val();
	var lth = $('.lth').val();
	var htl = $('.htl').val();
	var last24hrs = $('.last24hrs').val();
	var last7days = $('.last7days').val();
	var last30days = $('.last30days').val();
	var all = $('.all').val();

	//var filterattribute = $('#filterattribute').val();

	var dropdownvalues = $('#dropdownvalues').val();
	var multilevelvalues = $('#multilevelvalues').val();
	var rangevalues = $('#rangevalues').val();


	if (urgent == "0") {
		urgent = '';
	}
	if (ads == "0") {
		ads = '';
	}
	if (lth == "0") {
		lth = '';
	}
	if (htl == "0") {
		htl = '';
	}
	if (last24hrs == "1") {
		posted_within = 'last24hrs';
	}
	else if (last7days == "1") {
		posted_within = 'last7days';
	}
	else if (last30days == "1") {
		posted_within = 'last30days';
	}
	else if (all == "1") {
		posted_within = 'all';
	}
	else
	{
		posted_within = '';
	}

//	console.log(rangevalues);

//	alert(multilevelvalues);
	//return false;
	//alert(locationTracker+'fdsfsd');
	var locationTracker = 1;
	if (locationTracker == 1) {
		locationTracker = 0;

		//alert(urgent);

		$.ajax({
			type: 'POST',
			url: baseUrl+'/site/loadresults/',
			data: {
				lat: lat,
				lon: lon,
				offset: 0,
				distance: distance,
				"loadMore": 1,
				category: category,
				search: search,
				subcategory: subcategory,
				sub_subcategory: sub_subcategory,
				urgent: urgent,
				ads: ads,
				lth: lth,
				htl: htl,
				posted_within: posted_within,
				productcond : productcond,
				price: price,
				dropdownvalues:dropdownvalues,
				multilevelvalues:multilevelvalues,
				rangevalues:rangevalues,
			},
			beforeSend: function () {
				$('#location-loader').show();
				$('body').css({
					"overflow": "hidden"
				});
				$('.search-location').hide();
				$('.btn-worldwide').hide();
				$('.loading-btn').show();
			},
			success: function (datas) {

				console.log(datas);
				//return false;

				$('#location-loader').hide();
				$('body').css({
					"overflow": "auto"
				});

				//alert(datas + 'jj');
				//var grid = document.querySelector("#fh5co-board");
	       		var data = datas.trim();
	       		data = data.split("~#~");

	       		var count = parseInt(data[0].trim());
	       		$('#showUrgentStatus').hide();
	       		//var contentData = eval(data[1].trim()); 
	       		$("#fh5co-board").html($.trim(data[2]));

	       		var orignalCount = parseInt(data[1].trim());
	       		if(orignalCount == 0)
	       		{
	       			$('#showUrgentStatus').show();
	       		}


					salvattore.recreateColumns(grid);
		       		offset = count; 
		       		//loadClicks = 0;
						if(count >= 32) {  
							$("#heightt").show(); 
							$(".loadmorenow").show(); 
						} else {
							$("#heightt").hide(); 
							$(".loadmorenow").hide(); 
						}
					locationTracker = 1;
				/*
				if (urgent == 1) {
					var urCount = datas.split("#");

					if (urCount[1] == 0) {
						$('#new-search').hide();
						$('#showUrgentStatus').show();
					}
					else {
						if (urCount[1] > 31) {
							$('#UrgentFilterLoadmore').show();
						}
						else {
							$('#showUrgentStatus').hide();
							$('#UrgentFilterLoadmore').hide();
						}
					}
				}
				else {
					$('#new-search').hide();
					$('#showUrgentStatus').hide();
					$('#UrgentFilterLoadmore').show();
				}
				var splitt = datas.split("~");
				//alert(splitt);
				$('.search-location').hide();
				$('.loading-btn').hide();
				$('.btn-worldwide').html(splitt[0]);
				$('#Products_location').val(splitt[0]);
				if (splitt[1] == '1')
				{
					$('.show-world-wide').show();
					$('#showUrgentStatus').show();
				}
				else
				{
					$('.show-world-wide').hide();
				}

				var pricesplitt = datas.split("#_#");

				if (pricesplitt[1] == "0") {
					$('#no_item_search').html(yii.t('app','Sorry! No item found2'));
				}

				$('.loading-btn').show();
				$('.session-data').removeClass('hidden');
				$('.session-data').show();
				$("#fh5co-board").html($.trim(splitt[2]));
				salvattore.recreateColumns(grid);
				if (splitt[0] != "")
					$('.miles').html(splitt[0]);

				//$(".more-listing").show();

				$('.imgcls').load(function () {
					$('.imgcls').addClass('hgtremoved');
				});
				//.attr('style', 'height: auto !important;');
				offset = 32;
				adsoffset = 8;
				
				*/

			}
		});
	}

	return false;
}

function getLocationDataset(initialLoad) {

   var input = document.getElementById('pac-input').value;

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({ 
	 	address: input
	}, function(e, r) {
		var lat = "", lon = ""; 
		var search = $.trim($("#searchval").val()); 

	 	if (r == google.maps.GeocoderStatus.OK) {
	     	document.getElementById("map-latitude").value = e[0].geometry.location.lat();
	     	document.getElementById("map-longitude").value = e[0].geometry.location.lng();
	     	var lat = ($('#map-latitude').val());
			var lon = ($('#map-longitude').val());
	 	}

	 	if (onLoadSearchClick == 1) {
			onLoadSearchClick = 0;
					
			if(lat!="" && lon!="") {
				$.ajax({
					type: 'POST',
					url: baseUrl+'/site/currentloc/',
					data: {
						lat: lat,
						lon: lon,
						place: (input), 
						initialClick: 1,
					},
					beforeSend: function () {},
					success: function (datas) {
						window.location = baseUrl+"/category?search=" + search;
					}
				});
			} 

			if(lat=="" && lon=="") { 		 		
				$.ajax({
					type: 'POST',
					url: baseUrl+'/site/currentloc/',
					data: {
						remove: 1,
						initialClick: 0,
					},
					beforeSend: function () {},
					success: function (datas) {  
						/*if (search != "") {
							window.location = baseUrl+"/category?search=" + search; 
						}else if (onLoadLocation == 1) {  
							window.location = baseUrl+"/";
						}*/

						window.location = baseUrl+"/category?search=" + search; 
						
					}
				});
			} 

			if(onLoadSearchFlag == 1 && search == "") { 
				window.location = baseUrl+"/category?search="; 
				onLoadSearchFlag = 0; 
			}

			onLoadSearchClick = 1; 
		}
	}); 

	return false;
}


function setLocationdata(lat, lon)
{
	$.ajax({
		type: 'POST',
		url: baseUrl+'/site/currentloc/', 
		data: {
			lat: (latitude),
			lon: (longitude),
		},
		success: function (data) {
			
		}
	});
}
function getLocationDatamobile(initialLoad) {

	initialLoad = typeof initialLoad !== 'undefined' ? initialLoad : 0;
	var grid = document.querySelector('#fh5co-board');

	$('#Products_location').removeClass('warning');
	var lat = $('#map-latitude').val();
	var lon = $('#map-longitude').val();
	var price = $('#SliderPriceSM').val();
	var searchval = $("#searchvalmobile").val();
	distanceval = $("#Sliders3").val();
	PriceValue = price;
	if (typeof distanceval == 'undefined')
		distanceval = "";
	else {
		distanceval = distanceval.split(";");
		var distance = distanceval[1];
	}
	
	var category = $('.category-filter').val();
	//var search = $("#searchvalmobile").val().replace(/'/g, "\\'");
	var search = $("#searchvalmobile").val();
	var subcategory = $('.subcategory-filter').val();
	var urgent = $('.urgent-filter').val();
	var ads = $('.ads-filter').val();
	var catrest = $('#catrest').val();
	if (urgent == "0") {
		urgent = '';
	}

	if (ads == "0") {
		ads = '';
	}
	if (locationTracker == 1) {
		locationTracker = 0;
		$.ajax({
			type: 'POST',
			url: baseUrl+'/site/loadresults/',
			data: {
				lat: lat,
				lon: lon,
				distance: distance,
				"loadMore": 1,
				category: category,
				search: search,
				subcategory: subcategory,
				urgent: urgent,
				ads: ads,
				catrest: catrest,
				price: price,
			},
			async: false,
			beforeSend: function () {
				$('.search-location').hide();
				$('.btn-worldwide').hide();
				$('.loading-btn').show();
			},
			success: function (datas) {
				if (urgent == 1) {
					var urCount = datas.split("#");
					if (urCount[1] == 0) {
						$('#showUrgentStatus').show();
					}
					else {
						$('#showUrgentStatus').hide();
					}
				}
				else {
					$('#showUrgentStatus').hide();
				}
				var splitt = datas.split("~");
				$('.search-location').hide();
				$('.loading-btn').hide();
				$('.btn-worldwide').html(splitt[0]);
				$('#Products_location').val(splitt[0]);
				if (splitt[1] == '1')
					$('.show-world-wide').show();
				else
					$('.show-world-wide').hide();
				$('.loading-btn').show();
				$('.session-data').removeClass('hidden');
				$('.session-data').show();
				$("#fh5co-board").html($.trim(splitt[2]));
				salvattore.recreateColumns(grid);
				if (splitt[0] != "")
					$('.miles').html(splitt[0]);

				$(".more-listing").show();

				$('.imgcls').load(function () {
					$('.imgcls').addClass('hgtremoved');
				});
				//.attr('style', 'height: auto !important');
				offset = 32;
				adsoffset = 8;
				locationTracker = 1;
			}
		});
	}

	return false;
}
function getLocationDatamobileset(initialLoad) {


	  var input = document.getElementById('pac-input2').value;

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({ 
	 	address: input
	}, function(e, r) {
		var lat = "", lon = ""; 
		var search = $.trim($("#searchvalmobile").val()); 

	 	if (r == google.maps.GeocoderStatus.OK) {
	     	document.getElementById("map-latitude").value = e[0].geometry.location.lat();
	     	document.getElementById("map-longitude").value = e[0].geometry.location.lng();
	     	var lat = ($('#map-latitude').val());
			var lon = ($('#map-longitude').val());
	 	}

	 	if (onLoadSearchClick == 1) {
			onLoadSearchClick = 0;
					
			if(lat!="" && lon!="") {
				$.ajax({
					type: 'POST',
					url: baseUrl+'/site/currentloc/',
					data: {
						lat: lat,
						lon: lon,
						place: (input), 
						initialClick: 1,
					},
					beforeSend: function () {},
					success: function (datas) {
						window.location = baseUrl+"/category?search=" + search;
					}
				});
			} 

			if(lat=="" && lon=="") { 		 		
				$.ajax({
					type: 'POST',
					url: baseUrl+'/site/currentloc/',
					data: {
						remove: 1,
						initialClick: 0,
					},
					beforeSend: function () {},
					success: function (datas) {  
						if (search != "") {
							window.location = baseUrl+"/category?search=" + search; 
						}else if (onLoadLocation == 1) {  
							window.location = baseUrl+"/";
						}
						
					}
				});
			} 

			if(onLoadSearchFlag == 1 && search == "") { 
				window.location = baseUrl+"/category?search="; 
				onLoadSearchFlag = 0; 
			}

			onLoadSearchClick = 1; 
		}
	}); 

	return false;
}
function showexchangepopup() {
	var user = $('.logindetails').val();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		$('#popup_container').show();
		$('#popup_container').css({
			"opacity": "1"
		});
		$('#show-exchange-popup').show();
		$('body').css({
			"overflow": "hidden"
		});
	}
}

function showcouponpopup() {
	$('#popup_container').show();
	$('#popup_container').css({
		"opacity": "1"
	});
	$('#show-coupon-popup').show();
	$('body').css({
		"overflow": "hidden"
	});
}

/******************Rating Starts**************************/

function showreviewpopup(exid, userid) {
	
	$('.exchangeid').val(exid);
	$('.review-receiver').val(userid);
	$('#popup_container').show();
	$('#popup_container').css({
		"opacity": "1"
	});
	$('#review-user-popup').show();
	$('body').css({
		"overflow": "hidden"
	});
}
function editreview(id) {
	$('.reviewid').val(id);
	var textarea = $('#review' + id).val();
	var ratings = $('#ratings' + id).val();
	$('#popup_container').show();
	$('#popup_container').css({
		"opacity": "1"
	});
	$('.review-textarea').val(textarea);
	$('.current-rate').html(ratings);
	$('#rateval').val(ratings);

	if (ratings != 0) {
		switch (ratings) {
			case '5':
			$('.rating').addClass('active');

			$('.rating').addClass('fa-star');
			$('.rating').removeClass('fa-star-o');
			break;
			case '4':
			$('.four').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').addClass('fa-star');
			$('.two').removeClass('fa-star-o');
			$('.three').addClass('fa-star');
			$('.three').removeClass('fa-star-o');
			$('.four').addClass('fa-star');
			$('.four').removeClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
			case '3':
			$('.three').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').addClass('fa-star');
			$('.two').removeClass('fa-star-o');
			$('.three').addClass('fa-star');
			$('.three').removeClass('fa-star-o');
			$('.four').removeClass('fa-star');
			$('.four').addClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
			case '2':
			$('.two').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').addClass('fa-star');
			$('.two').removeClass('fa-star-o');
			$('.three').removeClass('fa-star');
			$('.three').addClass('fa-star-o');
			$('.four').removeClass('fa-star');
			$('.four').addClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
			case '1':
			$('.one').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').removeClass('fa-star');
			$('.two').addClass('fa-star-o');
			$('.three').removeClass('fa-star');
			$('.three').addClass('fa-star-o');
			$('.four').removeClass('fa-star');
			$('.four').addClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
		}
	}

	$('#review-edit-popup').show();
	$('.review-body-section').show();
	$('.review-response-message').hide();
	$('#').show();
	$('body').css({
		"overflow": "hidden"
	});
}

function editsavereview() {
	var id = $('.reviewid').val();
	var reviews = $('.review-textarea').val(); //$('#review'+id).val();
	var ratings = $('.current-rate').text(); //$('#ratings'+id).val();

	if (ratings == 0) {
		$('.review-error').show();
		$('.review-error').html(yii.t('app','Please give your ratings'));
		$('.review-error').fadeIn();
		setTimeout(function () {
			$('.review-error').fadeOut();
		}, 2000);
		return;
	}

	if (reviews == "") {
		$('.review-textarea').val('');
		$('.review-error').show();
		$('.review-error').html(yii.t('app','Please write your review'));
		$('.review-error').fadeIn();
		setTimeout(function () {
			$('.review-error').fadeOut();
		}, 2000);
		return;
	}

	$.ajax({
		url: yii.urls.base + '/item/exchanges/editsavereview/',
		type: "post",
		data: {
			'reviewId': id,
			'reviews': reviews,
			'ratings': ratings
		},
		beforeSending: function () {
			$('.send-button').html(yii.t('app', 'Sending...'));
		},
		success: function (response) {

			if (response == 'success') {
				html = "<h4 class='text-center thanks-message'>" + yii.t('app', 'Thanks for your review') + "</h4>";

				$('.review-body-section').hide();
				$('.review-response-message').show();
				$('.review-response-message').html(html);

			} else if (response == 'error') {
				html = "<h4 class='text-center thanks-message-err'>" + yii.t('app', 'Unfortunately Your review is not sent') +
				"</h4> <h6 class='thanks-message-err text-center'>" + yii.t('app', 'Please try again Later') + "</h6>";

				$('.review-body-section').hide();
				$('.review-response-message').show();
				$('.review-response-message').html(html);
			}
			setTimeout(function () {
				$('#review-user-popup').hide();
				$('#popup_container').hide();
			}, 3000);

			$('#review' + id).val(reviews);
			$('#ratings' + id).val(ratings);
			$('.review_subject' + id).html(reviews);
			switch (ratings) {
				case "5":
				$('.review_rating .static-rating').addClass('active');
				$('.review_rating .static-rating').addClass('fa-star');
				$('.review_rating .static-rating').removeClass('fa-star-o');

				break;
				case "4":
				$('.edit-4' + id).addClass('active');
				$('.edit-1' + id).addClass('fa-star');
				$('.edit-1' + id).removeClass('fa-star-o');
				$('.edit-2' + id).addClass('fa-star');
				$('.edit-2' + id).removeClass('fa-star-o');
				$('.edit-3' + id).addClass('fa-star');
				$('.edit-3' + id).removeClass('fa-star-o');
				$('.edit-4' + id).addClass('fa-star');
				$('.edit-4' + id).removeClass('fa-star-o');
				$('.edit-5' + id).removeClass('fa-star');
				$('.edit-5' + id).addClass('fa-star-o');

				break;
				case '3':
				$('.edit-3' + id).addClass('active');

				$('.edit-1' + id).addClass('fa-star');
				$('.edit-1' + id).removeClass('fa-star-o');
				$('.edit-2' + id).addClass('fa-star');
				$('.edit-2' + id).removeClass('fa-star-o');
				$('.edit-3' + id).addClass('fa-star');
				$('.edit-3' + id).removeClass('fa-star-o');
				$('.edit-4' + id).removeClass('fa-star');
				$('.edit-4' + id).addClass('fa-star-o');
				$('.edit-5' + id).removeClass('fa-star');
				$('.edit-5' + id).addClass('fa-star-o');
				break;
				case '2':
				$('.edit-2' + id).addClass('active');

				$('.edit-1' + id).addClass('fa-star');
				$('.edit-1' + id).removeClass('fa-star-o');
				$('.edit-2' + id).addClass('fa-star');
				$('.edit-2' + id).removeClass('fa-star-o');
				$('.edit-3' + id).removeClass('fa-star');
				$('.edit-3' + id).addClass('fa-star-o');
				$('.edit-4' + id).removeClass('fa-star');
				$('.edit-4' + id).addClass('fa-star-o');
				$('.edit-5' + id).removeClass('fa-star');
				$('.edit-5' + id).addClass('fa-star-o');
				break;
				case '1':
				$('.edit-1' + id).addClass('active');

				$('.edit-1' + id).addClass('fa-star');
				$('.edit-1' + id).removeClass('fa-star-o');
				$('.edit-2' + id).removeClass('fa-star');
				$('.edit-2' + id).addClass('fa-star-o');
				$('.edit-3' + id).removeClass('fa-star');
				$('.edit-3' + id).addClass('fa-star-o');
				$('.edit-4' + id).removeClass('fa-star');
				$('.edit-4' + id).addClass('fa-star-o');
				$('.edit-5' + id).removeClass('fa-star');
				$('.edit-5' + id).addClass('fa-star-o');
				break;
			}
			$('.current-rate').html(ratings);
			$('#rateval').val(ratings);

		}
	});


}
function saveReviewPopup() {

	var sender = $('.review-sender').val();
	var receiver = $('.review-receiver').val();
	var msg = $('.review-textarea').val();
	var exchangeId = $('.exchangeid').val();
	var reviewType = $('#reviewType').val();
	var message = msg.trim();

	if (rating == 0) {
		$('.review-error').show();
		$('.review-error').html(yii.t('app', 'Please give your ratings'));
		$('.review-error').fadeIn();
		setTimeout(function () {
			$('.review-error').fadeOut();
		}, 2000);
		return;
	}

	if (message == "") {
		$('.review-textarea').val('');
		$('.review-error').show();
		$('.review-error').html(yii.t('app', 'Please write your review'));
		$('.review-error').fadeIn();
		setTimeout(function () {
			$('.review-error').fadeOut();
		}, 2000);
		return;
	}

	$.ajax({

		url: yii.urls.base + '/item/exchanges/savereview/',
		type: "post",
		data: {
			'sender': sender,
			'receiver': receiver,
			'message': message,
			'exchangeId': exchangeId,
			'rating': rating,
			'reviewType': reviewType
		},
		beforeSend: function () {
			$('.send-button').html(yii.t('app', 'Sending...'));
		},
		success: function (response) {

			$('.review-btn' + exchangeId).hide();
			if (response == 'success') {
				html = "<h4 class='text-center thanks-message'>" + yii.t('app', 'Thanks for your review') + "</h4>";
				$('.review-body-section').html(html);
			} else if (response == 'error') {
				html = "<h4 class='text-center thanks-message-err'>" + yii.t('app', 'Unfortunately Your review is not sent') +
				"</h4> <h6 class='thanks-message-err text-center'>" + yii.t('app', ' Please try again Later ') + "</h6>";
				$('.review-body-section').html(html);
			}
			setTimeout(function () {
				$('#review-user-popup').hide();
				$('#popup_container').hide();
			}, 3000);

		}


	});

}
$(document).on('mouseover', '.one', function () {
	$('.rating').removeClass('active');
	$('.one').addClass('hover');
	$('.two').removeClass('hover');
	$('.three').removeClass('hover');
	$('.four').removeClass('hover');
	$('.five').removeClass('hover');
	if ($('.one').hasClass('fa-star-o')) {
		$('.one').addClass('fa-star');
		$('.one').removeClass('fa-star-o');
	}
	$('.current-rate').html('1');
});
$(document).on('mouseover', '.two', function () {
	$('.rating').removeClass('active');
	$('.one').addClass('hover');
	$('.two').addClass('hover');
	$('.three').removeClass('hover');
	$('.four').removeClass('hover');
	$('.five').removeClass('hover');
	if ($('.one').hasClass('fa-star-o')) {
		$('.one').addClass('fa-star');
		$('.one').removeClass('fa-star-o');
	}
	if ($('.two').hasClass('fa-star-o')) {
		$('.two').addClass('fa-star');
		$('.two').removeClass('fa-star-o');
	}

	$('.current-rate').html('2');
});
$(document).on('mouseover', '.three', function () {
	$('.rating').removeClass('active');
	$('.one').addClass('hover');
	$('.two').addClass('hover');
	$('.three').addClass('hover');
	$('.four').removeClass('hover');
	$('.five').removeClass('hover');

	if ($('.one').hasClass('fa-star-o')) {
		$('.one').addClass('fa-star');
		$('.one').removeClass('fa-star-o');
	}
	if ($('.two').hasClass('fa-star-o')) {
		$('.two').addClass('fa-star');
		$('.two').removeClass('fa-star-o');
	}
	if ($('.three').hasClass('fa-star-o')) {
		$('.three').addClass('fa-star');
		$('.three').removeClass('fa-star-o');
	}


	$('.current-rate').html('3');
});
$(document).on('mouseover', '.four', function () {
	$('.rating').removeClass('active');
	$('.one').addClass('hover');
	$('.two').addClass('hover');
	$('.three').addClass('hover');
	$('.four').addClass('hover');
	$('.five').removeClass('hover');

	if ($('.one').hasClass('fa-star-o')) {
		$('.one').addClass('fa-star');
		$('.one').removeClass('fa-star-o');
	}
	if ($('.two').hasClass('fa-star-o')) {
		$('.two').addClass('fa-star');
		$('.two').removeClass('fa-star-o');
	}
	if ($('.three').hasClass('fa-star-o')) {
		$('.three').addClass('fa-star');
		$('.three').removeClass('fa-star-o');
	}
	if ($('.four').hasClass('fa-star-o')) {
		$('.four').addClass('fa-star');
		$('.four').removeClass('fa-star-o');
	}
	$('.one').addClass('fa-star');
	$('.one').removeClass('fa-star-o');
	$('.two').addClass('fa-star');
	$('.two').removeClass('fa-star-o');
	$('.three').addClass('fa-star');
	$('.three').removeClass('fa-star-o');
	$('.four').addClass('fa-star');
	$('.four').removeClass('fa-star-o');
	$('.current-rate').html('4');
});
$(document).on('mouseover', '.five', function () {
	$('.rating').removeClass('active');
	$('.one').addClass('hover');
	$('.two').addClass('hover');
	$('.three').addClass('hover');
	$('.four').addClass('hover');
	$('.five').addClass('hover');

	if ($('.rating').hasClass('fa-star-o')) {
		$('.rating').addClass('fa-star');
		$('.rating').removeClass('fa-star-o');
	}

	$('.current-rate').html('5');
});
$(document).on('mouseout', '.rating', function () {
	$('.rating').removeClass('hover');
	if ($('.rating').hasClass('fa-star')) {
		$('.rating').addClass('fa-star-o');
		$('.rating').removeClass('fa-star');
	}
	$('.rating').removeClass('fa-star');
	$('.rating').addClass('fa-star-o');
	if (rating != 0) {
		switch (rating) {
			case '5':
			$('.rating').addClass('active');

			$('.rating').addClass('fa-star');
			$('.rating').removeClass('fa-star-o');
			break;
			case '4':
			$('.four').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').addClass('fa-star');
			$('.two').removeClass('fa-star-o');
			$('.three').addClass('fa-star');
			$('.three').removeClass('fa-star-o');
			$('.four').addClass('fa-star');
			$('.four').removeClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
			case '3':
			$('.three').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').addClass('fa-star');
			$('.two').removeClass('fa-star-o');
			$('.three').addClass('fa-star');
			$('.three').removeClass('fa-star-o');
			$('.four').removeClass('fa-star');
			$('.four').addClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
			case '2':
			$('.two').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').addClass('fa-star');
			$('.two').removeClass('fa-star-o');
			$('.three').removeClass('fa-star');
			$('.three').addClass('fa-star-o');
			$('.four').removeClass('fa-star');
			$('.four').addClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
			case '1':
			$('.one').addClass('active');

			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
			$('.two').removeClass('fa-star');
			$('.two').addClass('fa-star-o');
			$('.three').removeClass('fa-star');
			$('.three').addClass('fa-star-o');
			$('.four').removeClass('fa-star');
			$('.four').addClass('fa-star-o');
			$('.five').removeClass('fa-star');
			$('.five').addClass('fa-star-o');
			break;
		}
	}
	$('.current-rate').html(rating);
});

function ratingClick(value) {
	switch (value) {
		case "5":
		$('.rating').addClass('active');
		$('.rating').addClass('fa-star');
		$('.rating').removeClass('fa-star-o');

		break;
		case "4":
		$('.four').addClass('active');

		if ($('.rating.one').hasClass('active')) {
			$('.one').addClass('fa-star');
			$('.one').removeClass('fa-star-o');
		}
		if ($('.rating.two').hasClass('active')) {
			$('.two').addClass('fa-star');
			$('.two').removeClass('fa-star-o');
		}
		if ($('.rating.three').hasClass('active')) {
			$('.three').addClass('fa-star');
			$('.three').removeClass('fa-star-o');
		}
		if ($('.rating.four').hasClass('active')) {
			$('.four').addClass('fa-star');
			$('.four').removeClass('fa-star-o');
		}
		if ($('.rating.five').hasClass('fa-star')) {
			$('.five').removeClass('fa-star');
			$('.five').removeClass('fa-star-o');
		}
		break;
		case '3':
		$('.three').addClass('active');

		$('.one').addClass('fa-star');
		$('.one').removeClass('fa-star-o');
		$('.two').addClass('fa-star');
		$('.two').removeClass('fa-star-o');
		$('.three').addClass('fa-star');
		$('.three').removeClass('fa-star-o');
		$('.four').removeClass('fa-star');
		$('.four').addClass('fa-star-o');
		$('.five').removeClass('fa-star');
		$('.five').addClass('fa-star-o');
		break;
		case '2':
		$('.two').addClass('active');

		$('.one').addClass('fa-star');
		$('.one').removeClass('fa-star-o');
		$('.two').addClass('fa-star');
		$('.two').removeClass('fa-star-o');
		$('.three').removeClass('fa-star');
		$('.three').addClass('fa-star-o');
		$('.four').removeClass('fa-star');
		$('.four').addClass('fa-star-o');
		$('.five').removeClass('fa-star');
		$('.five').addClass('fa-star-o');
		break;
		case '1':
		$('.one').addClass('active');

		$('.one').addClass('fa-star');
		$('.one').removeClass('fa-star-o');
		$('.two').removeClass('fa-star');
		$('.two').addClass('fa-star-o');
		$('.three').removeClass('fa-star');
		$('.three').addClass('fa-star-o');
		$('.four').removeClass('fa-star');
		$('.four').addClass('fa-star-o');
		$('.five').removeClass('fa-star');
		$('.five').addClass('fa-star-o');
		break;
	}
	$('.current-rate').html(value);
	$('#rateval').val(value);
	rating = value;
}
/*************************Rating Ends***************************/

function showexchangehistory(exchangeId) {

	var timezone_offset_minutes = new Date().getTimezoneOffset();
	timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;


	$.ajax({
		type: 'POST',
		url: baseUrl+'/user/historyview/', 
		data: {
			exchangeId: exchangeId,
			timeMinutes: timezone_offset_minutes,
		},
		success: function (data) {
			$("#exchangeHistory").html(data);
		}
	});
}

function showcheckoutpopup() {
	var user = $('.logindetails').val();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		var itemOption = $('.item-option').val();
		var itemCartURL = $('.item-cartdata').val();
		if (itemOption == 1) {
			$('#popup_container').show();
			$('#popup_container').css({
				"opacity": "1"
			});
			$('#choose-option-popup').show();
			$('body').css({
				"overflow": "hidden"
			});
		} else {
			window.location = yii.urls.base + '/revieworder/' + itemCartURL;
		}
	}
}

function optionCheck() {
	var itemOption = $('.item-option').val();
	var selectOption = $('.item-qty').val();
	$('.option-error').html('');
	if (itemOption == 1 && selectOption != '') {
		$('#popup_container').hide();
		$('#popup_container').css({
			"opacity": "0"
		});
		$('#choose-option-popup').hide();
		$('body').css({
			"overflow": "auto"
		});
		window.location = yii.urls.base + '/revieworder/' + selectOption;
	} else {
		$('.option-error').html(
			yii.t('app', 'Please select a Option to proceed'));
	}
}

function checkout() {
	var productId = $('.review-order-product-id').val();
	var optionChoosed = $('.product-option-hidden').val();
	var quantityChoosed = $('.product-quantity-hidden').val();

	var shippingChoosed = $('.selected-shipping').val();
	var couponCode = $('.coupon-code-hidden').val();
	if (shippingChoosed == "") {
		$("#payerr").show();
		$("#payerr").html(yii.t('app',"Please select shipping"));
		setTimeout(function () {
			$("#payerr").fadeOut();
		}, 3000);
	}
	else {
		if (checkoutAjax == 1) {
			checkoutAjax = 0;
			$.ajax({
				url: yii.urls.base + '/placeorder',
				type: "post",
				dataType: "html",
				data: {
					'productId': productId,
					'optionChoosed': optionChoosed,
					'quantityChoosed': quantityChoosed,
					'shippingChoosed': shippingChoosed,
					'couponCode': couponCode
				},
				beforeSend: function () {
					$('.check-out-button').html(yii.t('app','Please wait')+'.......');
				},
				success: function (responce) {
					var output = responce.trim();
					if (output != 'false') {
						$('.payment-form').html(output);
						$('#paypal-form').submit();
					} else {
						$('.check-out-button').html(yii.t('app','Please try again!!'));
						$('.check-out-button').css({
							"background-color": "#fd2525"
						});
					}
					checkoutAjax = 1;
				},
				failed: function () {
					$('.check-out-button').html(yii.t('app','Please try again!!'));
					$('.check-out-button').css({
						"background-color": "#fd2525"
					});
				}
			});
		}
	}
}

function changeCard(type) {
	$('.card-type-view.active').removeClass('fa-dot-circle-o');
	$('.card-type-view.active').addClass('fa-circle-o');
	$('.card-type-view').removeClass('active');
	$('.card-type-view.' + type).removeClass('fa-circle-o');
	$('.card-type-view.' + type).addClass('fa-dot-circle-o');
	$('.card-type-view.' + type).addClass('active');
	$('.card-type').val(type);
	if (type == 'amex') {
		$('.card-cvv').attr('maxlength', '4');
	} else {
		$('.card-cvv').attr('maxlength', '3');
	}
	$('.card-cvv').val('');
}

function cardcheckout() {
	$('.ccError').html('');
	$('.ccError').hide();
	var productId = $('.review-order-product-id').val();
	var optionChoosed = $('.product-option-hidden').val();
	var quantityChoosed = $('.product-quantity-hidden').val();

	var shippingChoosed = $('.selected-shipping').val();
	var couponCode = $('.coupon-code-hidden').val();

	var cardType = $('.card-type').val();
	var cardNumber = $('.card-number').val();
	var expiryDate = $('.card-expiry').val();
	var cvv = $('.card-cvv').val();
	var firstname = $('.card-first-name').val();
	var lastname = $('.card-last-name').val();
	var errorFlag = 0;
	var expityvalid = /^(0[1-9]|1[0-2])\/(19|20)\d{2}$/;

	if (cardType == '') {
		$('.card-type-error').html('Select your card type');
		$('.card-type-error').show();
		errorFlag = 1;
	}
	if (cardNumber == '') {
		$('.card-number-error').html('Enter your card number');
		$('.card-number-error').show();
		errorFlag = 1;
	} else if (!numric.test(cardNumber)) {
		$('.card-number-error').html('Not a valid card number');
		$('.card-number-error').show();
		errorFlag = 1;
	}
	if (expiryDate == '') {
		$('.card-expiry-error').html('Enter your card expiry date');
		$('.card-expiry-error').show();
		errorFlag = 1;
	} else if (!expityvalid.test(expiryDate)) {
		$('.card-expiry-error').html('Enter a valid card expiry date MM/YYYY');
		$('.card-expiry-error').show();
		errorFlag = 1;
	}
	if (cvv == '') {
		$('.card-cvv-error').html('Enter your cvv');
		$('.card-cvv-error').show();
		errorFlag = 1;
	} else if (!numric.test(cvv)) {
		$('.card-cvv-error').html('Not a valid cvv');
		$('.card-cvv-error').show();
		errorFlag = 1;
	}
	if (firstname == '') {
		$('.card-first-name-error').html('Enter your first name');
		$('.card-first-name-error').show();
		errorFlag = 1;
	} else if (!alpha.test(firstname)) {
		$('.card-first-name-error').html('Not a valid first name');
		$('.card-first-name-error').show();
		errorFlag = 1;
	}
	if (lastname == '') {
		$('.card-last-name-error').html('Enter your last name');
		$('.card-last-name-error').show();
		errorFlag = 1;
	} else if (!alpha.test(lastname)) {
		$('.card-last-name-error').html('Not a valid last name');
		$('.card-last-name-error').show();
		errorFlag = 1;
	}

	if (checkoutAjax == 1 && errorFlag == 0) {
		checkoutAjax = 0;
		$.ajax({
			url: yii.urls.base + '/creditcardcheckout',
			type: "post",
			dataType: "html",
			data: {
				'productId': productId,
				'optionChoosed': optionChoosed,
				'quantityChoosed': quantityChoosed,
				'shippingChoosed': shippingChoosed,
				'couponCode': couponCode,
				'cardType': cardType,
				'cardNumber': cardNumber,
				'expiryDate': expiryDate,
				'firstname': firstname,
				'lastname': lastname,
				'cvv': cvv,
			},
			beforeSend: function () {
				$('.check-out-button').html(yii.t('app','Please wait')+'...');
				$('.check-out-button').attr('disabled', 'disabled');
			},
			success: function (responce) {
				var output = responce.trim();
				if (output != 'false') {
					$('.payment-form').html(output);
					$('#paypal-form').submit();
				} else {
					$('.check-out-button').html(yii.t('app','Please try again!!'));
					$('.check-out-button').css({
						"background-color": "#fd2525"
					});
				}
				checkoutAjax = 1;
			},
			failed: function () {
				$('.check-out-button').html('Please try again!!');
				$('.check-out-button').css({
					"background-color": "#fd2525"
				});
			}
		});
	}
}
function generateCoupon(pid, uid, price, currency) {
	$('#couponValue').bind('input', function () {
		var couponValue = this.value;
	});
	var couponValue = $("#couponValue").val().trim();
	if (couponValue == "") {
		$('.option-error').show();
		$("#couponValue").val('');
		$(".option-error").html(yii.t('app', 'Coupon value cannot be empty'));
		setTimeout(function () {
			$('.option-error').fadeOut();
		}, 3000);
	} else {
		$(".option-error").hide();
		if (generateCouponAjax == 1) {

			if (specials.test(couponValue)) {
				$('.option-error').show();
				$(".option-error").html(
					'<b>' + yii.t('app', 'Special Characters not allowed.')+ '</b>');
				$("#couponValue").val('');
				setTimeout(function () {
					$('.option-error').fadeOut();
				}, 500);
				return false;
			} else if (alpha.test(couponValue)) {
				$('.option-error').show();
				$(".option-error").html(
					'<b>' + yii.t('app', 'Only Numeric values allowed.')
					+ '</b>');
				$("#couponValue").val('');
				setTimeout(function () {
					$('.option-error').fadeOut();
				}, 500);
				return false;
			} else if (Number(price) <= Number(couponValue)) {
				$('.option-error').show();
				$(".option-error")
				.html(
					'<b>'
					+ yii
					.t('app',
						'Coupon value is equal to or greater than product price.')
					+ '</b>');
				setTimeout(function () {
					$('.option-error').fadeOut();
				}, 3000);
				return false;
			} else {
				if (generateCouponAjax == 1) {
					generateCouponAjax = 0;
					$.ajax({
						type: 'POST',
						url: yii.urls.base + '/products/generateCoupon',
						data: {
							productId: pid,
							userId: uid,
							price: price,
							couponValue: couponValue,
							currency: currency,
						},
						success: function (data) {
							$(".generate-coupon-container").hide();
							$('.new-coupon-link').show();
							$(".coupon-code").html(
								"<div style='font-size:25px'>"
								+ yii.t('app', 'Coupon Code')
								+ " :" + "<b>" + data
								+ "</b></div>");
							$(".coupon-code").fadeIn(1500);
							generateCouponAjax = 1;
						}
					});
				}
			}
		}
	}
}
function keyHandler(k) {
	var lkey = document.getElementById('lastkey').value;
	var message = document.getElementById('contact-textarea').value;

	var keypr = (window.event) ? event.keyCode : k.keyCode;
	var newmessage;

	if ((keypr == 32) || (keypr == 190) || (keypr == 188) || (keypr == 186)) {
		newmessage = message.substr(0, message.length - 1);
		if (lkey == keypr) {
			document.getElementById('contact-textarea').value = newmessage;
		}
		document.getElementById('lastkey').value = keypr;
	} else {
		newmessage = message.substr(0, message.length);
		document.getElementById('lastkey').value = keypr;
		if (lkey == '2')
			document.getElementById('contact-textarea').value = newmessage;
	}

}

function keyban(k) {
	var message = document.getElementById('contact-textarea').value;
	var keypr = (window.event) ? event.keyCode : k.keyCode;
	if (keypr != '16') {
		var reg = /^[^\da-zA-Z]$/;
		if (message.length < 2) {
			if (reg.test(String.fromCharCode(keypr)))
				document.getElementById('contact-textarea').value = '';
		}
	}
	var limitNum = 500;
	if (message.length > limitNum) {
		var textValue = $('.contact-textarea').val().substring(0, limitNum);
		$('.contact-textarea').val(textValue);
		$('.contactme-error').show();
		$('.contactme-error').html(
			yii.t('app', "Maximum Character limit") + " 500");
		$('.contactme-error').fadeIn();
		setTimeout(function () {
			$('.contactme-error').fadeOut();
		}, 3000);
	}
}

function keyban_msg(k) {
	var message = document.getElementById('contact-textarea').value;
	var keypr = (window.event) ? event.keyCode : k.keyCode;
	if (keypr != '16') {
		var reg = /^[^\da-zA-Z]$/;
		if (message.length < 2) {
			if (reg.test(String.fromCharCode(keypr)))
				document.getElementById('contact-textarea').value = '';
		}
	}
	var limitNum = 500;
	if (message.length == limitNum) {
		var textValue = $('.contact-textarea').val().substring(0, limitNum);
		$('.contact-textarea').val(textValue);
		$('.contactme-error').show();
		$('.contactme-error').html(
			yii.t('app', "Maximum Character limit") + " 500");
		$('.contactme-error').fadeIn();
		setTimeout(function () {
			$('.contactme-error').fadeOut();
		}, 3000);
	}
}

function contactMePopup() {
	$('.contactme-error').hide();
	var sender = $('.contact-sender').val();

	var receiver = $('.contact-receiver').val();
	var msg = $('.contact-textarea').val();
	var sourceId = $('.item-id').val();
	var message = msg.trim();

	if ($.trim(sender) == '') {
		window.location = baseUrl+'/site/login';
	}
	if (message == "") {
		$('.contact-textarea').val('');
		$('.contactme-error').show();
		$('.contactme-error').html(yii.t('app','Enter some Message to send'));
		$('.contactme-error').fadeIn();
		setTimeout(function () {
			$('.contactme-error').fadeOut();
		}, 3000);
		return;
	}
	if (contactAjax == 1) {
		$('.seller-chat-btn').html(yii.t('app','Sending...'));
		$('.seller-chat-btn').attr('disabled');
		contactAjax = 0;
		$.ajax({
			url: baseUrl+'/initiatechat',
			type: "post",
			dataType: "html",
			data: {
				'sender': sender,
				'receiver': receiver,
				'message': message,
				'messageType': "normal",
				'sourceId': sourceId,
			},
			beforeSend: function () {
			},
			success: function (responce) {
				//alert(responce);
				if ($.trim(responce) == "error") {
					window.location.reload();
				}
				else {
					$('.seller-chat-btn').html(yii.t('app','Send'));
					$('.seller-chat-btn').removeAttr('disabled');
					var output = responce.trim();
					
					//alert(output);
					if (output != 'failed') {
						$('#chat-with-seller-modal').hide();
						//$('#chat-with-seller-modal').modal('toggle');
						//$('#chat-with-seller-modal').removeClass("in");
						$('.contact-textarea').val('');
						$('#chat-with-seller-success-modal').show();
						$('#chat-with-seller-success-modal').addClass('in');
						//$('#chat-with-seller-success-modal').modal('show');
						$('.sent-text').html(yii.t('app','Message sent'));
					}
					contactAjax = 1;
				}
			}
		});
	}

}

function close_popup() {
	$('#chat-with-seller-success-modal').hide();
	$('#offer-success-modal').hide();
	$('#chat-with-seller-success-modal').removeClass("in");
	$('#offer-success-modal').removeClass("in");
	$(".modal-backdrop").hide();
	$('.sent-text').html('');
	$('body').removeClass("modal-open");
	$('body').css({
		"overflow": "auto"
	});
}

/*function validsigninfrm() {
	var email = $('#LoginForm_username').val();
	var password = $('#LoginForm_password').val();
	//let errmsg=yii.t("app", "Email cannot be blank");
	var val=0;
	if (email == '') {
		val=1;
		$("#LoginForm_username_em_").show();
		$('#LoginForm_username_em_')
		.text(yii.t('app',"Email cannot be blank"));
		setTimeout(function () {
			$('#LoginForm_username_em_').hide();
		}, 4000);
		//$('#LoginForm_username').focus();
		$('#LoginForm_username').keydown(function () {
			$('#LoginForm_username_em_').hide();
		});

		
		//return false;
		
	}
	else{
		if (!(isValidEmailAddress(email))) {
			val=1;
			$("#LoginForm_username_em_").show();
			$('#LoginForm_username_em_').text(yii.t('app','Please Enter a valid Email'));
			setTimeout(function () {
				$('#LoginForm_username_em_').hide();
			}, 4000);
			$('#LoginForm_username').keydown(function () {
				$('#LoginForm_username_em_').hide();
			});

		//$('#LoginForm_username').focus();
		//return false;
	}
	else{
		val=0;
	}
}

if (password == '') {
	val=1;
	$("#LoginForm_password_em_").show();
		//$("#LoginForm_username_em_").hide();
		$('#LoginForm_password_em_').text(yii.t('app','Password cannot be blank'));
		setTimeout(function () {
			$('#LoginForm_password_em_').hide();
		}, 4000);
		$('#LoginForm_password').keydown(function () {
			$("#LoginForm_password_em_").hide();
		});
		
		//$('#LoginForm_password').focus();
		//return false;
	}
	else{
		val=0;
	}
	if(val==0){
		$('#submit-btn').attr( 'name', 'submit' );
		setTimeout(function () {
			$('#submit-btn').removeAttr('name');
		}, 2000);
		

	}

	
}*/

function validsigninfrm() {
		
	var email = $('#LoginForm_username').val();
	var password = $('#LoginForm_password').val();
	//let errmsg=yii.t("app", "Email cannot be blank");
	var val=0;
	if (email == '') {
		val=1;
		$("#LoginForm_username_em_").show();
		$('#LoginForm_username_em_')
		.text(yii.t('app','Email cannot be blank'));

		setTimeout(function () {
			$('#LoginForm_username_em_').hide();
		}, 4000);
		$('#LoginForm_username').focus();
		$('#LoginForm_username').keydown(function () {
			$('#LoginForm_username_em_').hide();
		});

		
		//return false;
		
	}else{
		if (!(isValidEmailAddress(email))) {
			val=1;
			$("#LoginForm_username_em_").show();
			$('#LoginForm_username_em_').text(yii.t('app','Please Enter a valid Email'));
			setTimeout(function () {
				$('#LoginForm_username_em_').hide();
			}, 4000);
			$('#LoginForm_username').keydown(function () {
				$('#LoginForm_username_em_').hide();
			});

		//$('#LoginForm_username').focus();
		//return false;
		}else{
			$.ajax({
					url: baseUrl+'/site/getuserbyemail/',
					type: "post",
					data: {
						'email': email,
						'password' : password
					},
					success: function (responce) {

						if(responce == 'nonuser')
						{
							$('#LoginForm_username_em_').text(yii.t('app','User not registered'));
							setTimeout(function () {
								$('#LoginForm_username_em_').hide();
							}, 4000);
							$('#LoginForm_username').keydown(function () {
								$('#LoginForm_username_em_').hide();
							});
							return false;

						}else if(responce == 'wrongpassword'){
							$('.field-LoginForm_password .help-block-error').show();
							$('input#LoginForm_password').val('');
							//$('#LoginForm_password_em_').text(yii.t('app','Incorrect password'));
							setTimeout(function () {
								$('#LoginForm_password_em_').hide();
							}, 4000);

							$('#LoginForm_password').keydown(function () {
								$('#LoginForm_password_em_').hide();
							});
							return false;
						}else if(responce == 'passwordempty'){
							$('.field-LoginForm_password .help-block-error').hide();
							$('#LoginForm_password_em_').text(yii.t('app','Password cannot be empty'));
							setTimeout(function () {
								$('#LoginForm_password_em_').hide();
							}, 4000);
							$('#LoginForm_password').keydown(function () {
								$('#LoginForm_password_em_').hide();
							});
							return false;
						}else{
							val=0;
							
						}
						
					}
			});		
		}
	}

if (password == '') {
	val=1;
	$("#LoginForm_password_em_").show();
		//$("#LoginForm_username_em_").hide();
		$('#LoginForm_password_em_').text(yii.t('app','Password cannot be blank'));
		
		setTimeout(function () {
			$('#LoginForm_password_em_').hide();
		}, 4000);
		$('#LoginForm_password').keydown(function () {
			$("#LoginForm_password_em_").hide();
		});
	}
	else{
		val=0;
	}
	if(val==0){
			$('#lo-submitt').attr( 'value', '1' );
		setTimeout(function () {
			$('#lo-submitt').removeAttr('value');
		}, 3000);
	}
	else
	{
		return false;
	}
}
$('#Users_username').keypress(function (e) {
      
var regex = new RegExp("^[a-zA-Zء-ي0-9_]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}
e.preventDefault();
return false;
});
$('#Users_name').keypress(function (e) {
      
var regex = new RegExp("^[a-zA-Zء-ي ]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}
e.preventDefault();
return false;
});


$('#signupform-username').keypress(function (e) {
      
var regex = new RegExp("^[a-zA-Zء-ي0-9_]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}
e.preventDefault();
return false;
});
$('#signupform-name').keypress(function (e) {
      
var regex = new RegExp("^[a-zA-Zء-ي ]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}
e.preventDefault();
return false;
});


function signform() {

	var fullname = $('#Users_name').val();
	var username = $('#Users_username').val();
	var email = $('#Users_email').val();
	var password = $('#Users_password').val();
	var confirmpassword = $('#Users_confirm_password').val();
	var reg = /[0-9]/gi;

	if (fullname == '')  {

		$("#Users_name_em_").show();
		$("#badmessage").hide();
		$('#Users_name_em_').text(yii.t('app','Name cannot be blank'));
		$('#Users_name').val('');
		$('#Users_name').focus();
		$('#Users_name').keydown(function() {
			$('#Users_name_em_').hide();
		});
	} else if ( fullname.length < 3) {
		
			$("#Users_name_em_").show();
			$('#Users_name_em_').text(yii.t('app','Name should have minimum 3 characters'));
			$('#Users_name').keydown(function() {
				$('#Users_name_em_').hide();
			});
			return false;
 
	} else if (specials.test(fullname)) {
		alert('check');
					$("#Users_name_em_").show();
					$('#Users_name_em_').text(yii.t('app', 'Special Characters not allowed.'));
					$('#Users_name').keydown(function() {
						$('#Users_name_em_').hide();
					});
					return false;
				} 
				else if (reg.test(fullname)) {
						$("#Users_name_em_").show();
							$('#Users_name_em_').text(yii.t('app', 'Numbers not allowed.'));
							$('#Users_name').keydown(function() {
								$('#Users_name_em_').hide();
							});
							return false;
						} 
						else {
									fullname = fullname.replace(/\s{2,}/g, ' ');
									$('#Users_name').val(fullname);
									$('#Users_name_em_').hide();
									return false;

								}
			
				

                 if (username == '') {
				$("#Users_username_em_").show();
				$('#Users_username_em_').text(yii.t('app', 'Username is required'));
				$('#Users_username').val('');
				$('#Users_username').focus();
				$('#Users_username').keydown(function() {
					$('#Users_username_em_').hide();
				});
		    	} else if (username.length < 3) {
	
						$("#Users_username_em_").show();
						$('#Users_username_em_').text(yii.t('app', 'Username should have minimum 3 characters'));
						$('#Users_username').keydown(function() {
							$('#Users_username_em_').hide();
						});
						return false;
				}  else if (specials.test(username)) {
	
							$("#Users_username_em_").show();
							$('#Users_username_em_').text(yii.t('app', 'Special Characters not allowed.'));
							$('#Users_username').keydown(function() {
								$('#Users_username_em_').hide();
							});
							return false;
				} 

	if (email == '') {
		
		$("#Users_email_em_").show();
		$("#badmessage").hide();
		$('#Users_email_em_').text(yii.t('app', 'Email is required'));
		$('#Users_email').focus();
		$('#Users_email').val('');
		$('#Users_email').keydown(function() {
			$('#Users_email_em_').hide();
		});
	} else 	if (!(isValidEmailAddress(email))) {
		
				$("#Users_email_em_").show();
				$("#badmessage").hide();
				$('#Users_email_em_').text(yii.t('app', 'Enter a valid email'));
				$('#Users_email').focus();
				$('#Users_email').keydown(function() {
					$('#Users_email_em_').hide();
				});
				return false;
	}


		if (password == '') {
		
					$("#Users_password_em_").show();
					$("#badmessage").hide();
					$('#Users_password_em_').text(yii.t('app', 'Password should not be empty'));
					$('#Users_password').focus();
					$('#Users_password').val('');
					$('#Users_password').keydown(function() {
						$('#Users_password_em_').hide();
					});
				} else if (password.length < 6) {
		
							$("#Users_password_em_").show();
							$("#badmessage").hide();
							$('#Users_password_em_').text(
									yii.t('app','Password must be greater than 5 characters long'));
							$('#Users_password').focus();
					
							$('#Users_password').keydown(function() {
								$('#Users_password_em_').hide();
							});
							return false;
						}
	     	if (confirmpassword == '') {
		
					$("#Users_confirm_password_em_").show();
					$('#Users_confirm_password_em_').text(yii.t('app', 'Confirm Password should not be empty'));
					$('#Users_confirm_password').focus();
					$('#Users_confirm_password').val('');
					$('#Users_confirm_password').keydown(function() {
						$('#Users_confirm_password_em_').hide();
					});
				} else	if (confirmpassword.length < 6) {		
							$("#Users_confirm_password_em_").show();
							$('#Users_confirm_password_em_').text(yii.t('app','Confirm Password must be greater than 5 characters long'));
							$('#Users_confirm_password').focus();
					
							$('#Users_confirm_password').keydown(function() {
								$('#Users_confirm_password_em_').hide();
							});
							return false;
				} else if (password != confirmpassword) {
		
							$("#Users_confirm_password_em_").show();
							$('#Users_confirm_password_em_').text(yii.t('app', 'Confirm password does not match'));
							$('#Users_confirm_password').focus();
					
							$('#Users_confirm_password').keydown(function() {
								$('#Users_confirm_password_em_').hide();
							});
							return false;
						}
			
		
	
		// $('#submitt').attr( 'value', '1' );
		// 		setTimeout(function () {
		// 			$('#submitt').removeAttr('value');
		// 		}, 3000);
	return false;
}

// 	if (fullname == '') {
		
// 		$("#Users_name_em_").show();
// 		$("#badmessage").hide();
// 		$('#Users_name_em_').text(yii.t('app','Name cannot be blank'));
// 		$('#Users_name').val('');
// 		$('#Users_name').focus();
// 		$('#Users_name').keydown(function() {
// 			$('#Users_name_em_').hide();
// 		});
// 		return true;
// 	} else {
// 		fullname = fullname.replace(/\s{2,}/g, ' ');
// 		$('#Users_name').val(fullname);
// 		$('#Users_name_em_').hide();
// 	}
// 	if (fullname.length < 3) {
		
// 		$("#Users_name_em_").show();
// 		$('#Users_name_em_').text(yii.t('app','Name should have minimum 3 characters'));
// 		$('#Users_name').keydown(function() {
// 			$('#Users_name_em_').hide();
// 		});
// 		return true;
// 	} else {
// 		$('#Users_name_em_').hide();
// 	}
// 	if (specials.test(fullname)) {
		
// 		$("#Users_name_em_").show();
// 		$('#Users_name_em_').text(yii.t('app', 'Special Characters not allowed.'));
// 		return true;
// 	} else {
// 		$('#Users_name_em_').hide();
// 	}

// 	if (reg.test(fullname)) {

	
// 		$("#Users_name_em_").show();
// 		$('#Users_name_em_').text(yii.t('app', 'Numbers not allowed.'));
// 		return true;
// 	} else {
// 		$('#Users_name_em_').hide();
// 	}

// 	if (username == '') {
		
// 		$("#Users_username_em_").show();
// 		$('#Users_username_em_').text(yii.t('app', 'Username is required'));
// 		$('#Users_username').val('');
// 		$('#Users_username').focus();
// 		$('#Users_username').keydown(function() {
// 			$('#Users_username_em_').hide();
// 		});
// 	return  true;
// 	} else {
// 		$('#Users_username_em_').hide();
// 	}

// 	if (username.length < 3) {
	
// 		$("#Users_username_em_").show();
// 		$('#Users_username_em_').text(yii.t('app', 'Username should have minimum 3 characters'));
// 		$('#Users_username').keydown(function() {
// 			$('#Users_username_em_').hide();
// 		});
// 		return true;
// 	} else {
// 		$('#Users_username_em_').hide();
// 	}

// 	if (specials.test(username)) {
	
// 		$("#Users_username_em_").show();
// 		$('#Users_username_em_').text(yii.t('app', 'Special Characters not allowed.'));
// 		return true;
// 	} else {
// 		$('#Users_username_em_').hide();
// 	}
// 	if (email == '') {
		
// 		$("#Users_email_em_").show();
// 		$("#badmessage").hide();
// 		$('#Users_email_em_').text(yii.t('app', 'Email is required'));
// 		$('#Users_email').focus();
// 		$('#Users_email').val('');
// 		$('#Users_email').keydown(function() {
// 			$('#Users_email_em_').hide();
// 		});
// 		return true;
// 	} else {
// 		$('#Users_email_em_').hide();
// 	}

// 	if (!(isValidEmailAddress(email))) {
		
// 		$("#Users_email_em_").show();
// 		$("#badmessage").hide();
// 		$('#Users_email_em_').text(yii.t('app', 'Enter a valid email'));
// 		$('#Users_email').focus();
// 		$('#Users_email').keydown(function() {
// 			$('#Users_email_em_').hide();
// 		});
// 	//	return false;
// 	} else {
// 		$('#Users_email_em_').hide();
// 	}

// 	if (password == '') {
		
// 		$("#Users_password_em_").show();
// 		$("#badmessage").hide();
// 		$('#Users_password_em_').text(yii.t('app', 'Password should not be empty'));
// 		$('#Users_password').focus();
// 		$('#Users_password').val('');
// 		// $('#Users_password')
// 		// 		.keydown(
// 		// 				function() {
// 		// 					$('#Users_password_em_').text(yii.t('app','Password must be greater than 5 characters long'));
// 		// 				});

// 	//	return false;
// 	} else {
// 		$('#Users_password_em_').hide();
// 	}
// 	if (confirmpassword == '') {
		
// 		$("#Users_confirm_password_em_").show();
// 		$('#Users_confirm_password_em_').text(yii.t('app', 'Confirm Password should not be empty'));
// 		$('#Users_confirm_password').focus();
// 		$('#Users_confirm_password').val('');
// 		// $('#Users_confirm_password')
// 		// 		.keydown(
// 		// 				function() {
// 		// 					$('#Users_confirm_password_em_').text(yii.t('app','Confirm Password must be greater than 5 characters long'));
// 		// 				});

// 	//	return false;
// 	} else {
// 		$('#Users_confirm_password_em_').hide();
// 	}

// 	if (password.length < 6) {
		
// 		$("#Users_password_em_").show();
// 		$("#badmessage").hide();
// 		$('#Users_password_em_').text(
// 				yii.t('app','Password must be greater than 5 characters long'));
// 		$('#Users_password').focus();

// 		$('#Users_password').keydown(function() {
// 			$('#Users_password_em_').hide();
// 		});
// 	//	return false;
// 	} else {
// 		$('#Users_password_em_').hide();
// 	}

// 	if (confirmpassword.length < 6) {
		
// 		$("#Users_confirm_password_em_").show();
// 		$('#Users_confirm_password_em_').text(yii.t('app','Confirm Password must be greater than 5 characters long'));
// 		$('#Users_confirm_password').focus();

// 		$('#Users_confirm_password').keydown(function() {
// 			$('#Users_confirm_password_em_').hide();
// 		});
// 	//	return false;
// 	} else {
// 		$('#Users_confirm_password_em_').hide();
// 	}
// 	if (password != confirmpassword) {
		
// 		$("#Users_confirm_password_em_").show();
// 		$('#Users_confirm_password_em_').text(yii.t('app', 'Confirm password does not match'));
// 		$('#Users_confirm_password').focus();

// 		$('#Users_confirm_password').keydown(function() {
// 			$('#Users_confirm_password_em_').hide();
// 		});
// 	//	return false;
// 	} else {
// 		$('#Users_confirm_password_em_').hide();
// 	}

// 	$('#submitt').attr( 'value', '1' );
// 		setTimeout(function () {
// 			$('#submitt').removeAttr('value');
// 		}, 3000);

// }


/*
function signform() {
	var fullname = $('#Users_name').val();
	var username = $('#Users_username').val();
	var email = $('#Users_email').val();
	var password = $('#Users_password').val();
	var confirmpassword = $('#Users_confirm_password').val();
	var reg = /[0-9]/gi;
	if (fullname == '') {
		
		$("#Users_name_em_").show();
		$("#badmessage").hide();
		$('#Users_name_em_').text(yii.t('app','Name cannot be blank'));
		$('#Users_name').val('');
		$('#Users_name').focus();
		$('#Users_name').keydown(function() {
			$('#Users_name_em_').hide();
		});
		return false;
	} else {
		fullname = fullname.replace(/\s{2,}/g, ' ');
		$('#Users_name').val(fullname);
		$('#Users_name_em_').hide();
	}
	if (fullname.length < 3) {
		
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('app','Name should have minimum 3 characters'));
		$('#Users_name').keydown(function() {
			$('#Users_name_em_').hide();
		});
		return false;
	} else {
		$('#Users_name_em_').hide();
	}
	if (specials.test(fullname)) {
		
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('app', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Users_name_em_').hide();
	}

	if (reg.test(fullname)) {

	
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('app', 'Numbers not allowed.'));
		return false;
	} else {
		$('#Users_name_em_').hide();
	}

	if (username == '') {
		
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(yii.t('app', 'Username is required'));
		$('#Users_username').val('');
		$('#Users_username').focus();
		$('#Users_username').keydown(function() {
			$('#Users_username_em_').hide();
		});
		return false;
	} else {
		$('#Users_username_em_').hide();
	}

	if (username.length < 3) {
	
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(yii.t('app', 'Username should have minimum 3 characters'));
		$('#Users_username').keydown(function() {
			$('#Users_username_em_').hide();
		});
		return false;
	} else {
		$('#Users_username_em_').hide();
	}

	if (specials.test(username)) {
	
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(yii.t('app', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Users_username_em_').hide();
	}
	if (email == '') {
		
		$("#Users_email_em_").show();
		$("#badmessage").hide();
		$('#Users_email_em_').text(yii.t('app', 'Email is required'));
		$('#Users_email').focus();
		$('#Users_email').val('');
		$('#Users_email').keydown(function() {
			$('#Users_email_em_').hide();
		});
		return false;
	} else {
		$('#Users_email_em_').hide();
	}

	if (!(isValidEmailAddress(email))) {
		
		$("#Users_email_em_").show();
		$("#badmessage").hide();
		$('#Users_email_em_').text(yii.t('app', 'Enter a valid email'));
		$('#Users_email').focus();
		$('#Users_email').keydown(function() {
			$('#Users_email_em_').hide();
		});
		return false;
	} else {
		$('#Users_email_em_').hide();
	}

	if (password == '') {
		
		$("#Users_password_em_").show();
		$("#badmessage").hide();
		$('#Users_password_em_').text(yii.t('app', 'Password should not be empty'));
		$('#Users_password').focus();
		$('#Users_password').val('');
		// $('#Users_password')
		// 		.keydown(
		// 				function() {
		// 					$('#Users_password_em_').text(yii.t('app','Password must be greater than 5 characters long'));
		// 				});

		return false;
	} else {
		$('#Users_password_em_').hide();
	}
	if (confirmpassword == '') {
		
		$("#Users_confirm_password_em_").show();
		$('#Users_confirm_password_em_').text(yii.t('app', 'Confirm Password should not be empty'));
		$('#Users_confirm_password').focus();
		$('#Users_confirm_password').val('');
		// $('#Users_confirm_password')
		// 		.keydown(
		// 				function() {
		// 					$('#Users_confirm_password_em_').text(yii.t('app','Confirm Password must be greater than 5 characters long'));
		// 				});

		return false;
	} else {
		$('#Users_confirm_password_em_').hide();
	}

	if (password.length < 6) {
		
		$("#Users_password_em_").show();
		$("#badmessage").hide();
		$('#Users_password_em_').text(
				yii.t('app','Password must be greater than 5 characters long'));
		$('#Users_password').focus();

		$('#Users_password').keydown(function() {
			$('#Users_password_em_').hide();
		});
		return false;
	} else {
		$('#Users_password_em_').hide();
	}

	if (confirmpassword.length < 6) {
		
		$("#Users_confirm_password_em_").show();
		$('#Users_confirm_password_em_').text(yii.t('app','Confirm Password must be greater than 5 characters long'));
		$('#Users_confirm_password').focus();

		$('#Users_confirm_password').keydown(function() {
			$('#Users_confirm_password_em_').hide();
		});
		return false;
	} else {
		$('#Users_confirm_password_em_').hide();
	}
	if (password != confirmpassword) {
		
		$("#Users_confirm_password_em_").show();
		$('#Users_confirm_password_em_').text(yii.t('app', 'Confirm password does not match'));
		$('#Users_confirm_password').focus();

		$('#Users_confirm_password').keydown(function() {
			$('#Users_confirm_password_em_').hide();
		});
		return false;
	} else {
		$('#Users_confirm_password_em_').hide();
	}


	$('#submitt').attr( 'value', '1' );
		setTimeout(function () {
			$('#submitt').removeAttr('value');
		}, 3000);

}
*/
function isValidEmailAddress(email) {
	var emailreg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	return emailreg.test(email);
}

function adminsignform() {
	var fullname = $('#Users_name').val().trim();
	var username = $('#Users_username').val().trim();
	username = username.replace(" ", "");
	$('#Users_username').val(username);
	var email = $('#Users_email').val().trim();
	var password = $('#Users_password').val().trim();
	var reg = /[0-9]/gi;
	if (fullname == '') {
		$("#Users_name_em_").show();
		$("#badmessage").hide();
		$('#Users_name_em_').text(yii.t('admin', 'Name is required'));
		$('#Users_name').val('');
		$('#Users_name').focus();
		$('#Users_name').keydown(function () {
			$('#Users_name_em_').hide();
		});
		return false;
	} else {
		fullname = fullname.replace(/\s{2,}/g, ' ');
		$('#Users_name').val(fullname);
		$('#Users_name_em_').hide();
	}
	if (fullname.length < 3) {
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('app', 'Name should have minimum three characters'));
		$('#Users_name').keydown(function () {
			$('#Users_name_em_').hide();
		});
		return false;
	} else {
		$('#Users_name_em_').hide();
	}
	if (specials.test(fullname)) {
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(
			yii.t('app', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Users_name_em_').hide();
	}

	if (reg.test(fullname)) {
		$("#Users_name_em_").closest('div.form-group').removeClass('success');
		$("#Users_name_em_").closest('div.form-group').addClass('error');
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('app', 'Numbers not allowed.'));
		return false;
	} else {
		$('#Users_name_em_').hide();
	}

	if (username == '') {
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(yii.t('app', 'Username is required'));
		$('#Users_username').val('');
		$('#Users_username').keydown(function () {
			$('#Users_username_em_').hide();
		});
		return false;
	} else {
		$('#Users_username_em_').hide();
	}
	if (specials.test(username)) {
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(
			yii.t('app', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Users_username_em_').hide();
	}

	if (email == '') {
		$("#Users_email_em_").show();
		$("#badmessage").hide();
		$('#Users_email_em_').text(yii.t('app', 'Email is required'));
		$('#Users_email').focus();
		$('#Users_email').val('');
		$('#Users_email').keydown(function () {
			$('#Users_email_em_').hide();
		});
		return false;
	} else {
		$('#Users_email_em_').hide();
	}

	if (!(isValidEmailAddress(email))) {
		$("#Users_email_em_").show();
		$("#badmessage").hide();
		$('#Users_email_em_').text(yii.t('admin', 'Enter a valid email'));
		$('#Users_email').focus();
		$('#Users_email').keydown(function () {
			$('#Users_email_em_').hide();
		});
		return false;
	} else {
		$('#Users_email_em_').hide();
	}

	if (password == '') {
		$("#Users_password_em_").show();
		$("#badmessage").hide();
		$('#Users_password_em_').text(
			yii.t('admin', 'Password should not be empty'));
		$('#Users_password').focus();
		$('#Users_password').val('');
		$('#Users_password')
		.keydown(
			function () {
				$('#Users_password_em_')
				.text(
					yii
					.t('admin',
						'Password must be greater than 5 characters long'));
			});

		return false;
	} else {
		$('#Users_password_em_').hide();
	}
	if (password.length < 6) {
		$("#Users_password_em_").show();
		$("#badmessage").hide();
		$('#Users_password_em_').text(
			yii.t('admin',
				'Password must be greater than 5 characters long'));
		$('#Users_password').focus();

		$('#Users_password').keydown(function () {
			$('#Users_password_em_').hide();
		});
		return false;
	} else {
		$('#Users_password_em_').hide();
	}

}
// function signform() {
// 	//alert(1);
// 	var fullname = $('#Users_name').val().trim();
// 	//alert(fullname);
// 	var username = $('#Users_username').val().trim();
// 	var email = $('#Users_email').val().trim();
// 	var password = $('#Users_password').val().trim();
// 	var confirmpassword = $('#Users_confirm_password').val().trim();
// 	var reg = /[0-9]/gi;
// 	var val=0;
// 	if (fullname == '') {
// 		val=1;
// 		// $("#Users_name_em_").closest('div.row').removeClass('success');
// 		// $("#Users_name_em_").closest('div.row').addClass('error');
// 		$("#Users_name_em_").show();
// 		// $("#badmessage").hide();
// 		$('#Users_name_em_').text(yii.t('app','Name cannot be blank'));
// 		$('#Users_name').val('');
// 		$('#Users_name').focus();
// 		$('#Users_name').keydown(function () {
// 			$('#Users_name_em_').hide();
// 		});
		
		
// 	}
// 	else{
// 		if (fullname.length < 3) {
// 			val=1;
// 			$("#Users_name_em_").closest('div.row').removeClass('success');
// 			$("#Users_name_em_").closest('div.row').addClass('error');
// 			$("#Users_name_em_").show();
// 			$('#Users_name_em_').text(yii.t('app','Name should have minimum 3 characters'));
// 			$('#Users_name').keydown(function () {
// 				$('#Users_name_em_').hide();
// 			});

// 		} 
// 		if (specials.test(fullname)) {
// 			val=1;
// 			$("#Users_name_em_").closest('div.row').removeClass('success');
// 			$("#Users_name_em_").closest('div.row').addClass('error');
// 			$("#Users_name_em_").show();
// 			$('#Users_name_em_').text(yii.t('app','Special Characters not allowed.'));

// 		} 
// 		if (reg.test(fullname)) {
// 			val=1;
// 			$("#Users_name_em_").closest('div.row').removeClass('success');
// 			$("#Users_name_em_").closest('div.row').addClass('error');
// 			$("#Users_name_em_").show();
// 			$('#Users_name_em_').text(yii.t('app','Numbers not allowed.'));

// 		}

// 	}
	
	

	

// 	if (username == '') {
// 		val=1;
// 		$("#Users_username_em_").closest('div.row').removeClass('success');
// 		$("#Users_username_em_").closest('div.row').addClass('error');
// 		$("#Users_username_em_").show();
// 		$('#Users_username_em_').text(yii.t('app','Username cannot be blank'));
// 		$('#Users_username').val('');
// 		$('#Users_username').keydown(function () {
// 			$('#Users_username_em_').hide();
// 		});
		
// 	}
// 	else{
		
// 		if (username.length < 3) {
// 			$("#Users_username_em_").closest('div.row').removeClass('success');
// 			$("#Users_username_em_").closest('div.row').addClass('error');
// 			$("#Users_username_em_").show();
// 			$('#Users_username_em_').text(yii.t('app','Username should have minimum 3 characters'));
// 			$('#Users_username').keydown(function () {
// 				$('#Users_username_em_').hide();
// 			});

// 		}
// 		if (specials.test(username)) {
// 			val=1;
// 			$("#Users_username_em_").closest('div.row').removeClass('success');
// 			$("#Users_username_em_").closest('div.row').addClass('error');
// 			$("#Users_username_em_").show();
// 			$('#Users_username_em_').text(yii.t('app','Special Characters not allowed.'));

// 		} 

// 	}

	

	
// 	if (email == '') {
// 		$("#Users_email_em_").closest('div.row').removeClass('success');
// 		$("#Users_email_em_").closest('div.row').addClass('error');
// 		$("#Users_email_em_").show();
// 		$("#badmessage").hide();
// 		$('#Users_email_em_').text(yii.t('app','Email cannot be blank'));
// 		//$('#Users_email').focus();
// 		$('#Users_email').val('');
// 		$('#Users_email').keydown(function () {
// 			$('#Users_email_em_').hide();
// 		});
		
// 	} 
// 	else{
// 		if (!(isValidEmailAddress(email))) {
// 			val=1;
// 			$("#Users_email_em_").closest('div.row').removeClass('success');
// 			$("#Users_email_em_").closest('div.row').addClass('error');
// 			$("#Users_email_em_").show();
// 			$("#badmessage").hide();
// 			$('#Users_email_em_').text(yii.t('app','Enter a valid email'));
// 		//$('#Users_email').focus();
// 		$('#Users_email').keydown(function () {
// 			$('#Users_email_em_').hide();
// 		});
		
// 	} 
	
// }



// if (password == '') {
// 	$("#Users_password_em_").closest('div.row').removeClass('success');
// 	$("#Users_password_em_").closest('div.row').addClass('error');
// 	$("#Users_password_em_").show();
// 	$("#badmessage").hide();
// 	$('#Users_password_em_').text(yii.t('app','Password cannot be blank'));
// 		//$('#Users_password').focus();
// 		$('#Users_password').val('');
// 		$('#Users_password')
// 		.keydown(
// 			function () {
// 				//	$('#Users_password_em_').text('Password must be greater than 5 characters long');
// 				$('#Users_password_em_').hide();
// 			});

		
// 	}
// 	else{
// 		if (password.length < 6) {
// 			val=1;
// 			$("#Users_password_em_").closest('div.row').removeClass('success');
// 			$("#Users_password_em_").closest('div.row').addClass('error');
// 			$("#Users_password_em_").show();
// 			$("#badmessage").hide();
// 			$('#Users_password_em_').text(yii.t('app','Password must be greater than 5 characters long'));
// 		//$('#Users_password').focus();

// 		$('#Users_password').keydown(function () {
// 			$('#Users_password_em_').hide();
// 		});
		
// 	} 
	
// }
// if (confirmpassword == '') {
// 	$("#Users_confirm_password_em_").closest('div.row').removeClass('success');
// 	$("#Users_confirm_password_em_").closest('div.row').addClass('error');
// 	$("#Users_confirm_password_em_").show();
// 	$('#Users_confirm_password_em_').text(yii.t('app','Confirm password cannot be blank'));
// 		//$('#Users_confirm_password').focus();
// 		$('#Users_confirm_password').val('');
// 		$('#Users_confirm_password')
// 		.keydown(
// 			function () {
// 					$('#Users_confirm_password_em_').hide();
// 				});

		
// 	} 
// 	else{
// 		if (confirmpassword.length < 6) {
// 			val=1;
// 			$("#Users_confirm_password_em_").closest('div.row').removeClass('success');
// 			$("#Users_confirm_password_em_").closest('div.row').addClass('error');
// 			$("#Users_confirm_password_em_").show();
// 			$('#Users_confirm_password_em_').text(yii.t('app','Confirm Password must be greater than 5 characters long'));
// 		//$('#Users_confirm_password').focus();

// 		$('#Users_confirm_password').keydown(function () {
// 			$('#Users_confirm_password_em_').hide();
// 		});
		
// 	} 
	
// }




// if (password != confirmpassword) {
// 	val=1;
// 	$("#Users_confirm_password_em_").closest('div.row').removeClass(
// 		'success');
// 	$("#Users_confirm_password_em_").closest('div.row').addClass('error');
// 	$("#Users_confirm_password_em_").show();
// 	$('#Users_confirm_password_em_').text(yii.t('app','Confirm password does not match'));
// 		//$('#Users_confirm_password').focus();

// 		$('#Users_confirm_password').keydown(function () {
// 			$('#Users_confirm_password_em_').hide();
// 		});
		
// 	} 
	
// 	if (val==1) {
// 		return false;
// 	}



// 	if(val==0){
// 		$('#signup-btn').attr( 'name', 'submit' );
// 		//$(".loading").css("display","block");
// 		//$('#signup-btn').html('Please wait');
// 		setTimeout(function () {
// 			$('#signup-btn').removeAttr('name');
// 		}, 2000);
		

// 	}

// }

function signformpage() {
	var fullname = $('#Users_name').val().trim();
	var username = $('#Users_username').val().trim();
	var email = $('#Users_email').val().trim();
	var password = $('#Users_password').val().trim();
	var confirmpassword = $('#Users_confirm_password').val().trim();
	var reg = /[0-9]/gi;
	if (fullname == '') {
		$("#Users_name_em_").closest('div.row').removeClass('success');
		$("#Users_name_em_").closest('div.row').addClass('error');
		$("#Users_name_em_").show();
		$("#badmessage").hide();
		$('#Users_name_em_').text(yii.t('admin', 'Name cannot be blank'));
		$('#Users_name').val('');
		$('#Users_name').focus();
		$('#Users_name').keydown(function () {
			$('#Users_name_em_').hide();
		});
		return false;
	} else {
		fullname = fullname.replace(/\s{2,}/g, ' ');
		$('#Users_name').val(fullname);
		$('#Users_name_em_').hide();
	}
	if (fullname.length < 3) {
		$("#Users_name_em_").closest('div.row').removeClass('success');
		$("#Users_name_em_").closest('div.row').addClass('error');
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('admin', 'Name should have minimum 3 characters'));
		$('#Users_name').keydown(function () {
			$('#Users_name_em_').hide();
		});
		return false;
	} else {
		$('#Users_name_em_').hide();
	}
	if (specials.test(fullname)) {
		$("#Users_name_em_").closest('div.row').removeClass('success');
		$("#Users_name_em_").closest('div.row').addClass('error');
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('admin', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Users_name_em_').hide();
	}

	if (reg.test(fullname)) {

		$("#Users_name_em_").closest('div.row').removeClass('success');
		$("#Users_name_em_").closest('div.row').addClass('error');
		$("#Users_name_em_").show();
		$('#Users_name_em_').text(yii.t('admin', 'Numbers not allowed.'));
		return false;
	} else {
		$('#Users_name_em_').hide();
	}

	if (username == '') {
		$("#Users_username_em_").closest('div.row').removeClass('success');
		$("#Users_username_em_").closest('div.row').addClass('error');
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(yii.t('admin', 'Username is required'));
		$('#Users_username').val('');
		$('#Users_username').keydown(function () {
			$('#Users_username_em_').hide();
		});
		return false;
	} else {
		$('#Users_username_em_').hide();
	}

	if (username.length < 3) {
		$("#Users_username_em_").closest('div.row').removeClass('success');
		$("#Users_username_em_").closest('div.row').addClass('error');
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(yii.t('admin', 'Username should have minimum 3 characters'));
		$('#Users_username').keydown(function () {
			$('#Users_username_em_').hide();
		});
		return false;
	} else {
		$('#Users_username_em_').hide();
	}

	if (specials.test(username)) {
		$("#Users_username_em_").closest('div.row').removeClass('success');
		$("#Users_username_em_").closest('div.row').addClass('error');
		$("#Users_username_em_").show();
		$('#Users_username_em_').text(yii.t('admin', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Users_username_em_').hide();
	}
	if (email == '') {
		$("#Users_email_em_").closest('div.row').removeClass('success');
		$("#Users_email_em_").closest('div.row').addClass('error');
		$("#Users_email_em_").show();
		$("#badmessage").hide();
		$('#Users_email_em_').text(yii.t('admin', 'Email is required'));
		$('#Users_email').focus();
		$('#Users_email').val('');
		$('#Users_email').keydown(function () {
			$('#Users_email_em_').hide();
		});
		return false;
	} else {
		$('#Users_email_em_').hide();
	}

	if (!(isValidEmailAddress(email))) {
		$("#Users_email_em_").closest('div.row').removeClass('success');
		$("#Users_email_em_").closest('div.row').addClass('error');
		$("#Users_email_em_").show();
		$("#badmessage").hide();
		$('#Users_email_em_').text(yii.t('admin', 'Enter a valid email'));
		$('#Users_email').focus();
		$('#Users_email').keydown(function () {
			$('#Users_email_em_').hide();
		});
		return false;
	} else {
		$('#Users_email_em_').hide();
	}

	if (password == '') {
		$("#Users_password_em_").closest('div.row').removeClass('success');
		$("#Users_password_em_").closest('div.row').addClass('error');
		$("#Users_password_em_").show();
		$("#badmessage").hide();
		$('#Users_password_em_').text(yii.t('admin', 'Password should not be empty'));
		$('#Users_password').focus();
		$('#Users_password').val('');
		$('#Users_password')
		.keydown(
			function () {
				$('#Users_password_em_').text(yii.t('admin', 'Password must be greater than 5 characters long'));
			});

		return false;
	} else {
		$('#Users_password_em_').hide();
	}
	if (confirmpassword == '') {
		$("#Users_confirm_password_em_").closest('div.row').removeClass('success');
		$("#Users_confirm_password_em_").closest('div.row').addClass('error');
		$("#Users_confirm_password_em_").show();
		$('#Users_confirm_password_em_').text(yii.t('admin', 'Confirm Password should not be empty'));
		$('#Users_confirm_password').focus();
		$('#Users_confirm_password').val('');
		$('#Users_confirm_password')
		.keydown(
			function () {
				$('#Users_confirm_password_em_').text(yii.t('admin', 'Confirm Password must be greater than 5 characters long'));
			});

		return false;
	} else {
		$('#Users_confirm_password_em_').hide();
	}

	if (password.length < 6) {
		$("#Users_password_em_").closest('div.row').removeClass('success');
		$("#Users_password_em_").closest('div.row').addClass('error');
		$("#Users_password_em_").show();
		$("#badmessage").hide();
		$('#Users_password_em_').text(
			yii.t('admin', 'Password must be greater than 5 characters long'));
		$('#Users_password').focus();

		$('#Users_password').keydown(function () {
			$('#Users_password_em_').hide();
		});
		return false;
	} else {
		$('#Users_password_em_').hide();
	}

	if (confirmpassword.length < 6) {
		$("#Users_confirm_password_em_").closest('div.row').removeClass('success');
		$("#Users_confirm_password_em_").closest('div.row').addClass('error');
		$("#Users_confirm_password_em_").show();
		$('#Users_confirm_password_em_').text(yii.t('admin', 'Confirm Password must be greater than 5 characters long'));
		$('#Users_confirm_password').focus();

		$('#Users_confirm_password').keydown(function () {
			$('#Users_confirm_password_em_').hide();
		});
		return false;
	} else {
		$('#Users_confirm_password_em_').hide();
	}
	if (password != confirmpassword) {
		$("#Users_confirm_password_em_").closest('div.row').removeClass(
			'success');
		$("#Users_confirm_password_em_").closest('div.row').addClass('error');
		$("#Users_confirm_password_em_").show();
		$('#Users_confirm_password_em_').text(yii.t('admin', 'Confirm password does not match'));
		$('#Users_confirm_password').focus();

		$('#Users_confirm_password').keydown(function () {
			$('#Users_confirm_password_em_').hide();
		});
		return false;
	} else {
		$('#Users_confirm_password_em_').hide();
	}

}

function validforgot() {
	$('input.forgot-btn').prop('disabled', true);
	var email = $('.forgetpasswords').val();
	
	if (email == '' || email == undefined) {
		$('#Users_emails_em_').show();
		$('#Users_emails_em_').text(yii.t('app','Email cannot be blank'));
		$('input.forgot-btn').prop('disabled', false);
		return false;
	} else if (!isValidEmailAddress(email)) {
		$('#Users_emails_em_').show();
		$('#Users_emails_em_').text(yii.t('app','Please Enter a valid Email'));
		$('input.forgot-btn').prop('disabled', false);
		return false;
	} else {
		if (mailcheck == 1) {

			$.ajax({
				url: baseUrl+'/site/check_mailstatus/',
				type: "post",
				dataType: "html",
				data: {
					'email': email,
				},

				success: function (responce) {
					//alert(responce);
					var result = responce.split('-');
					if ($.trim(result[0]) == '0') {
						$('#Users_emails_em_').show();
						$('#Users_emails_em_').text(yii.t('app','Email not found'));
						$('input.forgot-btn').prop('disabled', false);
					} else if ($.trim(result[0]) == '1') {
						$('#Users_emails_em_').show();
						$('#Users_emails_em_').text(yii.t('app','User is not verified. Activate the account to click the following link'));
						$('#Users_emails_em_').append('<a href="' +baseUrl+ '/site/verifymail/?id=' + result[1] + '" id="email_verify_link">' + yii.t('app','Click') + '</a>');
						$('input.forgot-btn').prop('disabled', false);
					} else {
						// $('input.forgot-btn').prop('disabled', true);
						mailcheck = 0;
						$('#Users_emails_em_').hide();
						$('#forgetpassword-form').submit();

					}
				}
			});
			return false;
		}
		return true;

	}

}



function validreset() {
	var resetpassword = $('#Resetpassword_resetpassword').val();
	var confirmpassword = $('#Resetpassword_confirmpassword').val();
	$('.errorMessage').hide();
	if (resetpassword == '') {
		$('#resetpassword_em_').show();
		$('#resetpassword_em_').text(yii.t('app', 'Password cannot be blank'));
		return false;
	} else if (confirmpassword == '') {
		$('#confirmpassword_em_').show();
		$('#confirmpassword_em_').text(yii.t('app', 'Confirm password cannot be blank'));
		return false;
	} else if (confirmpassword != resetpassword) {
		$('#confirmpassword_em_').show();
		$('#confirmpassword_em_').text(yii.t('app', 'Confirm password should match with new password'));
		return false;
	} else {
		$('#resetpassword_em_').hide();
		$('#confirmpassword_em_').hide();
	}

	$(document).on('submit', '#resetpassword-form', function () {
		$('.forgotBtn').attr('disabled', 'disabled');
	});
}


function validaddship() {
	var name = $('#Tempaddresses_name').val();
	var nickName = $('#Tempaddresses_nickname').val();
	var country = $('#Tempaddresses_country').val();
	var state = $('#Tempaddresses_state').val();
	var add1 = $('#Tempaddresses_address1').val();
	var add2 = $('#Tempaddresses_address2').val();
	var city = $('#Tempaddresses_city').val();
	var zip = $('#Tempaddresses_zipcode').val();
	var phone = $('#Tempaddresses_phone').val();
	var reg = /[0-9]/gi;

	if (nickName == '') {
		$("#Tempaddresses_nickname_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_nickname_em_').text(yii.t('app','Enter your nickname'));
		$('#Tempaddresses_nickname').focus();
		$('#Tempaddresses_nickname').keydown(function () {
			$('#Tempaddresses_nickname_em_').hide();
		});
		return false;
	} else {
		var nick = nickName.replace(/\s/g, '');
		$('#Tempaddresses_nickname').val(nick);
		if (specials.test(nickName)) {
			$("#Tempaddresses_nickname_em_").closest('div.form-group')
			.removeClass('success');
			$("#Tempaddresses_nickname_em_").closest('div.form-group')
			.addClass('error');
			$("#Tempaddresses_nickname_em_").show();
			$('#Tempaddresses_nickname_em_').text(yii.t('app','Special Characters not allowed.'));
			return false;
		} else {
			$('#Tempaddresses_nickname_em_').hide();
		}
		if (reg.test(nickName)) {
			$("#Tempaddresses_nickname_em_").closest('div.form-group')
			.removeClass('success');
			$("#Tempaddresses_nickname_em_").closest('div.form-group')
			.addClass('error');
			$("#Tempaddresses_nickname_em_").show();
			$('#Tempaddresses_nickname_em_').text(yii.t('app','Numbers not allowed.'));
			return false;
		} else {
			$('#Tempaddresses_nickname_em_').hide();
		}
	}
	if (name == '') {
		$("#Tempaddresses_name_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_name_em_')
		.text(yii.t('app','Full name is required'));
		$('#Tempaddresses_name').focus();
		$('#Tempaddresses_name').keydown(function () {
			$('#Tempaddresses_name_em_').hide();
		});
		return false;
	} else {
		name = name.replace(/\s{2,}/g, ' ');
		$('#Tempaddresses_name').val(name);
		if (specials.test(name)) {
			$("#Tempaddresses_name_em_").closest('div.form-group').removeClass(
				'success');
			$("#Tempaddresses_name_em_").closest('div.form-group').addClass(
				'error');
			$("#Tempaddresses_name_em_").show();
			$('#Tempaddresses_name_em_').text(yii.t('app','Special Characters not allowed.'));
			return false;
		} else {
			$('#Tempaddresses_name_em_').hide();
		}
		if (reg.test(name)) {
			$("#Tempaddresses_name_em_").closest('div.form-group').removeClass(
				'success');
			$("#Tempaddresses_name_em_").closest('div.form-group').addClass(
				'error');
			$("#Tempaddresses_name_em_").show();
			$('#Tempaddresses_name_em_').text(yii.t('app','Numbers not allowed.'));
			return false;
		} else {
			$('#Tempaddresses_name_em_').hide();
		}
		if (name.length < 3) {
			$("#Tempaddresses_name_em_").closest('div.form-group').removeClass(
				'success');
			$("#Tempaddresses_name_em_").closest('div.form-group').addClass(
				'error');
			$("#Tempaddresses_name_em_").show();
			$('#Tempaddresses_name_em_').text(yii.t('app','Name should be minimum 3 characters'));
			return false;
		} else {
			$('#Tempaddresses_name_em_').hide();
		}
	}

	if (country == '') {
		$("#Tempaddresses_country_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_country_em_')
		.text(yii.t('app','Enter your country'));
		$('#Tempaddresses_country').focus();
		$('#Tempaddresses_country').keydown(function () {
			$('#Tempaddresses_country_em_').hide();
		});
		return false;
	}

	if (add1 == '') {
		$("#Tempaddresses_address1_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_address1_em_').text(yii.t('app','Enter your address'));
		$('#Tempaddresses_address1').focus();
		$('#Tempaddresses_address1').keydown(function () {
			$('#Tempaddresses_address1_em_').hide();
		});
		return false;
	} else if (add1.length < 3) {
		$("#Tempaddresses_address1_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_address1_em_').text(yii.t('app','Address should be minimum 3 characters.'));
		$('#Tempaddresses_address1').focus();
		$('#Tempaddresses_address1').keydown(function () {
			$('#Tempaddresses_address1_em_').hide();
		});
		return false;
	} else {
		$('#Tempaddresses_address1_em_').hide();
	}



	if (city == '') {
		$("#Tempaddresses_city_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_city_em_').text(yii.t('app','Enter your city'));
		$('#Tempaddresses_city').focus();
		$('#Tempaddresses_city').keydown(function () {
			$('#Tempaddresses_city_em_').hide();
		});
		return false;
	} else {
		if (specials.test(city)) {
			$("#Tempaddresses_city_em_").closest('div.form-group').removeClass(
				'success');
			$("#Tempaddresses_city_em_").closest('div.form-group').addClass(
				'error');
			$("#Tempaddresses_city_em_").show();
			$('#Tempaddresses_city_em_').text(yii.t('app','Special Characters not allowed.'));
			return false;
		} else {
			$('#Tempaddresses_city_em_').hide();
		}
		if (reg.test(city)) {
			$("#Tempaddresses_city_em_").closest('div.form-group').removeClass('success');
			$("#Tempaddresses_city_em_").closest('div.form-group').addClass('error');
			$("#Tempaddresses_city_em_").show();
			$('#Tempaddresses_city_em_').text(yii.t('app','Numbers not allowed.'));
			return false;
		} else if (city.length < 2) {
			$("#Tempaddresses_city_em_").closest('div.form-group').removeClass('success');
			$("#Tempaddresses_city_em_").closest('div.form-group').addClass('error');
			$("#Tempaddresses_city_em_").show();
			$('#Tempaddresses_city_em_').text(yii.t('app','City should be minimum 2 characters.'));
			return false;
		} else {
			$('#Tempaddresses_city_em_').hide();
		}
	}
	if (state == '') {
		$("#Tempaddresses_state_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_state_em_').text(yii.t('app','Enter your state'));
		$('#Tempaddresses_state').focus()
		$('#Tempaddresses_state').keydown(function () {
			$('#Tempaddresses_state_em_').hide();
		})
		return false;
	} else {
		if (specials.test(state)) {
			$("#Tempaddresses_state_em_").closest('div.form-group')
			.removeClass('success');
			$("#Tempaddresses_state_em_").closest('div.form-group').addClass(
				'error');
			$("#Tempaddresses_state_em_").show();
			$('#Tempaddresses_state_em_').text(yii.t('app','Special Characters not allowed.'));
			return false;
		} else {
			$('#Tempaddresses_state_em_').hide();
		}
		if (reg.test(state)) {
			$("#Tempaddresses_state_em_").closest('div.form-group').removeClass('success');
			$("#Tempaddresses_state_em_").closest('div.form-group').addClass('error');
			$("#Tempaddresses_state_em_").show();
			$('#Tempaddresses_state_em_').text(yii.t('app','Numbers not allowed.'));
			return false;
		} else if (state.length < 2) {
			$("#Tempaddresses_state_em_").closest('div.form-group').removeClass('success');
			$("#Tempaddresses_state_em_").closest('div.form-group').addClass('error');
			$("#Tempaddresses_state_em_").show();
			$('#Tempaddresses_state_em_').text(yii.t('app','State should be minimum 2 characters.'));
			return false;
		} else {
			$('#Tempaddresses_state_em_').hide();
		}
	}
	if (zip == '') {
		$("#Tempaddresses_zipcode_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_zipcode_em_').text(yii.t('app','Enter your area code'));
		$('#Tempaddresses_zipcode').focus();
		$('#Tempaddresses_zipcode').keydown(function () {
			$('#Tempaddresses_zipcode_em_').hide();
		});
		return false;
	} else {
		if (specials.test(zip)) {
			$("#Tempaddresses_zipcode_em_").closest('div.form-group')
			.removeClass('success');
			$("#Tempaddresses_zipcode_em_").closest('div.form-group').addClass(
				'error');
			$("#Tempaddresses_zipcode_em_").show();
			$('#Tempaddresses_zipcode_em_').text(yii.t('app','Special Characters not allowed.'));
			return false;
		} else {
			$('#Tempaddresses_zipcode_em_').hide();
		}
	}
	if (phone == '') {
		$("#Tempaddresses_phone_em_").show();
		$("#badMessage").hide();
		$('#Tempaddresses_phone_em_').text(yii.t('app','Enter your phone no'));
		$('#Tempaddresses_phone').focus();
		$('#Tempaddresses_phone').keydown(function () {
			$('#Tempaddresses_phone_em_').hide();
		});
		return false;
	} else {
		var check = /^[0-9]+$/;
		if (reg.test(phone)) {
			$("#Tempaddresses_phone_em_").hide();

		} else {
			$("#Tempaddresses_phone_em_").show();
			$("#Tempaddresses_phone_em_").html(yii.t('app','Only numbers allowed.'));
			return false;
		}
	}
	$(document).on('submit', '#shippingaddress-form', function () {
		$('#shipping_btn').attr('disabled', 'disabled');
	});
	//return true;
}

function couponValidate() {
	if ($("#couponValue").val() == "") {
		$("#Coupons_couponValue_em_").show();
		$("#Coupons_couponValue_em_").html(
			yii.t('app', "Coupon Value cannot be blank"));
	} else {
		$("#Coupons_couponValue_em_").hide();
	}
	if ($("#Coupons_startDate").val() == "") {
		$("#Coupons_startDate_em_").show();
		$("#Coupons_startDate_em_").html(
			yii.t('app', "Coupon Start date cannot be blank"));
	} else {
		$("#Coupons_startDate_em_").hide();
	}
	if ($("#Coupons_endDate").val() == "") {
		$("#Coupons_endDate_em_").show();
		$("#Coupons_endDate_em_").html(
			yii.t('app', "Coupon End date cannot be blank"));
	} else {
		$("#Coupons_endDate_em_").hide();
	}

}

function isNumber(eve) {
	var charCode = (eve.which) ? eve.which : event.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}


function validateProduct() {
	var name = $("#Products_name").val().trim();
	var inp = document.getElementById('image_file');    
	uploadedfiles = $("#uploadedfiles").val();
	var pcount = $("#pcount").val();
	
	var currentDevice = $("#currentDevice").val();

	//Add validation to attribute fields.
	var validate = 0;
	var attributeSubmit = false;
	var rangeSubmit = false;
	var rangeValcheckbelow = false;
	var rangeValcheckabove = false;
	var rangenumchk =false;

	var cat = $("#Products_category").val();
	var givingAway = $("#giving_away").val();
	var price = $("#Products_price").val().trim();
	var insbuy = $("#Products_instantBuy").val();
	var proCond = $("#Products_productCondition").val();
	var location = $("#Products_location").val();
	var latitude = $("#latitude").val();
	var longitude = $("#longitude").val();
	var pattern = /^\d{0,6}(\.{1}\d{0,2})?$/g;
	var productImage=parseInt(document.getElementById('count').value,10);
	var videoUrl = $("#videoUrl").val();
	var videoPattern = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
	
    if(videoUrl != "") {
    
	if (!videoPattern.test(videoUrl)) {    
		$("#Products_videourl_").show();
		$("#badMessage").hide();
		$('#Products_videourl_').text(yii.t('app','Invaild Video Url'));
		$('#videoUrl').focus();
		$('#videoUrl').keydown(function () {
			$('#Products_videourl_').hide();
		});
		return false;
		
	}
	}
	
	
	if (productImage == 0) {
		$("#image_error").show();
		$("#badMessage").hide();
		//$('#Products_name').focus();
		$('html, body').animate({ scrollTop: $('#products-form').offset().top }, 'slow');  
		$('#image_error').text(yii.t('app','Upload atleast a single product image'));
		/*
		setTimeout(function () {
			$('#image_error').fadeOut('slow');
		}, 3000);
		*/
		return false;
	}else{
		$("#image_error").hide();
	}

	if (productImage > 5) {
		$("#image_error").show();
		$("#badMessage").hide();
		$('html, body').animate({ scrollTop: $('#products-form').offset().top }, 'slow');  
		$('#image_error').text(yii.t('app','You can upload 5 images only..'));
		setTimeout(function () {
			$('#image_error').fadeOut('slow');
		}, 3000);
		return false;
	}

	// if (uploadedfiles == "" || pcount == "0") {


	// }

	if (cat == "") {

		$("#Products_category_em_").show();
		$("#badMessage").hide();
		$('#Products_category_em_').text(yii.t('app','Product Category cannot be blank'));
		//$('#Products_category').focus();
		$('html, body').animate({ scrollTop: $('#products-form').offset().top }, 'slow'); 
		$('#Products_category').change(function () {
			$('#Products_category_em_').hide();
		});
		setTimeout(function () {
			$('#Products_category_em_').fadeOut('slow');
		}, 3000);
		return false;
	}


	var subCatelength = $('#Products_subCategory').children('option').length;
	var subCatevalue = $('#Products_subCategory').val();

	if(subCatelength  > 1 && subCatevalue == '')
	{
		$("#Products_subcategory_em_").show();
		$("#badMessage").hide();
		$('#Products_subcategory_em_').text(yii.t('app','Product subCategory cannot be blank'));
		//$('#Products_subCategory').focus();
		$('html, body').animate({ scrollTop: $('#products-form').offset().top }, 'slow'); 
		$('#Products_subCategory').change(function () {
			$('#Products_subcategory_em_').hide();
		});
		setTimeout(function () {
			$('#Products_subcategory_em_').fadeOut('slow');
		}, 3000);
		return false;
	}

	var sub_subCatelength = $('#Products_sub_subCategory').children('option').length;
	var sub_subCatevalue = $('#Products_sub_subCategory').val();

	if(sub_subCatelength  > 1 && sub_subCatevalue == '')
	{
		
		$("#Products_sub_subCategory_em_").show();
		$("#badMessage").hide();
		 //alert('ok com');
		$('#Products_sub_subCategory_em_').text(yii.t('app','Product child category cannot be blank'));
		$('html, body').animate({ scrollTop: $('#products-form').offset().top }, 'slow');
		$('#Products_sub_subCategory').change(function () {
			$('#Products_sub_subCategory_em_').hide();
		});
		setTimeout(function () {
			$('#Products_sub_subCategory_em_').fadeOut('slow');
		}, 3000);
		return false;
	}
	
	//return false;


	//alert(validate);
	//return false;
	//if(validate == 1)
	//	return false;
	//alert('value noy empty');
	//return false;
	//Add validation to range fields.
	$(".productattributerange").each(function(){
	   var attributerangeid = $(this).attr('id');
	   //alert($('#'+attributerangeid).val());
	   if($('#'+attributerangeid).val() == '')
	   {
	   		rangeSubmit = attributerangeid;
	   		//$('.'+attributerangeid).html('Cannot be blank');
	   		return false;

	   }else{

	   		var rangeValue = $('#'+attributerangeid+'_values').val();
	   		var inputRange = $('#'+attributerangeid).val();
	   		if(inputRange % 1 !== 0)
	   		{
	   			rangenumchk = attributerangeid;
	   		
	   			return false;
	   		}
	   		var split = rangeValue.split(';');
	   		
	   		if(	parseInt(inputRange) < parseInt(split[0]) )
	   		{
	   			rangeValcheckbelow = attributerangeid;
	   			rangeval = split[0]+' - '+split[1];
	   			$('.'+attributerangeid).html('Values between '+split[0]+' - '+split[1]);
	   			return false;
	   		}else if(parseInt(inputRange) > parseInt(split[1]))
	   		{
	   			rangeValcheckabove = attributerangeid;
	   			rangeval = split[0]+' - '+split[1];
	   			$('.'+attributerangeid).html('Values between '+split[0]+' - '+split[1]);
	   			return false;
	   		}
	   }
	});

	if (rangeSubmit) {
		
		$('.'+rangeSubmit).show();
		$('html, body').animate({ scrollTop: $('#Products_subCategory').offset().top }, 'slow'); 
   		$('.'+rangeSubmit).html('Cannot be blank');
   		setTimeout(function () {
					$('.'+rangeSubmit).fadeOut('slow');
				}, 3000);
    	return false;
	}

	if (rangenumchk) {
		
		$('.'+rangenumchk).show();
		$('html, body').animate({ scrollTop: $('.'+rangenumchk).offset().top }, 'slow'); 
   		$('.'+rangenumchk).html('Range value must be numric');
   		setTimeout(function () {
					$('.'+rangenumchk).fadeOut('slow');
				}, 3000);
    	return false;
	}


		
			if(rangeValcheckbelow)
	   		{

	   			$('.'+rangeValcheckbelow).show();
	   			$('html, body').animate({ scrollTop: $('#Products_subCategory').offset().top }, 'slow');
   				$('.'+rangeValcheckbelow).html('Values must between  '+rangeval);
   				setTimeout(function () {
					$('.'+rangeValcheckbelow).fadeOut('slow');
				}, 3000);
    			return false;
	   			
	   		
	   		}if(rangeValcheckabove)
	   		{
	   		
	   			$('.'+rangeValcheckabove).show();
	   			$('html, body').animate({ scrollTop: $('#Products_subCategory').offset().top }, 'slow'); 
   				$('.'+rangeValcheckabove).html('Value must between '+rangeval);
   				setTimeout(function () {
					$('.'+rangeValcheckabove).fadeOut('slow');
				}, 3000);
    			return false;
	   		}


	   		$(".productattributes").each(function(){
	   var attributeids = $(this).attr('id');
	   
	   if($('#'+attributeids).val() == '')
	   {
	   		  attributeSubmit = attributeids;
              return false;
	   }
	   	
	   //$('Products_'+$(this).attr('id')+'_em_').html();
	});
	if (attributeSubmit) {
		$('.'+attributeSubmit).show();
		$('html, body').animate({ scrollTop: $('#Products_subCategory').offset().top }, 'slow'); 
   		$('.'+attributeSubmit).html('Cannot be blank');
   		setTimeout(function () {
			$('.'+attributeSubmit).fadeOut('slow');
		}, 3000);
    	return false;
	}

	/*if (rangeSubmit) {
   		$('.'+attributerangeid).html('Cannot be blank');
	   	return false;
	}
	if (rangeValcheck == 0) {
   				$('.'+attributerangeid).html('Value must be above '+split[0]);
	   			return false;
	}
	if (rangeValcheck == 1) {
   				$('.'+attributerangeid).html('Value must be below '+split[0]);
	   			return false;
	}*/


	if (currentDevice == 'pc') {
		var desc = CKEDITOR.instances['Products_description'].getData();
		$("#Products_description").val(desc.trim());
		var desc = desc.replace(/&nbsp;/gi,'');
		var desc = $('<div/>').html(desc).text().trim();
	} else {
		var desc = $("#Products_description").val();
		$("#Products_description").val(desc.trim());
	}
	//var desc = CKEDITOR.instances['Products_description'].getData();
	

	if (name == "") {
		//console.log("name");
		// $("#Products_name_em_").slideUp();
		// $('#Products_name_em_').html('Product Name cannot be blank');

		$("#Products_name_em_").show();
		$("#badMessage").hide();
	
		$('#Products_name_em_').text(yii.t('app','Product Name cannot be blank'));
		$('#Products_name').focus();
		$('#Products_name').keydown(function () {
			$('#Products_name_em_').hide();
		});
		
		return false;
	}else {
		name = name.replace(/\s{2,}/g, ' ');
		$('#Products_name').val(name);
		$('#Products_name_em_').hide();
	}

	// if (productImage > 5) {
	// 	$("#image_error").show();
	// 	$("#badMessage").hide();
	// 	$('#image_error').text('You can upload 5 images only.');
	// 	return false;
	// }
	
	
	if (desc == "" || desc.length == 0) {

		$("#Products_description_em_").show();
		$("#badMessage").hide();
		    $('html, body').animate({
		      scrollTop: $("#cke_Products_description").offset().top
		    }, 1000);
		$('#Products_description_em_').text(yii.t('app','Product Description cannot be blank'));
		
		// $('#cke_Products_description').focus();
		// $('#Products_description').keydown(function () {
		// 	$('#Products_description_em_').hide();
		// });
		setTimeout(function () {
			$('#Products_description_em_').fadeOut('slow');
		}, 3000);
		return false;
	}

	if (givingAway == 0 && givingAway != "") {
		if (price == "" || price == 0) {
			$("#Products_price_em_").show();
			$("#badMessage").hide();
			$('#Products_price_em_').text(yii.t('app','Product Price cannot be blank'));
			$('#Products_price').focus();
			$('#Products_price').keydown(function () {
				$('#Products_price_em_').hide();
			});
			return false;
		} 
		// else if (!pattern.test(price)) {
		// 	$("#Products_price_em_").show();
		// 	$("#badMessage").hide();
		// 	$('#Products_price_em_').text(yii.t('app','Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
		// 	$('#Products_price').focus();
		// 	$('#Products_price').keydown(function () {
		// 		$('#Products_price_em_').hide();
		// 	});
		// 	return false;
		// }
	}
	if (proCond == "") {

		$("#Products_productCondition_em_").show();
		$("#badMessage").hide();
		$('#Products_productCondition_em_').text(yii.t('app','Product Condition cannot be blank'));
		$('#Products_productCondition').focus();
		$('#Products_productCondition').change(function () {
			$('#Products_productCondition_em_').hide();
		});
		return false;
	}

	if (givingAway == 0 && givingAway != "") {
		if ($('#Products_instantBuy').is(':checked') == true) {
			var pattern = /^\d{0,6}(\.{1}\d{0,2})?$/g;
			// var paypalid = $('#Products_paypalid').val();
			var shippingCost = $('#Products_shippingCost').val();
			//var shippingTime = $('#Products_shippingTime').val();

			// if (paypalid == '') {
			// 	$("#Products_paypalid_em_").show();
			// 	$("#badMessage").hide();
			// 	$('#Products_paypalid_em_').text(yii.t('app','Paypal ID cannot be blank'));
			// 	$('#Products_instantBuy').focus();
			// 	$('#Products_paypalid').keydown(function () {
			// 		$('#Products_paypalid_em_').hide();
			// 	});
			// 	return false;
			// } else if (!(isValidEmailAddress(paypalid))) {
			// 	$("#Products_paypalid_em_").show();
			// 	$("#badMessage").hide();
			// 	$('#Products_paypalid_em_').text(yii.t('app','Paypal ID should be a valid email id'));
			// 	$('#Products_instantBuy').focus();
			// 	$('#Products_paypalid').keydown(function () {
			// 		$('#Products_paypalid_em_').hide();
			// 	});
			// 	return false;
			// }
			if (shippingCost == '') {
				$("#Products_shippingCost_em_").show();
				$("#badMessage").hide();
				$('#Products_shippingCost_em_').text(yii.t('app','Shipping Cost cannot be blank'));
				$('#Products_shippingCost').focus();
				$('#Products_shippingCost').keydown(function () {
					$('#Products_shippingCost_em_').hide();
				});
				return false;
			}
			else if (!pattern.test(shippingCost)) {
				$("#Products_shippingCost_em_").show();
				$("#badMessage").hide();
				$('#Products_shippingCost_em_').text(yii.t('app','Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
				$('#Products_shippingCost').focus();
				$('#Products_shippingCost').keydown(function () {
					$('#Products_shippingCost_em_').hide();
				});
				return false;
			}

		}
	}
	
	if (location == "") {
		$("#Products_location_em_").show();
		$("#badMessage").hide();
		$('#Products_location_em_').text(yii.t('app','Location Required'));
		$('#Products_location').focus();
		$('#Products_location').keydown(function () {
			$('#Products_location_em_').hide();
		});
		return false;
	}



	
	if (latitude == "" || longitude == "" || latitude == "0"
		|| longitude == "0") {
		$("#Products_location_em_").show();
	$("#badMessage").hide();
	$('#Products_location_em_').text(yii.t('app','Invalid Location.Select Location From Drop Down.'));
	$('#Products_location').focus();
	$('#Products_location').text('');
	$('#Products_location').keydown(function () {
		$('#Products_location_em_').hide();
	});
	return false;
} else {
	$('#Products_location_em_').hide();
}

	//alert('alert4');return false;
	var updateFlag = $('.product-update-flag').val();
	var mobiledetect = $("#mobiledetect").val();



	//var sss = $('#products-form').serialize();
	//console.log(sss);
	//alert('get responed');
	//alert(updateFlag);	
	//return false;

	//alert(updateFlag);
	if (updateFlag == 0) {
		
		//if (mobiledetect == 0) {
			$('.btnUpdate').attr('disabled', 'disabled');
			$.post($('#products-form').attr('action'), $('#products-form').serialize(), function (res) {

				console.log(res);
				
				var resultData = res.split('-_-');
				if (resultData[0] == 0) {
					window.location = resultData[1];
				}
				else {
					console.log('working');
					
					$('.promotion-cancel').attr('href', resultData[1]);
					//$('#shareonfacebook').attr('onclick', "sharePopup('https://www.facebook.com/sharer/sharer.php?u="+resultData[1]+"');");
					$('.promotion-product-id').val(resultData[0]);
					$('#UPromotionProductid').val(resultData[0]);
					$('#ADPromotionProductid').val(resultData[0]);
					$('#post-your-list').modal('show');
				}
			});
			return false;
		//}
		/*else {
			$('.btnUpdate').attr('disabled', 'disabled');
			$('#products-form').submit()
		}*/
	} else {
		//alert(0);
		$(document).on('submit', '#products-form', function () {
			$('.btnUpdate').attr('disabled', 'disabled');
		});
	}
}

// function updatePromotion(promotionId) {
// 	$('#promotion-addtype').val(promotionId);
// 	$('#ADPromotionid').val(promotionId);
// }

function updatePromotion(promotionId,price){
	$('#promotion-addtype').val(promotionId);
	$('#ADPromotionid').val(promotionId);
	$('#promotionids').val(promotionId);
	$('#totalpricee').val(price);

}

function showListingPromotion(productId) {
	$('.promotion-product-id').val(productId);
	$('#UPromotionProductid').val(productId);
	$('#ADPromotionProductid').val(productId);
	$('#post-your-list').modal('show');
}

function promotionUpdate(promotionType) {

	var promotionId = $('#promotion-addtype').val();
	console.log(promotionId);

	var productId = $('.promotion-product-id').val();
	var errorSelector = "." + promotionType + "-promote-error";

	if (promotionType == "adds" && promotionId == "") {
		$(errorSelector).html(yii.t('app','Select a Promotion'));
		$(errorSelector).show();
		setTimeout(function () {
			$(errorSelector).html('');
			$(errorSelector).hide();
		}, 1500);
		return false;
	} else {
		$.ajax({
			url: baseUrl+'/products/promotionstatus/',
			type: "post",
			dataType: "html",
			data: {
				promotionType: promotionType,
				promotionId: promotionId,
				productId: productId
			},
			success: function (responce) {
				
				responce = responce.trim();

				if (responce == 0) {
					$(errorSelector).html(yii.t('app','Already Promoted'));
					$(errorSelector).show();
					setTimeout(function () {
						$(errorSelector).html('');
						$(errorSelector).hide();
					}, 1500);
					return false;
				} else if (responce == 1) {
					$(errorSelector).html(yii.t('app','Sold Product Cannot Be Promoted'));
					$(errorSelector).show();
					setTimeout(function () {
						$(errorSelector).html('');
						$(errorSelector).hide();
					}, 1500);
					return false;
				} else {
					//$('.paypal-form-container').html(responce);
					//$('#paypal-form').submit();
				}
			}
		});
	}
}

function IsAlphaNumeric(e) {
	var specialKeys = new Array();
	specialKeys.push(8); // Backspace
	specialKeys.push(9); // Tab
	specialKeys.push(46); // Delete
	specialKeys.push(36); // Home
	specialKeys.push(35); // End
	specialKeys.push(37); // Left
	specialKeys.push(39); // Right
	specialKeys.push(27); // Space
	var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
	var ret = ((keyCode >= 48 && keyCode <= 57)
		|| (keyCode >= 65 && keyCode <= 90) || (keyCode == 32)
		|| (keyCode >= 97 && keyCode <= 122) || (specialKeys
			.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
	return ret;
}

function IsAlphaNumericnospace(e) {
	var specialKeys = new Array();
	specialKeys.push(8); // Backspace
	specialKeys.push(9); // Tab
	specialKeys.push(46); // Delete
	specialKeys.push(36); // Home
	specialKeys.push(35); // End
	specialKeys.push(37); // Left
	specialKeys.push(39); // Right
	var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
	var ret = ((keyCode >= 48 && keyCode <= 57)
		|| (keyCode >= 65 && keyCode <= 90) || (keyCode != 32)
		|| (keyCode >= 97 && keyCode <= 122) || (specialKeys
			.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
	return ret;
}

function isAlpha(e) {
	var specialKeys = new Array();
	specialKeys.push(8); // Backspace
	specialKeys.push(9); // Tab
	specialKeys.push(46); // Delete
	specialKeys.push(36); // Home
	specialKeys.push(35); // End
	specialKeys.push(37); // Left
	specialKeys.push(39); // Right
	specialKeys.push(27); // Space
	var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
	var ret = ((keyCode >= 65 && keyCode <= 90) || (keyCode == 32)
		|| (keyCode >= 97 && keyCode <= 122) || (specialKeys
			.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
	return ret;
}

function isNumber(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}

/*
	Social share on facebook
*/
function sharePopup(url){
    window.open(url , '123456789' , 'height=368,width=585,resizeable=yes');
	return false;
}

function dropCategory() {
	//alert(0);
	$("#catImage").show();
	if ($("#dropCat").val() != "") {
		$("#catImage").hide();
		$("#itemCondition").hide();
		$("#exchangetoBuy").hide();
		$("#myOffer").hide();
		$("#contactSeller").hide();
		$("#buyNow").hide();
		$("#subcategoryVisible").show();


	} else {
		$("#catImage").show();
		$("#itemCondition").show();
		$("#exchangetoBuy").show();
		$("#myOffer").show();
		$("#contactSeller").show();
		$("#buyNow").show();
		$("#subcategoryVisible").hide();
	}
}
function limitText(limitNum, evt) {
	if ($(".commenter-text").val().length > limitNum) {
		var textValue = $(".commenter-text").val().substring(0, limitNum);
		$(".commenter-text").val(textValue);
	} else {
		var countValue = limitNum - $(".commenter-text").val().length;
		$("#countdown").html(countValue);
	}
}

function limitMessageText(limitNum, evt) {
	if ($("#MyOfferForm_message").val().length > limitNum) {
		var textValue = $("#MyOfferForm_message").val().substring(0, limitNum);
		$("#MyOfferForm_message").val(textValue);
	} else {
		var countValue = limitNum - $("#MyOfferForm_message").val().length;
		$("#msgcountdown").val(countValue);
	}
}

function limitMessage(limitNum, evt) {
	if ($("#messageInput").val().length > limitNum) {
		var textValue = $("#messageInput").val().substring(0, limitNum);
		$("#messageInput").val(textValue);
		$('#messageInput').addClass('has-error');
		$(".message-limit").html(
			yii.t('app', "Maximum Character limit") + " 500");
		$(".message-limit").fadeIn();
		setTimeout(function () {
			$('#messageInput').removeClass('has-error');
			$(".message-limit").fadeOut();
		}, 3000);
	}
	var message = $("#messageInput").val();
	var keypr = (window.event) ? event.keyCode : evt.keyCode;
	if (keypr != '16') {
		var reg = /^[^\da-zA-Z]$/;
		if (message.length < 2) {
			if (reg.test(String.fromCharCode(keypr)))
				$("#messageInput").val('');
		}
	}
}
function limitDescription(limitNum) {
	if ($("#Products_description").val().length > limitNum) {
		var textValue = $("#Products_description").val().substring(0, limitNum);
		$("#Products_description").val(textValue);
		$("#Products_description_em_").show();
		$("#Products_description_em_").html(
			yii.t('admin', "Maximum Character limit Exceeded"));
		$("#Products_description_em_").fadeIn();
		setTimeout(function () {
			$("#Products_description_em_").fadeOut();
		}, 3000);
	}
}


function postcomment() {
	var user = $('.logindetails').val();
	//alert(user);
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		var reg = /<(.|\n)*?>/g;
		var comment = $('.commenter-text').val();
		var itemId = $('.item-id').val();
		$('.commenter-text').val('');
		var commentCount = $("#commentCount").val();
		$('#post-comment').attr('disabled');
		if (comment != '') {
			if (reg.test(comment) == true) {
				$('.comment-error').html(yii.t('app','Comment cannot have html'));
				$(".comment-error").fadeIn();
				setTimeout(function () {
					$(".comment-error").fadeOut();
				}, 3000);
			} else {

				$.ajax({
					url: baseUrl+'/products/savecomment',
					type: "post",
					dataType: "html",
					data: { 'comment': comment, 'itemId': itemId },
					beforeSend: function () {
						$('#post-comment').html(yii.t('app','Posting')+'...');
					},
					success: function (responce) {
						//alert(responce);
						$("#countdown").html('120');
						$('#post-comment').html(yii.t('app','Post Comment'));
						$('#post-comment').removeAttr('disabled');
						var output = responce.trim();
						$(".empty-comment").hide();
						$('.comment-section').prepend(output);
						$('.commenter-text').val('');
						var incCmnt = Number(commentCount) + Number(1);
						$("#commentCount").val(incCmnt);
					}
				});
			}
		} else {
			$('.comment-error').html(yii.t('app','Comment cannot be empty'));
			setTimeout(function () {
				$(".comment-error").html("")
			}, 3000);
			$(".comment-error").css('display', 'inline-block');

		}
		return false;
	}
}

function deletecomment(commentId, itemId) {
	
	$.ajax({
		url: baseUrl+'/products/deletecomment',
		type: "post",
		dataType: "html",
		data: { 'commentId': commentId, 'itemId': itemId },
		beforeSend: function () {
		},
		success: function (responce) {

			$("#cmt-" + commentId).remove();
		}
	});
}


function showmorecomment() {
	$('.view_more_comnts').slideDown();
	$('.view-more-comnt').hide();
	$('.hide-more-comnt').show();
	$('.comment-section').css('max-height', '600px');
	$('.comment-section').css('min-height', '300px');
}

function hidemorecomment() {
	$('.view_more_comnts').slideUp();
	$('.hide-more-comnt').hide();
	$('.view-more-comnt').show();
	$('.comment-section').css('height', 'auto');
}

function showmoredesc() {
	$('.ellipses').hide();
	$('.moredesc').slideToggle();
	$('.moredesc').css('display', 'inline');
	$('.showmoredesc').hide();
	$('.hidemoredesc').show();
	moredesc = $("#moremoredesc").text();
	//alert(moredesc);
	$(".moredesc").html(moredesc).text();
}

function lessmoredesc() {
	$('.ellipses').show();
	$('.moredesc').slideToggle();
	$('.moredesc').css('display', 'none');
	$('.showmoredesc').show();
	$('.hidemoredesc').hide();
}

function selectExchangeproduct(id) {
	$('.exchange-product-grid').removeClass('active');
	$('.exchange' + id).addClass('active');
	$('#exchange_product_id').val(id);
}

function createExchange(mainProductId, requestTo) {

	var exchangeProductId = $("#exchange_product_id").val();
//	$('#exchange-btn').text('Sending Request...');
 	$('#exchange-btn').css('opacity','0.5');
	document.getElementById('exchange-btn').style.pointerEvents = 'none';
	if (exchangeProductId != '') {
		$.ajax({
			type: 'POST',
			url: baseUrl+"/products/requestexchange",
			data: {
				'mainProductId': mainProductId,
				'exchangeProductId': exchangeProductId,
				'requestTo': requestTo
			},
			success: function (data) {

				//alert(data+'data return');
				//return false;

				if (data == 'error') {
					window.location.reload();
				}
				else if (data == 'success') {

				} else if (data == 'blocked') {
					$(".option-error").show();
					$(".option-error").focus();
					$(".option-error")
					.html(
						'<div class="col-md-12" style = "color:red;text-align:center;">'+yii.t('app','Exchange Request for this product has been blocked.')+'</div>');
					$('#exchange-btn').css('opacity','unset');
					document.getElementById('exchange-btn').style.pointerEvents = 'auto';
				} else if (data == 'sent') {
					$(".option-error").show();
					$(".option-error").focus();
					$(".option-error")
					.html(
						'<div class="col-md-12" style = "color:red;text-align:center;">'+yii.t('app','Exchange Request exists.Please check Your Exchanges.')+'</div>');
					$('#exchange-btn').css('opacity','unset');
					document.getElementById('exchange-btn').style.pointerEvents = 'auto';
				} else if (data == 'sold') {
					$(".option-error").show();
					$(".option-error").focus();
					$(".option-error")
					.html(
						'<div class="col-md-12" style = "color:red;text-align:center;">'+yii.t('app','Product has been soldout unexpectedly')+'</div>');
					$('.exchange-to-buy').hide();
					$('#exchange-btn').css('opacity','unset');
					document.getElementById('exchange-btn').style.pointerEvents = 'auto';
				} else if (data == 'exchangesold') {
					$(".option-error").show();
					$(".option-error").focus();
					$(".option-error")
					.html(
						'<div class="col-md-12" style = "color:red;text-align:center;">'+yii.t('app','Your choosen Product has been soldout, choose a different one.')+'</div>');
					$('#product_view_' + exchangeProductId).hide();
					$('#exchange-btn').css('opacity','unset');
					document.getElementById('exchange-btn').style.pointerEvents = 'auto';
				} else {
					$(".option-error").show();
					$(".option-error").focus();
					$(".option-error")
					.html(
						'<div class="col-md-12" style = "color:red;text-align:center;">'+yii.t('app','Please select atleast one product to request exchange.')+'</div>');
					$('#exchange-btn').css('opacity','unset');
					document.getElementById('exchange-btn').style.pointerEvents = 'auto';
				}
				setTimeout(function () {
					$(".option-error").hide();
				}, 4000);
				if (data == 'success') {
					setTimeout(function () {
						$('.exchange-product-grid').removeClass('active');
						$('#exchange_product_id').val('');
						$('#exchange-modal').hide();
						$('#chat-with-seller-success-modal').show();
						$("#chat-with-seller-success-modal").addClass("in");
						$('.sent-text').html(yii.t('app','Exchange Request Sent'));
						$('#exchange-btn').css('opacity','unset');
						document.getElementById('exchange-btn').style.pointerEvents = 'auto';
					}, 2000);
				}
			},
			error: function(err)
			{
				console.log("error page");
			}
		});
	} else {
		$(".option-error").show();
		$(".option-error")
		.html(yii.t('app',"You've not selected any Item for exchange.Please select an Item To Proceed"));
		$(".option-error").focus();
	}
}


function generateapipassword() {
	//alert(0);
	var charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	var charshuffle = shuffle(charset);
	var password = charshuffle.substring(0, 8);
	//alert(password);
	if (confirm(yii.t('app','Are you sure, you want to change password?'))) {
		$('#Sitesettings_apiPassword').val(password);
		$('#show_apipassword').val(password);
	}
}

function showapipassword() {
	if ($('.show-button').hasClass('fa-eye')) {
		$('.show-button').removeClass('fa-eye');
		$('.show-button').addClass('fa-eye-slash');
		$('#Sitesettings_apiPassword').hide();
		$('#show_apipassword').show();
	} else {
		$('.show-button').removeClass('fa-eye-slash');
		$('.show-button').addClass('fa-eye');
		$('#Sitesettings_apiPassword').show();
		$('#show_apipassword').hide();
	}
	return false;
}


function shuffle(string) {
	var parts = string.split('');
	for (var i = parts.length; i > 0;) {
		var random = parseInt(Math.random() * i);
		var temp = parts[--i];
		parts[i] = parts[random];
		parts[random] = temp;
	}
	return parts.join('');
}

function changeQuantity() {
	var selectedQty = $(".product-quantity").val();
	$(".product-quantity-hidden").val(selectedQty);
	var unitPrice = $(".product-unit-price").val();
	var currency = $('.currency').val();
	var total = selectedQty * unitPrice;
	var oldTotal = total;
	var couponType = $(".coupon-type-hidden").val();
	var couponValue = $(".coupon-value-hidden").val();
	var shipping = $(".item-shipping").val();

	if (couponType == "1") {
		if (couponValue > 0) {
			total = Number(total) - (selectedQty * Number(couponValue));
			$(".product-coupon-discount")
			.val(selectedQty * Number(couponValue));
			$(".coupon-discount")
			.html(
				yii.t('app', 'Discount')
				+ ': ( - ) <span class="amnt product-item-coupondiscount">'
				+ Number(selectedQty) * couponValue + " "
				+ currency + '</span>');
		}
	} else {
		discount = (Number(total) * (Number(couponValue) / 100));
		if (discount > 0) {
			if ($(".coupon-max-hidden").val() != ""
				&& $(".coupon-max-hidden").val() < discount) {
				discount = $(".coupon-max-hidden").val();
		}
		total = Number(total) - Number(discount);
		$(".product-coupon-discount").val(discount);
		$(".coupon-discount")
		.html(
			yii.t('app', 'Discount')
			+ ': ( - ) <span class="amnt product-item-coupondiscount">'
			+ discount + " " + currency + '</span>');
	}
}
var grandTotal = Number(total) + Number(shipping);

$(".product-sub-total").html(
	yii.t('app', 'Subtotal')
	+ ':<span class="amnt product-item-total"> ' + oldTotal
	+ " " + currency + ' </span>');
$('.product-sub-total-hidden').val(oldTotal);
$(".sub-total-hidden").val(total);
$(".product-grand-total").html(
	yii.t('app', 'Order Total')
	+ ': <span class="amnt product-item-grandtotal"> '
	+ grandTotal + " " + currency + ' </span>');
}
function checkRange(country) {
	var shipp = $(".shipping-range-hidden").val();
	$
	.ajax({
		type: 'POST',
		url: yii.urls.base + '/item/buynow/checkrange',
		data: {
			country: country,
			shippingRange: shipp
		},
		success: function (data) {
			if (data == 'false') {
				$(".country-error")
				.html(
					yii
					.t('app',
						"Item cannot be shipped to your location"));
				$(".country-error").show();
				document.getElementById("check-out-button")
				.setAttribute("disabled", "disabled");
			} else {
				$(".country-error").hide();
				$("#check-out-button").removeAttr("disabled");
			}
		}
	});
}

function shippingChange() {
	var shipCost = $(".shipping-cost-hidden").val();
	var shipp = $(".shipping-range-hidden").val();
	var shippingReturn = $(".shipping-addresses").val().split('-');
	var nickname = shippingReturn[1];
	var shippingId = shippingReturn[0];
	$(".country-error").hide();
	$.ajax({
		type: 'POST',
		url: yii.urls.base + '/item/buynow/getShippingAddress',
		data: {
			nickname: nickname,
			shippingId: shippingId,
			shipCost: shipCost,
			shippingRange: shipp
		},
		success: function (data) {
			var output = JSON.parse(data);
			checkRange(output.country);
			var currency = $('.currency').val();
			$(".selected-shipping").val(output.shippingId);
			$(".fullname").val(output.username);
			$(".address1").val(output.address1);
			$(".address2").val(output.address2);
			$(".city").val(output.city);
			$(".pincode").val(output.pincode);
			$(".state").val(output.state);
			$(".country").val(output.country);
			$(".phone").val(output.phone);
			$(".product-item-shippingcost").html(
				output.shipPrice + " " + currency);
			$(".item-shipping").val(output.shipPrice);
			var subtotal = $(".product-sub-total-hidden").val();
			var discount = $(".product-coupon-discount").val();
			var shipping = $(".item-shipping").val();
			var grandTotal = (Number(subtotal) + Number(shipping))
			- Number(discount);
			$(".product-item-grandtotal").html(grandTotal + " " + currency);
		}
	});
}

function applyCoupon() {
	var couponCode = $(".couponCode").val();
	var currency = $('.currency').val();
	var productId = $(".review-order-product-id").val();
	var sellerId = $(".review-order-seller-id").val();
	if (couponCode == "") {
		$(".option-error").show();
		$(".option-error").html(yii.t('app', "Please Enter your Coupon Code"));
		$(".option-error").fadeIn();
		setTimeout(function () {
			$(".option-error").fadeOut();
		}, 3000);
	} else {
		$(".option-error").hide();
		if (couponCode.length != 8) {
			$(".option-error").show();
			$(".option-error").html(yii.t('app', "Invalid Coupon Code"));
			$(".option-error").fadeIn();
			setTimeout(function () {
				$(".option-error").fadeOut();
				$(".couponCode").val("");
			}, 3000);
		} else {
			$
			.ajax({
				type: 'POST',
				url: yii.urls.base + '/item/buynow/applyCoupon',
				data: {
					couponCode: couponCode,
					sellerId: sellerId,
					productId: productId
				},
				success: function (data) {
					if (data != "") {
						var output = JSON.parse(data);
						var couponId = output.couponId;
						var couponValue = output.couponValue;
						var couponType = output.couponType;
						var startDate = output.startDate;
						var endDate = output.endDate;
						var maxAmount = output.maxAmount;

						var selectedQty = $(".product-quantity-hidden")
						.val();
						var unitPrice = $(".product-unit-price").val();
						var total = selectedQty * unitPrice;
						var shipping = $(".item-shipping").val();
						var subtotal;
						var grandTotal;
						var date = new Date();
						curYear = date.getFullYear();
						curMonth = date.getMonth() + 1;
						curDate = date.getDate();
						if (curDate < 10) {
							curDate = '0' + curDate;
						}
						if (curMonth < 10) {
							curMonth = '0' + curMonth;
						}
						var curDate = curYear + '-' + curMonth + '-'
						+ curDate;
						$(".coupon-type-hidden").val(couponType);
						$(".coupon-code-hidden").val(couponCode);
						if (couponType == '1') {
							if (couponValue > total) {
								$(".option-error").show();
								$(".option-error").html(
									yii.t('app',
										"Invalid Coupon Code"));
								$(".option-error").fadeIn();
								setTimeout(function () {
									$(".option-error").fadeOut();
									$(".couponCode").val("");
								}, 3000);
							} else {
								$(".coupon-value-hidden").val(
									couponValue);
								subtotal = Number(total)
								- Number(selectedQty)
								* Number(couponValue);
								grandTotal = Number(subtotal)
								+ Number(shipping);
								if (grandTotal < 0) {
									$(".option-error").show();
									$(".option-error")
									.html(
										yii
										.t('app',
											"Invalid Coupon Code"));
									$(".option-error").fadeIn();
									setTimeout(function () {
										$(".option-error").fadeOut();
										$(".couponCode").val("");
									}, 3000);
								} else {
									$(".product-coupon-discount").val(
										Number(selectedQty)
										* couponValue);
									$(".coupon-discount")
									.html(
										yii.t('app',
											'Discount')
										+ ': ( - ) <span class="amnt product-item-coupondiscount">'
										+ Number(selectedQty)
										* couponValue
										+ " "
										+ currency
										+ '</span>');
									$(".product-grand-total")
									.html(
										yii
										.t('app',
											'Order Total')
										+ ': <span class="amnt product-item-grandtotal"> '
										+ grandTotal
										+ " "
										+ currency
										+ ' </span>');
									setTimeout(function () {
										$(".couponCode").val("");
									}, 3000);
								}
							}
						} else {
							if (curDate >= startDate
								&& curDate <= endDate) {
								discount = (Number(total) * (Number(couponValue) / 100));
							if (maxAmount > 0) {
								if (discount > maxAmount) {
									discount = maxAmount;
								}
								$(".coupon-max-hidden").val(
									maxAmount);
							}
							subtotal = Number(total) - discount;
							grandTotal = Number(subtotal)
							+ Number(shipping);
							$(".product-coupon-discount").val(
								discount);
							$(".coupon-value-hidden").val(
								couponValue);
							$(".coupon-discount")
							.html(
								yii
								.t('app',
									'Discount')
								+ ': ( - ) <span class="amnt product-item-coupondiscount">'
								+ discount
								+ " "
								+ currency
								+ '</span>');
							$(".sub-total-hidden").val(subtotal);
							$(".product-grand-total")
							.html(
								yii.t('app',
									'Order Total')
								+ ': <span class="amnt product-item-grandtotal"> '
								+ grandTotal
								+ " "
								+ currency
								+ ' </span>');
							setTimeout(function () {
								$(".couponCode").val("");
							}, 3000);
						} else {
							$(".option-error").show();
							$(".option-error")
							.html(
								yii
								.t('app',
									"Expired or Invalid Coupon Code"));
							$(".option-error").fadeIn();
							setTimeout(function () {
								$(".option-error").fadeOut();
								$(".couponCode").val("");
							}, 3000);
						}
					}
				} else {
					$(".option-error").show();
					$(".option-error").html(
						yii.t('app', "Invalid Coupon Code"));
					$(".option-error").fadeIn();
					setTimeout(function () {
						$(".option-error").fadeOut();
						$(".couponCode").val("");
					}, 3000);
				}
			},
			error: function () {
				$(".option-error").show();
				$(".option-error").html(
					yii.t('app', "Invalid Coupon Code"));
				$(".option-error").fadeIn();
				setTimeout(function () {
					$(".option-error").fadeOut();
					$(".couponCode").val("");
				}, 3000);
			},
		});
}
}
}
function changeOrderStatus(status, orderId) {
	var pleasewait = yii.t('app',"Please wait");
	
	var pages = $(".page-value-hidden").val();
	$.ajax({
		type: 'GET',
		url: baseUrl+'/buynow/changestatus',
		data: {
			status: status,
			orderId: orderId,
			pages: pages
		},
		beforeSend: function () {
			$('#exc-accept').html(pleasewait+'...');
		},
		success: function (data) {
			//alert(data);
			if (pages == "" || typeof (pages) == 'undefined') {
				window.location.reload();
			} else {
				$(".item-view").load("orders?page=" + pages);
			}
		}
	});
}
function changeSalesStatus(status, orderId) {
	//var pages = $(".page-value-hidden").val();
	$.ajax({
		type: 'GET',
		url: baseUrl+'/buynow/changestatus',
		data: {
			status: status,
			orderId: orderId,
			//pages: pages
		},
		success: function (data) {

			window.location.href = baseUrl+'/sales';
			$(".process" + orderId).hide();
			// if (pages == "") {
			// 	$(".item-view").load("sales");
			// } else {
			// 	$(".item-view").load("sales?page=" + pages);
			// }
		}
	});
}
function edit_tracking(trackid, orderid) {
	//alert('jlrrrr'); return false;
	$.ajax({
		type: 'GET',
		url:  baseUrl+'/buynow/edit_tracking_details',
		data: {
			trackid: trackid,
			orderid: orderid,
		},
		success: function (data) {
			if (trackid != 0) {
				var obj = jQuery.parseJSON(data);

				$("#Trackingdetails_orderid").val(obj['orderid']);
				$("#Trackingdetails_shippingdate").val(obj['shippingdate']);
				$("#Trackingdetails_couriername").val(obj['couriername']);
				$("#Trackingdetails_courierservice").val(obj['courierservice']);
				$("#Trackingdetails_trackingid").val(obj['trackingid']);
				$("#Trackingdetails_notes").val(obj['notes']);
				$("#trackingdetails-shippingdate").val(obj['shippingdate']);
				
			}
			else if (trackid == 0) {
				$("#Trackingdetails_orderid").val(orderid);
				$('#shipping-seller-modal').modal('show');
			}
		},
		error: function()
		{
			console.log("error");
		}
	});
}

function claimorder(btn, orderid) {
	if (confirm(yii.t('app',"Are you sure, you want to claim the order?"))) {
		$.ajax({
			type: 'GET',
			url: baseUrl+'/buynow/claimorder/',
			data: {
				"orderid": orderid,
			},
			success: function (data) {
				$(btn).hide();
				$("#claimsuccess").show();
				$(".status-txt").text('Claimed');
				
				$("#claimsuccess").html(yii.t('app',"Claim created successfully"));
				setTimeout(function () {
					$("#claimsuccess").fadeOut();
				}, 3000);
			}
		});
	}
}

function validatetracking() {
	shipdate = $("#Trackingdetails_shippingdate").val();
	couriername = $("#Trackingdetails_couriername").val();
	shippingservice = $("#Trackingdetails_courierservice").val();
	trackingid = $("#Trackingdetails_trackingid").val();
	if (shipdate == "") {
		$("#Trackingdetails_shippingdate_em_").show();
		$('#Trackingdetails_shippingdate_em_').text(yii.t('app','Enter shipment date'));
		$('#Trackingdetails_shippingdate').focus();
		$('#Trackingdetails_shippingdate').blur(function () {
			$('#Trackingdetails_shippingdate_em_').hide();
		});
		return false;
	}
		var CurrentDate = new Date();
		shipmentDate = new Date(shipdate);

		if(shipmentDate < CurrentDate.setHours(0, 0, 0, 0)){
		$("#Trackingdetails_shippingdate_em_").show();
		$('#Trackingdetails_shippingdate_em_').text(yii.t('app','Shipment date is higher than today date.'));
		
		$('#Trackingdetails_shippingdate').blur(function () {
			$('#Trackingdetails_shippingdate_em_').hide();
		});
		return false;
		}
	if ($.trim(couriername) == "") {
		$("#Trackingdetails_couriername_em_").show();
		$('#Trackingdetails_couriername_em_').text(yii.t('app','Enter courier name'));
		$('#Trackingdetails_couriername').focus();
		$('#Trackingdetails_couriername').keydown(function () {
			$('#Trackingdetails_couriername_em_').hide();
		});
		return false;
	}
	if ($.trim(shippingservice) == "") {
		$("#Trackingdetails_courierservice_em_").show();
		$('#Trackingdetails_courierservice_em_').text(yii.t('app','Enter shipping service'));
		$('#Trackingdetails_courierservice').focus();
		$('#Trackingdetails_courierservice').keydown(function () {
			$('#Trackingdetails_courierservice_em_').hide();
		});
		return false;
	}
	if ($.trim(trackingid) == "") {
		$("#Trackingdetails_trackingid_em_").show();
		$('#Trackingdetails_trackingid_em_').text(yii.t('app','Enter shipping service'));
		$('#Trackingdetails_trackingid').focus();
		$('#Trackingdetails_trackingid').keydown(function () {
			$('#Trackingdetails_trackingid_em_').hide();
		});
		return false;
	}
	return true;
}

function showinvoicepopup(invoiceId) {
	$('#popup_container').show();
	$('#popup_container').css({
		"opacity": "1"
	});
	$('body').css({
		"overflow": "hidden"
	});
	getInvoiceData(invoiceId);
	$('#show-exchange-popup').show();
}

function getInvoiceData(invoiceId) {
	$.ajax({
		type: 'POST',
		url: yii.urls.base + '/useractivity/useraction/getInvoiceData',
		data: {
			invoiceId: invoiceId
		},
		success: function (data) {
			$('#show-exchange-popup').html(data);
		}
	});
}

function shippingConfirmValidation() {
	if ($("#subject").val() == "") {
		$(".empty-error-sub").html(yii.t('app', "Subject Cannot be Empty"));
		return false;
	} else if ($("#message").val() == "") {
		$(".empty-error-sub").hide();
		$(".empty-error-msg").html(yii.t('app', "Message Cannot be Empty"));
		return false;
	}
}
function addtracking() {
	var BaseURL = getBaseURL();
	var shippingdate = $('#shipmentdate').val();
	var couriername = $('#couriername').val();
	var courierservice = $('#courierservice').val();
	var trackid = $('#trackingid').val();
	var notes = $('#notes').val();
	var orderid = $('#hiddenorderid').val();
	var buyeremail = $('#hiddenbuyeremail').val();
	var buyername = $('#hiddenbuyername').val();
	var orderstatus = $('#hiddenorderstatus').val();
	var address = $('#hiddenbuyeraddress').val();
	var id = $('#trackid').val();
	$('.error').html('');

	if (shippingdate == '') {
		$('.shipmentdateerror').html(
			yii.t('app', 'Shipment Date cannot be empty'));
		return false;
	} else if (couriername == '') {
		$('.couriernameerror').html(
			yii.t('app', 'Courier Name cannot be empty'));
		return false;
	} else if (trackid == '') {
		$('.trackingiderror').html(yii.t('app', 'Tracking ID cannot be empty'));
		return false;
	}
	$.ajax({
		url: BaseURL + "tracking",
		type: "post",
		data: {
			'orderid': orderid,
			'buyeremail': buyeremail,
			'orderstatus': orderstatus,
			'address': address,
			'buyername': buyername,
			'shippingdate': shippingdate,
			'couriername': couriername,
			'trackid': trackid,
			'notes': notes,
			'courierservice': courierservice,
			'id': id
		},
		beforeSend: function () {
			$('.updatetrackingloader').show();
		},
		success: function (responce) {
			$('.updatetrackingloader').hide();
			window.location = BaseURL + 'orders';
		}
	});
}

function like(id) {
	//alert(0);
	var user = $('.logindetails').val();
	var productId = $(".item-id").val();
	var like_count = $('#like_counter').html();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		//alert(id);
		if (likeAjax == 1) {
			likeAjax = 0;
			$.ajax({
				type: 'POST',
				url: baseUrl+'/products/like/' + id,
				beforeSend: function () {
					var real_count = parseInt(like_count) + parseInt(1);
					//alert(real_count);
					$('#like_counter').html(real_count);
					$("#favs").html(
						'<a href="javascript:void(0)" onclick="dislike('
						+ productId
						+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right" id="liked"></div></i></a>');
					likeAjax = 1;
				},
				success: function (data) {

					//alert(data);
					var data = $.trim(data);

					if (data != 1) {
						var real_count = parseInt(like_count) - parseInt(1);
						$('#like_counter').html(real_count);
						$("#favs").html('<a href="javascript:void(0)" onclick="like('
							+ productId
							+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right"></div></i></a>');
						dislikeAjax = 1;

					}

				},
				error: function(error)
				{
					console.log('error');
				}
			});
		}
	}
}
function dislike(id) {
	var user = $('.logindetails').val();
	var productId = $(".item-id").val();
	var like_count = $('#like_counter').html();
	if (user == '') {
		window.location =baseUrl+'/site/login';
	} else {
		if (dislikeAjax == 1) {
			dislikeAjax = 0;
			$.ajax({
				type: 'POST',
				url: baseUrl+'/products/dislike/' + id,
				beforeSend: function () {
					var real_count = parseInt(like_count) - parseInt(1);
					$('#like_counter').html(real_count);
					$("#favs").html('<a href="javascript:void(0)" onclick="like('
						+ productId
						+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right"></div></i></a>');
					dislikeAjax = 1;
				},
				success: function (data) {
					var data = $.trim(data);
					if (data != 1) {
						var real_count = parseInt(like_count) + parseInt(1);
						$('#like_counter').html(real_count);
						$("#favs").html(
							'<a href="javascript:void(0)" onclick="dislike('
							+ productId
							+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right" id="liked"></div></i></a>');
						likeAjax = 1;
					}

				}
			});
		}
	}
}
function mobile_like(id) {
	var user = $('.logindetails').val();
	var productId = $(".item-id").val();
	var like_count = $('#mobile_like_counter').html();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		$(".favs")
		.html(
			'<a class="btn like-btn" href="javascript:void(0)"><i class="fa fa-heart-o btn-lg"></i></a>');
		$.ajax({
			type: 'POST',
			url: baseUrl+'/products/like/' + id,
			beforeSend: function () {
				var real_count = parseInt(like_count) + parseInt(1);
				$('#mobile_like_counter').html(real_count);
				$("#mobile_favs").html(
					'<a href="javascript:void(0)" onclick="mobile_dislike('
					+ productId
					+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right" id="liked"></div></i></a>');
			},
			success: function (data) {
				var data = $.trim(data);
				if (data != 1) {
					var real_count = parseInt(like_count) - parseInt(1);
					$('#mobile_like_counter').html(real_count);
					$("#mobile_favs").html('<a href="javascript:void(0)" onclick="mobile_like('
						+ productId
						+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right"></div></i></a>');
				}
			}
		});
	}
}
function mobile_dislike(id) {
	var user = $('.logindetails').val();
	var productId = $(".item-id").val();
	var like_count = $('#mobile_like_counter').html();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		$(".favs")
		.html(
			'<a class="btn like-btn" href="javascript:void(0)"><i class="fa fa-heart btn-lg"></i></a>');
		$
		.ajax({
			type: 'POST',
			url: baseUrl+'/products/dislike/' + id,
			beforeSend: function () {
				var real_count = parseInt(like_count) - parseInt(1);
				$('#mobile_like_counter').html(real_count);
				$("#mobile_favs").html('<a href="javascript:void(0)" onclick="mobile_like('
					+ productId
					+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right"></div></i></a>');
			},
			success: function (data) {
				var data = $.trim(data);
				if (data != 1) {
					var real_count = parseInt(like_count) + parseInt(1);
					$('#mobile_like_counter').html(real_count);
					$("#mobile_favs").html(
						'<a href="javascript:void(0)" onclick="mobile_dislike('
						+ productId
						+ ')"><div class="product-like col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pull-right" id="liked"></div></i></a>');
				}
			}
		});
	}
}

function clear_add_address() {
	$("#Tempaddresses_nickname").val("");
	$("#Tempaddresses_name").val("");
	$("#Tempaddresses_address1").val("");
	$("#Tempaddresses_address2").val("");
	$("#Tempaddresses_city").val("");
	$("#Tempaddresses_state").val("");
	$("#Tempaddresses_zipcode").val("");
	$("#Tempaddresses_phone").val("");
	$("#shippingId").val("");
}

$(document).ready(function() {
	mapView();
});


function mapView() {

	var location = $(".product-location-name").val();
	var latitude = $(".product-location-lat").val();
	var longitude = $(".product-location-long").val();
	//alert(location);
	$('#map_canvas').delay('700').fadeIn();
	$('#mobile_map_canvas').delay('700').fadeIn();

	if (mapClick == 1) {
		setTimeout(function () {
			showMap(location, latitude, longitude);
			mobile_showMap(location, latitude, longitude);
		 }, 1000);
		mapClick = 0;
	}
}


function cancelExchange(exchangeId) {
	$(".no-more-" + exchangeId).html(
		'<span class="exchange-btn" id="exc-success">'
		+ yii.t('app','Requesting...') + '</span>');
	$.ajax({
		type: 'POST',
		dataType: "html",
		url: baseUrl+'/user/cancelexchange/' + exchangeId,
		success: function (data) {
			$(".no-more-" + exchangeId).html(
				'<span class="exchange-btn" id="exc-success" onclick="allowExchange('
				+ exchangeId + ')">'
				+ yii.t('app','Allow Exchanges') + '</span>');
		},
		error:function(error)
		{
			console.log("Error");
		}
	});
}

function allowExchange(exchangeId) {
	$(".no-more-" + exchangeId).html(
		'<span class="exchange-btn" id="exc-success">'
		+ yii.t('app','Requesting...') + '</span>');
	$.ajax({
		type: 'POST',
		url: baseUrl+'/user/allowexchange/' + exchangeId,
		success: function (data) {
			$(".no-more-" + exchangeId).html(
				'<span class="exchange-btn" id="exc-failed" onclick="cancelExchange('
				+ exchangeId + ')">'
				+ yii.t('app','Block Exchanges') + '</span>');
		},
		
	});
}

function accept(exchangeId) {
	var successUrl = yii.urls.base + '/item/exchanges/success?id=' + exchangeId;
	var failedUrl = yii.urls.base + '/item/exchanges/failed?id=' + exchangeId;
	$.ajax({
		type: 'POST',
		url: yii.urls.base + '/item/exchanges/accept?id=' + exchangeId,
		data: { 'ajax': 1 },
		success: function (data) {
			if (data == 1) {
				$(".exchange-status-" + exchangeId).html(yii.t('app', 'Current Status')
					+ ":<label class='label-lg label-success'>" + yii.t('app', 'ACCEPTED')
					+ "</label>");
				$(".exchange-action-" + exchangeId).html("<span type='button' class='exchange-btn' id='exc-success' " +
					"onclick='confirmModal(\"link\", \"item/exchanges/success?id=\", " + exchangeId + ")' " +
					"style='font-size: 13px; float: left;text-decoration:none;'>" + yii.t('app', 'SUCCESS') + "</span>" +
					"<span type='button' class='exchange-btn' id='exc-failed' " +
					"onclick='confirmModal(\"link\", \"item/exchanges/failed?id=\", " + exchangeId + ")' " +
					"style='font-size: 13px; float: left;text-decoration:none;'>" + yii.t('app', 'FAILED') + "</span>");
				$('#confirm_popup_container').modal('hide');
			} else {
				window.location.href = yii.urls.base + '/item/exchanges?type=failed';
			}

		},
	});
}


	function cancelOffer() {
		$(".offer-form").hide();
	}

	function mobile_verification() {

		var mobile = $('#mobile_number').val();
		var counrty_code = $('#counrty_code').val();


		var verifymobile = $('#verify_mobile_number').val();
		$('#otp-error').hide();
		$('#verification_error').html('');

		if (mobile == '' && counrty_code == '') {
			$('.mobile-error').css('display', 'inline-block');
			$('.mobile-error').html(yii.t('app', 'Enter your country code and mobile number'));
			return false;

		}
		else {
			if (mobile == '' && counrty_code != '') {
				$('.mobile-error').css('display', 'inline-block');
				$('.mobile-error').html(yii.t('app', 'Enter your mobile number'));
				return false;
			}
			if (counrty_code == '' && mobile != '') {
				$('.mobile-error').css('display', 'inline-block');
				$('.mobile-error').html(yii.t('app', 'Enter country code'));
				return false;
			}

		}


		if (mobile == verifymobile) {
			$('.mobile-error').css('display', 'inline-block');
			$('.mobile-error').html(yii.t('app', 'This account is already verified using this number'));
			return false;
		}
		if (mob_check == 1) {
			$.ajax({
				type: 'POST',
				url: yii.urls.base + '/user/mobile_verificationCode',
				data: { mobile: mobile, country: counrty_code },
				beforeSend: function () {
					mob_check = 0;
					console.log(" mob_check: " + mob_check);
				},
				success: function (data) {
					$('#mobile-otp').addClass('in');
					$('body').addClass('modal-open');
					$('body').append('<div class="modal-backdrop fade in"></div>');
					$('#mobile-otp').css('display', 'block');
					var data = $.trim(data);
				//alert(data);
				if (data == 1) {
					$(".rand_code").css({ "color": "green" });
					$('.rand_code').html(yii.t('app', "Mobile verification code successfully send."));
					$("#mobile-verification").css({ "display": "block" });

				}
				else {
					$(".rand_code").css({ "color": "red" });
					$('.rand_code').html(yii.t('app', "message sending failed."));

				}

				var phlast = mobile.slice(-4);
				phlast = '****' + phlast;
				$('.mob_code').html(phlast);
				mob_check = 1;
				console.log(" mob_check success: " + mob_check);
			}
		});
		}


	}

	function close_otp() {
		$('#mobile-otp').removeClass('in');
		$('body').removeClass('modal-open');
		$('.modal-backdrop').remove();
		$('#mobile-otp').css('display', 'none');
		$('#otp_code').val('');
		$('.otp-error').hide();
	}

	function verify_otp() {
		var otp_code = $('#otp_code').val();
		var mobile = $('#mobile_number').val();
		if (otp_code != '') {
			$('#verify_text').html(yii.t('app', 'Verifying...'));
			$('#verification_error').html('');
			$.ajax({
				type: 'POST',
				url: yii.urls.base + '/user/mobile_verificationStatus',
				data: { otp_code: otp_code, mobile: mobile },
				success: function (data) {
					if ($.trim(data) == '1') {
						$('#verify_text').html(yii.t('app', 'Verify'));
						$('#n_number').html(mobile);
						$('#otp_code').val('');
						$('#mobile_number').val('');
						$('#mobile-otp').removeClass('in');
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();
						$('#mobile-otp').css('display', 'none');
						$('#profile-mobile-details').hide();
						$('#mobile-verification').css('display', 'block');
						$('.profile-email-txt.add-phone').hide();
						document.getElementById('add-phone').style.display = 'block';
					} else {
						$('#verify_text').html(yii.t('app', 'Verify'));
						$('.otp-error').css('display', 'inline');
					}
				}
			});
		} else {
			$('#verification_error').html(yii.t('app', 'Enter Verification Code....'));
			$('#otp-error').hide();
		}
	}

	function dosearch() {

		var searchval = $('input[name=search]').val();
		searchval = searchval.trim();
		gotogetLocationData();
		if (searchval == '') {
			return false;
		}
	}
	function validateNumeric(thi, e) {
		var specialKeys = new Array();
	specialKeys.push(8); // Backspace
	specialKeys.push(9); // Tab
	specialKeys.push(46); // Delete
	specialKeys.push(36); // Home
	specialKeys.push(35); // End
	specialKeys.push(37); // Left
	specialKeys.push(39); // Right
	specialKeys.push(27); // Space
	if (window.event)
		keycode = window.event.keyCode;
	else if (e)
		keycode = e.which;
	else
		return true;



	if (((keycode >= 65) && (keycode <= 90))
		|| (keycode == 32)
		|| ((keycode >= 97) && (keycode <= 122))
		|| (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode)) {
		return true;
} else {

	var regex = new RegExp("^[a-zA-Zء-ي ]+$");
	var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	if (regex.test(str)) {
	return true;
	}

	return false;
}
}

function profileVal() {
	//alert('comes');
	var name = $("#Users_namee").val();
	var latitude = $("#latitude").val();
	var longitude = $("#longitude").val();

	if (name == "") {
		
		$("#Users_namee_em_").show();
		$("#Users_namee_em_").html(yii.t('app',"Name cannot be blank"));
		$("#Users_namee_em_").focus();
		return false;
	} else {
		name = name.replace(/\s{2,}/g, ' ');
		$('#Users_namee').val(name);
		$('#Users_namee_em_').hide();
	}
	if (name.length < 3) {
		$("#Users_namee_em_").show();
		$("#Users_namee_em_").html(yii.t('app',"Name should be minimum 3 characters"));
		$("#Users_namee_em_").focus();
		return false;
	}
	if (specials.test(name)) {
		$("#Users_namee_em_").show();
		$('#Users_namee').val('');
		$('#Users_namee_em_').text(yii.t('app','Special Characters not allowed.'));
		$("#Users_namee_em_").focus();
		return false;
	} else {
		$('#Users_namee_em_').hide();
	}

	var reg = /[0-9]/gi;
	if (reg.test(name)) {
		$("#Users_namee_em_").show();
		$("#Users_namee_em_").html(yii.t('app','Numbers not allowed.'));
		$('#Users_namee').val('');
		$('#Users_namee').focus();
		return false;
	} else {
		$('#Users_namee_em_').hide();
	}
		//new changes
	if (latitude == "" || longitude == "" || latitude == "0"
		|| longitude == "0") {
		$("#User_location_em_").show();
		$("#badMessage").hide();
		$('#User_location_em_').text(yii.t('app','Invalid Location.Select Location From Drop Down.'));
		$('#geolocationDetails').focus();
		$('#geolocationDetails').text('');
		$('#geolocationDetails').keydown(function () {
			$('#User_location_em_').hide();
		});
	return false;
	} else {
		$('#User_location_em_').hide();
	}
}

function resetLatLong() {
	$("#latitude").val('');
	$("#longitude").val('');
	$("#map-latitude").val('');
	$("#map-longitude").val('');
}

function showMap(loc, lat, long) {
	var cityCircle;
	var location = loc;
	var myLatlng = new google.maps.LatLng(lat, long);
	var myOptions = {
		zoom: 15,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	var marker = new google.maps.Marker({
		position: myLatlng,
		title: location,
	});

	var circleOptions = {
		strokeColor: '#2FDAB8',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#2FDAB8',
		fillOpacity: 0.35,
		map: map,
		center: myLatlng,
		radius: 300
	};
	cityCircle = new google.maps.Circle(circleOptions);
	marker.setMap(map);
}

function mobile_showMap(loc, lat, long) {
	var cityCircle;
	var location = loc;
	var myLatlng = new google.maps.LatLng(lat, long);
	var myOptions = {
		zoom: 15,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("mobile_map_canvas"),
		myOptions);
	var marker = new google.maps.Marker({
		position: myLatlng,
		title: location,
	});
	var circleOptions = {
		strokeColor: '#2FDAB8',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#2FDAB8',
		fillOpacity: 0.35,
		map: map,
		center: myLatlng,
		radius: 300
	};

	cityCircle = new google.maps.Circle(circleOptions);
	marker.setMap(map);
}

$(document).on('click', '.btn-worldwide', function () {
	$(this).addClass('hidden');
	$('#location-search-remove').hide();
	$('.search-location').show();

});

$(document).on('click', '.btn-worldwide-placename', function () {

	$('#location-search-remove').show();

});

function removeLocation() {
	var grid = document.querySelector('#fh5co-board');
	var category = $('.category-filter').val();
	var search = $('.search-filter').val();
	var subcategory = $('.subcategory-filter').val();
	var urgent = $('.urgent-filter').val();
	var ads = $('.ads-filter').val();

	if (urgent == "0") {
		urgent = '';
	}

	if (ads == "0") {
		ads = '';
	}

	$('.search-location').hide();
	$('.loading-btn').show();
	var remove = 'remove';

	if (locationTracker == 1) {
		locationTracker = 0;
		$.ajax({
			url: baseUrl+'/site/loadresults/',
			type: "post",
			dataType: "html",
			data: {
				'remove': remove,
				"loadMore": 1, category: category, search: search,
				subcategory: subcategory, urgent: urgent, ads: ads
			},
			beforeSend: function () {
				$('.loading-btn').show();
			},
			success: function (responce) {
				//alert(responce);
				$('.loading-btn').hide();
				$('.btn-worldwide').removeClass('session-data');
				$('.btn-worldwide').removeClass('btn-worldwide-placename');
				$('.btn-worldwide').removeClass('hidden');
				$('.btn-worldwide').html(yii.t('app', 'World wide'));
				$('.btn-worldwide').show();
				$('.loading-btn').hide();
				$("#fh5co-board").html($.trim(responce));
				salvattore.recreateColumns(grid);
				$('.miles').html(yii.t('app', 'World Wide...'));
				$('#nearmemodals').modal('toggle');
				$(".more-listing").show();
				$("#pac-input").val('');
				$("#pac-input2").val('');
				$('.show-world-wide').hide();
				locationTracker = 1;
			}

		});
	}

}

function getfollows(userid) {

	//alert(userid);
	//alert('getfollows');

	var user_id = "#follow" + userid;
	var sellerfollow = $('#seller_follow').val();
	if (followval == 1) {
		followval = 0;
		$.ajax({
			url: baseUrl+'/user/getfollow/',
			type: "post",
			dataType: "html",
			data: { 'fuserid': userid },
			beforeSend: function () {
				if ($.trim(sellerfollow) == '1') {
					var followcount = $('.follower-count').html();
					following_count = parseInt(followcount) + parseInt(1);
					$('.follower-count').html(following_count);
					$('#follow' + userid).removeClass('btn-make-an-offer');
					$('#follow' + userid).addClass('btn-chat-with-seller');
					$('#follow' + userid).addClass('primary-bg-color');
					$('#sellerfollow' + userid).css('color', '#ffffff');
					$('#sellerfollow' + userid).html(yii.t('app','Following'));
				} else {
					$(user_id).html(yii.t('app','Following'));
				}
				$(user_id).attr("onclick", "return deletefollows(" + userid + ");");
				followval = 1;

			},
			success: function (responce) {
				//alert(responce);
				var responce = $.trim(responce);
				//New changes
				if (responce != 1) {
					if ($.trim(sellerfollow) == '1') {
						var followcount = $('.follower-count').html();
						following_count = parseInt(followcount) - parseInt(1);
						$('.follower-count').html(following_count);
						$('#follow' + userid).addClass('btn-make-an-offer');

						$('#follow' + userid).removeClass('btn-chat-with-seller');
						$('#follow' + userid).css('margin-top', '20px');
						$('#follow' + userid).css('border', '1px solid #d0dbe5');
						$('#sellerfollow' + userid).css('color', '#515e6a');
						$('#sellerfollow' + userid).html(yii.t('app','Follow'));
					} else {
						$(user_id).html(yii.t('app','Follow'));
					}
					//$(user_id).attr("onclick", "return getfollows(" + userid + ");");
					unfollowval = 1;
				}
				// New changes

			},
			error: function(err){
				console.log("error");
			}

		});
	}

}
function deletefollows(userid) {

	//alert(userid);
	//alert('deletefollows');

	var user_id = "#follow" + userid;
	var sellerfollow = $('#seller_follow').val();
	//alert(user_id);
	if (unfollowval == 1) {
		unfollowval = 0;
		$.ajax({
			url:baseUrl+'/user/deletefollow/',
			type: "post",
			dataType: "html",
			data: { 'userid': userid },
			beforeSend: function () {
				if ($.trim(sellerfollow) == '1') {
					$('#sellerfollow' + userid).html(yii.t('app','Follow'));
					var followcount = $('.follower-count').html();
					following_count = parseInt(followcount) - parseInt(1);
					$('.follower-count').html(following_count);
					$('#follow' + userid).addClass('btn-make-an-offer');

					$('#follow' + userid).removeClass('btn-chat-with-seller');
					$('#follow' + userid).css('margin-top', '20px');
					$('#follow' + userid).css('border', '1px solid #d0dbe5');
					$('#sellerfollow' + userid).css('color', '#515e6a');
					$('#sellerfollow' + userid).html(yii.t('app','Follow'));
				} else {
					$(user_id).html(yii.t('app','Follow'));
				}
				$(user_id).attr("onclick", "return getfollows(" + userid + ");");
				unfollowval = 1;
			},
			success: function (responce) {
				//alert(responce);
				var responce = $.trim(responce);

				//New changes
				if (responce != 1) {
					if ($.trim(sellerfollow) == '1') {
						var followcount = $('.follower-count').html();
						following_count = parseInt(followcount) + parseInt(1);
						$('.follower-count').html(following_count);
						$('#follow' + userid).removeClass('btn-make-an-offer');
						$('#follow' + userid).addClass('btn-chat-with-seller');
						$('#follow' + userid).addClass('primary-bg-color');
						$('#sellerfollow' + userid).css('color', '#ffffff');
						$('#sellerfollow' + userid).html('Following');
					} else {
						$(user_id).html(yii.t('app','Follow'));
					}
					//$(user_id).attr("onclick", "return deletefollows(" + userid + ");");
					followval = 1;
				}
				// New changes
			}

		});
	}

}


$(document).on('click', '#reportflagok', function () {
	var user = $('.logindetails').val();
	var loguserid = $('.product-user-id').val();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		var itemid = $(".item-id").val();
		//alert(itemid);
	//	if (confirm('Report this as inappropriate or broke')) {
			$.ajax({
				url: baseUrl+'/products/reportitem/',
				type: "GET",
				data: { 'itemid': itemid, 'userid': loguserid },
				beforeSend: function () {
					$('.reportloader').show();
				},
				success: function (responce) {
				//	$(".report-text").html(yii.t('app','Undo reporting'));
				//	$(".item-report").attr("id", "unreportflag");
				//	$('.report-active').removeClass('exclamatory-icon');
				//	$('.report-active').addClass('exclamatory-icon-active');
				
						window.location.reload();
				}
			});
		//	closeConfirmrmodal();
	
		
	}

});
$(document).on('click', '#reportflagcancel', function () {
$('#reportconfirm_popup_container').modal('hide');

});

$(document).on('click', '#undoreportflagok', function () {
	var user = $('.logindetails').val();
	var loguserid = $('.product-user-id').val();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		var itemid = $(".item-id").val();
	//	if (confirm("Cancel Report this ?")) {
			$.ajax({
				url: baseUrl+'/products/undoreport/',
				type: "GET",
				data: { 'itemid': itemid, 'userid': loguserid },
				beforeSend: function () {
					$('.reportloader').show();
				},
				success: function (responce) {
				//	$(".report-text").html(yii.t('app','Report inappropriate'));
				//	$(".item-report").attr("id", "reportflag");
				//	$('.report-active').removeClass('exclamatory-icon-active');
				//	$('.report-active').addClass('exclamatory-icon');
					
						window.location.reload();
				}
			});
		//}
	
		
	}

});
$(document).on('click', '#undoreportflagcancel', function () {
$('#undoreportconfirm_popup_container').modal('hide');

});


$(document).on('click', '#cancelorderflagok', function () {
	var orderId = $('#cancelOrderid').val();
window.location.href = baseUrl+'/buynow/cancelorder/' + orderId;

});
$(document).on('click', '#cancelorderflagcancel', function () {
$('#cancelorderconfirm_popup_container').modal('hide');

});



$(document).on('click', '#mobreportflagok', function () {
	var user = $('.logindetails').val();
	var loguserid = $('.product-user-id').val();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		var itemid = $(".item-id").val();
		//if (confirm(yii.t('app', 'Report this as inappropriate or broken?'))) {
			$.ajax({
				url: baseUrl+'/products/reportitem/',
				type: "GET",
				data: { 'itemid': itemid, 'userid': loguserid },
				beforeSend: function () {
					$('.reportloader').show();
				},
				success: function (responce) {
				/*	$(".mob-report-text").html(yii.t('app', 'Undo reporting'));
					$(".mob-item-report").attr("id", "mob_unreportflag");
					$('.report-mob-active').removeClass('exclamatory-icon');
					$('.report-mob-active').addClass('exclamatory-icon-active');*/
					window.location.reload();
				}
			});
		//}
	}

});

$(document).on('click', '#mobreportflagcancel', function () {
$('#mobreportconfirm_popup_container').modal('hide');

});


$(document).on('click', '#mobundoreportflagok', function () {
	var user = $('.logindetails').val();
	var loguserid = $('.product-user-id').val();
	if (user == '') {
		window.location = baseUrl+'/site/login';
	} else {
		var itemid = $(".item-id").val();
		//if (confirm(yii.t('app',"Cancel Report this ?"))) {
			$.ajax({
				url: baseUrl+'/products/undoreport/',
				type: "GET",
				data: { 'itemid': itemid, 'userid': loguserid },
				beforeSend: function () {
					$('.reportloader').show();
				},
				success: function (responce) {
				/*	$(".mob-report-text").html(yii.t('app', 'Report inappropriate'));
					$(".mob-item-report").attr("id", "mob_reportflag");
					$('.report-mob-active').removeClass('exclamatory-icon-active');
					$('.report-mob-active').addClass('exclamatory-icon');*/
					window.location.reload();
				}
			});
		//}
	}

});

$(document).on('click', '#mobundoreportflagcancel', function () {
$('#mobundoreportconfirm_popup_container').modal('hide');

});

function popitup(type) {
	var url = baseUrl + '/user/getsocialaccess?provider=facebook';
	var screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
	screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
	outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
	outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
	width = 600,
	height = 400,
	left = parseInt(screenX + ((outerWidth - width) / 2), 10),
	top = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
	features = 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top;
	newwindow = window.open(url, 'Social Login', features);
	if (window.focus) { newwindow.focus(); }
	return false;
}

function selectpromotion() {
	$(".promotion-error").hide();
	$(".promotion-success").hide();
	var currency = $('#selectedoption').val();
	if (currency == 0) {
		$(".promotion-error").html(
				yii.t('app','Please select any one of the Currency')
			);
		$(".promotion-error").show();
		return false;
	}
	$.ajax({
		url: baseUrl+'/admin/promotions/promotioncurrencies',
		type: "GET",
		data: { 'currency': currency },
		beforeSend: function () {
			$('.promotionloader').show();
		},
		success: function (responce) {
			if (responce != '') {
				$('.promotionloader').hide();
				$('.promotion-success').html(yii.t('app','Promotion Currency Updated'));
				//yii.t('app','Promotion Currency Updated')
				$('.promotion-success').show();
			}


		}
	});

}

function switchVisible_promotion(id) {
	$.ajax({
		url: baseUrl+'/user/promotiondetails/',
		type: "POST",
		data: { 'id': id },
		dataType: "html",
		success: function (responce) {
			$('#promotion-content').css('display', 'none');
			$('#promotion-details').css('display', 'block');
			$('#promotion-details').html(responce);
		},
		error: function(error){
			console.log("error");
		}
	});
}

function switchVisible_promotionback() {
	$('#promotion-details').css('display', 'none');
	$('#promotion-content').css('display', 'block');
	$('.promotions-content-cnt').remove();
}



function cancel_order(orderId) {
	if (confirm(yii.t('app','Are you sure, you want to cancel the order?'))) {
		window.location.href = baseUrl+'/buynow/cancelorder/' + orderId;
	}
	else
		return false;
}

function select_shipping(shippingId) {
	$("#selectedshipping").val(shippingId);
	$(".reviewOrderShippingId").val(shippingId);
	$("#stripeshippingid").val(shippingId);


	$(".joysale-acc-addr-cnt").css("box-shadow", "none");
	$("#highlight" + shippingId).css("box-shadow", "0 0 0 3px #2bc248 inset");
	$(".address-active").hide();
	$("#activeaddr" + shippingId).show();
}

function edit_shipping(addressId) {

	//alert(addressId);
	$.ajax({
		url: baseUrl+'/buynow/getshipping/',
		type: "GET",
		data: { 'id': addressId },
		success: function (responce) {

			
			var obj = jQuery.parseJSON(responce);
			
			countrysel = obj['countryCode'] + "-" + obj['country'];
			$("#Tempaddresses_nickname").val(obj['nickname']);
			$("#Tempaddresses_name").val(obj['name']);
			$("#Tempaddresses_address1").val(obj['address1']);
			$("#Tempaddresses_address2").val(obj['address2']);
			$("#Tempaddresses_city").val(obj['city']);
			$("#Tempaddresses_state").val(obj['state']);
			$("#Tempaddresses_zipcode").val(obj['zipcode']);
			$("#Tempaddresses_phone").val(obj['phone']);
			$("#shippingId").val(obj['slug']);
			$('option[value=' + countrysel + ']').attr('selected', 'selected');
		}
	});
}
function braintree_payment() {
	shippingId = $("#selectedshipping").val();
	productId = $(".review-order-product-id").val();
	totalcost = $("#totalcost").html();
	currency = $("#itemcurrency").html();
	userId = $("#loguserid").val();
	card_name = $("#card_name").val();
	card_number = $("#card_number").val();
	expiry_month = $("#expiry_month").val();
	expiry_year = $("#expiry_year").val();
	cvv = $("#cvv").val();

	$.ajax({
		url: yii.urls.base + '/buynow/checkout/braintreepayment/',
		type: "POST",
		data: {
			'shippingId': shippingId, 'productId': productId, 'totalcost': totalcost, 'currency': currency, 'userId': userId,
			'card_name': card_name, 'card_number': card_number, 'expiry_month': expiry_month, 'expiry_year': expiry_year, 'cvv': cvv
		},
		success: function (responce) {
			if (responce == "success") {
				window.location.href = yii.urls.base + '/checkout/success';
			}
			else {
				window.location.href = yii.urls.base + '/checkout/canceled';

			}
		}
	});
}


function view_invoice(orderId) {
	$.ajax({
		url: baseUrl+'/buynow/viewinvoice',
		type: "GET",
		data: { 'orderId': orderId },
		dataType: 'html',
		success: function (responce) {
			//alert(responce);
			//console.log(responce);
			$("#invoice_content").html(responce);
			//$("#invoice_content").show();
		},
		error: function(){
			console.log("error");
		}
	});
}
/* price decimal script */
$(document).on('keypress',
	'#Products_price', function (evt) {
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if ((charCode) != 46 && (charCode) > 31
			&& ((charCode) < 48 || (charCode) > 57))
			return false;

		return true;

	});

// $(document).on('keypress',
// 	'#Products_price', function (evt) {
// 		var pricerange_before_decimal = "";
// 		var pricerange_after_decimal = "";
// 		var pricerange = [];
// 	   $.ajax({
//    		url: baseUrl+'/site/getpricerange/',
//    		type: "GET",
//    		dataType: "html",
//    		ContentType :'text/html',
//    		success: function (responce) {
//    			 pricerange = JSON.parse(responce);
// 			 pricerange_before_decimal = pricerange['before_decimal_notation'];
// 			 pricerange_after_decimal = pricerange['after_decimal_notation'];
// 			//  var productprice = $('#Products_price').val();
// 		 //     var number = (productprice.split('.'));
// 			//  if (number[0].length > pricerange_before_decimal || number[1].length > pricerange_after_decimal) {
			
// 			// 	var res = $th.val().substr(0, 6);
// 			// 	$th.val(res);
			
// 			// }
		
	
	


	  
// 		},
// 		error: function(err){
// 			console.log(err);
// 		}
// 	});
	 

// 	});



$(document).on('keyup',
	'#Products_price', function (evt) {
		var exp = /^\d{0,7}(\.{1}\d{0,2})?$/g;
		var letter = /^[a-zA-Z]+$/;
		var $th = $(this);

		 var before_decimal = $('#before_decimal').val();
		 var after_decimal = $('#after_decimal').val();
		if(isNaN($th.val())){

			$("#Products_price_em_").show();
				$("#badMessage").hide();
				$('#Products_price_em_').text(yii.t('app','Invalid format'));
				$("#Products_price_em_").fadeIn();
				setTimeout(function () {
					$("#Products_price_em_").fadeOut();
				}, 2000);
				return false;
	 	}else{
		$("#Products_price_em_").hide();
	 	}

		// if (!exp.test($th.val())) {

			var number = ($(this).val().split('.'));
			if (number[0].length > before_decimal) {
				var res = $th.val().substr(0, before_decimal);
				$th.val(res);
			$("#Products_price_em_").show();
			$("#badMessage").hide();
			//$('#Products_price_em_').text(yii.t('app','Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
			$('#Products_shippingCost_em_').text(yii.t('app','Invalid format (only ' + before_decimal + ' digit allowed before decimal point and ' + after_decimal +' digit after decimal point)'));
			$("#Products_price_em_").fadeIn();
			setTimeout(function () {
				$("#Products_price_em_").fadeOut();
			}, 2000);
			return false;

			}
			
		
			var length_before_decimal =number[0].length;
			var add_decimal_point = Number(length_before_decimal)+1;
			var total_length;	
			if (number[1].length > after_decimal)
			{		
					total_length = Number(add_decimal_point) + Number(after_decimal);
					var res = $th.val().substr(0, total_length);
					$th.val(res);
					$("#Products_price_em_").show();
			$("#badMessage").hide();
			//$('#Products_price_em_').text(yii.t('app','Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
			$('#Products_shippingCost_em_').text(yii.t('app','Invalid format (only ' + before_decimal + ' digit allowed before decimal point and ' + after_decimal +' digit after decimal point)'));
			$("#Products_price_em_").fadeIn();
			setTimeout(function () {
				$("#Products_price_em_").fadeOut();
			}, 2000);
			return false;
				

			}
	
		
	});

 	$(document).on('keypress',
	'.productattributerange', function (e) {
	 	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}

			
	});

	$(document).on('keyup',
	'.productattributerange', function (evt) {

		var $th = $(this).val();
	
			var id=$(this).attr('id');
	
		if(isNaN($th)){

				$("#badMessage").hide();
				$("."+id).show();
				$("."+id).html('Invalid format');
				$("."+id).fadeIn();
				setTimeout(function () {
					$("."+id).fadeOut();
				}, 2000);
				return false;
	 	}
	 });



function myOfferRate() {
	var offerRate = $("#MyOfferForm_offer_rate").val();
	var productPrice = $(".product-price-hidden").val();

	var offer = offerRate.replace(/\s/g, '');


		if(isNaN(offerRate)){

		$(".message-error").show();
					$(".message-error").html(
						yii.t('app', 'Offer Price should be numbers.'));

					setTimeout(function () {
						$(".message-error").show();
					});
	 	}else{
		$(".message-error").hide();
	 	}


	if (productPrice != "" && productPrice != 0) {
		if (offerRate != "" && offerRate > 0) {
			if (Number(offerRate) >= Number(productPrice)) {
				$(".offer-form").hide();
				$(".message-error").show();
				$(".message-error").html(
					yii.t('app', 'Offer Price should be less than Product Price.'));

				setTimeout(function () {
					$(".message-error").show();
				});
			} else {
				$(".message-error").hide();
				$(".offer-form").show();
			}

		} else {
			$(".offer-form").hide();
		}
	}
}

function myoffer() {
	var offerRate = $("#MyOfferForm_offer_rate").val();
	var productPrice = $(".product-price-hidden").val();
	var productId = $('.item-id').val();
	var name = $("#MyOfferForm_name").val().trim();
	var email = $("#MyOfferForm_email").val();
	var phone = $("#MyOfferForm_phone").val().trim();
	var message = $("#MyOfferForm_message").val().trim();
	var currency = $(".price-option-hidden").val();
	var sellerId = $(".product-user-id").val();
	var numbers = /[0-9]/gi;
	var offer = offerRate.replace(/\s/g, '');
	$("#MyOfferForm_offer_rate").val(offer);
	if ($.trim(offerRate) == '') {
		$('.message-error').show();
		$('.message-error').html(yii.t('app','Offer value can not be empty'));
		return false;
	}
	if ((offerRate) < 1) {
		$('.message-error').show();
		$('.message-error').html(yii.t('app','Minimum  Offer value should  be 1'));
		return false;
	}
	if (message == "") {
		$('#errorMessage').show();
		$("#errorMessage").html(yii.t('app','Message cannot be blank'));
		$("#MyOfferForm_message").val('');
		return false;
	}
	if (productPrice != "" && productPrice != "0") {
		if (parseFloat(offerRate) >= parseFloat(productPrice)) {
			$('.message-error').show();
			$('.message-error').html(yii.t('app', 'Offer Price should be less than Product Price.'));
			return false;
		}
	}
	if ($.trim(phone) == '') {
		phone = 'NILL';
	}

	if (name != "" && email != "" && message != ""
		&& offerRate > 0) {
		var imageUrl = baseUrl+'/images/loader.gif';
		/*$('.offer-send-btn').html(yii.t('app','Sending...'));
		$('.offer-send-btn').attr('disabled');*/
		if (offercheck == 1) {
			offercheck = 0;
			
			$.ajax({
				type: 'POST',
				url: baseUrl+'/products/myoffer/',
				data: {
					offerRate: offerRate,
					name: name,
					email: email,
					phone: phone,
					message: message,
					sellerId: sellerId,
					currency: currency,
					productId: productId
				},
				beforeSend: function () {
					$('.offer-send-btn').html(yii.t('app','Sending...'));
					$('.offer-send-btn').attr('disabled');
				},
				success: function (data) {
					//alert(data);
					data = data.trim();
					if (data == "error") {
						window.location.reload();
					}
					else if (data == 11 || data == 12) {
						$('#errorMessage').show();
						$('.offer-send-btn').html(yii.t('app','Send'));

						if (data == 11) { $("#errorMessage").html('conversation blocked'); } //Seller is blocked by you
						else { $("#errorMessage").html(yii.t('app','conversation blocked')); } //You blocked this seller
						setTimeout(function () {
							$("#errorMessage").html("");
							$('#errorMessage').hide();
						}, 3000);
						$('.offer-send-btn').removeAttr('disabled');
						return false;
					}
					else {
						$('.offer-send-btn').html(yii.t('app','Send'));
						$('.offer-send-btn').removeAttr('disabled');
						$("#MyOfferForm_offer_rate").val('');
						$("#MyOfferForm_message").val('');
						$('#offer-modal').hide();
						$('#offer-success-modal').show();
						$("#offer-success-modal").addClass("in");
						$('.sent-text').html(yii.t('app','Your Offer sent'));
						offercheck = 1;
					}


				}
			});
		}
	}
}


function isNumberrKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if ((charCode) != 46 && (charCode) > 31 && ((charCode) < 48 || (charCode) > 57))
		return false;

	return true;
}


//shipping cost
$(document).on('keypress',
	'#Products_shippingCost', function (evt) {
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if ((charCode) != 46 && (charCode) > 31
			&& ((charCode) < 48 || (charCode) > 57))
			return false;

		return true;

	});


$(document).on('keyup',
	'#Products_shippingCost', function (evt) {
		var exp = /^\d{0,6}(\.{1}\d{0,2})?$/g;
		var letter = /^[a-zA-Z]+$/;
		var $th = $(this);

			var before_decimal = $('#before_decimal').val();
			 var after_decimal = $('#after_decimal').val();
			if(isNaN($th.val())){

				$("#Products_shippingCost_em_").show();
					$("#badMessage").hide();
					$('#Products_shippingCost_em_').text(yii.t('app','Invalid format'));
					$("#Products_shippingCost_em_").fadeIn();
					setTimeout(function () {
						$("#Products_shippingCost_em_").fadeOut();
					}, 2000);
					return false;
		 	}else{
			$("#Products_shippingCost_em_").hide();
		 	}


		 	var number = ($(this).val().split('.'));
			if (number[0].length > before_decimal) {
				var res = $th.val().substr(0, before_decimal);
				$th.val(res);
			$("#Products_shippingCost_em_").show();
			$("#badMessage").hide();
			$('#Products_shippingCost_em_').text(yii.t('app','Invalid format (only ' + before_decimal + ' digit allowed before decimal point and ' + after_decimal +' digit after decimal point)'));
			$("#Products_shippingCost_em_").fadeIn();
			setTimeout(function () {
				$("#Products_shippingCost_em_").fadeOut();
			}, 2000);
			return false;

			}
			
		
			var length_before_decimal =number[0].length;
			var add_decimal_point = Number(length_before_decimal)+1;
			var total_length;	
			if (number[1].length > after_decimal)
			{		
					total_length = Number(add_decimal_point) + Number(after_decimal);
					var res = $th.val().substr(0, total_length);
					$th.val(res);
					$("#Products_shippingCost_em_").show();
			$("#badMessage").hide();
			//$('#Products_shippingCost_em_').text(yii.t('app','Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
			$('#Products_shippingCost_em_').text(yii.t('app','Invalid format (only ' + before_decimal + ' digit allowed before decimal point and ' + after_decimal +' digit after decimal point)'));
			$("#Products_shippingCost_em_").fadeIn();
			setTimeout(function () {
				$("#Products_shippingCost_em_").fadeOut();
			}, 2000);
			return false;
				

			}

	});

// shipping cost decimal

/* end price decimal script */


function ajaxSearch(org, event) {

	$(".tags").autocomplete({
		source: baseUrl+'/site/autosearch/'
	});
	/*if(event.keyCode == 13)
	{
		dosearch();
	}
	else
		{
	searchstring = $(org).val();
		$.ajax({
		  url: baseUrl+'/site/autosearch/',
		  type: "POST",
		  data: { 'searchstring':searchstring},
		  success: function(responce){alert(responce);
				var parsedJson = $.parseJSON(responce);alert(parsedJson[0]);
			$( ".tags" ).autocomplete({
			  source: parsedJson
			});

		  }
		});*/
	//}
}
function gotogetLocationDataHome() {
	//	search = $("#searchval").val(" ");
	$('#pac-input').attr("value", "");
	search = $("#pac-input").val();

	//Unset session data.
	$.ajax({
		url: baseUrl+'/site/currentloc/',
		type: "POST",
		dataType : "html",
		data: {
			remove : 1,
		},
		success: function (res) {

		}
	});

	getLocationDataset('search');
}
function gotogetLocationDatamobileHome() {
	$('#pac-input2').attr("value", "");
	search = $("#pac-input2").val();
	getLocationDatamobileset('search');

}

$(document).ready(function () {
	var pacinput = $("#pac-input").val();
	if (pacinput == ",") {
		$("#pac-input").val("");
	}
	/*$("#pac-input").keypress(function () {
		var aa = $("#pac-input").val();
		var bb = $.trim(aa);
		$("#pac-input").val(bb);
	}); */

	var pacinput2 = $("#pac-input2").val();
	if (pacinput2 == ",") {
		$("#pac-input2").val("");
	}
	$("#pac-input2").keypress(function () {
		var a2 = $("#pac-input2").val();
		var b2 = $.trim(a2);
		$("#pac-input2").val(b2);
	});
});
$('#addProduct').removeAttr('disabled');


//joysale 2.3.1 update
// function userPermission(callValue) {
// 	var currentChatUserID = $("#currentChatUserID").val();
// 	var currentUserID = $("#currentUserID").val();

// 	$.ajax({
// 		url: yii.urls.base + '/chataction',
// 		type: "post",
// 		dataType: "html",
// 		data: { 'callValue': callValue, 'currentChatUserID': currentChatUserID },
// 		success: function (responce) {
// 			responce = responce.trim();
// 			var responce = responce.split("~#~");
// 			if ($.trim(responce[0]) == "unblocked") {
// 				appendText = '<a href="javascript: void(0);" id="user_pb" class="user_Permission" onclick="userPermission(\'WW14dlkycz0=\');">' + yii.t('app', 'Block User') + '</a>';
// 				$('.user_pactive').html($.trim(appendText));
// 				$('.message-block-container').attr({ style: 'display:none;' });
// 			} else if ($.trim(responce[0]) == "blocked") {
// 				appendText = '<a href="javascript: void(0);" id="user_pub" class="user_Permission" onclick="userPermission(\'ZFc1aWJHOWphdz09\');">' + yii.t('app', 'Unblock User') + '</a>';
// 				$('.user_pactive').html($.trim(appendText));
// 				if ($.trim(responce[1]) == currentChatUserID)
// 					$('.message-block-container > span').html(yii.t('app', 'You have blocked this user'));
// 				$('.message-block-container').attr({ style: 'display:block;' });

// 			} else if ($.trim(responce[0]) == "currentblocked") {
// 				if ($.trim(responce[1]) == currentUserID)
// 					$('.message-block-container > span').html(yii.t('app', 'You are blocked'));
// 				$('.message-block-container').attr({ style: 'display:block;' });
// 				$('#user_pb').attr({ style: 'display:none;' });
// 			}
// 		}
// 	});




// }

function offerStatus(messageId) {


	// Create Base64 Object
	var Base64 = { _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) { var t = ""; var n, r, i, s, o, u, a; var f = 0; e = Base64._utf8_encode(e); while (f < e.length) { n = e.charCodeAt(f++); r = e.charCodeAt(f++); i = e.charCodeAt(f++); s = n >> 2; o = (n & 3) << 4 | r >> 4; u = (r & 15) << 2 | i >> 6; a = i & 63; if (isNaN(r)) { u = a = 64 } else if (isNaN(i)) { a = 64 } t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a) } return t }, decode: function (e) { var t = ""; var n, r, i; var s, o, u, a; var f = 0; e = e.replace(/[^A-Za-z0-9+/=]/g, ""); while (f < e.length) { s = this._keyStr.indexOf(e.charAt(f++)); o = this._keyStr.indexOf(e.charAt(f++)); u = this._keyStr.indexOf(e.charAt(f++)); a = this._keyStr.indexOf(e.charAt(f++)); n = s << 2 | o >> 4; r = (o & 15) << 4 | u >> 2; i = (u & 3) << 6 | a; t = t + String.fromCharCode(n); if (u != 64) { t = t + String.fromCharCode(r) } if (a != 64) { t = t + String.fromCharCode(i) } } t = Base64._utf8_decode(t); return t }, _utf8_encode: function (e) { e = e.replace(/rn/g, "n"); var t = ""; for (var n = 0; n < e.length; n++) { var r = e.charCodeAt(n); if (r < 128) { t += String.fromCharCode(r) } else if (r > 127 && r < 2048) { t += String.fromCharCode(r >> 6 | 192); t += String.fromCharCode(r & 63 | 128) } else { t += String.fromCharCode(r >> 12 | 224); t += String.fromCharCode(r >> 6 & 63 | 128); t += String.fromCharCode(r & 63 | 128) } } return t }, _utf8_decode: function (e) { var t = ""; var n = 0; var r = c1 = c2 = 0; while (n < e.length) { r = e.charCodeAt(n); if (r < 128) { t += String.fromCharCode(r); n++ } else if (r > 191 && r < 224) { c2 = e.charCodeAt(n + 1); t += String.fromCharCode((r & 31) << 6 | c2 & 63); n += 2 } else { c2 = e.charCodeAt(n + 1); c3 = e.charCodeAt(n + 2); t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63); n += 3 } } return t } }
	var decodedString1 = Base64.decode(messageId);
	var decodedString = Base64.decode(decodedString1);
	//console.log(decodedString); // Outputs: 18@#@accept
	var offerDeails = decodedString.split("@#@");
	//alert(offerDeails[0]); //offer id
	//alert(offerDeails[1]); //type
	var randNo = offerDeails[2]
	//alert(randNo);

	$.ajax({
		url: baseUrl+'/products/offerstatus/',
		type: "post",

		data: { 'messageId': messageId },
		beforeSend: function () {
			$('.btn-process-' + randNo).hide();
		},
		success: function (responce) {

	//	alert(responce);
	responce = responce.trim();

	if (responce == 'B11' || responce == 'B12') {
		$('#makeoffer_error_msg').html(yii.t('app','Your chat is blocked status unable allow this process.'));
		setTimeout(function () {
			$("#makeoffer_error_msg").html(" ");
		}, 3000);
		$('.btn-process-' + randNo).show();
	}
	else if (responce == 0) {
		$('.btn-process-' + randNo).show();
	}
	else {
				//$('.accept_decline').hide();	
				$('#offerADId').val(responce); // new accept/decline offer id
				$('#offerADType').val(offerDeails[1]); //accept,decline
				//$("#messageInputOffer").val(responce);
				$('#sendform').click();
			}

		}
	});
}







function braintreecheckout() {
	/*$.ajax({
	      url: yii.urls.base+ '/buynow/checkout/mypayment/',
	      type: "POST",
	      data: {},
	      success: function(responce){
	      	alert(responce);
      		if(responce == "success")
      			window.location.href = yii.urls.base+ '/checkout/success';
      		else
      			window.location.href = yii.urls.base+ '/checkout/canceled';
	      }
	  });*/
	  window.location.href = yii.urls.base + '/buynow/checkout/mypayment/';

	}


	$(document).ready(function() {

		$("#giving_away").click(function() {
    	//alert(0);
    	if( $(this).attr('checked') ){
    		$("#giving_away").val('0');
    		$("#Products_price").val('');
    		$('#giving_away').removeAttr('checked');
    		$('.Category-price-box-row').slideToggle(500);

    	}else{
    		$("#giving_away").val('1');
    		$('#giving_away').attr('checked','checked');
    		$('.Category-price-box-row').slideToggle(500);
    	}
    	var productPropertyUpdates = 0;	
    	var selectedCategory = $('#Products_category').val();
    	var giving_away = $("#giving_away").val();
    	if(productPropertyUpdates==0 && selectedCategory!=0 && selectedCategory !=""){
    		productPropertyUpdates = 1;
    		//console.log('Products_category on change call click on giving away');
    		$.ajax({
    			url: baseUrl+'/products/productproperty/',
    			type: "post",
    			data: {'selectedCategory':selectedCategory, 'givingAway':giving_away},
    			dataType: "html",
    			success: function(responce){
    				$('.instant-buy-details').fadeOut('fast');
    				responce = responce.trim();
    				propertyData = eval(responce);
    				if(propertyData[0] == 0){
    					$('.dynamicProperty').html(propertyData[1]);
    					$('.dynamic-section').hide();
    				}else{
    					$('.dynamicProperty').html(propertyData[1]);
    					$('.dynamic-section').show();
    				}
    				//$('#Products_subCategory').html(propertyData[2]); 
    				productPropertyUpdates = 0;
					//alert(responce);
				}
			});
    	}

    });


		$("#facebook_share").click(function() {
			fbsession = $("#facebooksession").val();

			if( $(this).attr('checked') ){
				$('#facebook_share').removeAttr('checked');
			}else{
				if(fbsession=="0")
					popitup('Facebook');
				$('#facebook_share').attr('checked','checked');
			}
		});

	});



function remove_images(org,imgname)
{
	$(org).hide();
	$(org).prev("img").hide();
	$(org).parent().remove();
	uploadedfiles = $("#uploadedfiles").val();
	filesarr = JSON.parse(uploadedfiles);
	filesarr = $.grep(filesarr, function(value) {
		return value != imgname;
	});

	if(filesarr.length >= 1){
		files = JSON.stringify(filesarr);
		$("#uploadedfiles").val(files);
	}else{
		$("#uploadedfiles").val("");
	}
	var cnt=$("#count").val();
	var a=cnt-1;;
	$("#count").val(a);
	$.ajax({
		url: baseUrl+'/products/remove_blogimage/',
		type: "POST",
		dataType : "html",
		data: {
			image : imgname,

		},
		success: function (res) {

		}
	});

}

function remove_images1(org,imgname,pid)
{


	$(org).hide();
	$(org).prev("img").hide();
	$(org).parent().remove();

	var cnt=$("#count").val();
	var a=cnt-1;;
	$("#count").val(a);
	removeimg.push(imgname);
	$("#removefiles").val(removeimg);
	// $.ajax({
	// 	url: baseUrl+'/products/remove_blogimage1/',
	// 	type: "POST",
	// 	dataType : "html",
	// 	data: {
	// 		'image' : imgname,
	// 		'productId' : pid,
	// 	},
	// 	success: function (res) {
 //            //alert(res);
 //        }
 //    });

}


function userPermission(callValue) {
	
	var currentChatUserID = $("#currentChatUserID").val();
	var currentUserID = $("#currentUserID").val();

	$.ajax({
		url: baseUrl+'/message/chataction',
		type: "post",
		dataType: "html",
		data: { 'callValue': callValue, 'currentChatUserID': currentChatUserID },
		success: function (responce) {

			responce = responce.trim();
			var responce = responce.split("~#~");

			var a=$.trim(responce[1]);
			var val=a.substring(0, 4);

			if ($.trim(responce[0]) == "unblocked") {
				appendText = '<a href="javascript: void(0);" id="user_pb" class="user_Permission" onclick="userPermission(\'WW14dlkycz0=\');">' + yii.t('app','Block User') + '</a>';
				$('.user_pactive').html($.trim(appendText));
				$('.message-block-container').attr({ style: 'display:none;' });
			} else if ($.trim(responce[0]) == "blocked") {
				appendText = '<a href="javascript: void(0);" id="user_pub" class="user_Permission" onclick="userPermission(\'ZFc1aWJHOWphdz09\');">' + yii.t('app','Unblock User') + '</a>';
				$('.user_pactive').html($.trim(appendText));
				if (val == currentChatUserID)
					$('#block_msg').html(yii.t('app','You have blocked this user'));
				$('.message-block-container').attr({ style: 'display:block;' });

			} else if ($.trim(responce[0]) == "currentblocked") {
				if ($.trim(responce[1]) == currentUserID)
					$('#block_msg').html(yii.t('app','You are blocked'));
				$('.message-block-container').attr({ style: 'display:block;' });
				$('#user_pb').attr({ style: 'display:none;' });
			}
			//alert(responce);
		}
	});




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



function shareLocationmap()
{
  var a = $("#pac-input1").val();
if (a == "") {
			$('#errmsg').show();
    		$('#map_button').addClass('map_but');
			$('#errmsg').html('Please select any location');
			setTimeout(function () {
				$('#errmsg').hide();
				$('#map_button').removeClass('map_but');
			}, 5000);
		return false;
}

  var geocoder = new google.maps.Geocoder();
var address = "new york";
document.getElementById('close-modal').click();
geocoder.geocode( { 'address': a}, function(results, status) {

  if (status == google.maps.GeocoderStatus.OK) {
    var lat = results[0].geometry.location.lat();
    var lon = results[0].geometry.location.lng();
   

	if (lat != "" && lon != "") {
		var str = '@#@';
		var staticMap = lat + str + lon;
		//alert(staticMap);
		$('#shareMap').val(staticMap);
		$('#sendform').click();
		//$('#sendform').removeAttr("onclick");
	
		$("#chtShareLocation").addClass("share_loction");
	$("#chtShareLocation").removeClass("share_loction_loader");
	console.log("location shared");
	}
	else {
		alert("Error share location");
	}


  } 
})


}



//// ADDBANNER VALIDATION

// banner image validation

function checkBanner()
{	var imageInput = document.getElementById('bannerimage');
	var reader = new FileReader();
	reader.readAsDataURL(imageInput.files[0]);
            reader.onload = function (e) {
              var image = new Image();
               image.src = e.target.result;
                 image.onload = function () {
                    var height = this.height;
                    var width = this.width;
				   if(width!=1920 && height!="400")
				   {
					$("#bannerimage_error").show();
	            	$("#badMessage").hide();
	            	$('#bannerimage_error').text(yii.t('app','Upload image with specified width & heigth'));
		            setTimeout(function () {
		        	$('#bannerimage_error').fadeOut('slow');
	            	}, 3000);
		            return false; 
				   }
                 } 
	}


}


function checkAppbanner()
{
	var appimageInput = document.getElementById('appbannerimage');
	var readerapp = new FileReader();
		readerapp.readAsDataURL(appimageInput.files[0]);
		readerapp.onload = function (e) {
              var image = new Image();
               image.src = e.target.result;
                 image.onload = function () {
                    var height = this.height;
                    var width = this.width;
				   if(width!=1024 && height!="500")
				   {
					$("#appbannerimage_error").show();
	            	$("#badMessage").hide();
	            	$('#appbannerimage_error').text(yii.t('app','Upload image with specified width & heigth'));
		            setTimeout(function () {
		        	$('#appbannerimage_error').fadeOut('slow');
	            	}, 3000);
		            return false; 
				   }
                   } 
	}
}

// date picker validation
$(document).ready(function () {
	$("#startdate").datepicker({
		minDate: 0,
		dateFormat: 'yy-mm-dd',
		onSelect: function(selected,evnt) {
			var startDate = $("#startdate").val();
			var date = new Date(startDate);
			var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
			$("#enddate").datepicker({
				//	minDate: new Date(y, m, d+1),
				minDate: new Date(startDate),
				dateFormat: 'yy-mm-dd'
			});

			var startDate = $("#startdate").val();
			var endDate = $("#enddate").val(); 
			
			if (startDate =="") {
				$("#startDate_error").show();
				$("#badMessage").hide();
				$("#total_Cost").hide();
				$('#startDate_error').text(yii.t('app','Start date cannot be blank'));
				setTimeout(function () {
					$('#startDate_error').fadeOut('slow');
				}, 3000);
				return false;
			} 	
		
		}
	});

	$("#enddate").datepicker({
		minDate: 0,
		dateFormat: 'yy-mm-dd',
		beforeShow: function () {  
			var start_date = $('#startdate').val();
         if(start_date == "") {
         	$("#startdate").datepicker('show');
            return false;
         }
		},onSelect: function(selected,evnt) {
			

			var startDate = $("#startdate").val();
			var endDate = $("#enddate").val(); 
			
			
				if (startDate > endDate) {
				  	$("#endDate_error").show();
					$("#badMessage").hide();
					$("#total_Cost").hide();
					$('#endDate_error').text(yii.t('app','End Date must be greater than start date'));
					setTimeout(function () {
						$('#endDate_error').fadeOut('slow');
					}, 3000);
					return false;
				} else {
					//alert('comes');
					var perdayCost =$("#perdaycost").val(); 
	    			var currencycode =$("#currencycode").val();
	    			var currencyposition =$("#currencyposition").val(); 
	    			//alert(currencycode + " " + currencyposition);
					var date1, date2;
         		
         		date1 = new Date(startDate);
		 			date2 = new Date(endDate);
					var res = Math.abs(date1 - date2) / 1000;
					var days = Math.floor(res / 86400)+1;
					var totalCost = parseInt(days) * parseFloat(perdayCost);
					$("#total_Cost").show();
					$("#devicesuccess").hide();

			
					if(currencyposition == "prefix")
					{
						$('#total_Cost').text(yii.t('app','Your ads will run for')+ ' ' + days + ' ' + yii.t('app','days. You have to spend')+' ' + currencycode + ' ' + totalCost );
					}else{
						$('#total_Cost').text(yii.t('app','Your ads will run for')+ ' ' + days + ' ' + yii.t('app','days. You have to spend')+' ' + totalCost + ' ' + currencycode );
					}
					
					//	$('#PayValidation').val(1); 
					// setTimeout(function () {
					// 	$('#endDate_error').fadeOut('slow');
					// }, 3000);
					//	return false;
				//}
			}		
		
		}
	}); 

});


/*$(document).ready(function () {
   $("#enddate").click(function() {
 
	var startDate = $("#startdate").val();		
	if(!startDate)
	{
		$("#startDate_error").show();
$("#badMessage").hide();
$('#startDate_error').text(yii.t('app','Start date cannot be blank'));
setTimeout(function () {
	$('#startDate_error').fadeOut('slow');
}, 3000);
return false;
	}
});
});*/

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

function validateBanner123()
{
	 var startDate = $("#startdate").val();
	var endDate = $("#enddate").val();
	alert(startDate);
}
function validateBanner()
{
	
    var startDate = $("#startdate").val();
	var endDate = $("#enddate").val();
    var bannerimage = $("#bannerimage").val();  
    var appbannerimage = $("#appbannerimage").val();  
	var bannerurl = $("#bannerurl").val(); 
	var appbannerurl = $("#appurl").val(); 
     var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
	var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
	var imageInput = document.getElementById('bannerimage');
	var appimageInput = document.getElementById('appbannerimage');
	var perdayCost =$("#perdaycost").val(); 
	var currencycode =$("#currencycode").val(); 
	var dateValidation =  $("#dateValidation").val();
	var PayValidation = $("#PayValidation").val();

	 	 date1 = new Date(startDate);
		 date2 = new Date(endDate);
		 var date1month = date1.getMonth()+1;
		 var date2month = date2.getMonth()+1;
    
    if (date1.getFullYear() == "" || date1.getMonth()+1 == "" || date1.getDate() == "") {
    	alert(date1.getDate());
   $("#startDate_error").show();
				$("#badMessage").hide();
				$('#startDate_error').text(yii.t('app','Please provide valid start date'));
				setTimeout(function () {
					$('#startDate_error').fadeOut('slow');
				}, 3000);
				return false;
  }

       	if (date2.getFullYear() == ""
    || date2.getMonth()+1 == ""
    || date2.getDate() == "") {

   				$("#endDate_error").show();
				$("#badMessage").hide();
				$('#endDate_error').text(yii.t('app','Please provide valid end date'));
				setTimeout(function () {
					$('#endDate_error').fadeOut('slow');
				}, 3000);
				return false;
  }
	      var today = new Date();
	      var formattoday = formatDate(today);
	      var formatstartdate = formatDate(startDate);
	      var formatenddate = formatDate(endDate);
	      if(formatstartdate < formattoday)
	      {
	      	$("#startDate_error").show();
				$("#badMessage").hide();
				$('#startDate_error').text(yii.t('app','Please provide valid dates'));
				setTimeout(function () {
					$('#startDate_error').fadeOut('slow');
				}, 3000);
				return false;
	      }
        	     if(formatenddate < formattoday)
	      {
	      	$("#startDate_error").show();
				$("#badMessage").hide();
				$('#startDate_error').text(yii.t('app','Please provide valid dates'));
				setTimeout(function () {
					$('#startDate_error').fadeOut('slow');
				}, 3000);
				return false;
	      }
	     var res = Math.abs(date1 - date2) / 1000;
         var days = Math.floor(res / 86400)+1;


         if(isNaN(days))
         {
         		$("#startDate_error").show();
				$("#badMessage").hide();
				$('#startDate_error').text(yii.t('app','Please provide valid dates'));
				setTimeout(function () {
					$('#startDate_error').fadeOut('slow');
				}, 3000);
				return false;
         }

  if (bannerimage =='') {
		$("#bannerimage_error").show();
		$("#badMessage").hide();
		$('#bannerimage_error').text(yii.t('app','Upload web banner image'));
		setTimeout(function () {
			$('#bannerimage_error').fadeOut('slow');
		}, 3000);
		return false;
    }
	var isValid = allowedExtensions.test(imageInput.value);
    var isValidapp = allowedExtensions.test(appimageInput.value);

    if(!isValid && !isValidapp)
    {
    	$("#bannerimage_error").show();
		$("#badMessage").hide();
		$('#bannerimage_error').text(yii.t('app','Upload only image file'));
		setTimeout(function () {
			$('#bannerimage_error').fadeOut('slow');
		}, 3000);
			$("#appbannerimage_error").show();
		$("#badMessage").hide();
		$('#appbannerimage_error').text(yii.t('app','Upload only image file'));
		setTimeout(function () {
			$('#appbannerimage_error').fadeOut('slow');
		}, 3000);
		return false;
    }
	if (!isValid) {
		$("#bannerimage_error").show();
		$("#badMessage").hide();
		$('#bannerimage_error').text(yii.t('app','Upload only image file'));
		setTimeout(function () {
			$('#bannerimage_error').fadeOut('slow');
		}, 3000);
		return false;
    }




	if (!isValidapp) {
		$("#appbannerimage_error").show();
		$("#badMessage").hide();
		$('#appbannerimage_error').text(yii.t('app','Upload only image file'));
		setTimeout(function () {
			$('#appbannerimage_error').fadeOut('slow');
		}, 3000);
		return false;
    }
	if (bannerimage !='') {
		var reader = new FileReader();
		reader.readAsDataURL(imageInput.files[0]);
            reader.onload = function (e) {
              var image = new Image();
               image.src = e.target.result;
                 image.onload = function () {
                    var height = this.height;
                    var width = this.width;
				   if(width!=1920 && height!="400")
				   {
					$("#bannerimage_error").show();
	            	$("#badMessage").hide();
	            	$('#bannerimage_error').text(yii.t('app','Upload image with specified width & heigth'));
		            setTimeout(function () {
		        	$('#bannerimage_error').fadeOut('slow');
	            	}, 3000);
		            return false; 
				   }
                 } 
	}
	
	}

	//app banner image validation
    if (appbannerimage =='') {
		$("#appbannerimage_error").show();
		$("#badMessage").hide();
		$('#appbannerimage_error').text(yii.t('app','Upload app banner image'));
		setTimeout(function () {
			$('#appbannerimage_error').fadeOut('slow');
		}, 3000);
		return false;
	}




	if (appbannerimage !='') {
		var readerapp = new FileReader();
		readerapp.readAsDataURL(appimageInput.files[0]);
		readerapp.onload = function (e) {
              var image = new Image();
               image.src = e.target.result;
                 image.onload = function () {
                    var height = this.height;
                    var width = this.width;
				   if(width!=1024 && height!="500")
				   {
					$("#appbannerimage_error").show();
	            	$("#badMessage").hide();
	            	$('#appbannerimage_error').text(yii.t('app','Upload image with specified width & heigth'));
		            setTimeout(function () {
		        	$('#appbannerimage_error').fadeOut('slow');
	            	}, 3000);
		            return false; 
				   }
                   } 
	}
	}
   
    if (bannerurl =='') {
		$("#bannerurl_error").show();
		$("#badMessage").hide();
		$('#bannerurl_error').text(yii.t('app','Banner link cannot be blank'));
		setTimeout(function () {
			$('#bannerurl_error').fadeOut('slow');
		}, 3000);
		return false;
	}
	
    if(!regex.test(bannerurl)) {
        $("#bannerurl_error").show();
		$("#badMessage").hide();
		$('#bannerurl_error').text(yii.t('app','Banner link is not valid'));
		setTimeout(function () {
			$('#bannerurl_error').fadeOut('slow');
		}, 3000);
		return false;
	}
	if (appbannerurl =='') {
		$("#appbannerurl_error").show();
		$("#badMessage").hide();
		$('#appbannerurl_error').text(yii.t('app','App banner link cannot be blank'));
		setTimeout(function () {
			$('#appbannerurl_error').fadeOut('slow');
		}, 3000);
		return false;
	}
	
	if(!regex.test(appbannerurl)) {
        $("#appbannerurl_error").show();
		$("#badMessage").hide();
		$('#appbannerurl_error').text(yii.t('app','App banner link is not valid'));
		setTimeout(function () {
			$('#appbannerurl_error').fadeOut('slow');
		}, 3000);
		return false;
    }
    if (startDate =='') {
		$("#startDate_error").show();
		$("#badMessage").hide();
		$('#startDate_error').text(yii.t('app','Start date cannot be blank'));
		setTimeout(function () {
			$('#startDate_error').fadeOut('slow');
		}, 3000);
		return false;
    }
    
    if (endDate =='') {
		$("#endDate_error").show();
		$("#badMessage").hide();
		$('#endDate_error').text(yii.t('app','End date cannot be blank'));
		setTimeout(function () {
			$('#endDate_error').fadeOut('slow');
		}, 3000);
		return false;
    }

    if (startDate > endDate){
        $("#endDate_error").show();
		$("#badMessage").hide();
		$('#endDate_error').text(yii.t('app','End Date must be greater than start date'));
		setTimeout(function () {
			$('#endDate_error').fadeOut('slow');
		}, 3000);
		return false;
   }
  
  if(dateValidation==0) {
  if(startDate !='' && endDate !='' )
   {   
    
	var getDateArray = function(start, end) {
		var arr = new Array();
		var dt = new Date(start);
		while (dt <= end) {
			arr.push(new Date(dt));
			dt.setDate(dt.getDate() + 1);
		}
		return arr;
	}
	
	var startDate = new Date(startDate); //YYYY-MM-DD
    var endDate = new Date(endDate);
	var dateArr = getDateArray(startDate, endDate);

	$.ajax({
		url: baseUrl+'/site/getdate',
		type: "post",
		dataType: "json",
		data: {'dataArr':dateArr },
		success: function (responce) {
		
       		
			if(responce==0) {
				
				$('#dateValidation').val(1); 
				// $('#banner-form').submit();
				

			} else
			{	
				$("#devicesuccess").show();
				$("#devicesuccess").html(yii.t('app','The following dates are unavailable')+ '<br />' +responce.join(' '));
				return false;	   
				//return false;
			}
			// setTimeout(function () {
			// 	$("#devicesuccess").fadeOut();
			// }, 3000);
			
		}

	});

	//if(fail==1)
		return false;
   }
}
/*if(PayValidation==0) {
   if(startDate !='' && endDate !='' )
   {   
	   	//var date1, date2;
         //date1 = new Date(startDate);
		 //date2 = new Date(endDate);
	     //var res = Math.abs(date1 - date2) / 1000;
         //var days = Math.floor(res / 86400)+1;
		// var totalCost = parseInt(days) * parseInt(perdayCost);
	    //$("#total_Cost").show();
		//$("#devicesuccess").hide();
		//$('#total_Cost').text(yii.t('app','Your ads will run for')+ ' ' + days + ' ' + yii.t('app','days. You will spend no more than')+' ' + currencycode + totalCost );
			$('#PayValidation').val(1); 
		// setTimeout(function () {
		// 	$('#endDate_error').fadeOut('slow');
		// }, 3000);
		//return false;
   }
}*/

	var type = document.getElementsByName('bannerpayment');
	var type_value = $('#paymenttype').text()
	// for(var i = 0; i < type.length; i++){
	//     if(type[i].checked){
	//         type_value = type[i].value;
	//     }
	// }

		if(type_value == "stripe")
	{
		 // $('#stripepaymentbtn').attr('disabled', 'disabled');
		var stripebtn = $('#stripepaymentbtn').attr('disabled');
		if(stripebtn != 'disabled'){

		var stripekey = $('#stripekey').val();//alert(stripekey);
		
		var currency = $('#currencycode').val();//alert(stripekey);
		var totalprice = $('#totalprice').val();

		 var token = function(res){
		 var $input = $('<input type=hidden name=stripeToken class=stripee />').val(res.id);
		
		 $('#stripepaymentbtn').attr('disabled', 'disabled');
		
		 $('#banner-form').append($input).submit();

		 };
	
		 StripeCheckout.open({
			 key:         stripekey,
			 address:     false,
			
			 amount:      totalprice,
			 currency:    currency,
			 name:        'Joysale',
			 panelLabel:  'Checkout',
			 token:       token,
	
			 closed : function() {
			 		var stripeee = $('.stripee').val();
			 	
					if(typeof(stripeee) === "undefined"){
					
                   
					$("#stripepaymentbtn").removeAttr('disabled');

					}else{
					
						
			            $('#stripepaymentbtn').attr('disabled', 'disabled');
			           
                     }
                   
				
			 	}
		 });
		return false;
		}

		
		
	}

		
}


$("#startdate").keypress(function(event) {event.preventDefault();});
$("#enddate").keypress(function(event) {event.preventDefault();});
 $('#enddate').bind("paste",function(e) {
          e.preventDefault();
      });
  $('#startdate').bind("paste",function(e) {
          e.preventDefault();
      });


function amountcalc()
{

		var startDate = $("#startdate").val();
		var endDate = $("#enddate").val();

		  date1 = new Date(startDate);
		 date2 = new Date(endDate);



        
	       	if (date1.getFullYear() == ""
			    || date1.getMonth()+1 == ""
			    || date1.getDate() == "") {
			   $("#startDate_error").show();
					$("#badMessage").hide();
					$('#startDate_error').text(yii.t('app','Please provide valid start date'));
					setTimeout(function () {
						$('#startDate_error').fadeOut('slow');
					}, 3000);
					return false;
	 		 }

	       	if (date2.getFullYear() == ""
			    || date2.getMonth()+1 == ""
			    || date2.getDate() == "") {

	   				$("#endDate_error").show();
					$("#badMessage").hide();
					$('#endDate_error').text(yii.t('app','Please provide valid end date'));
					setTimeout(function () {
						$('#endDate_error').fadeOut('slow');
					}, 3000);
					return false;
	  		}
      
	     var res = Math.abs(date1 - date2) / 1000;
         var days = Math.floor(res / 86400)+1;
         if(isNaN(days))
         {
         		$("#startDate_error").show();
				$("#badMessage").hide();
				$('#startDate_error').text(yii.t('app','Please provide valid dates'));
				setTimeout(function () {
					$('#startDate_error').fadeOut('slow');
				}, 3000);
				return false;
         }
			
			  if (startDate =="") 
	{
		
		$("#startDate_error").show();
		$("#badMessage").hide();
		$("#total_Cost").hide();
		$('#startDate_error').text(yii.t('app','Start date cannot be blank'));
		setTimeout(function () {
			$('#startDate_error').fadeOut('slow');
		}, 3000);
		return false;
	}
	else{
	
    if (startDate > endDate){
        $("#endDate_error").show();
		$("#badMessage").hide();
		$("#total_Cost").hide();
		$('#endDate_error').text(yii.t('app','End Date must be greater than start date'));
		setTimeout(function () {
			$('#endDate_error').fadeOut('slow');
		}, 3000);
		return false;
   }
   else{
		var perdayCost =$("#perdaycost").val(); 
	    var currencycode =$("#currencycode").val(); 
	    var currencyposition =$("#currencyposition").val();
	    alert(currencycode + " " + currencyposition);
		var date1, date2;
         date1 = new Date(startDate);
		 date2 = new Date(endDate);
	     var res = Math.abs(date1 - date2) / 1000;
         var days = Math.floor(res / 86400)+1;
       
		 var totalCost = parseInt(days) * parseFloat(perdayCost);
	    $("#total_Cost").show();
	    $("#totalprice").val(totalCost * 100);
		$("#devicesuccess").hide();
		if(currencyposition == "prefix")
					{
						$('#total_Cost').text(yii.t('app','Your ads will run for')+ ' ' + days + ' ' + yii.t('app','days. You have to spend')+' ' + currencycode + ' ' + totalCost );
					}else{
						$('#total_Cost').text(yii.t('app','Your ads will run for')+ ' ' + days + ' ' + yii.t('app','days. You have to spend')+' ' + totalCost + ' ' + currencycode );
					}
		//	$('#PayValidation').val(1); 
		// setTimeout(function () {
		// 	$('#endDate_error').fadeOut('slow');
		// }, 3000);
	//	return false;
}
}
};




function myFunction() {
	var productCategory = document.getElementById("Products_category").value;
	$.ajax({
           type: "POST",
           url: baseUrl+'/products/getfilter',
           data: {'subcat':productCategory }, // serializes the form's elements.
           success: function(data)
           {	
           		if(data==0)
				{
					$("#showField").html("");
				}
				else
				{
					$("#showField").html(data);
				}
				return false;
           }
    });
}



function getval(selval){

	var parentattributevalue = selval.value;
	var parentids = selval.id;
	var parentid = parentids.split('_');
	//alert(baseUrl);

	$.ajax({
           type: "POST",
           url: baseUrl+'/products/getchildlevel/'+parentattributevalue,
           data: {'id':parentattributevalue,'filter_id':parentid[1] }, // serializes the form's elements.
           success: function(data)
           {
           		if(data==0)
				{
					$("#childField").html("");
				}
				else
				{
					$('div#'+parentids).html(data);
				}
				return false;
           }
    });
}

/*
* Get taginput field values.
*/

function tagclose(e1){
	var searchType = $(e1).attr("id");
	var searchTypeSelector = "." + searchType;
	var hiddenfieldSelector = searchTypeSelector + "-filter";
	$("#"+searchType).prop("checked", false);

	if ($(searchTypeSelector).is(':checked'))
		$(hiddenfieldSelector).val('1');  // checked
	else
		$(hiddenfieldSelector).val('0');

	getLocationData(1);
	$(e1).parent(".tagParent").remove();
}

function tagcloseproductcondn(e1){
	var searchType = $(e1).attr("id");
	var searchTypeSelector = "." + searchType;
	var hiddenfieldSelector = searchTypeSelector + "-filter";
	$("#"+searchType).prop("checked", false);


	productcond.splice( $.inArray(searchType, productcond), 1 );

	if ($(searchTypeSelector).is(':checked'))
		$(hiddenfieldSelector).val('1');  // checked
	else
		$(hiddenfieldSelector).val('0');

	getLocationData(1);
	$(e1).parent(".tagParent").remove();

}


function tagclosepostwithinsearch(e1){
	var searchType = $(e1).attr("id");
	var searchTypeSelector = "." + searchType;
	//var hiddenfieldSelector = searchTypeSelector + "-filter";
	$("#"+searchType).prop("checked", false);


	productcond.splice( $.inArray(searchType, productcond), 1 );

	if ($(searchTypeSelector).is(':checked'))
		$(searchTypeSelector).val('1');  // checked
	else
		$(searchTypeSelector).val('0');

	getLocationData(1);
	$(e1).parent(".tagParent").remove();

}

$("span#tagCloser").click(function(){
	

    $(this).parent(".tagParent").hide();
	if($('.filterTags .tagParent:visible').length == 0) $('.filterTags').hide();
});

$('input[name="postedwithin[]"]').on('change', function() {
   $('input[name="postedwithin[]"]').not(this).prop('checked', false);
}); 


function tagclosefiltersearch(e1){
	
	var searchType = $(e1).attr("id");
	var searchTypeSelector = "." + searchType;
	$("#"+searchType).prop("checked", false);

	var favorite = [];
	var favorite1 = [];
	var favorite2 = [];
	$.each($("input[name='dropdown[]']:checked"), function(){            
                favorite.push($(this).val());
    });
    var dropdownvalues = favorite.join(",");


    $.each($("input[name='multilevel[]']:checked"), function(){            
                favorite1.push($(this).val());
    });
     var multilevelvalues = favorite1.join(",");

    $.each($("input[name='sliderhiddenattribute[]']"), function(){            
			favorite2.push($(this).val());
    });
    var sliderhiddenattribute = favorite2.join(",");


	$('#dropdownvalues').val(dropdownvalues);
	$('#multilevelvalues').val(multilevelvalues);
	$('#rangevalues').val(sliderhiddenattribute);
		
	getLocationData(1);
	$(e1).parent(".tagParent").remove();

}


