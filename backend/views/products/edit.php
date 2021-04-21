<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

                <div class="content">
                    <div class="row">
                        <div class="col-lg-12 userinfo">
                                                </div>
                    </div>

                
                <div class="container">
                <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Update Products </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Product 
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
<div class="form">
    <p class="note">
    Fields with
        <span class="required"> * </span>
        are required
    </p>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4" style="width: auto; padding: 0px; float: right;">
            <div class="edit-btn">
            <?php

$base_id=yii::$app->Myclass->safe_b64encode($model->productId.'-0');

//print_r($base_id);exit;
             if($model->soldItem == 1){ 

                ?>
                <input type="hidden" id="base_id" value="<?=$base_id?>">
                <a data-loading-text="Posting..." id="load" data-toggle="modal"
                    class="sold-btn sale-btn m-b-20" onClick="soldItemAdmin(0)">
                    <?php echo 'Back to sale'; ?>
                </a>
            <?php }else{ ?>
                <input type="hidden" id="base_id" value="<?=$base_id?>">
                <a data-loading-text="Posting..." id="load" data-toggle="modal"
                    class="sold-btn m-b-20" onClick="soldItemAdmin(1)">
                    <?php echo 'Mark as sold'; ?>
                
                </a>
            <?php } ?>
            </div>
        </div>
    </div>
    

      <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return validatebanner()'],'id'=>'products-form','enableAjaxValidation'=>true]); ?>


    <div>
    <style type="text/css">
        .img-wrap {
    position: relative;
    display: inline-block;
    
    font-size: 0;
}
.img-wrap .close {
  position: absolute;
    /* top: 2px; */
    right: 2px;
    z-index: 100;
    background-color: #FFF;
    padding: 4px;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    opacity: .5;
    text-align: center;
    font-size: 22px;
    line-height: 10px;
    /* border-radius: 50%; */
}
.img-wrap:hover .close {
    opacity: 1;
}
    </style>

<?php
//print_r($photos);exit;
      if(!empty($photos)):
                foreach ($photos as $photo) { ?>

        <div class="img-wrap">      
   <span class="close" onclick="delete_image(<?=$photo->photoId?>,<?=$photo->productId?>)">&times;</span>
                    <?php echo Html::img(Yii::getAlias('@web').'/uploads/'.$model->productId.'/'.$photo->name,['height' => '150','width' => '200']); ?>

                    </div>
<?php
                } 


    endif;?>


     <?= $form->field($models, 'name[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label(false); ?>



    </div>
 
    <div class="form-group">
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
    </div>

    <div class="form-group">
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    </div>


    <div class="form-group">
 <?php if (!empty($parentCategory)){
     echo Html::activeDropDownList($model, 'category',$parentCategory, ['prompt'=>'Select Category','class'=>'form-control select-box-down-arrow','id'=>'Products_category']) ;
    }else{
     echo Html::activeDropDownList($model, 'category', ['prompt'=>'Select Parent Category','class'=>'form-control select-box-down-arrow','id'=>'Products_category']) ;
    }
    ?>
    </div>

    <div class="form-group">
    <?php if (!empty($subCategory)){
     echo Html::activeDropDownList($model, 'subCategory',$subCategory, ['prompt'=>'Select subcategory','class'=>'subcatid form-control','id'=>'Products_subCategory']) ;
    }else{
     echo Html::activeDropDownList($model, 'subCategory', ['prompt'=>'Select subcategory','class'=>'subcatid form-control','id'=>'Products_subCategory']) ;
   }
    ?>
    </div>

    <div class="Category-give-away-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
        <div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom: 10px;">
        
            <div class="switch-box col-xs-6 col-sm-3 col-md-2 col-lg-2 no-hor-padding" style="width:50%;">
                <div class="switch-1 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

                <?php


                 if($model->price == "0")
                { ?>
                      <input id="giving_away" class="cmn-toggle cmn-toggle-round" name="giving_away" type="checkbox" value="1" checked="checked">
                <?php } else { ?>  
                      <input id="giving_away" class="cmn-toggle-1 cmn-toggle-round-1" name="giving_away" type="checkbox" value="0">
                <?php } ?>
                      <label for="giving_away"></label>
                </div>
            </div>
        </div>
    </div>

    <?php if($model->price == 0) { 
        $price_box = "display: none;";
    } else {
        $price_box = "display: block;";
    }?>

    <div class="form-group col-md-12 col-sm-12 Category-price-box-row no-hor-padding" style="<?php echo $price_box; ?>">
         <?= $form->field($model, 'price')->textInput(['class' => 'form-control','onkeypress'=>'return isNumberrKey(event)',
                    'style'=>'margin:0; height:38px; display: inline-block; float: left;width: 70%;','maxlength'=>"10"])->label(false); ?>
        
        <div class="currency-select-box-row col-xs-12 col-sm-2 col-md-3 col-lg-2" style="display: inline-block;width: 30%;">
            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
            <?php $currencyList = yii::$app->Myclass->getCurrencyData();
            $hideCurrencyFlag = 0; ?>
              <select class="form-control select-box-down-arrow" id="sel1" name="Products[currency]">
              <?php foreach ($currencyList as $currency){
                $currencySelect = "";
                $currencyDetails = $currency->currency_symbol."-".$currency->currency_shortcode;
                if($model->currency == $currencyDetails)
                    $currencySelect = "selected";
                echo "<option $currencySelect value='$currencyDetails'>$currency->currency_shortcode</option>";
              }?>
              </select>
            </div>
        </div>
        
    </div>

    <div class="dynamicProperty col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

                        <?php
                        $sitesetting = yii::$app->Myclass->getSitesettings();
                        $paymentmode = json_decode($sitesetting->sitepaymentmodes,true);
                        //print_r($paymentmode);exit;
                        if($paymentmode['buynowPaymentMode'] == 1)
                        {
                            if(!$model->isNewRecord){
                                $instantBuyDetails = "";
                                if($model->instantBuy == 1){
                                    $instantBuyDetails = "style='display:block;'";
                                }
                            }else{
                                //$userId = Yii::app()->user->id;
                                //$model->paypalid = yii::$app->Myclass->getLastProductPaypalId($userId);
                            }
                        ?>
                        <div class="form-group">
                        <div class="add-stuff-Category-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding instant-buy-details" <?php echo $instantBuyDetails; ?>>
                            <div class="add-stuff-Category-heading m-b-10">
                                <span>Instant buy details</span>
                            </div>

                                <div class="Category-input-box-row form-group m-b-30">
                                    <?= $form->field($model, 'paypalid')->textInput(['maxlength' => true,'class' => 'form-control', 'placeholder'=> 'Paypal Id']) ?>
                                    
                                    <span class="label-note col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">Note: This will be your default payment processing account.</span>
                                    
                                </div>
                                <?php
                                if($paymentmode['buynowPaymentMode'] == 1) {
                                    if($model->shippingCost == "" || $model->shippingCost == '0')
                                        $model->shippingCost = "";
                                ?>
                                <div class="Category-input-box-row form-group">
                                    <?= $form->field($model, 'shippingCost')->textInput(['class' => 'form-control', 'placeholder'=> 'Shipping Cost']) ?>
                                    
                                </div>
                                <?php } ?>
                                <input id="shippingcountry" type="hidden" name="Products[shippingcountry]" value="<?php $model->shippingcountry;?>">
                        </div>
                    </div>
                        <?php } ?>

    <div class="form-group">
    Where the item is located?
        <input id="Products_location" class="form-control" type="text"
            placeholder="Tell where you sell the item"
            name="Products[location]" onchange="return resetLatLong()"
            value="<?php echo $model->location; ?>"> <input id="latitude"
            type="hidden" name="Products[latitude]"
            value="<?php echo $model->latitude;?>"> <input id="longitude"
            type="hidden" name="Products[longitude]"
            value="<?php echo $model->longitude;?>">
        <p>
        Note: Select Location Only from Dropdown.Please don't enter manually.
        </p>
        <div class="errorMessage" id="Products_location_em_"></div>
    </div>

    <div class="form-group">
     <?= Html::submitButton('Save', ['class' => 'btn btn-success btnUpdate']) ?>
    <br><br><br>

      <?php ActiveForm::end(); ?>

</div>
</div>
                    </div><!-- /.row (nested) -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->
</div><!-- /#page-wrapper -->               </div>
                <div class="clear"></div>
                    <footer class="footer text-right">
                        2016 © .
                    </footer>
                </div>
<br>
    <?php $id = yii::$app->Myclass->safe_b64encode($model->productId.'-0'); ?>
 <script>
var shippingArray = new Array();

<?php
//print_r($photos);exit;

 if (isset($jsShippingDetails) && $jsShippingDetails != ''){ ?>
shippingArray = [<?php echo $jsShippingDetails; ?>];
<?php } ?>
<?php if (!$model->isNewRecord){ ?>

window.onload = function() {

    productId = "<?php echo $model->productId; ?>";

    $.getJSON('<?php echo Yii::$app->urlManager->createUrl("upload", array("_method" => "list", "id" => $model->productId)); ?>', function (result) {
        var objForm = $('#products-form');
        if (result && result.length) {
            objForm.fileupload('option', 'done').call(objForm, null, {result: result});
            productImage = parseInt(result.length);
            console.log("In product append: "+productImage);
        }
    });
    var selectedCategory = $('#Products_category').val();
    var giving_away = $("#giving_away").val();
    console.log('Products_category on change call');
    $.ajax({
        url: "http://localhost/joysale_website/admin/products/productproperty",
        type: "post",
        data: {'selectedCategory':selectedCategory, 'givingAway':giving_away, 'productId': productId},
        dataType: "html",
        success: function(responce){
            //alert(responce);
            responce = responce.trim();
            var result = jQuery.parseJSON(responce);
            //console.log("Responce string: "+responce);
            if(result[1] == ""){
                $('.dynamicProperty').html("");
                $('.dynamic-section').hide();
            }else{
                $('.dynamicProperty').html(result[1]);
                $('.dynamic-section').show();
                //alert(result[1]);
            }
        }
    });
}
<?php } ?>
</script> 
<script>



</script>

<script>

$("#showMore").hide();
function changeCurDiv(cur,code) {
  $("#cur").html(cur+' <span class="caret"></span>');
  $("#showMore").hide();
  $("#currency").val(cur+'-'+code);
}
function showMore() {
    $("#showMore").show();
}

 function initMap() {
       
      var autocomplete = new google.maps.places.Autocomplete((document
            .getElementById('Products_location')), {
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
    
    $("#latitude").val(latitude);
     $("#longitude").val(longitude);

    });

    
      }

//google.maps.event.addDomListener(window, 'load', initialize);
</script>

<?php
$siteSettings = yii::$app->Myclass->getSitesettings();
//print_r($siteSettings->googleapikey);exit;
if(!empty($siteSettings) && isset($siteSettings->googleapikey) && $siteSettings->googleapikey!="")
$googleapikey = "&key=".$siteSettings->googleapikey;
else
$googleapikey = "";
?>
   <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapikey; ?>&libraries=places&callback=initMap&language=en"
        async defer></script>
