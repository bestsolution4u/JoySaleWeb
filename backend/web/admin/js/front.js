if (!(globalSize)) {
	var globalSize = new Array();
}
var ajaxcart = 1;
var contactAjax = 1,
	checkoutAjax = 1;
reviewAjax = 1;
var productImage = 0,
	addImage = 0,
	generateCouponAjax = 1,
	addImageError = 0;
var mapClick = 1;
var offercheck = 1;
var followval = 1;
var unfollowval = 1;
var mobile_mapClick = 1;
var rating = 0,
	urgent = 0,
	ads = 0;
PriceValue = "0;4999"; //SliderPrice 
var specials = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
var alpha = /[a-zA-Z]/gi;
var numric = /[0-9]/gi;
var mailcheck = 1;
var locationTracker = 1;
var likeAjax = 1,
	dislikeAjax = 1;
var messageUserScrollPosition = 0;
var mob_check = 1;
var removeimg = [];
//baseurl is given in main page
$("#offer-form").hide();

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

$('#users-name').keypress(function (e) {
      
var regex = new RegExp("^[a-zA-Zء-ي ]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}
e.preventDefault();
return false;
});

  if($('#show_userpassword').css('display') == 'none')
{
 
  $('.field-show_userpassword .help-block').css("display", "none");
}
  function showuserpassword() {
  if ($('.show-button').hasClass('fa-eye')) {
    $('.show-button').removeClass('fa-eye');
    $('.show-button').addClass('fa-eye-slash');
    $('#Users_password').hide();
  	$('.field-Users_password .help-block').css("display", "none");
  	$('#show_userpassword').show();
  	$('.field-show_userpassword .help-block').css("display", "block");
    
  } else {
    $('.show-button').removeClass('fa-eye-slash');
    $('.show-button').addClass('fa-eye');
    $('#Users_password').show();
   	$('.field-Users_password .help-block').css("display", "block");
    $('#show_userpassword').hide();
   $('.field-show_userpassword .help-block').css("display", "none");
  }
  return false;
}



$(document).on('mouseover', '.action-star', function () {
	var onStar = $(this).data('star');
	var starSelector = ".star" + onStar;
	$('.action-star').removeClass('g-color');
	$('.action-star').addClass('gray');
	$(starSelector).removeClass('gray');
	$(starSelector).addClass('g-color');
});

$(document).on('click', '#adminpushnot', function () {
	adminData = $('#contact-textarea').val();
	if (adminData == '') {

		$('.adminpushnot-error').html(yii.t('app', 'Please enter text'));
		setTimeout(function () {
			$('.adminpushnot-error').fadeOut('slow');
		}, 5000);
		return;

	}
	//alert(adminData);
	if (adminData != '') {
		$.ajax({
			type: 'GET',
			url: baseUrl + '/admin/sendpushnot/',
			data: {
				'adminData': adminData
			},
			dataType: 'text',
			beforeSend: function () {
				$('#adminpushnot').html("Sending..");
				//$('#adminpushnot').attr('id','adminpushnots');
			},
			success: function (data) {
				$('textarea#contact-textarea').val("");
				$('#adminpushnots').attr('id', 'adminpushnot');
				$('#adminpushnot').html('Sent');
				
				setTimeout(function () {
					$('#adminpushnot').html('app', 'Send');
					
				}, 2000);



				if (data == "error") {
					$(".adminpushnot-error").html( 'Message not sent..!!' );
					$('textarea#contact-textarea').val("");
					setTimeout(function () {
						$('.adminpushnot-error').fadeOut('slow');
					}, 5000);
					return;
				}
				window.location.reload();
			},
			error: function () {
				$('#adminpushnot').html('Sent');
				$('textarea#contact-textarea').val("");
				window.location.reload();
			}
		});
	}

});

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



function generateReviewStarsCode(stars) {
	var starsCode = '';
	starsCode += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding review-stars-container">' +
		'<div class="write-review-1">';
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
// $(document).on('click', '.dropdown-toggle.profile', function () {
// 	if ($('.dropdown.profile-drop-li').hasClass('open')) {
// 		$('.dropdown.profile-drop-li').removeClass('open');
// 	} else {
// 		$('.dropdown.profile-drop-li').addClass('open');
// 	}
// });

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

$(document).on('keyup', '#Users_password', function () {

  $('#show_userpassword').val($('#Users_password').val());
});

$(document).on('keyup', '#show_userpassword', function () {
  $('#Users_password').val($('#show_userpassword').val());
});

$(document).on('keyup', '.option, .quantity, .price', function () {
	if ($('.option-add-btn').is(':disabled')) {
		$('.option-add-btn').removeAttr('disabled');
	}
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
		console.log("Left: " + currentLeftClick + " Totalimage: " +
			totalMoreImage);
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
		console.log("Right: " + currentRightClick + " Totalimage: " +
			totalMoreImage);
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
	'#Products_quantity, .quantity, .price',
	function () {
		var $th = $(this);
		$th.val($th.val().replace(/[^0-9]/g, function (str) {
			return '';
		}));
	});
$(document).on('mouseup', '#popup_container', function (e) {
	var container = $(".popup");

	if (!container.is(e.target) // if the target of the click isn't the
		// container...
		&&
		container.has(e.target).length === 0) // ... nor a descendant
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


// function to set the height on fly
function autoHeight() {
	$('#content').css('min-height', 0);
	$('#content').css('min-height', (
		$(document).height() -
		$('.joysale-menu').height() -
		$('.footer').height()
	));
}

// onDocumentReady function bind
$(document).ready(function () {

	//$("input[name=ReportitemSearch[price]]").attr('type','number');

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


function paymentMethod() {
	$('.revieworder-head ul li').removeClass('active');
	$('.paymentdetails-li').addClass('active');
	$('.revieworder-details').hide();
	$('.payment-details').fadeIn();
}

function closeConfirm() {
	$('#confirm_popup_container').modal('hide');
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
			output += '<li><input type="text" value="" name="Products[shipping][' + shipdetails[0] +
				']" style="margin-left: 3px;" class="form-control ship-to-' + shipdetails[0] +
				'" onkeypress="return isNumber(event)" maxlength="9"/></li>';
			output += '<li><p onclick="delectShipping(' + shipdetails[0] +
				')"><i class="fa fa-trash-o"></i></p></li>';
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


function deleteOption(size) {
	var deleteIndex = globalSize.indexOf(size);
	if (deleteIndex != -1) {
		globalSize.splice(deleteIndex, 1);
		var sizeClass = size.replace(/\s/g, "-");
		$('.option-' + sizeClass).remove();
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

function validsigninfrm() {
	var email = $('#LoginForm_username').val();
	var password = $('#LoginForm_password').val();
	if (email == '') {
		$("#LoginForm_username_em_").show();
		$('#LoginForm_username_em_')
			.text(yii.t('app', 'Email cannot be blank'));
		$('#LoginForm_username').focus();
		$('#LoginForm_username').keydown(function () {
			$('#LoginForm_username_em_').hide();
		});
		"<?= $phpVar ?>";
		return false;
	}
	if (!(isValidEmailAddress(email))) {
		$("#LoginForm_username_em_").show();
		$('#LoginForm_username_em_').text(
			yii.t('app', 'Please Enter a valid Email'));
		$('#LoginForm_username').focus();
		return false;
	}
	if (password == '') {
		$("#LoginForm_password_em_").show();
		$("#LoginForm_username_em_").hide();
		$('#LoginForm_password_em_').text(
			yii.t('app', 'Password cannot be blank'));
		$('#LoginForm_password').focus();
		return false;
	}
}

function isValidEmailAddress(email) {
	var emailreg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	return emailreg.test(email);
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
		$("#Products_sub_subcategory_em_").show();
		$('#Products_sub_subcategory_em_').text(yii.t('app','Product child category cannot be blank'));
		$('#Products_sub_subCategory').change(function () {
			$('#Products_sub_subcategory_em_').hide();
		});
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
		$('#Products_description_em_').text(yii.t('app','Product Description cannot be blank'));
		$('#Products_description').focus();
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
		} else if (!pattern.test(price)) {
			$("#Products_price_em_").show();
			$("#badMessage").hide();
			$('#Products_price_em_').text(yii.t('app','Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
			$('#Products_price').focus();
			$('#Products_price').keydown(function () {
				$('#Products_price_em_').hide();
			});
			return false;
		}
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

function updatePromotion(promotionId) {
	$('#promotion-addtype').val(promotionId);
	$('#ADPromotionid').val(promotionId);
}

function showListingPromotion(productId) {
	$('.promotion-product-id').val(productId);
	$('#UPromotionProductid').val(productId);
	$('#ADPromotionProductid').val(productId);
	$('#post-your-list').modal('show');
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
	var ret = ((keyCode >= 48 && keyCode <= 57) ||
		(keyCode >= 65 && keyCode <= 90) || (keyCode == 32) ||
		(keyCode >= 97 && keyCode <= 122) || (specialKeys
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
	var ret = ((keyCode >= 48 && keyCode <= 57) ||
		(keyCode >= 65 && keyCode <= 90) || (keyCode != 32) ||
		(keyCode >= 97 && keyCode <= 122) || (specialKeys
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
	var ret = ((keyCode >= 65 && keyCode <= 90) || (keyCode == 32) ||
		(keyCode >= 97 && keyCode <= 122) || (specialKeys
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

function validateProfile() {
	var name = $("#Admins_name").val().trim();
	var password = $("#Admins_password").val();
	var email = $("#Admins_email").val();
	if (name == "") {
		$("#Admins_name_em_").show();
		$("#Admins_name_em_").html(yii.t('app', 'Name cannot be blank'));
		$('#Admins_name').val('');
		$('#Admins_name').focus();
		$('#Admins_name').keydown(function () {
			$('#Admins_name_em_').hide();
		});
		return false;
	} else {
		name = name.replace(/\s{2,}/g, ' ');
		$('#Admins_name').val(name);
		if (specials.test(name)) {
			$("#Admins_name_em_").show();
			$("#Admins_name_em_").html(yii.t('app', 'Special Characters not allowed.'));
			$('#Admins_name').val('');
			$('#Admins_name').focus();
			return false;
		} else {
			$('#Admins_name_em_').hide();
		}

		var reg = /[0-9]/gi;
		if (reg.test(name)) {
			$("#Admins_name_em_").show();
			$("#Admins_name_em_").html(yii.t('app', 'Numbers not allowed.'));
			$('#Admins_name').val('');
			$('#Admins_name').focus();
			return false;
		} else {
			$('#Admins_name_em_').hide();
		}
	}
	if (email == "") {
		$("#Admins_email_em_").show();
		$("#Admins_email_em_").html(yii.t('app', "Email cannot be blank"));
		$('#Admins_email').focus();
		$('#Admins_email').keydown(function () {
			$('#Admins_email_em_').hide();
		});
		return false;
	}
	if (!isValidEmailAddress(email)) {
		$('#Admins_email_em_').show();
		$('#Admins_email_em_').text(yii.t('app', 'Please Enter a valid Email'));
		return false;
	}

	if (password == "") {
		$("#Admins_password_em_").show();
		$("#Admins_password_em_").html(yii.t('app', "Password cannot be blank"));
		$('#Admins_password').focus();
		$('#Admins_password').keydown(function () {
			$('#Admins_password_em_').hide();
		});
		return false;
	}
	if (password.length < 6) {
		$("#Admins_password_em_").show();
		$("#Admins_password_em_").html(yii.t('app', "Password must be greater than 5 characters long"));
		$('#Admins_password').focus();
		$('#Admins_password').keydown(function () {
			$('#Admins_password_em_').hide();
		});
		return false;
	} else {
		$('#Admins_password_em_').hide();
	}

}


function validateCurrency() {
	var name = $("#Currencies_currency_name").val();
	var shortcode = $("#Currencies_currency_shortcode").val();
	var symbol = $("#Currencies_currency_symbol").val();

	if (name == "") {
		$("#Currencies_currency_name_em_").show();
		$("#Currencies_currency_name_em_").html(yii.t('app', "Name cannot be blank"));
		$('#Currencies_currency_name').focus();
		$('#Currencies_currency_name').keydown(function () {
			$('#Currencies_currency_name_em_').hide();
		})
		return false;
	}
	if (shortcode == "") {
		$("#Currencies_currency_shortcode_em_").show();
		$("#Currencies_currency_shortcode_em_").html(
			yii.t('admin', "Shortcode cannot be blank"));
		$('#Currencies_currency_shortcode').focus()
		$('#Currencies_currency_shortcode').keydown(function () {
			$('#Currencies_currency_shortcode_em_').hide();
		})
		return false;
	}
	if (symbol == "") {
		$("#Currencies_currency_symbol_em_").show();
		$("#Currencies_currency_symbol_em_").html(
			yii.t('admin', "Symbol cannot be blank"));
		$('#Currencies_currency_symbol').focus()
		$('#Currencies_currency_symbol').keydown(function () {
			$('#Currencies_currency_symbol_em_').hide();
		})
		return false;
	}
}

function validateCommission() {
	var com = $("#commission").val();
	var min = $("#minrange").val().trim();
	var max = $("#maxrange").val().trim();


	var com1 = com.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/a-zA-Z]/gi, "");
	var min1 = min.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/a-zA-Z]/gi, "");
	var max1 = max.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/a-zA-Z]/gi, "");



	if (com == "") {
		$("#commission-error").show();
		$("#commission-error").html(yii.t('app', "Commission Amount cannot be blank"));
		$('#commission').focus();
		$('#commission').keydown(function () {
			$('#commission-error').hide();
		});
		return false;
	} else {
		if (com1 != com) {
			$("#commission-error").show();
			$('#commission-error').html(yii.t('app', 'Only numeric values allowed.'));
			setTimeout(function () {
				$("#commission").val('');
				$("#commission").focus();
				$("#commission-error").fadeOut();
			}, 1500);
			return false;
		} else {
			$('#commission-error').hide();
		}
	}

	com = com.replace(/\s/g, "");
	min = min.replace(/\s/g, "");
	max = max.replace(/\s/g, "");
	$('#commission').val(com);
	$('#minrange').val(min);
	$("#maxrange").val(max);

	if (com < 1 || com > 100) {
		$("#commission-error").show();
		$("#commission-error").html(yii.t('app', "Commission percentage should be between 1% to 100%."));
		$('#commission').focus();
		$('#commission').keydown(function () {
			$('#commission-error').hide();
		});
		return false;
	} else {
		$('#commission-error').hide();
	}
	if (min == "") {
		$("#minError").show();
		$("#minError").html(yii.t('app', "Minimum Range cannot be blank"));
		$('#minrange').focus();
		$('#minrange').keydown(function () {
			$('#minError').hide();
		});
		return false;
	} else if (min <= 0) {
		$("#minError").show();
		$("#minError").html(yii.t('app', "Minimum Range should be greater than zero"));
		$('#minrange').focus();
		$('#minrange').keydown(function () {
			$('#minError').hide();
		});
		return false;
	} else {
		if (min1 != min) {
			$("#minError").show();
			$('#minError').html(yii.t('app', 'Only numeric values allowed.'));
			setTimeout(function () {
				$("#minrange").val('');
				$("#minrange").focus();
				$("#minError").fadeOut();
			}, 1500);
			return false;
		} else {
			$('#minError').hide();
		}
	}
	if (max == "") {
		$("#maxError").show();
		$("#maxError").html(yii.t('app', "Maximum Range cannot be blank"));
		$('#maxrange').focus();
		$('#maxrange').keydown(function () {
			$('#maxError').hide();
		});
		return false;
	} else {
		if (max1 != max) {
			$("#maxError").show();
			$('#maxError').html(yii.t('app', 'Only numeric values allowed.'));
			setTimeout(function () {
				$("#maxrange").val('');
				$("#maxrange").focus();
				$("#maxError").fadeOut();
			}, 1500);
			return false;
		} else {
			$('#maxError').hide();
		}
	}

	if (Number(min) >= Number(max)) {
		$("#maxError").show();
		$("#maxError").html(yii.t('app', "Maximum Range should be greater than minimum value."));
		$('#maxrange').focus();
		$('#maxrange').keydown(function () {
			$('#maxError').hide();
		});
		return false;
	}
	return true;
}

function dropCategory() {
	//alert(0);
	$("#catImage").show();
	$("#subcat").show();
	if ($("#dropCat").val() != "") {
		$("#catImage").hide();
		$("#subcat").show();
		$("#itemCondition").hide();
		$("#exchangetoBuy").hide();
		$("#myOffer").hide();
		$("#contactSeller").hide();
		$("#buyNow").hide();
		$("#subcategoryVisible").show();
		document.getElementById('categories-subcategoryvisible').setAttribute('checked', 'checked');

	} else {
		$("#subcat").hide();
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
	moredesc = $("#moremoredesc").val();
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

function genpass(type) {
	var charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	var charshuffle = shuffle(charset);
	var password = charshuffle.substring(0, 8);
	if (type == "update") {
		if (confirm(yii.t('admin', "Are you sure, you want to change password?"))) {
			$('#Users_password').val(password);
		}
	} else {
		$('#Users_password').val(password);

		$('#show_userpassword').val(password);
	}
}

function generateapipassword() {
	//alert(0);
	var charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	var charshuffle = shuffle(charset);
	var password = charshuffle.substring(0, 8);
	//alert(password);
	//if (confirm('Are you sure, you want to change password?')) {
		$('#Sitesettings_apiPassword').val(password);
		$('#show_apipassword').val(password);
	//}
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
					yii.t('app', 'Discount') +
					': ( - ) <span class="amnt product-item-coupondiscount">' +
					Number(selectedQty) * couponValue + " " +
					currency + '</span>');
		}
	} else {
		discount = (Number(total) * (Number(couponValue) / 100));
		if (discount > 0) {
			if ($(".coupon-max-hidden").val() != "" &&
				$(".coupon-max-hidden").val() < discount) {
				discount = $(".coupon-max-hidden").val();
			}
			total = Number(total) - Number(discount);
			$(".product-coupon-discount").val(discount);
			$(".coupon-discount")
				.html(
					yii.t('app', 'Discount') +
					': ( - ) <span class="amnt product-item-coupondiscount">' +
					discount + " " + currency + '</span>');
		}
	}
	var grandTotal = Number(total) + Number(shipping);

	$(".product-sub-total").html(
		yii.t('app', 'Subtotal') +
		':<span class="amnt product-item-total"> ' + oldTotal +
		" " + currency + ' </span>');
	$('.product-sub-total-hidden').val(oldTotal);
	$(".sub-total-hidden").val(total);
	$(".product-grand-total").html(
		yii.t('app', 'Order Total') +
		': <span class="amnt product-item-grandtotal"> ' +
		grandTotal + " " + currency + ' </span>');
}


function showinvoicepopup(invoiceId) {
	
	//alert(invoiceId);
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
		url: baseUrl + '/invoices/getinvoicedata/',
		data: {
			invoiceId: invoiceId
		},
		success: function (data) {
				$("#invoice_content").html(data);
		//	$('#show-exchange-popup').html(data);
		}
	});
}

function shippingConfirmValidation() {
	if ($("#subject").val() == "") {
		$(".empty-error-sub").html(yii.t('app', "Subject Cannot be Empty"));
		return false;
	} else if ($("#mebbage").val() == "") {
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

function mapView() {
	var location = $(".product-location-name").val();
	var latitude = $(".product-location-lat").val();
	var longitude = $(".product-location-long").val();
	$('#map_canvas').delay('700').fadeIn();
	$('#mobile_map_canvas').delay('700').fadeIn();
	if (mapClick == 1) {
		// setTimeout(function () {
		// 	showMap(location, latitude, longitude);
		// 	mobile_showMap(location, latitude, longitude);
		// }, 1000);
		mapClick = 0;
	}
}



function showTop() {
	var priority1 = $("#priority1").val();
	var priority2 = $("#priority2").val();
	var priority3 = $("#priority3").val();
	var priority4 = $("#priority4").val();
	var priority5 = $("#priority5").val();
	if (priority1 == "empty") {
		$("#priority1Error").html(
			yii.t('admin', 'Priority') + ' 1 ' +
			yii.t('admin', 'cannot be blank'));
		return false;
	} else {
		$("#priority1Error").hide();
	}
	if (priority5 != "empty" &&
		(priority5 == priority1 || priority5 == priority2 ||
			priority5 == priority3 || priority5 == priority4)) {
		$("#priority5Error").html(
			yii.t('admin', 'Priority') + ' 5 ' +
			yii.t('admin', 'should be unique'));
		$(this).val("");
		return false;
	} else {
		$("#priority5Error").hide();
	}
	if (priority4 == "empty" && (priority5 != "empty")) {
		$("#priority4Error").html(
			yii.t('admin', 'Priority') + ' 4 ' +
			yii.t('admin', 'cannot be blank'));
		return false;
	} else {
		if (priority4 != "empty" &&
			(priority4 == priority1 || priority4 == priority2 ||
				priority4 == priority3 || priority4 == priority5)) {
			$("#priority4Error").html(
				yii.t('admin', 'Priority') + ' 4 ' +
				yii.t('admin', 'should be unique'));
			$(this).val("");
			return false;
		} else {
			$("#priority4Error").hide();
		}
	}

	if (priority3 == "empty" && (priority4 != "empty" || priority5 != "empty")) {
		$("#priority3Error").html(
			yii.t('admin', 'Priority') + ' 3 ' +
			yii.t('admin', 'cannot be blank'));
		return false;
	} else {
		if (priority3 != "empty" &&
			(priority3 == priority1 || priority3 == priority2 ||
				priority3 == priority4 || priority3 == priority5)) {
			$("#priority3Error").html(
				yii.t('admin', 'Priority') + ' 3 ' +
				yii.t('admin', 'should be unique'));
			$(this).val("");
			return false;
		} else {
			$("#priority3Error").hide();
		}
	}

	if (priority2 == "empty" &&
		(priority3 != "empty" || priority4 != "empty" || priority5 != "empty")) {
		$("#priority2Error").html(
			yii.t('admin', 'Priority') + ' 2 ' +
			yii.t('admin', 'cannot be blank'));
		return false;
	} else {
		if (priority2 != "empty" &&
			(priority2 == priority1 || priority2 == priority3 ||
				priority2 == priority4 || priority2 == priority5)) {
			$("#priority2Error").html(
				yii.t('admin', 'Priority') + ' 2 ' +
				yii.t('admin', 'should be unique'));
			$(this).val("");
			return false;
		} else {
			$("#priority2Error").hide();
		}
	}
	if (priority1 == "empty" &&
		(priority2 != "empty" || priority3 != "empty" ||
			priority4 != "empty" || priority5 != "empty")) {
		$("#priority1Error").html(
			yii.t('admin', 'Priority') + ' 1 ' +
			yii.t('admin', 'cannot be blank'));
		return false;
	} else {
		$("#priority1Error").show();
		if (priority1 != "empty" &&
			(priority1 == priority2 || priority1 == priority3 ||
				priority1 == priority4 || priority1 == priority5)) {
			$("#priority1Error").html(
				yii.t('admin', 'Priority') + ' 1 ' +
				yii.t('admin', 'should be unique'));
			$(this).val("");
			return false;
		} else {
			$("#priority1Error").hide();
		}
	}

}




function showTopCat() {
	var priority1 = $("#catpriority1").val();
	var priority2 = $("#catpriority2").val();
	var priority3 = $("#catpriority3").val();
	var priority4 = $("#catpriority4").val();
	var priority5 = $("#catpriority5").val();
	var priority6 = $("#catpriority6").val();
	var priority7 = $("#catpriority7").val();
	var priority8 = $("#catpriority8").val();
	var priority9 = $("#catpriority9").val();
	var priority10 = $("#catpriority10").val();


	if (priority1 == "empty") {
		$("#priority1Error").html(
			yii.t('app', 'Priority') + ' 1 ' +
			yii.t('app', 'cannot be blank'));
		return false;
	} else {
		$("#priority1Error").hide();
	}

	if (priority10 != "empty" &&
		(priority10 == priority1 || priority10 == priority2 || priority10 == priority3 || priority10 == priority4 || priority10 == priority5 ||
			priority10 == priority6 || priority10 == priority7 || priority10 == priority8 || priority10 == priority9)) {
		$("#priority10Error").html(yii.t('app', 'Priority') + ' 10 ' +
			yii.t('app', 'should be unique'));
		$("#priority10Error").show();
		$(this).val("");
		return false;
	} else {
		$("#priority10Error").hide();
	}

	if (priority9 == "empty" && (priority10 != "empty")) {

		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority2 == "empty") {
			$("#priority2Error").html(
				yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority3 == "empty") {
			$("#priority3Error").html(
				yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority4 == "empty") {
			$("#priority4Error").html(
				yii.t('app', 'Priority') + ' 4 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority5 == "empty") {
			$("#priority5Error").html(
				yii.t('app', 'Priority') + ' 5 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority6 == "empty") {
			$("#priority6Error").html(
				yii.t('app', 'Priority') + ' 6 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority7 == "empty") {
			$("#priority7Error").html(
				yii.t('app', 'Priority') + ' 7 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority8 == "empty") {
			$("#priority8Error").html(
				yii.t('app', 'Priority') + ' 8 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority9 == "empty") {
			$("#priority9Error").html(
				yii.t('app', 'Priority') + ' 9 ' +
				yii.t('app', 'cannot be blank'));
		}
		return false;
	} else {
		if (priority9 != "empty" &&
			(priority9 == priority1 || priority9 == priority2 || priority9 == priority3 || priority9 == priority4 || priority9 == priority5 ||
				priority9 == priority6 || priority9 == priority7 || priority9 == priority8 || priority9 == priority10)) {
			$("#priority9Error").html(yii.t('app', 'Priority') + ' 9 ' +
				yii.t('app', 'should be unique'));
			$("#priority9Error").show();
			$(this).val("");
			return false;
		} else {
			$("#priority9Error").hide();
		}
	}

	if (priority8 == "empty" && (priority9 != "empty")) {

		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority2 == "empty") {
			$("#priority2Error").html(
				yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority3 == "empty") {
			$("#priority3Error").html(
				yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority4 == "empty") {
			$("#priority4Error").html(
				yii.t('app', 'Priority') + ' 4 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority5 == "empty") {
			$("#priority5Error").html(
				yii.t('app', 'Priority') + ' 5 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority6 == "empty") {
			$("#priority6Error").html(
				yii.t('app', 'Priority') + ' 6 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority7 == "empty") {
			$("#priority7Error").html(
				yii.t('app', 'Priority') + ' 7 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority8 == "empty") {
			$("#priority8Error").html(
				yii.t('app', 'Priority') + ' 8 ' +
				yii.t('app', 'cannot be blank'));
		}
		return false;
	} else {
		if (priority8 != "empty" &&
			(priority8 == priority1 || priority8 == priority2 || priority8 == priority3 || priority8 == priority4 || priority8 == priority5 ||
				priority8 == priority6 || priority8 == priority7 || priority8 == priority9 || priority8 == priority10)) {
			$("#priority8Error").html(yii.t('app', 'Priority') + ' 8 ' +
				yii.t('app', 'should be unique'));
			$(this).val("");
			$("#priority8Error").show();
			return false;
		} else {
			$("#priority8Error").hide();
		}
	}

	if (priority7 == "empty" && (priority8 != "empty")) {
		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority2 == "empty") {
			$("#priority2Error").html(
				yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority3 == "empty") {
			$("#priority3Error").html(
				yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority4 == "empty") {
			$("#priority4Error").html(
				yii.t('app', 'Priority') + ' 4 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority5 == "empty") {
			$("#priority5Error").html(
				yii.t('app', 'Priority') + ' 5 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority6 == "empty") {
			$("#priority6Error").html(
				yii.t('app', 'Priority') + ' 6 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority7 == "empty") {
			$("#priority7Error").html(
				yii.t('app', 'Priority') + ' 7 ' +
				yii.t('app', 'cannot be blank'));
		}

		return false;
	} else {
		if (priority7 != "empty" &&
			(priority7 == priority1 || priority7 == priority2 || priority7 == priority3 || priority7 == priority4 || priority7 == priority5 ||
				priority7 == priority6 || priority7 == priority8 || priority7 == priority9 || priority7 == priority10)) {
			$("#priority7Error").html(yii.t('app', 'Priority') + ' 7 ' +
				yii.t('app', 'should be unique'));
			$("#priority7Error").show();
			$(this).val("");
			return false;
		} else {
			$("#priority7Error").hide();
		}
	}

	if (priority6 == "empty" && (priority7 != "empty")) {

		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority2 == "empty") {
			$("#priority2Error").html(
				yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority3 == "empty") {
			$("#priority3Error").html(
				yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority4 == "empty") {
			$("#priority4Error").html(
				yii.t('app', 'Priority') + ' 4 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority5 == "empty") {
			$("#priority5Error").html(
				yii.t('app', 'Priority') + ' 5 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority6 == "empty") {
			$("#priority6Error").html(
				yii.t('app', 'Priority') + ' 6 ' +
				yii.t('app', 'cannot be blank'));
		}

		return false;
	} else {
		if (priority6 != "empty" &&
			(priority6 == priority1 || priority6 == priority2 || priority6 == priority3 || priority6 == priority4 || priority6 == priority5 ||
				priority6 == priority7 || priority6 == priority8 || priority6 == priority9 || priority6 == priority10)) {
			$("#priority6Error").html(yii.t('app', 'Priority') + ' 6 ' +
				yii.t('app', 'should be unique'));
			$("#priority6Error").show();

			$(this).val("");
			return false;
		} else {
			$("#priority6Error").hide();
		}
	}

	if (priority5 == "empty" && (priority6 != "empty")) {

		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority2 == "empty") {
			$("#priority2Error").html(
				yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority3 == "empty") {
			$("#priority3Error").html(
				yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority4 == "empty") {
			$("#priority4Error").html(
				yii.t('app', 'Priority') + ' 4 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority5 == "empty") {
			$("#priority5Error").html(
				yii.t('app', 'Priority') + ' 5 ' +
				yii.t('app', 'cannot be blank'));
		}

		return false;
	} else {
		if (priority5 != "empty" &&
			(priority5 == priority1 || priority5 == priority2 || priority5 == priority3 || priority5 == priority4 || priority5 == priority6 ||
				priority5 == priority7 || priority5 == priority8 || priority5 == priority9 || priority5 == priority10)) {
			$("#priority5Error").html(yii.t('app', 'Priority') + ' 5 ' +
				yii.t('app', 'should be unique'));
			$("#priority5Error").show();
			return false;
		} else {
			$("#priority5Error").hide();
		}
	}

	if (priority4 == "empty" && priority5 != "empty") {

		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority2 == "empty") {
			$("#priority2Error").html(
				yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority3 == "empty") {
			$("#priority3Error").html(
				yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority4 == "empty") {
			$("#priority4Error").html(
				yii.t('app', 'Priority') + ' 4 ' +
				yii.t('app', 'cannot be blank'));
		}


		return false;
	} else {
		if (priority4 != "empty" &&
			(priority4 == priority1 || priority4 == priority2 || priority4 == priority3 || priority4 == priority5 || priority4 == priority6 ||
				priority4 == priority7 || priority4 == priority8 || priority4 == priority9 || priority4 == priority10)) {
			$("#priority4Error").html(yii.t('app', 'Priority') + ' 4 ' +
				yii.t('app', 'should be unique'));
			$("#priority4Error").show();
			$(this).val("");
			return false;
		} else {
			$("#priority4Error").hide();
		}
	}
	if (priority3 == "empty" && (priority4 != "empty" || priority5 != "empty")) {
		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority2 == "empty") {
			$("#priority2Error").html(
				yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'cannot be blank'));
		}
		if (priority3 == "empty") {
			$("#priority3Error").html(
				yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'cannot be blank'));
		}


		return false;
	} else {
		if (priority3 != "empty" &&
			(priority3 == priority1 || priority3 == priority2 || priority3 == priority4 || priority3 == priority5 || priority3 == priority6 ||
				priority3 == priority7 || priority3 == priority8 || priority3 == priority9 || priority3 == priority10)) {
			$("#priority3Error").html(yii.t('app', 'Priority') + ' 3 ' +
				yii.t('app', 'should be unique'));
			$("#priority3Error").show();
			$(this).val("");
			return false;
		} else {
			$("#priority3Error").hide();
		}
	}

	if (priority2 == "empty" &&
		(priority3 != "empty" || priority4 != "empty" || priority5 != "empty")) {
		if (priority1 == "empty") {
			$("#priority1Error").html(
				yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'cannot be blank'));
		}

		$("#priority2Error").html(
			yii.t('app', 'Priority') + ' 2 ' +
			yii.t('app', 'cannot be blank'));



		return false;
	} else {
		if (priority2 != "empty" &&
			(priority2 == priority1 || priority2 == priority3 || priority2 == priority4 || priority2 == priority5 || priority2 == priority6 ||
				priority2 == priority7 || priority2 == priority8 || priority2 == priority9 || priority2 == priority10)) {
			$("#priority2Error").html(yii.t('app', 'Priority') + ' 2 ' +
				yii.t('app', 'should be unique'));
			$("#priority2Error").show();
			$(this).val("");
			return false;
		} else {
			$("#priority2Error").hide();
		}
	}
	if (priority1 == "empty" &&
		(priority2 != "empty" || priority3 != "empty" ||
			priority4 != "empty" || priority5 != "empty")) {

		$("#priority1Error").html(
			yii.t('app', 'Priority') + ' 1 ' +
			yii.t('app', 'cannot be blank'));

		return false;
	} else {
		$("#priority1Error").show();
		if (priority1 != "empty" &&
			(priority1 == priority2 || priority1 == priority3 || priority1 == priority4 || priority1 == priority5 || priority1 == priority6 ||
				priority1 == priority7 || priority1 == priority8 || priority1 == priority9 || priority1 == priority10)) {
			$("#priority1Error").html(yii.t('app', 'Priority') + ' 1 ' +
				yii.t('app', 'should be unique'));
			$("#priority1Error").show();
			$(this).val("");
			return false;
		} else {
			$("#priority1Error").hide();
		}
	}

}

function changeCategory(no, sel) {
	var nextNo = no + 1;
	var value = sel.value;
	if (value != 'empty')
		$("#catpriority" + nextNo).removeAttr('disabled');
	else
		for ($i = nextNo; $i <= 10; $i++) {
			$("#catpriority" + $i).prop('selectedIndex', 0);
			$("#catpriority" + $i).attr('disabled', 'disabled');
		}


}

function defaultSettings() {
	var name = $("#Sitesettings_sitename").val();
	var googleapikey = $("#Sitesettings_googleapikey").val();
	if (name == "") {
		$("#Sitesettings_sitename_em_").show();
		$("#Sitesettings_sitename_em_").html(
			yii.t('admin', "Site Name") + ' ' +
			yii.t('admin', "cannot be blank"));
		$("#Sitesettings_sitename").focus();
		return false;
	} else {
		$('#Sitesettings_sitename_em_').hide();
	}
	if (googleapikey == "") {
		$("#Sitesettings_googleapikey_em_").show();
		$("#Sitesettings_googleapikey_em_").html(
			yii.t('admin', "Google api key") + ' ' +
			yii.t('admin', "cannot be blank"));
		$("#Sitesettings_googleapikey").focus();
		return false;
	} else {
		$('#Sitesettings_googleapikey_em_').hide();
	}
	if (specials.test(name)) {
		$("#Sitesettings_sitename_em_").show();
		$('#Sitesettings_sitename_em_').text(
			yii.t('admin', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Sitesettings_sitename_em_').hide();
	}
}

function cancelOffer() {
	$(".offer-form").hide();
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

	if (((keycode >= 65) && (keycode <= 90)) ||
		(keycode == 32) ||
		((keycode >= 97) && (keycode <= 122)) ||
		(specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode)) {
		return true;
	} else {
		return false;
	}
}

function profileVal() {
	var name = $("#Users_name").val().trim();
	if (name == "") {
		$("#Users_name_em_").show();
		$("#Users_name_em_").html(yii.t('app', "Name cannot be blank"));
		$("#Users_name_em_").focus();
		return false;
	} else {
		name = name.replace(/\s{2,}/g, ' ');
		$('#Users_name').val(name);
		$('#Users_name_em_').hide();
	}
	if (name.length < 3) {
		$("#Users_name_em_").show();
		$("#Users_name_em_").html(yii.t('app', "Name should be minimum 3 characters"));
		$("#Users_name_em_").focus();
		return false;
	}
	if (specials.test(name)) {
		$("#Users_name_em_").show();
		$('#Users_name').val('');
		$('#Users_name_em_').text(
			yii.t('app', 'Special Characters not allowed.'));
		return false;
	} else {
		$('#Users_name_em_').hide();
	}

	var reg = /[0-9]/gi;
	if (reg.test(name)) {
		$("#Users_name_em_").show();
		$("#Users_name_em_").html(yii.t('app', 'Numbers not allowed.'));
		$('#Users_name').val('');
		$('#Users_name').focus();
		return false;
	} else {
		$('#Users_name_em_').hide();
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

function selectpromotion() {
	$(".promotion-error").hide();
	$(".promotion-success").hide();
	var currency = $('#selectedoption').val();
	if (currency == 0) {
		$(".promotion-error").html(
			//	yii.t('admin','Please select any one of the Currency')
			'Please select any one of the Currency'
		);
		$(".promotion-error").show();
		return false;
	}
	$.ajax({
		url: baseUrl + '/promotions/promotioncurrencies',
		type: "GET",
		data: {
			'currency': currency
		},
		beforeSend: function () {
			$('.promotionloader').show();
		},
		success: function (responce) {
			if (responce != '') {
				$('.promotionloader').hide();
				$('.promotion-success').html('Promotion Currency Updated');
				//yii.t('app','Promotion Currency Updated')
				$('.promotion-success').show();
			}


		}
	});

}

function selectbannercurrency() {

	$(".banner-error").hide();
	$(".banner-success").hide();
	var currency = $('#selectedoption').val();
	if (currency == 0) {
		$(".banner-error").html(
			//	yii.t('admin','Please select any one of the Currency')
			'Please select any one of the Currency'
		);
		$(".banner-error").show();
		return false;
	}
	$.ajax({
		url: baseUrl + '/banners/bannercurrency',
		type: "GET",
		data: {
			'currency': currency
		},
		beforeSend: function () {
			$('.bannerloader').show();
		},
		success: function (responce) {
			if (responce != '') {
				$('.bannerloader').hide();
				$('.banner-success').html('Banner Currency Updated');
				//yii.t('app','Promotion Currency Updated')
				$('.banner-success').show();
			}


		}
	});

}


function switchVisible_promotionback() {
	$('#promotion-details').css('display', 'none');
	$('#promotion-content').css('display', 'block');
	$('.promotions-content-cnt').remove();
}

function urgentpromotion() {
	var urgentprice = $('#urgentprice').val();
	if (urgentprice == "") {
		$("#urgentpriceError").show();
		$("#urgentpriceError").html(
			yii.t('admin', "Price Amount cannot be blank"));
		$('#urgentprice').focus();
		$('#urgentprice').keydown(function () {
			$('#urgentpriceError').hide();
		});
		return false;
	} else if (isNaN(urgentprice)) {
		$("#urgentpriceError").show();
		$("#urgentpriceError").html(
			yii.t('admin', "Price Amount should be numeric"));
		$('#urgentprice').focus();
		$('#urgentprice').keydown(function () {
			$('#urgentpriceError').hide();
		});
		return false;
	}

}


function select_shipping(shippingId) {
	$("#selectedshipping").val(shippingId);
	$(".reviewOrderShippingId").val(shippingId);
	$(".joysale-acc-addr-cnt").css("box-shadow", "none");
	$("#highlight" + shippingId).css("box-shadow", "0 0 0 3px #2bc248 inset");
	$(".address-active").hide();
	$("#activeaddr" + shippingId).show();
}



function save_android_key() {
	androidkey = $("#androidkey").val();
	//alert(androidkey);
	if ($.trim(androidkey) == "") {
		$("#androidkeysuccess").show();
		$("#androidkeysuccess").html(yii.t('app', "Please enter android key"));
		setTimeout(function () {
			$("#androidkeysuccess").fadeOut();
		}, 3000);
	} else {
		$.ajax({
			url: baseUrl + '/admin/saveandroidkey', // point to server-side PHP script
			type: "GET",
			data: {
				'androidkey': androidkey
			},
			beforeSend: function () {
				$("#androidkeysave").html(yii.t('app', "Uploading..."));
				$("#androidkeysave").attr("disabled", true);
			},
			success: function (res) {
				$("#androidkeysuccess").show();
				$("#androidkeysave").html(yii.t('app', "Upload"));
				$("#androidkeysave").removeAttr("disabled");
				window.location.reload();
			}
		});
	}
}

	/* price decimal script */
$(document).on('keypress',
	'#sitesettings-ad_price',
	function (event) {

     var $this = $(this);
	    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
	       ((event.which < 48 || event.which > 57) &&
	       (event.which != 0 && event.which != 8))) {
	           event.preventDefault();
	    }

	    var text = $(this).val();
	    if ((event.which == 46) && (text.indexOf('.') == -1)) {
	        setTimeout(function() {
	            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
	                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
	            }
	        }, 1);
	    }

	    if ((text.indexOf('.') != -1) &&
	        (text.substring(text.indexOf('.')).length > 2) &&
	        (event.which != 0 && event.which != 8) &&
	        ($(this)[0].selectionStart >= text.length - 2)) {
	            event.preventDefault();
	    }

	    var number = text.split('.');
	    if(number[0].length > 5) {
	    	if(number.length == 1 && number[0].length == 6 && event.which != 46 && event.which != 8) {
	    		event.preventDefault();
	    	} else if (number.length == 2) {
	 			if((event.which < 48 || event.which > 57) && event.which != 8)
	 				event.preventDefault();
	    	}
	    }


   });


	$('#sitesettings-ad_price').bind("paste", function(e) {
		var text = e.originalEvent.clipboardData.getData('Text');
		if ($.isNumeric(text)) {
		    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
		        e.preventDefault();
		        $(this).val(text.substring(0, text.indexOf('.') + 3));
		   }
		}
		else {
		        e.preventDefault();
		     }



	});

$(document).on('keypress',
	'#sitesettings-ad_limit	',
	function (evt) {
	  if (evt.which != 8 && evt.which != 0 && (evt.which < 48 || evt.which > 57)) {
      	  $("#Sitesettings_ad_limit_em_").html("Please enter integer values").show().fadeOut("slow");
               return false;
    }

	});


$("#ad-setting-submit").click(function () {
	var data = $('#sitesettings-ad_price').val();
	/*if(advertise_price % 1 !== 0){
			$("#Sitesettings_ad_price_em_").show();
			$("#Sitesettings_ad_price_em_").html("Please enter integer values.");
				setTimeout(function () {
					$("#Sitesettings_ad_price_em_").fadeOut();
				}, 2000);
				return false;
	}*/


				var number = data.split('.');


				if(number.length>2){
					$("#Sitesettings_ad_price_em_").show();
					$("#Sitesettings_ad_price_em_").html("Kindly enter valid price, Example: (1.50 , 5 , 100 +)");
						setTimeout(function () {
							$("#Sitesettings_ad_price_em_").fadeOut();
						}, 2000);
						return false;
				}

				
				if(number.length == 1 && data.localeCompare(Math.round(data))) {

					$("#Sitesettings_ad_price_em_").show();
					$("#Sitesettings_ad_price_em_").html("Kindly enter valid price, Example: (1.50 , 5 , 100 +)");
						setTimeout(function () {
							$("#Sitesettings_ad_price_em_").fadeOut();
						}, 2000);
						return false;
					//msg = "Kindly enter valid price, Example: (1.50 , 5 , 100 +)";	validCheck = true;
				} else if(number.length > 1 && number[0].localeCompare(Math.round(number[0]))) {
					$("#Sitesettings_ad_price_em_").show();
					$("#Sitesettings_ad_price_em_").html("Kindly enter valid price, Example: (1.50 , 5 , 100 +)");
						setTimeout(function () {
							$("#Sitesettings_ad_price_em_").fadeOut();
						}, 2000);
						return false;
					//msg = "Kindly enter valid price, Example: (1.50 , 5 , 100 +)";	validCheck = true;
				}

				if(number.length > 1 && number[1].localeCompare(Math.round(number[1]))) {
					$("#Sitesettings_ad_price_em_").show();
					$("#Sitesettings_ad_price_em_").html("Kindly enter valid price, Example: (1.50 , 5 , 100 +)");
						setTimeout(function () {
							$("#Sitesettings_ad_price_em_").fadeOut();
						}, 2000);
						return false;
					//msg = "Kindly enter valid price, Example: (1.50 , 5 , 100 +)";	validCheck = true;
				}
				if(number.length > 1 && number[1].length == 0) {
					$("#Sitesettings_ad_price_em_").show();
					$("#Sitesettings_ad_price_em_").html("Decimal price value is not valid");
						setTimeout(function () {
							$("#Sitesettings_ad_price_em_").fadeOut();
						}, 2000);
						return false;
				//	msg = "Decimal price value is not valid";	validCheck = true;
				} else if(number[0].length > 6) {
					$("#Sitesettings_ad_price_em_").show();
					$("#Sitesettings_ad_price_em_").html("Decimal price value should not exceed 999999 (6 digit)");
						setTimeout(function () {
							$("#Sitesettings_ad_price_em_").fadeOut();
						}, 2000);
						return false;
					//msg = "Decimal price value should not exceed 999999 (6 digit)";	validCheck = true;
				}
				if(number[1].length > 2) {
					$("#Sitesettings_ad_price_em_").show();
					$("#Sitesettings_ad_price_em_").html("Decimal price value should not exceed 2 digit after deciaml point)");
						setTimeout(function () {
							$("#Sitesettings_ad_price_em_").fadeOut();
						}, 2000);
						return false;
					//msg = "Decimal price value should not exceed 999999 (6 digit)";	validCheck = true;
				}

	var advertise_limit = $('#sitesettings-ad_limit').val();
	if(advertise_limit % 1 !== 0){
			$("#Sitesettings_ad_limit_em_").show();
			$("#Sitesettings_ad_limit_em_").html("Please enter integer values.");
				setTimeout(function () {
					$("#Sitesettings_ad_limit_em_").fadeOut();
				}, 2000);
				return false;
	}
		
});

/* price decimal script */
$(document).on('keypress',
	'#Products_price',
	function (evt) {
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if ((charCode) != 46 && (charCode) > 31 &&
			((charCode) < 48 || (charCode) > 57))
			return false;

		return true;

	});

$(document).on('keyup',
	'#Products_price',
	function (evt) {
		var exp = /^\d{0,6}(\.{1}\d{0,2})?$/g;
		var letter = /^[a-zA-Z]+$/;
		var $th = $(this);

		if (!exp.test($th.val())) {

			var number = ($(this).val().split('.'));
			if (number[0].length > 6) {
				var res = $th.val().substr(0, 6);
				$th.val(res);

			} else if ((number[1].length > 2) && (number[0].length == 1)) {
				var res = $th.val().substr(0, 5);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 2)) {
				var res = $th.val().substr(0, 6);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 3)) {
				var res = $th.val().substr(0, 7);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 4)) {
				var res = $th.val().substr(0, 8);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 5)) {
				var res = $th.val().substr(0, 9);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 6)) {
				var res = $th.val().substr(0, 10);
				$th.val(res);
			} else {
				$("#Products_price_em_").show();
				$("#badMessage").hide();
				$('#Products_price_em_').text(
					yii.t('app', 'Invalid format'));
				$("#Products_price_em_").fadeIn();
				setTimeout(function () {
					$("#Products_price_em_").fadeOut();
				}, 2000);
				return false;
			}

			$("#Products_price_em_").show();
			$("#badMessage").hide();
			$('#Products_price_em_').text(
				yii.t('app', 'Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
			$("#Products_price_em_").fadeIn();
			setTimeout(function () {
				$("#Products_price_em_").fadeOut();
			}, 2000);
			return false;
		}

	});


function myOfferRate() {
	var offerRate = $("#MyOfferForm_offer_rate").val();
	var productPrice = $(".product-price-hidden").val();

	var offer = offerRate.replace(/\s/g, '');

	if (productPrice != "" && productPrice != 0) {
		if (offerRate != "" && offerRate > 0) {
			if (Number(offerRate) >= Number(productPrice)) {
				$(".offer-form").hide();
				$("#errorMessage").show();
				$("#errorMessage").html(
					yii.t('app', 'Offer Price should be less than Product Price.'));

				setTimeout(function () {
					$("#errorMessage").show();
				});
			} else {
				$("#errorMessage").hide();
				$(".offer-form").show();
			}

		} else {
			$(".offer-form").hide();
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
	'#Products_shippingCost',
	function (evt) {
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if ((charCode) != 46 && (charCode) > 31 &&
			((charCode) < 48 || (charCode) > 57))
			return false;

		return true;

	});


$(document).on('keyup',
	'#Products_shippingCost',
	function (evt) {
		var exp = /^\d{0,6}(\.{1}\d{0,2})?$/g;
		var letter = /^[a-zA-Z]+$/;
		var $th = $(this);

		if (!exp.test($th.val())) {

			var number = ($(this).val().split('.'));
			if (number[0].length > 6) {
				var res = $th.val().substr(0, 6);
				$th.val(res);

			} else if ((number[1].length > 2) && (number[0].length == 1)) {
				var res = $th.val().substr(0, 5);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 2)) {
				var res = $th.val().substr(0, 6);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 3)) {
				var res = $th.val().substr(0, 7);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 4)) {
				var res = $th.val().substr(0, 8);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 5)) {
				var res = $th.val().substr(0, 9);
				$th.val(res);
			} else if ((number[1].length > 2) && (number[0].length == 6)) {
				var res = $th.val().substr(0, 10);
				$th.val(res);
			} else {
				$("#Products_shippingCost_em_").show();
				$("#badMessage").hide();
				$('#Products_shippingCost_em_').text(
					yii.t('app', 'Invalid format'));
				$("#Products_shippingCost_em_").fadeIn();
				setTimeout(function () {
					$("#Products_shippingCost_em_").fadeOut();
				}, 2000);
				return false;
			}

			$("#Products_shippingCost_em_").show();
			$("#badMessage").hide();
			$('#Products_shippingCost_em_').text(
				yii.t('app', 'Invalid format (only 6 digit allowed before decimal point and 2 digit after decimal point)'));
			$("#Products_shippingCost_em_").fadeIn();
			setTimeout(function () {
				$("#Products_shippingCost_em_").fadeOut();
			}, 2000);
			return false;
		}

	});

// shipping cost decimal

/* end price decimal script */


function gotogetLocationDataHome() {
	//	search = $("#searchval").val(" ");
	$('#pac-input').attr("value", "");
	search = $("#pac-input").val();
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
	$("#pac-input").keypress(function () {
		var aa = $("#pac-input").val();
		var bb = $.trim(aa);
		$("#pac-input").val(bb);
	});

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


function start_image_upload() {

	var inp = document.getElementById('image_file');

	uploadedfiles = $("#uploadedfiles").val();
	if (uploadedfiles != "") {
		uploaded = jQuery.parseJSON(uploadedfiles);
		uploadedlen = uploaded.length;
	} else {
		uploadedlen = 0;
	}
	imagesarr = [];
	var i = 0,
		len = parseInt(inp.files.length, 10),
		img, reader, file;
	j = parseInt(document.getElementById('count').value, 10);

	var filePath = inp.value;

	var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
	if (!allowedExtensions.exec(filePath)) {
		$("#image_error").show();
		$("#image_error").html(yii.t('app', "Upload only image file"));
		setTimeout(function () {
			$("#image_error").slideUp();
			$('#image_error').html('');
		}, 5000);
		return false;
	}


	remainfiles = parseInt(10) - parseInt(uploadedlen);
	if (len == 0) {
		$("#image_error").show();
		$("#image_error").html(yii.t('app', "Please Select Image"));
		setTimeout(function () {
			$("#image_error").slideUp();
			$('#image_error').html('');
		}, 5000);
		return false;
	}
	let uploadfiles = j + len;
	if (uploadfiles > 5) {
		$("#image_error").show();
		$("#image_error").html(yii.t('app', "You can upload 5 images only"));
		setTimeout(function () {
			$("#image_error").slideUp();
			$('#image_error').html('');
		}, 5000);
		return false;
	}
	formdata = new FormData();
	for (; i < len; i++) {
		file = inp.files[i];

		if (!!file.type.match(/image.*/)) {
			document.getElementById('count').value = (j + len);

			if (window.FileReader) {
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

	var d = parseInt(document.getElementById('count').value, 10);

	if (formdata) {
		$.ajax({
			url: baseUrl + '/products/startfileupload/', // point to server-side PHP script 
			type: "POST",
			data: formdata,
			processData: false,
			contentType: false,
			beforeSend: function () {
				$("#loadingimg").show();
				$(".loading").css("display", "block");
				$("#startuploadbtn").attr("disabled", "true");
			},
			success: function (res) {
				//alert(res);
				$("#imagenames").html("");
				$("#loadingimg").hide();
				if ($.trim(res) == "error") {
					$(".blog_img_error").show();
					$(".blog_img_error").html(yii.t('app', "File is too big"));
					setTimeout(function () {
						$(".blog_img_error").slideUp();
						$('.blog_img_error').html('');
					}, 4000);
					$("#startuploadbtn").removeAttr("disabled");
					$("#blogfile").val("");
				} else {
					result = res.split("***");
					inputfiles = $("#uploadedfiles").val();
					if (inputfiles == "")
						$("#uploadedfiles").val(result[1]);
					else {
						newfiles = result[1].replace('[', '');
						existfiles = $("#uploadedfiles").val();
						existfiles = existfiles.replace(']', '');
						$("#uploadedfiles").val(existfiles + ',' + newfiles);
					}
					$(".blog_images").append(result[0]);
					$("#startuploadbtn").removeAttr("disabled");
					$("#loadingimg").hide();
					$(".loading").css("display", "none");
					$("#uploadfile").val("");
				}
			}
		});
	}
}

function remove_images1(org, imgname, pid) {


	$(org).hide();
	$(org).prev("img").hide();
	$(org).parent().remove();
	var cnt = $("#count").val();
	var a = cnt - 1;;
	$("#count").val(a);
	removeimg.push(imgname);
	$("#removefiles").val(removeimg);

	// $.ajax({
	//        url: baseUrl+'/products/remove_blogimage1/',
	//        type: "POST",
	//        dataType : "html",
	//        data: {
	//            'image' : imgname,
	//            'productId' : pid,
	//        },
	//        success: function (res) {
	//            //alert(res);
	//        }
	//    });

}

function remove_images(org, imgname) {

	$(org).hide();
	$(org).prev("img").hide();
	$(org).parent().remove();
	uploadedfiles = $("#uploadedfiles").val();
	filesarr = JSON.parse(uploadedfiles);
	filesarr = $.grep(filesarr, function (value) {
		return value != imgname;
	});

	if (filesarr.length >= 1) {
		files = JSON.stringify(filesarr);
		$("#uploadedfiles").val(files);
	} else {
		$("#uploadedfiles").val("");
	}
	var cnt = $("#count").val();
	var a = cnt - 1;;
	$("#count").val(a);
	$.ajax({
		url: baseUrl + 'products/remove_blogimage/',
		type: "POST",
		dataType: "html",
		data: {
			image: imgname,

		},
		success: function (res) {

		}
	});
}




$(".bannerapprove").click(function () {
	var autoapprove = $('.bannerapprove').val();
	$(".videobannerapprove").prop("checked", false);
	if (autoapprove == '1') {
		$('.bannerapprove').val('0');
	} else {
		$('.bannerapprove').val('1');
	}
	var enablestatus = parseInt($('.bannerapprove').val());
	var videoenablestatus = parseInt($('.videobannerapprove').val());

	if (enablestatus == 1) {
		videoenablestatus = 0;
	}


	$.ajax({
		type: 'POST',
		url: baseUrl + '/banners/bannerenable',
		data: {
			enablestatus: enablestatus,
			videoenablestatus: videoenablestatus
		},
		success: function (responce) {

		}
	});
});


$(".videobannerapprove").click(function () {
	var autoapprove = $('.videobannerapprove').val();
	$(".bannerapprove").prop("checked", false);
	if (autoapprove == '1') {
		$('.videobannerapprove').val('0');
	} else {
		$('.videobannerapprove').val('1');
	}
	var enablestatus = parseInt($('.videobannerapprove').val());
	var bannerenablestatus = parseInt($('.bannerapprove').val());

	if (enablestatus == 1) {
		bannerenablestatus = 0;
	}

	$.ajax({
		type: 'POST',
		url: baseUrl + '/banners/bannervideoenable',

		data: {
			enablestatus: enablestatus,
			bannerenablestatus: bannerenablestatus
		},

		success: function () {

		}
	});
});

/* mobile banner */
$(".mobilebannerapprove").click(function () {
	var autoapprove = $('.mobilebannerapprove').val();
	if (autoapprove == '1') {
		$('.mobilebannerapprove').val('0');
	} else {
		$('.mobilebannerapprove').val('1');
	}
	var enablestatus = parseInt($('.mobilebannerapprove').val());
	$.ajax({
		type: 'POST',
		url: baseUrl + '/banners/appbannerenable',

		data: {
			enablestatus: enablestatus
		},

		success: function (data) {
		}
	});
});

$(".autoapprove").click(function () {

	var autoapprove = $('.autoapprove').val();
	if (autoapprove == '1') {
		$('.autoapprove').val('0');
	} else {
		$('.autoapprove').val('1');
	}
	var autoapprovestatus = $('.autoapprove').val();
	$.ajax({
		type: 'POST',
		url: baseUrl + '/products/itemautoapprove',
		data: {
			autoapprovestatus: autoapprovestatus
		},
		success: function (data) {
			//	alert(data);


		}
	});
});


function validatebanner() {
	bannerimage = document.getElementById('Banners_bannerimage').value;
	appbannerimage = document.getElementById('Banners_appbannerimage').value;
	bannerurl = document.getElementById('Banners_bannerurl').value;
	bannerimage1 = document.getElementById('hiddenwebImage').value;
	appbannerimage1 = document.getElementById('hiddenappImage').value;
	//alert(bannerimage1);
	//alert(appbannerimage1);
	if (bannerimage == "" && bannerimage1 == "") {
		$("#bannerimageerr").html(yii.t('app', "Please select image"));
		return false;
	} else {
		$("#bannerimageerr").hide();
	}

	if (bannerimage != "") {
		var fileInput = document.getElementById('Banners_bannerimage');

		var filePath = fileInput.value;
		var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
		if (!allowedExtensions.exec(filePath)) {
			$('#bannerimageerr').html(yii.t('app', "Invalid file format. Please Upload an Image file"));

			fileInput.value = '';
			$("#bannerimageerr").show();
			setTimeout(function () {
				$('#bannerimageerr').fadeOut();
			}, 5000);
			return false;
		}
	}

	if (appbannerimage == "" && appbannerimage1 == "") {
		$("#appbannerimageerr").html(yii.t('app', "Please select image"));

		return false;
	} else {
		$("#appbannerimageerr").hide();
	}

	if (appbannerimage != "") {
		var fileInput = document.getElementById('Banners_appbannerimage');

		var filePath = fileInput.value;
		var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
		if (!allowedExtensions.exec(filePath)) {
			$('#appbannerimageerr').html(yii.t('app', "Invalid file format. Please Upload an Image file"));
			fileInput.value = '';

			$("#appbannerimageerr").show();
			setTimeout(function () {
				$('#appbannerimageerr').fadeOut();
			}, 5000);
			return false;
		}
	}
	if ($.trim(bannerurl) == "") {
		$("#bannerurlerr").html(yii.t('app', "Please enter banner url"));
		setTimeout(function () {
			$('#bannerurlerr').html("");
		}, 5000);
		return false;
	}
	return true;
}

function validatepromotion() {
	var promotionname = $("#Promotions_name").val();
	var promotiondays = $("#Promotions_days").val();
	var promotionprice = $("#Promotions_price").val();

	if (promotionname == "") {
		$("#nameerr").html(yii.t('app', "Please enter promotion name"));
		setTimeout(function () {
			$('#nameerr').html("");
		}, 5000);
		return false;
	}
	if (promotiondays == "") {

		$("#dayserr").html(yii.t('app', "Please enter promotion days"));
		setTimeout(function () {
			$('#dayserr').html("");
		}, 5000);
		return false;
	}
	if (promotiondays <= 0) {
		$("#dayserr").html(yii.t('app', "Please enter promotion days greater than 0"));
		setTimeout(function () {
			$('#dayserr').html("");
		}, 5000);
		return false;
	}
	if (promotionprice == "") {
		$("#priceerr").html(yii.t('app', "Please enter promotion price"));
		setTimeout(function () {
			$('#priceerr').html("");
		}, 5000);
		return false;
	}
	if (promotionprice <= 0) {
		$("#priceerr").html(yii.t('app', "Please enter promotion price greater than 0"));
		setTimeout(function () {
			$('#priceerr').html("");
		}, 5000);
		return false;
	}




    if(isNaN(promotionprice)){
    	$("#priceerr").html(yii.t('app', "Price should be numeric"));
		setTimeout(function () {
			$('#priceerr').html("");
		}, 5000);
		return false;
    }
    if(isNaN(promotiondays)){
        
     	$("#dayserr").html(yii.t('app', "Days should be numeric"));
		setTimeout(function () {
			$('#dayserr').html("");
		}, 5000);
		return false;
    }
	$(document).on('submit', '#promo_form', function () {
		$('#btnUpdate').attr('disabled', 'disabled');
	});
}


function validatebannervideo() {

	var video = $("#file").val();
	var video1 = $("#hiddenBannerVideo").val();
	var txt = $("#hiddenBannerVideo").val();

	if (video == "" && video1 == "") {
		$("#bannervideoError").html(yii.t('app', 'Please select the video'));
		document.getElementById("beforeupload").style.display = "block";
		document.getElementById("upload").style.display = "none";
		setTimeout(function () {
			$('#bannervideoError').html("");
		}, 5000);
		return false;
		
	}
	if (video != "") {
		var fileInput = document.getElementById('file');

		var filePath = fileInput.value;
		var allowedExtensions = /(\.mp4)$/i;
		if (!allowedExtensions.exec(filePath)) {
			$('#bannervideoError').html(yii.t('app', "Invalid file format. Please Upload an Video file"));
			fileInput.value = '';
			document.getElementById("beforeupload").style.display = "block";
			document.getElementById("upload").style.display = "none";
			return false;
		}
	}

	document.getElementById("beforeupload").style.display = "block";
	document.getElementById("upload").style.display = "none";
	return true;

}



function delete_image(id, pid) {
	var id = id;
	var pid = pid;
	//alert(id);
	$.ajax({
		type: 'POST',
		url: baseUrl + '/products/deletephoto',
		data: {
			id: id,
			pid: pid
		},
		success: function (data) {
			//alert(data);
			location.reload();

		}
	});
}



function soldItemAdmin(id, value) {
	//var id = $("#base_id").val();
	//alert(video1);
	/*var value = 0;
	if ($('#soldOut').is(':checked')) {
		value = 1;
	}*/
	console.log(id);
	//alert(value);
	$.ajax({
		type: 'POST',
		url: baseUrl + '/products/itemsold',
		data: {
			id: id,
			value: value
		},
		success: function (data) {
			var appendText = '';
			if (value == 0) {
				appendText = '<a  data-loading-text="Posting..." id="load" data-toggle="modal" ' +
					'class="sold-btn m-b-20" onclick="soldItemAdmin(\'' + id + '\', \'1\')" style="cursor: pointer;">' +
					yii.t('app', 'Mark as sold') + '</a>';
			} else {
				appendText = '<a  data-loading-text="Posting..." id="load" data-toggle="modal" ' +
					'class="sold-btn sale-btn m-b-20" onclick="soldItemAdmin(\'' + id + '\', \'0\')" style="cursor: pointer;">' +
					yii.t('app', 'Back to sale') + '</a>';


			}

			$('.edit-btn').html(appendText);
			console.log('success');
			//location.reload();

		}


	});
}


$(document).on('click', '#Products_instantBuy', function () {

	if ($('#Products_instantBuy').is(':checked')) {
		$('.instant-buy-details').fadeIn('slow');
	} else {
		$('.instant-buy-details').fadeOut('fast');
	}
});

$("#giving_away").click(function () {

	var labels = document.getElementById('giving_away').checked;

	if (labels == false) {
		$("#giving_away").val('0');
		$("#Products_price").val('');
		$('#giving_away').removeAttr('checked');
		$('.Category-price-box-row').slideToggle(500);
		//alert(0);
	} else {
		$("#giving_away").val('1');
		$('#giving_away').attr('checked', 'checked');
		$('.Category-price-box-row').slideToggle(500);
		//	alert(1);
	}

	var productPropertyUpdates = 0;
	var selectedCategory = $('#Products_category').val();
	var giving_away = $("#giving_away").val();
	//alert(giving_away);
	if (productPropertyUpdates == 0 && selectedCategory != 0 && selectedCategory != "") {
		productPropertyUpdates = 1;
		console.log('Products_category on change call click on giving away');
		$.ajax({
			url: baseUrl + "/products/productproperty",
			type: "post",
			data: {
				'selectedCategory': selectedCategory,
				'givingAway': giving_away
			},
			dataType: "html",
			success: function (responce) {
				$('.instant-buy-details').fadeOut('fast');
				responce = responce.trim();
				propertyData = eval(responce);
				if (propertyData[0] == 0) {
					$('.dynamicProperty').html(propertyData[1]);
					$('.dynamic-section').hide();
				} else {
					$('.dynamicProperty').html(propertyData[1]);
					$('.dynamic-section').show();
				}
				$('#Products_subCategory').html(propertyData[2]);
				productPropertyUpdates = 0;
				//alert(propertyData[1]);
			}
		});
	}

});


var productPropertyUpdate = 0;
$(document).on('change', '#Products_category', function () {

	var selectedCategory = document.getElementById('Products_category').value;
	//alert(selectedCategory);

	var productId = $('#productId').val();
	var giving_away = $("#giving_away").val();

	//alert(giving_away);
	console.log('Products_category on change call');

	if (productPropertyUpdate == 0) {
		productPropertyUpdate = 1;


		$.ajax({
			url: baseUrl + "/products/productproperty",
			type: "post",
			data: {
				'selectedCategory': selectedCategory,
				'givingAway': giving_away,
				'productId': productId
			},
			dataType: "html",
			success: function (responce) {
				//alert(responce);
				$('.instant-buy-details').fadeOut('fast');
				responce = responce.trim();
				propertyData = eval(responce);
				if (propertyData[0] == 0) {
					$('.dynamicProperty').html(propertyData[1]);
					$('.dynamic-section').hide();
				} else {
					$('.dynamicProperty').html(propertyData[1]);
					$('.dynamic-section').show();
				}
				$('#Products_subCategory').html(propertyData[2]);
				$('#Products_sub_subCategory').html(propertyData[3]);
				$('#Products_sub_subCategory_head').html('Select child category');
				productPropertyUpdate = 0;
			}
		});
	}
});


$(document).on('change', '#Products_subCategory', function () {
	var productCategory = document.getElementById("Products_category").value;
	var ProductsubCategory = document.getElementById("Products_subCategory").value;
	var productId = document.getElementById("productId").value;

	//Get instant data from the posted values.
	$.ajax({
           type: "POST",
           url: baseUrl+'/products/getfilter',
           data: { 
           		'cat':productCategory,
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
					//alert(parentids);
					//return false;
					//$(data).appendTo('#'+parentids);
					$('div#'+parentids).html(data);
					//alert('set back');
				}
				return false;
           }
    });
}

function submitfilter() {
	// body...
	var filtertype = $('#filter-type').val();
	var filtername = $('#filter-name').val();

	if(filtername == ''){
		//alert('test filter');
		$('.help-block-name').html('Filter name cannot be blank');
		
	}

	//alert(filtertype); return false;

	if(filtertype == 'range'){
		if($('#dynamic_range').val() == '')
		{
			//alert('test filter');
			$('.help-block-range').html('Value cannot be blank.');
			
		}
	}else{
		var dropdown = $('#dynamic_dropdown').val();
		if($('#dynamic_dropdown').val() == '')
		{
			//alert('test filter');
			$('.help-block-dropdown').html('Value cannot be blank');
			
		}
	}
}

//Add dynamic input fields.
$(document).ready(function(){
//$('input#parent').tagsinput({maxChars: 30});
$('input#child').tagsinput({maxChars: 18});
$('input#dropdownval').tagsinput({maxChars: 18});

$('#filter_update').click(function( event ) {

	var filtername = $('#filter-name').val();
	var filtertype = $('#filter-type').val();
	

	if(filtername == ''){
		$('.help-block-name').show();
		$('.help-block-name').html('Filter cannot be blank.').css('color','red');
		setTimeout(function () {
			$('.help-block-name').fadeOut('slow');
		}, 5000);
		return false;
	}

	
	if(filtertype == 'range'){
		var minval =  parseFloat($('#dynamic_range_min').val());
		var maxval =  parseFloat($('#dynamic_range_max').val());

		if(	$('#dynamic_range_min').val() == '' || 
			($('#dynamic_range_max').val() == '' || $('#dynamic_range_max').val() == '0')
			)
		{
			if($('#dynamic_range_max').val() == '0')
			{
				var validatemsg = 'Max Range cannot be zero';
			}else{
				var validatemsg = 'Filter range cannot be blank.';
			}
			$('.help-block-range.errormessage').show();
			$('.help-block-range.errormessage').html(validatemsg).css('color','red');
			setTimeout(function () {
				$('.help-block-range.errormessage').fadeOut('slow');
			}, 5000);
			return false;
		}
			if(minval > maxval)
			{
				$('.help-block-range.errormessage').show();
				$('.help-block-range.errormessage').html('Min and Max range have wrong values.').css('color','red');
				setTimeout(function () {
					$('.help-block-range').fadeOut('slow');
				}, 5000);
				return false;
			}
		
	}else if( filtertype == 'multilevel' ){

		if($('#parent').val() == '')
		{
			$('.help-block-multilevelvalues').show();
			$('.help-block-multilevelvalues').html('Add parent and Child category list.').css('color','red');
				setTimeout(function () {
					$('.help-block-multilevelvalues').fadeOut('slow');
				}, 5000);
			return false;
		}else if($('#child').val() == '')
		{
			$('.help-block-multilevelvalues').show();
			$('.help-block-multilevelvalues').html('Add parent and Child category list.').css('color','red');
				setTimeout(function () {
					$('.help-block-multilevelvalues').fadeOut('slow');
				}, 5000);
			return false;
		}
	}else if(filtertype == 'dropdown'){
		if( $('#dropdownval').val() == '' )
		{
			$('.help-block-dropdownerror').show();
			$('.help-block-dropdownerror').html('Value cannot be blank').css('color','red');
			setTimeout(function () {
					$('.help-block-dropdownerror').fadeOut('slow');
				}, 5000);
			return false;
		}
	}
});


$(document).ready(function() {
        $('#dropdownval').on('input propertychange', function() {
            CharLimit(this, 18);
        });

        $('#dynamic_range_min').on('input propertychange', function() {
            CharLimit(this, 6);
        });

        $('#dynamic_range_max').on('input propertychange', function() {
            CharLimit(this, 6);
        });


    });

    function CharLimit(input, maxChar) {
        var len = $(input).val().length;
        if (len > maxChar) {
            $(input).val($(input).val().substring(0, maxChar));
        }
    }



$('#filter_submit').submit(function( event ) {

	//$('button#filter_submit').attr('disabled','disabled');

	var filtername = $('#filter-name').val();
	var filtertype = $('#filter-type').val();
	

	if(filtername == ''){
		$('.help-block-name').show();
		$('.help-block-name').html('Filter cannot be blank.').css('color','red');
		setTimeout(function () {
			$('.help-block-name').fadeOut('slow');
		}, 5000);
		return false;
	}

	//alert(filtertype); return false;

	if(filtertype == 'range'){

		var minval =  parseFloat($('#dynamic_range_min').val());
		var maxval =  parseFloat($('#dynamic_range_max').val());


		if(	$('#dynamic_range_min').val() == '' || 
			($('#dynamic_range_max').val() == '' || $('#dynamic_range_max').val() == '0')
			)
		{
			if($('#dynamic_range_max').val() == '0')
			{
				var validatemsg = 'Max Range cannot be zero';
			}else{
				var validatemsg = 'Filter range cannot be blank.';
			}
			$('.help-block-range').show();
			$('.help-block-range').html(validatemsg).css('color','red');
			setTimeout(function () {
				$('.help-block-range').fadeOut('slow');
			}, 5000);
			return false;
		}else{
			if(minval > maxval)
			{
				$('.help-block-range').show();
				$('.help-block-range').html('Min and Max range have wrong values.').css('color','red');
				setTimeout(function () {
					$('.help-block-range').fadeOut('slow');
				}, 5000);
				return false;
			}
		}
	}else if( filtertype == 'multilevel' ){

		var isValid;
		$("input[id=parent]").each(function() {
			if($(this).val() == '')
			{
				//var classname = $(this).attr("class");
				$(this).css('border','1px solid #FF0000');
				isValid = 'false';
			}else{
				$(this).css('border','1px solid #ccc');
			}
		});

		if(isValid == 'false')
		{
			$('.help-block-multilevelvalues').show();
				$('.help-block-multilevelvalues').html('Parent values are empty.').css('color','red');
					setTimeout(function () {
						$('.help-block-multilevelvalues').fadeOut('slow');
					}, 5000);

			return false;
		}


		var isValidchild;
		$("input[id=child]").each(function() {
			
			if($(this).val() == '')
			{
				$(this).css('border','1px solid #FF0000');
				isValidchild = 'false';
			}else{
				$(this).css('border','1px solid #ccc');
				
			}
			
		});


		if(isValidchild == 'false')
		{
			$('.help-block-multilevelvalues').show();
				$('.help-block-multilevelvalues').html('Child values are empty.').css('color','red');
					setTimeout(function () {
						$('.help-block-multilevelvalues').fadeOut('slow');
					}, 5000);
			return false;
		}

	}else if(filtertype == 'dropdown'){
		if( $('#dropdownval').val() == '' )
		{
			$('.help-block-dropdownerror').show();
			$('.help-block-dropdownerror').html('Value cannot be blank').css('color','red');
			setTimeout(function () {
					$('.help-block-dropdownerror').fadeOut('slow');
				}, 5000);
			return false;
		}
	}

	$('button#filter_submit').attr('disabled','disabled');



});


var i = 1;
var filtertype = $('#filter-type').val();
//alert(filtertype);
	if( filtertype == 'range' ){
		$('#dropdownsection').hide();
		$('#multilevelsection').hide();
		$('#rangesection').show();
	}else if( filtertype == 'dropdown' ){
		$('#dropdownsection').html('');
		var getfilterval = $('#filterval').val();
		//alert(getfilterval);
		if(getfilterval == 'null')
		{
			$('#dropdownsection').html('<label class="control-label" for="filter-type">Values</label><input type="text" name="Filter[dynamic][dropdown]" id="dropdownval" class="form-control" value="" data-role="tagsinput" /><div class="help-block-dropdownerror"></div>');
		}else{
			$('#dropdownsection').html('<label class="control-label" for="filter-type">Values</label><input type="text" name="Filter[dynamic][dropdown]" id="dropdownval" class="form-control" value="'+getfilterval+'" data-role="tagsinput" /><div class="help-block-dropdownerror"></div>');
		}		
		$('input#dropdownval').tagsinput({maxChars: 18});
		$('#rangesection').hide();
		$('#multilevelsection').hide();
	}else if(filtertype == 'multilevel')
	{
		$('#multilevelsection').show();
		//$('input#parent').tagsinput({maxChars: 30});
		$('input#child').tagsinput({maxChars: 18});
		$('#dropdownsection').hide();
		$('#rangesection').hide();
	}
	var i = 1;
		
//Change filters by choosed options.
$('#filter-type').change(function(){
	var filtertype = $('#filter-type').val();
	if(filtertype == 'range'){
		$('#dropdownsection').hide();
		$('#multilevelsection').hide();
		$('#rangesection').show();
	}else if(filtertype == 'dropdown'){
		$('#dropdownsection').show();
		$('#dropdownsection').html('<label class="control-label" for="filter-type">Values</label><input type="text" name="Filter[dynamic][dropdown]" id="dropdownval" class="form-control" value="" data-role="tagsinput" /><div class="help-block-dropdownerror"></div>');
		$('input#dropdownval').tagsinput({maxChars: 18});
		$('#rangesection').hide();
		$('#multilevelsection').hide();
	}else if(filtertype == 'multilevel'){
		$('#multilevelsection').show();
		//$('input#parent').tagsinput({maxChars: 30});
		$('input#child').tagsinput({maxChars: 18});
		$('#rangesection').hide();
		$('#dropdownsection').hide();
	}
});

$('#add').click(function() {
	var inputs = $("#gridsection").find($(".field") );
	if(inputs.length > 1)
	{
		i = inputs.length+1;
	}else{
		i = i;
	}
$('<div class="form-group field-filter-name required dynamic_'+i+'"><input type="text" class="field form-control" name="dynamic[]" placeholder="Value"  /><a href="javascript:void(0);" onclick="removeattribute(\'dynamic_'+i+'\')" class="dynamic_'+i+'">Remove</a></div>').fadeIn('slow').appendTo('.inputs');
i++;
});




$('#addlevel').click(function() {
	var inputs = $("#multilevelsection").find($(".field") );
	if(inputs.length > 1)
	{
		i = inputs.length+1;
	}else{
		i = i;
	}
	$('<div class="form-group field-filter-name required dynamic_'+i+'"><input type="text" id="parent" maxlength="18" class="field form-control multilevel" name="Filter[dynamic][parent][]" placeholder="Value"  /><input type="text" data-role="tagsinput" name="Filter[dynamic][child][]" placeholder="child level" class="form-control multilevel child_'+i+' tagnewinput" id="child" /><a href="javascript:void(0);" onclick="removeattribute(\'dynamic_'+i+'\')" class="dynamic_'+i+'">Remove</a></div>').fadeIn('slow').appendTo('.inputs');
	$('input.tagnewinput').tagsinput('refresh');
	$('input#child').tagsinput({maxChars: 18});
	
	i++;
});


$('#resetlevel').click(function() {
	var inputs = $("#multilevelsection").find($(".field") );
	if(inputs.length > 1)
	{
		i = inputs.length+1;
	}else{
		i = i;
	}
$('<div class="form-group field-filter-name required dynamic_'+i+'"><input type="text" class="field form-control" name="dynamic[]" placeholder="Value"  /><a href="javascript:void(0);" onclick="removeattribute(\'dynamic_'+i+'\')" class="dynamic_'+i+'">Remove</a></div>').fadeIn('slow').appendTo('.inputs');
i++;
});


$('#removelevel').click(function() {
	var i = $('.field').length;
	if(i > 1) {
		$('.field:last').remove();
		i--;
	}
});

$('#remove').click(function() {
	var i = $('.field').length;
	if(i > 1) {
		$('.field:last').remove();
		i--;
	}
});

$('#reset').click(function() {
	while(i > 1) {
		$('.field:last').remove();
		i--;
	}
});
// here's our click function for when the forms submitted
});


function removeattribute(classname)
{
	//e.preventDefault();
	$('div.'+classname).remove();
	return false;
}

//Checkbox check and uncheck functionality
function checkwallfunc()
{
	//$('#priviliges').change(function() {
	  var n = $( "input:checked" ).length;
	  //alert(n);
	  if(n<=29)
	  {
	  	$('#check_uncheck').prop('checked', false);
	  }else{
	  	$('#check_uncheck').prop('checked', true);
	  }
	//});
	  //return false;
	/*
	if($('#priviliges').is(":checked")) 
	{
		alert('checked the fields');
	}else{
		alert('unchecked the fields');
	}
	*/
	//var ttt = $('#priviliges').prop('checked');
	//alert(ttt);
	//return false;
	/*
	if($('#priviliges'). prop("checked") == false){
		$('#check_uncheck').prop('checked', false);
	}
	/*
		var getfunc = $('input:checkbox[id=priviliges]').attr('checked',false);
		if( getfunc )
		{
			$('#check_uncheck').prop('checked', false);
		}else{
			$('#check_uncheck').prop('checked', true);
		}
		return true;
	*/
}

$(document).ready(function () {
	var rolesCountChecked = $("input.priviliges:checkbox:checked").length;
		var rolesCountall = $("input.priviliges:checkbox").length;

		if(rolesCountall == rolesCountChecked)
		{
			$('#check_uncheck').prop('checked', true);
		}else{
			$('#check_uncheck').prop('checked', false);
		}

$("input.priviliges").click(function () {
		
		var rolesCountChecked = $("input.priviliges:checkbox:checked").length;
		var rolesCountall = $("input.priviliges:checkbox").length;

		if(rolesCountall == rolesCountChecked)
		{
			$('#check_uncheck').prop('checked', true);
		}else{
			$('#check_uncheck').prop('checked', false);
		}
    });
 
	  $(".roles-form .checkbox-danger > label").click(function () { 
	     	var labelClick = $(this).prev('#priviliges').prop('checked'); 
	     	
	     	if(labelClick == true)
	    		$(this).prev('#priviliges').prop('checked', false);
	    	else
	    		$(this).prev('#priviliges').prop('checked', true); 

			var rolesCountChecked_lc = $("input.priviliges:checkbox:checked").length;
			var rolesCountall_lc = $("input.priviliges:checkbox").length;

			if(rolesCountall_lc == rolesCountChecked_lc) {
				$('#check_uncheck').prop('checked', true);
			}else{
				$('#check_uncheck').prop('checked', false);
			}

	  });

	  $(".card-box >.checkbox-danger > label").click(function () { 
	     	var labelClick = $(this).prev('#check_uncheck').prop('checked'); 
	     	
	     	if(labelClick == true) {
	    		$(this).prev('#check_uncheck').prop('checked', false);
	    		var source = false;
	     	} else {
	    		$(this).prev('#check_uncheck').prop('checked', true);
	    		var source = true; 
	     	}

	    	var checkboxes = document.querySelectorAll('.card-box .row input[type="checkbox"]');
		   for (var i = 0; i < checkboxes.length; i++) {
		         checkboxes[i].checked = source; 
		   }

	  });


	$("#saverolebtn").click(function () {
		var rolename = $('#roles-name').val();
		var rolesComments = $('#roles-comments').val();
		var rolesCountChecked = $("input.priviliges:checkbox:checked").length;

		if(rolename == '')
		{
			$('.field-roles-comments .help-block').html('');
			$('.field-roles-name .help-block').html('Role name cannot be empty..').css('color','red');
			return false;
		}else if(rolesComments == '')
		{
			$('.field-roles-name .help-block').html('');
			$('.field-roles-comments .help-block').html('Role comments cannot be empty..').css('color','red');
			return false;
		}else if(rolesCountChecked == ''){
			$('.field-roles-checkboxes .help-block').html('');
			$('.field-roles-checkboxes .help-block').html('Roles cannot be empty..').css('color','red');
			return false;
		}else{
			return true;
		}
	});


		$("#updaterolebtn").click(function () {
		var rolename = $('#roles-name').val();
		var rolesComments = $('#roles-comments').val();
		var rolesCountChecked = $("input.priviliges:checkbox:checked").length;

		if(rolename == '')
		{
			$('.field-roles-comments .help-block').html('');
			$('.field-roles-name .help-block').html('Role name cannot be empty..').css('color','red');
			return false;
		}else if(rolesComments == '')
		{
			$('.field-roles-name .help-block').html('');
			$('.field-roles-comments .help-block').html('Role comments cannot be empty..').css('color','red');
			return false;
		}else if(rolesCountChecked == ''){
			$('.field-roles-checkboxes .help-block').html('');
			$('.field-roles-checkboxes .help-block').html('Roles cannot be empty..').css('color','red');
			return false;
		}else{
			return true;
		}
	});
});

$(document).ready(function () {

	//$("input[name=ReportitemSearch[price]]").attr('type','number');

});

$('#multilevel-opt-parent').change(function(){
	var getlevelval = $('#multilevel-opt-parent').val();
	$.ajax({
		type: 'POST',
		url: baseUrl + '/categories/getsublevel/',
		data: {
			parentlevel: getlevelval
		},
		success: function (data) {
			//alert(data);
			$('#subleveltag').html(data);

		}
	});
});

$('input#Categories_name').on('keypress', function (event) {
	//alert('hi');
    var regex = new RegExp("^[a-zA-Zء-ي& ]+$");   
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});

$('input#filter-name').on('keypress', function (event) {
	//alert('hi');
    //var regex = new RegExp("^[a-z]+$");
    var regex = new RegExp("^[a-zA-Zء-ي ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});




 function urgentPromotionVal() {

            var urgentVal = $("#urgentprice").val();
            if(isNaN(urgentVal)){
        
        $('.errorMessage').show();
        $(".errorMessage").html(yii.t('app','Price should be numeric'));
    
        return false;
    }
  
    
    }
	function adPromotionVal()
	{
	var adPriceVal = $("#Promotions_price").val();
            if(isNaN(adPriceVal)){
        
        $('#priceerr').show();
        $("#priceerr").html(yii.t('app','Price should be numeric'));
    
        return false;
    }
    

     var adDaysVal = $("#Promotions_days").val();
            if(isNaN(adDaysVal)){
        
        $('#dayserr').show();
        $("#dayserr").html(yii.t('app','Days should be numeric'));
    
        return false;
    }
 
  
	}

function Expand(obj){
      if (!obj.savesize) obj.savesize=obj.size;
      obj.size=Math.max(obj.savesize,obj.value.length);
     }