


//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {

    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    })
})

/*$(document).on('change','#Banners_bannerimage', function(){
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
            if(this.width == "1140" && this.height == "325")
            {
            	$("#bannerimageerr").html("dfds");
            	$("#bannercreatebtn").removeAttr("disabled");
            	//$("#Banners_bannerimage_wrap_list").show();
            }
            else
            {
            	$("#bannerimageerr").html("Image size should be 1140 x 325");
						setTimeout(function() {
							$("#bannerimageerr").html("");
						}, 3000);
            	$("#Banners_bannerimage_wrap_list").html("");
            	$(".MultiFile").removeAttr("disabled");
            	$("#bannercreatebtn").attr("disabled","true");
            }
        };
    }
});*/




/*$(document).on('change','#Sitesettings_bannerstatus', function(){
	if ($('#Sitesettings_bannerstatus').is(':checked')) {
		enablestatus = "1";
	}
	else
	{
		enablestatus = "0";
	}
		$.ajax({
			url: yii.urls.base + '/admin/banners/bannerenable',
			type: "post",
			data: {'enablestatus':enablestatus},
			dataType: "html",
			success: function(responce){

			}
		});
}); */
function validate_defaultsetting()
{
       var googleapikey = $("#Sitesettings_googleapikey").val();
           if (googleapikey == "") {
        $("#Sitesettings_googleapikey_em_").show();
        $("#Sitesettings_googleapikey_em_").html(yii.t('app', "Google api key") + ' '
            + yii.t('app', "cannot be blank"));
        $("#Sitesettings_googleapikey").focus();
        return false;
    } else {
        $('#Sitesettings_googleapikey_em_').hide();
    }
    return true;
}

	

function validate_logos()	{	

		var logo = $("#logo").val();	
		var dlogo = $("#darklogo").val();	
		var userimage = $("#userimage").val();	
		var favicon = $("#favicon").val();

        var name = $("#Sitesettings_sitename").val();
    var googleapikey = $("#Sitesettings_googleapikey").val();

    if (name == "") {
        $("#Sitesettings_sitename_em_").show();
        $("#Sitesettings_sitename_em_").html(yii.t('app', "Site Name") + ' '
            + yii.t('app', "cannot be blank"));
        $("#Sitesettings_sitename").focus();
        return false;
    } else {
        $('#Sitesettings_sitename_em_').hide();
    }
    if (googleapikey == "") {
        $("#Sitesettings_googleapikey_em_").show();
        $("#Sitesettings_googleapikey_em_").html(yii.t('app', "Google api key") + ' '
            + yii.t('app', "cannot be blank"));
        $("#Sitesettings_googleapikey").focus();
        return false;
    } else {
        $('#Sitesettings_googleapikey_em_').hide();
    }
    if (specials.test(name)) {
        $("#Sitesettings_sitename_em_").show();
        $('#Sitesettings_sitename_em_').text(yii.t('app', 'Special Characters not allowed.'));
        return false;
    } else {
        $('#Sitesettings_sitename_em_').hide();
    }
		//alert(userimage);
		
if (logo != "") {
		var fileInput = document.getElementById('logo');
		
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
    if(!allowedExtensions.exec(filePath)){
      $('#Sitesettings_logo_em_').html(yii.t('app', "Upload only image file"));
        fileInput.value = '';
        
        return false;

			}
}
if (dlogo != "") {

			var fileInput1 = document.getElementById('darklogo');
		
    var filePath1 = fileInput1.value;
    var allowedExtensions1 = /(\.jpeg|\.jpg|\.png)$/i;
    if(!allowedExtensions1.exec(filePath1)){
      $('#Sitesettings_logoDarkVersion_em_').html(yii.t('app', "Upload only image file"));
        fileInput1.value = '';
      
        return false;

			}
}
if (userimage != "") {
	var fileInput2 = document.getElementById('userimage');
		
    var filePath2 = fileInput2.value;
    var allowedExtensions2 = /(\.jpeg|\.jpg|\.png)$/i;
    if(!allowedExtensions2.exec(filePath2)){
      $('#Sitesettings_default_userimage_em_').html(yii.t('app', "Upload only image file"));
        fileInput2.value = '';
       
        return false;

			}
}

		if (favicon != "") {
				var fileInput3 = document.getElementById('favicon');
		
    var filePath3 = fileInput3.value;
    var allowedExtensions3 = /(\.jpeg|\.jpg|\.png)$/i;
    if(!allowedExtensions3.exec(filePath3)){
      $('#Sitesettings_favicon_em_').html(yii.t('app', "Upload only image file"));
        fileInput3.value = '';
      
        return false;

			}
		}	
		
		return true;	
	
    }
    function validateCategory()
    {
             var name = $("#Categories_name").val();
    

    if (name == "") {
        $("#Categories_name_em_").show();
        $("#Categories_name_em_").html(yii.t('app', "Name cannot be blank."));
        $("#Categories_name_em_").focus();
        return false;
    } else {
        $('#Categories_name_em_').hide();
    }
    return true;
    }

    
function validate_categorylogos()	{	

    var image_file = $("#image_file").val();	
    //alert(userimage);   
if (image_file != "") {
    var fileInput = document.getElementById('image_file');
    
var filePath = fileInput.value;
var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
if(!allowedExtensions.exec(filePath)){
  $('#Sitesettings_logo_em_').html(yii.t('app', "Upload only image file"));
    fileInput.value = '';
    
    return false;

        }
}




    
    return true;	

}


function check_details() {
        var price=$('#urgentprice').val();
        if (price == '') {
            $('#urgentpriceError').html(yii.t('app','Price should not be null'));
            return false;
        }
        if (price == 0) {
            $('#urgentpriceError').html(yii.t('app','Price should be greater than zero'));
            return false;
        }
          if (!$.isNumeric(price))
          {
             
            $('#urgentpriceError').html(yii.t('app','Price should be numeric'));
            return false;
        
          }
        return true;
    }


function validateCurrencyData() {

    var shortcode = $('#curshortcode').val();
    var currencymerchantid = $('#currencymerchantid').val();
    var paymenttype = $('#paymenttype').val();
    //alert(paymenttype);return false;
    var currencyname = $('#currencyname').val();
    var currencysymbol = $('#currencysymbol').val();   
    var currencymode = document.querySelector('input[name="Currencies[currency_mode]"]:checked').value;
    var currencyposition = document.querySelector('input[name="Currencies[currency_position]"]:checked').value;



    if($.trim(shortcode) == "") {
        $('.currencySCErr').show();
        $('.currencySCErr').html(yii.t('app','Shortcode cannot be blank'));
        $('.currencySCErr').fadeIn();
        setTimeout(function() {
            $(".currencySCErr").fadeOut();
        }, 3000);
        return false;
    }
    if(paymenttype == 1)
    {
        if($.trim(currencymerchantid) == "") {
        $('.currencyErr').show();
        $('.currencyErr').html(yii.t('app','Braintree Merchant Id') +' '+ yii.t('app','cannot be blank'));
        setTimeout(function() {
            $(".currencyErr").fadeOut();
        }, 3000);
        return false;
        }
    }
    

     if($.trim(currencymode) == "") {
        $('.currencymodeErr').show();
        $('.currencymodeErr').html(yii.t('app','Please select currency mode'));
        setTimeout(function() {
            $(".currencymodeErr").fadeOut();
        }, 3000);
        return false;
    }
     if($.trim(currencyposition) == "") {
        $('.currencypositionErr').show();
        $('.currencypositionErr').html(yii.t('Please select the position of currency'));
        setTimeout(function() {
            $(".currencypositionErr").fadeOut();
        }, 3000);
        return false;
    }

    return true;
}


function validate_details() {
                        var mer_id=$('#mer_id').val();
                        var pub_id=$('#public_key').val();
                        var pri_id=$('#private_id').val();
                        if(mer_id == "")
                        {
                             $('#Sitesettings_brainTreeMerchantId_em_').show();
                            $('#Sitesettings_brainTreeMerchantId_em_').html(yii.t('app','Merchant key')+' '+ yii.t('app','cannot be blank'));
                            $('#Sitesettings_brainTreeMerchantId_em_').fadeIn();
                            setTimeout(function() {
                            $("#Sitesettings_brainTreeMerchantId_em_").fadeOut();
                            }, 3000);
                            $('#mer_id').keydown(function () {
                            $("#Sitesettings_brainTreeMerchantId_em_").fadeOut();
                             });
                            return false;
                        }
                        if(pub_id == "")
                        {
                             $('#Sitesettings_brainTreePublicKey_em_').show();
                            $('#Sitesettings_brainTreePublicKey_em_').html(yii.t('app','Public key')+' '+ yii.t('app','cannot be blank'));
                            $('#Sitesettings_brainTreePublicKey_em_').fadeIn();
                            setTimeout(function() {
            $("#Sitesettings_brainTreePublicKey_em_").fadeOut();
        }, 3000);
                            $('#public_key').keydown(function () {
            $('#Sitesettings_brainTreePublicKey_em_').fadeOut();
        });
                            return false;
                        }
                        if(pri_id == "")
                        {
                             $('#Sitesettings_brainTreePrivateKey_em_').show();
                            $('#Sitesettings_brainTreePrivateKey_em_').html(yii.t('app','Private key')+' '+ yii.t('app','cannot be blank'));
                            $('#Sitesettings_brainTreePrivateKey_em_').fadeIn();
                            setTimeout(function() {
            $("#Sitesettings_brainTreePrivateKey_em_").fadeOut();
        }, 3000);
                            $('#private_id').keydown(function () {
            $('#Sitesettings_brainTreePrivateKey_em_').fadeOut();
        });
                            return false;
                        }
                        return true;
                    }