<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use dosamigos\ckeditor\CKEditor;

use common\models\Filter;
use common\models\Filtervalues;
use common\models\Productfilters;
use common\models\Sitesettings;
error_reporting(0);
$sitesetting = yii::$app->Myclass->getSitesettings();

$givingawayStatus = $sitesetting->givingaway;
?>


 <?php $form = ActiveForm::begin(['id'=>'products-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validateProduct()']]); ?>
        <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
                <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Update').' '.Yii::t('app','Products'); ?></h4>
            </div>
            <div class="">
                <?php
                    $base_id=yii::$app->Myclass->safe_b64encode($model->productId.'-0');
                    if($model->soldItem == 1){ ?>
                    <button class='btn btn-success align-text-top border-0 m-b10' onClick="soldItemAdmin('<?php echo yii::$app->Myclass->safe_b64encode($model->productId.'-0') ?>',0)">
                        <input type="hidden" id="base_id" value="<?=$base_id?>">
                        <?=Html::a(Yii::t('app','Back to sale')); ?> 
                    </button> 
                <?php }else{ ?>
                    <button class='btn btn-info align-text-top border-0 m-b10' onClick="soldItemAdmin('<?php echo yii::$app->Myclass->safe_b64encode($model->productId.'-0') ?>',1)">
                        <input type="hidden" id="base_id" value="<?=$base_id?>">
                        <?=Html::a(Yii::t('app','Mark as sold')); ?> 
                    </button>  
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <?php
                if (isset($plen)) {?>
                <input type="hidden" name="count" id="count" value="<?php echo $plen; ?>">
                <?php }else{ ?>

                <input type="hidden" name="count" id="count" value="0">
                <?php }
            ?>
            <label class="m-b20"><?php echo Yii::t('app','Add photos of your stuff (Hover the images to remove)'); ?> </label><span class="required" style="color: red;"> * </span><br>
            <input class="m-b15 p-2 borderGrey w-100 dis_none" type="file" id="image_file" multiple="true" name="XUploadForm[file]" accept=".png, .jpg, .jpeg" onchange="start_image_upload();">
            <label tabindex="0" for="image_file" class="input-file-trigger align_middle m-r20"><div class="img_browse"><div class="add_img"></div></div></label>

            <?php if(!empty($photos)):
                    foreach ($photos as $photo) { 
                    echo '<div class="uploaded_img align_middle m-r20"><img src="'.Yii::$app->urlManagerfrontEnd->baseUrl.'/media/item/'.$model->productId.'/'.$photo->name.'"" class="img-responsive" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin:5px;"><button type="button" class="close post_img_cls" data-dismiss="modal" aria-label="Close" onclick="remove_images1(this,\''.$photo->name.'\',\''.$model->productId.'\')"><span aria-hidden="true">×</span></button></div>';
                    } 
                endif;
            ?>
            <div class="blog_images margin_bottom20" style=""></div>
            <input type="hidden" value='' name="uploadedfiles" id="uploadedfiles">
            <input type="hidden" name="removefiles" id="removefiles">
            <p class="text-danger" id="image_error"></p>
        </div>

           <div class="form-group">
            <label><?php echo Yii::t('app','Product Name'); ?> </label><span class="required" style="color: red;"> * </span>
            <?= $form->field($model, 'name')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter your product name'),'maxlength' => true,'id' => 'Products_name'])->label(false); ?>
            <p class="text-danger" id="Products_name_em_"></p>
        </div>
         <input type="hidden" name="productId" id="productId" value="<?php echo $model->productId; ?>">
                 <div class="form-group">
            <label><?php echo Yii::t('app','Description'); ?> </label><span class="required" style="color: red;"> * </span>
            <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                'options' => ['rows' => 3, 'id' => 'Products_description'],
                'preset' => 'basic'
            ])->label(false); ?>
            <p class="text-danger" id="Products_description_em_"></p>
        </div>
                <div class="form-group ">
            <label><?php echo Yii::t('app','Select Category'); ?> </label><span class="required" style="color: red;"> * </span>
                <?php if (!empty($parentCategory)){
                    echo Html::activeDropDownList($model, 'category',$parentCategory, ['prompt'=>Yii::t('app','Select Category'),'class'=>'form-control select-box-down-arrow','id'=>'Products_category']) ;
                    }else{
                    echo Html::activeDropDownList($model, 'category', ['prompt'=>Yii::t('app','Select Parent Category'),'class'=>'form-control select-box-down-arrow','id'=>'Products_category']) ;
                    }
                ?>
            <p class="text-danger" id="Products_category_em_"></p>
        </div>

    <div class="form-group ">
            <label><?php echo Yii::t('app','Select Subcategory'); ?> </label><span class="required" style="color: red;"> * </span>
                <?php if (!empty($subCategory)){
                    echo $form->field($model, 'subCategory')->dropDownList( 
                                $subCategory,
                            ['prompt'=>Yii::t('app','Select subcategory'),'class'=>'subcatid form-control','id'=>'Products_subCategory'])->label(false);

                    }else{
                    echo $form->field($model, 'subCategory')->dropDownList( 
                            $subCategory,
                            ['prompt'=>'Select subcategory','class'=>'subcatid form-control','id'=>'Products_subCategory'])->label(false);
                    }
                ?>
                <p class="text-danger" id="Products_subcategory_em_"></p>
        </div>








        <div id="showField">

            <?php //if (!empty($sub_subCategory)){ ?>
                <div class="form-group">

                <label class="Category-select-box-heading" for="Products_sub_subCategory" id="Products_sub_subCategory_head"><?=Yii::t('app','Select child category for ').ucfirst($sub_cat_name)?></label>			
                        
                        <?php echo $form->field($model, 'sub_subCategory')->dropDownList( 
                                        $sub_subCategory,
                                                    ['prompt'=>Yii::t('app','Select Subcategory'),'class'=>'form-control select-box-down-arrow','id'=>'Products_sub_subCategory'])->label(false);
                            
                    ?>
                    <p class="text-danger" id="Products_sub_subcategory_em_"></p>
                    </div>

            <?php //} ?>
            <div id="showsubfield">
              <?php

              //echo '<pre>'; print_r($attributes); exit;
        $options = '';
        $multilevelvalues = array();
        foreach($attributes as $key=>$val)
        {
            $filterModel = Filter::find()->where(['id'=>$val])->one();

            if(empty($filterModel))
                continue;
            //echo '<pre>'; print_r($filterModel); exit;
            if($filterModel->type == 'dropdown'){

                $filtervalueModel = Filtervalues::find()->where(['filter_id'=>$filterModel->id])->one();

                $options .='<div class="Category-select-box-row no-hor-padding">
                                <div class="form-group  no-hor-padding">';
                $options .= '<label class="Category-select-box-heading required" for="Products_category">'.ucfirst($filterModel->name).'</label>';
                $options.=' <select id="product_attributes_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" >';
                
                    $options .='<option value="">Select '.ucfirst($filterModel->name).'</option>';
                    $getchildvals = Filtervalues::find()->where(['parentid'=>$filtervalueModel->id, 'parentlevel'=>'1'])->all();

                    $getProductfilter = Productfilters::find()->where([
                        'product_id'=>$model->productId,
                        'filter_id'=>$filterModel->id,
                        'filter_type'=>'dropdown'
                        ])->one();
                    
                    foreach($getchildvals as $cData) {
                        //print_r($getProductfilter); exit;
                        $checkstatus = (isset($getProductfilter->level_two) && $getProductfilter->level_two == $cData->id) ? 'selected="selected"' : '';
                        $options .='<option value="'.$cData->id.'" '.$checkstatus.'>'.$cData->name.'</option>';
                    }
                
                $options .= '</select>';
                $options .= '<div class="text-danger product_attributes_'.$filterModel->id.' errorMessage"></div>';
                $options .= '</div>';
                $options .= '</div>';
            }elseif($filterModel->type == 'range')
            {
                $getFilterrange = Productfilters::find()->where([
                        'product_id'=>$model->productId,
                        'filter_id'=>$filterModel->id,
                        'filter_type'=>'range'
                        ])->one();

                $getminMax = explode(';', $filterModel->value);

                $fieldname = str_replace(' ', '_', strtolower($filterModel->id));
                $options .='<div class="form-group Category-input-box-row no-hor-padding location-container">';
                $options .= "<label class='Category-input-box-heading   no-hor-padding'>".ucfirst($filterModel->name)."</label>";
                $options.='<input type="number" min="'.$getminMax[0].'" max="'.$getminMax[1].'" id="product_attributes_'.$filterModel->id.'" class="form-control productattributerange" value="'.$getFilterrange['level_two'].'" name="Products[attributes]['.$fieldname.']"  placeholder = "values between '.$getminMax[0].' - '.$getminMax[1].'">';
                
                $options.= '<input type="hidden" id="product_attributes_'.$filterModel->id.'_values" class="form-control" value="'.$filterModel->value.'" name="range_values">';
                $options .= '<div class="text-danger product_attributes_'.$filterModel->id.' errorMessage"></div>';
                $options.= '<input type="hidden" id="'.$fieldname.'" value="'.$filterModel->value.'" />';
                $options.= '</div>';
            }elseif($filterModel->type == 'multilevel')
            {
                $getFiltermulti = Productfilters::find()->where([
                        'product_id'=>$model->productId,
                        'filter_id'=>$filterModel->id,
                        'filter_type'=>'multilevel'
                        ])->one();



                

                $getFiltervals = Filtervalues::find()->where(['filter_id'=>$filterModel->id,
                    'type'=>'multilevel'])->one();
                $getparentlevel = Filtervalues::find()->where(['parentid'=>$getFiltervals->id,
                    'parentlevel'=>'3'])->all();


                $options.='<div class="Category-select-box-row  no-hor-padding" id="multilevelss_'.$filterModel->id.'">
                                <div class="form-group no-hor-padding">';
                $options.= '<label class="Category-select-box-heading " for="Products_category">'.$filterModel->name.'</label>';
                $options.=' <select id="multilevel_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" onchange="getval(this);" >';
                $options.= '<option value="">Select parent option</option>';
                foreach($getparentlevel as $parentvalues)
                {
                    $checked = (isset($getFiltermulti->level_two) && $getFiltermulti->level_two == $parentvalues->id) ? 'selected="selected"' : '';
                    $options.= '<option value="'.$parentvalues->id.'" '.$checked.'>'.$parentvalues->name.'</option>';
                }
                $options.= '</select>';
                $options .= '<div class="text-danger multilevel_'.$filterModel->id.' errorMessage"></div>';
                $options.= '</div>';
                $options.='<div id="multilevel_'.$filterModel->id.'">';
                if(isset($getFiltermulti->level_two))
                {   
                    $loadFilter = Filtervalues::find()->where([
                    'parentid'=>$getFiltermulti->level_two,
                    'parentlevel'=>'4'])->all();

                //echo $getFiltermulti->level_two;
                //echo '<br/>';
                //echo '<pre>'; print_r($filterModel); exit;

                $options.= '<div class="Category-select-box-row  no-hor-padding childlevelattr '.$filterModel->id.'">
                                    <div class="form-group no-hor-padding '.$loadFilter[0]->parentid.'">';
                $options.=' <select id="childattribute_'.$loadFilter[0]->parentid.'" class="form-control productattributes" name="Products[attributes][multilevel]['.$loadFilter[0]->parentid.']" >';
                $options.= '<option value="">Select Child value</option>';
                foreach( $loadFilter as $key=>$value )
                {

                    $checked = ($getFiltermulti->level_three == $value->id) ? 'selected="selected"' : '';
                    $options.= '<option value="'.$value->id.'" '.$checked.'>'.$value->name.'</option>';
                }
                $options.= '</select>';
                $options.= '</div>';

                }
                
                $options.= '</div>';
                $options.='</div>';
                $options.='</div>';

            }
        }
        $options .= '';
        echo $options;
        ?>
    </div>
</div>

<?php   if($givingawayStatus =='yes') {?>

      <div class="form-group">
            <label><?php echo Yii::t('app','Giving away'); ?> </label>
            <?php if($model->price=="0"){$checked='checked';$value=1;}else{$checked='';$value=0;} ?>
            <div class="custom-control custom-switch" style="padding-left:3rem!important;">
                <input type="checkbox" class="custom-control-input" id="giving_away" name="Products[giving_away]"  value="<?php echo $value;?>" <?php echo $checked;?>>
                <label class="custom-control-label" for="giving_away"></label>
            </div>
        </div>
    <?php } ?>

     <?php if($model->price == 0 && $givingawayStatus =='yes') { 
            $price_box = "display: none;";
            } else {
                $price_box = "display: block;";
            }
        ?>

        <div class="form-group Category-price-box-row" style="<?php echo $price_box; ?>">
            <label><?php echo Yii::t('app','Price'); ?> </label><span class="required" style="color: red;"> * </span>

            <?= $form->field($model, 'price')->textInput(['class' => 'form-control','onkeypress'=>'return isNumberrKey(event)','maxlength' => true,'placeholder'=>Yii::t('app','Enter price'),'id'=>'Products_price','style'=>'margin:0; height:38px; display: inline-block; float: left;width:70%'])->label(false); ?>
            <?php $currencyList = yii::$app->Myclass->getCurrencyData();
            $hideCurrencyFlag = 0; ?>
              <select class="form-control select-box-down-arrow" id="sel1" name="Products[currency]" style="display: inline-block;width:30%">
              <?php foreach ($currencyList as $currency){
                $currencySelect = "";
                $currencyDetails = $currency->currency_symbol."-".$currency->currency_shortcode;
                if($model->currency == $currencyDetails)
                    $currencySelect = "selected";
                echo "<option $currencySelect value='$currencyDetails'>$currency->currency_shortcode</option>";
              }?>
              </select>
            
            <p class="text-danger" id="Products_price_em_"></p>
        </div>



  <div class="dynamicProperty "></div>

<?php
$sitesetting = yii::$app->Myclass->getSitesettings();
$paymentmode = json_decode($sitesetting->sitepaymentmodes,true);
//print_r($paymentmode);exit;
  $instantBuyDetails = "";
if($paymentmode['buynowPaymentMode'] == 1 )
{

    if($model->price != 0){

  $cate = yii::$app->Myclass->getCategoryDet($model->category);

              $buynow = Json::decode($cate->categoryProperty,true);
    if(!$model->isNewRecord){

        $instantBuyDetails = "";

        if($model->instantBuy == 1 && $buynow['buyNow'] == 'enable'){

            $instantBuyDetails = "style='display:block;'";
        }
        else
        {
              $instantBuyDetails = "style='display:none;'";
        }
    }else{

        //$userId = Yii::app()->user->id;
        //$model->paypalid = yii::$app->Myclass->getLastProductPaypalId($userId);
    }
}
else
{

     $instantBuyDetails = "style='display:none;'";

}

?>
        <?php }
        else{
             $instantBuyDetails = "style='display:none;'";
        } ?>

    <div class="form-group instant-buy-details" <?php echo $instantBuyDetails; ?>>
            <label><b><?php echo Yii::t('app','Instant buy details'); ?></b> </label><br>
           <!--  <label><?php echo Yii::t('app','Paypay Id'); ?> </label><span class="required" style="color: red;"> * </span>
            <?= $form->field($model, 'paypalid')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Paypay Id'),'id'=>'Products_paypalid'])->label(false); ?>
            <p class="text-danger" id="Products_paypalid_em_"></p>
            <span style="font-size:small;"><?=Yii::t('app','Note: This will be your default payment processing account')?></span> -->
        
        <?php
                if($paymentmode['buynowPaymentMode'] == 1) {
                    if($model->shippingCost == "")
            ?>
            <div class="form-group m-t20">
                <label><?php echo Yii::t('app','Shipping Cost'); ?> </label><span class="required" style="color: red;"> * </span>
                <?= $form->field($model, 'shippingCost')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Shipping Cost'),'id'=>'Products_shippingCost'])->label(false); ?>
                <?php } ?>
                <?php 
                if(isset($shipping_country_code) && $shipping_country_code != "") {
                    ?>
                    <input id="shippingcountry" type="hidden" name="Products[shippingcountry]" value="<?php echo $shipping_country_code; ?>">
                    <?php }else {?>
                    <input id="shippingcountry" type="hidden" name="Products[shippingcountry]" value="<?php echo $model->shippingcountry; ?>">
                    <?php }
                ?>
                <p class="text-danger" id="Products_shippingCost_em_"></p>
            </div>
        </div>


           <div class="form-group">
            <label><?php echo Yii::t('app','Where the item is located?'); ?> </label>   <span class="required" style="color: red;"> * </span>
            <input id="Products_location" class="form-control" type="text" placeholder="Tell where you sell the item" name="Products[location]" onchange="return resetLatLong()" value="<?php echo $model->location; ?>">
            <input id="latitude" type="hidden" name="Products[latitude]" value="<?php echo $model->latitude;?>"> 
            <input id="longitude" type="hidden" name="Products[longitude]" value="<?php echo $model->longitude;?>">
            <p class="m-t10" style="font-size:small;">
            <?=Yii::t('app',"Note: Select Location Only from Dropdown.Please don't enter manually.")?>
            </p>
            <p class="text-danger" id="Products_location_em_"></p>
        </div>

        <div class="form-group">
            <label><?php echo Yii::t('app','Video Url'); ?> </label><span class="required" style="color: red;"> * </span>
            <?= $form->field($model, 'videoUrl')->textInput(['name'=>'Products[videoUrl]', 'class' => 'form-control','placeholder'=>Yii::t('app','Enter video url'),'maxlength' => true,'id' => 'videoUrl'])->label(false); ?>
            <p class="text-danger" id="Products_name_em_"></p>
        </div>




<?php echo Html::submitButton(Yii::t('app','Save'),
									array('id'=>'addProduct','class'=>'btn btn-primary align-text-top border-0 m-b10','disabled'=>'disabled')); 

									?>

  <?php ActiveForm::end(); ?>

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
        url: "<?=Yii::$app->getUrlManager()->getBaseUrl()?>/products/productproperty",
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
                //$('#Products_subCategory').html(result[2]);
                productPropertyUpdate = 0;
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
       //  alert(country);
         $('#shippingcountry').val(country);
     }
     count--;
    }
    
    $("#latitude").val(latitude);
     $("#longitude").val(longitude);
// alert(latitude);
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

<style>
    .field-Products_price
    {
        margin-bottom: 0px;
    }

    .instant-buy-details {
     display: block; 
   }
    </style>