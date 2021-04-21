/* profile upload start*/
$(document).ready(function(){
    // Prepare the preview for profile picture
        $("#logo").change(function(){
            var logo = $("#logo").val();
                if (logo != "") {
                    var fileInput = document.getElementById('logo');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#Sitesettings_logo_em_').show();
                        $('#Sitesettings_logo_em_').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
                $('#Sitesettings_logo_em_').hide();
                logoreadURL(this);
                
        });

        $("#darklogo").change(function(){
            var darklogo = $("#darklogo").val();
                if (darklogo != "") {
                    var fileInput = document.getElementById('darklogo');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#Sitesettings_logoDarkVersion_em_').show();
                        $('#Sitesettings_logoDarkVersion_em_').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
            $('#Sitesettings_logoDarkVersion_em_').hide();
            darklogoreadURL(this);
        });

        $("#favicon").change(function(){
            var logo = $("#favicon").val();
                if (logo != "") {
                    var fileInput = document.getElementById('favicon');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#Sitesettings_favicon_em_').show();
                        $('#Sitesettings_favicon_em_').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
            $('#Sitesettings_favicon_em_').hide();
            faviconreadURL(this);
        });

          $("#watermark").change(function(){
            var logo = $("#watermark").val();
                if (logo != "") {
                    var fileInput = document.getElementById('watermark');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#Sitesettings_watermark_em_').show();
                        $('#Sitesettings_watermark_em_').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }

                var reader = new FileReader();
                reader.readAsDataURL(fileInput.files[0]);
            reader.onload = function (e) {
              var image = new Image();
               image.src = e.target.result;
                 image.onload = function () {
                    var height = this.height;
                    var width = this.width;
                   if(width==200 || height==200)
                   {
                    return true;
                   }
                   else
                   {
                     $('#Sitesettings_watermark_em_').show();
                        $('#Sitesettings_watermark_em_').html(yii.t('app', "Upload image with specified width & heigth"));
                        fileInput.value = '';
                        return false;
                   }
                 } 
            }

            $('#Sitesettings_watermark_em_').hide();
            watermarkreadURL(this);
        });

                   $("#ad_image").change(function(){
            var logo = $("#ad_image").val();
                if (logo != "") {
                    var fileInput = document.getElementById('ad_image');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#Sitesettings_ad_image_em_').show();
                        $('#Sitesettings_ad_image_em_').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
            $('#Sitesettings_ad_image_em_').hide();
            adimagereadURL(this);
        });

        $("#userimage").change(function(){
                var logo = $("#userimage").val();
                    if (logo != "") {
                        var fileInput = document.getElementById('userimage');
                        var filePath = fileInput.value;
                        var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                        if(!allowedExtensions.exec(filePath)){
                            $('#Sitesettings_default_userimage_em_').show();
                            $('#Sitesettings_default_userimage_em_').html(yii.t('app', "Upload only image file"));
                            fileInput.value = '';
                            return false;
                        }
                    }
                $('#Sitesettings_default_userimage_em_').hide();
            userreadURL(this);
        });

                $("#productimage").change(function(){
                var logo = $("#productimage").val();
                    if (logo != "") {
                        var fileInput = document.getElementById('productimage');
                        var filePath = fileInput.value;
                        var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                        if(!allowedExtensions.exec(filePath)){
                            $('#Sitesettings_default_productimage_em_').show();
                            $('#Sitesettings_default_productimage_em_').html(yii.t('app', "Upload only image file"));
                            fileInput.value = '';
                            return false;
                        }
                    }
                $('#Sitesettings_default_productimage_em_').hide();
            productreadURL(this);
        });

        $("#catImagee").change(function(){
            var logo = $("#catImagee").val();
                if (logo != "") {
                    var fileInput = document.getElementById('catImagee');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#Categories_image_em_').show();
                        $('#Categories_image_em_').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
                $('#Categories_image_em_').hide();
            catreadURL(this);
        });

        $("#Banners_bannerimage").change(function(){
            var logo = $("#Banners_bannerimage").val();
                if (logo != "") {
                    var fileInput = document.getElementById('Banners_bannerimage');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#bannerimageerr').show();
                        $('#bannerimageerr').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
                $('#bannerimageerr').hide();
                bannerreadURL(this);
        });

        $("#Banners_appbannerimage").change(function(){
            var logo = $("#Banners_appbannerimage").val();
                if (logo != "") {
                    var fileInput = document.getElementById('Banners_appbannerimage');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#appbannerimageerr').show();
                        $('#appbannerimageerr').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
                $('#appbannerimageerr').hide();
                appbannerreadURL(this);
        });
    });


      $("#catImagee").change(function(){
            var logo = $("#catImagee").val();
                if (logo != "") {
                    var fileInput = document.getElementById('catImagee');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
                    if(!allowedExtensions.exec(filePath)){
                        $('#Categories_image_em_').show();
                        $('#Categories_image_em_').html(yii.t('app', "Upload only image file"));
                        fileInput.value = '';
                        return false;
                    }
                }
                $('#Categories_image_em_').hide();
                catimagereadURL(this);
                
        });
      
    function catimagereadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                
                    $('#catimagePreview').attr('src', e.target.result).fadeIn('slow');
               
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function logoreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                
                    $('#logoPreview').attr('src', e.target.result).fadeIn('slow');
               
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
 
    function darklogoreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#darklogoPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
 
    function faviconreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#faviconPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
       function watermarkreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#watermarkPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

      function adimagereadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#adimagePreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
 
    function userreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#userPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

       function productreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#productPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function catreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#catPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function bannerreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#bannerPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function appbannerreadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#appbannerPreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


  /* profile upload end*/
$("#country_selector").countrySelect({
    // defaultCountry: "jp",
    // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
    // responsiveDropdown: true,
    preferredCountries: ['ca', 'gb', 'us']
});

$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});
