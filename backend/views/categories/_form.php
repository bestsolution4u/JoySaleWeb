<?php
use common\models\Categories;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\Request;
?>

   
           
    
<div class="form">

<?php $form = ActiveForm::begin(['options' => ['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validateCategory()']]); ?>
        <?php if(Yii::$app->controller->action->id== 'create') {?>
               <h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Add').' '.Yii::t('app','Category'); ?></h4>
            <?php  } else {?>
              <h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Update').' '.Yii::t('app','Category'); ?></h4>
              <?php } ?>
  
 
    <div class="form-group">
    <label for="Categories_name" class="required"><?php echo Yii::t('app', 'Name'); ?> <span class="required">*</span></label>  
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'id' => 'Categories_name'])->label(false) ?>  
    <div class="errorMessage" id="Categories_name_em_"></div>

    </div>

        <div class="form-group" id="catImage">
                <label><?php echo Yii::t('app','Image') ?> </label><span class="required" style="color: red;"> * </span>
                <input id="hiddenImage" type="hidden" value="<?php echo $model->image;?>" name="<?php echo $model->image;?>" /> 
                <?= $form->field($model, 'image')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'catImagee'])->label(false); ?>
                 <?php if(!empty($model->image)):
                    echo  Html::img(Yii::getAlias('@web').'/uploads/'.$model->image, array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'));


                endif;?>

                <img src="" class="borderCurve borderGradient picture-src dnone" id="catimagePreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
                
                <p class="text-danger m-t20" id="Categories_image_em_"></p>
            </div>




<div class="form-group" id="catImage">
    <label for="Categories_image">Choose Categgory attributes</label>
    <div style="overflow: scroll; height: 50em; "> 
    <?php
        //print_r($attributes); 
        //exit;
    /*
        foreach($attributes as $key=>$value)
        {
            if(!empty($model->categoryAttributes))
            {
                $splitarray = explode(',', $model->categoryAttributes);
                if(in_array($value->id, $splitarray))
                {
                    $checkboxStatus = 'checked';
                }else{
                    $checkboxStatus = '';
                }
            }

            ?>
            <input type="checkbox" <?php echo $checkboxStatus; ?> name="attributes[]" value="<?php echo $value->id; ?>" id="attributes">
            <?php
            echo $value->name.'('.$value->type.'- '.$value->value.')'.'<br/>';
        }
        */
    ?>



    <table class="table">
    <thead>
        <tr>
            <th>Options</th>
            <th style="text-align:left; word-break: initial; " >Filter name</th>
            <th style="text-align:left; word-break: initial; ">Filter type</th>
            <th style="width:15%;text-align:center;">Values</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($attributes as $key=>$value)
        {
            if(!empty($model->categoryAttributes))
            {
                $splitarray = explode(',', $model->categoryAttributes);
                if(in_array($value->id, $splitarray))
                {
                    $checkboxStatus = 'checked="checked"';
                }else{
                    $checkboxStatus = '';
                }
            }
            //echo '<pre>'; print_r($parentAttribute); exit;
            if(!empty($parentAttribute))
            {
                if(in_array($value->id, $parentAttribute))
                {
                    $inputStatus = 'disabled';
                }else{
                    $inputStatus = '';
                }
            }else{
                $inputStatus = '';
            }
    ?>
            <tr data-key="55">
                <td>


                        <div class="form-group ">
                        <div class="m-b20 d-flex">
                            <div class="m-r50">
                                <div class="custom-control custom-checkbox">
                               
                                    <input type="checkbox" class="custom-control-input" name="attributes[]" id="<?php echo $value->id; ?>" value="<?php echo $value->id; ?>" <?php echo $checkboxStatus.' '.$inputStatus ; ?>>
                                    <label class="custom-control-label" for="<?php echo $value->id; ?>"></label>
                                </div>
                            </div>
                        </div>
                    </div>

   
      
               
                <td style="text-align:left; word-break: initial;" ><?php echo substr($value->name, 0, 30); ?></td>
                <td style="text-align:left; word-break: initial;"  ><?php echo ucfirst($value->type); ?></td>
                <td><?php echo str_replace(',', ' | ', ucfirst($value->value)); ?></td>
            </tr> 
            <?php
                }
            ?>
    
    </tbody>
    </table>

    </div>
    </div>


      


<?php if(isset($model->parentCategory) && $model->parentCategory == 0 || !isset($model->parentCategory)) { ?>
<label>Extras:</label>

<div id="itemCondition">


  <div class="form-group ">
            <div class="m-b20 d-flex">
                <div class="m-r50">
                    <div class="custom-control custom-checkbox">
                      
                        <input type="checkbox" class="custom-control-input" name="Categories[itemCondition]" id="categories-itemcondition" value="1" <?php if($model->itemCondition == '1')echo 'checked'?>>
                        <label class="custom-control-label" for="categories-itemcondition"><?=Yii::t('app', 'Product').' '.Yii::t('app', 'Conditions')?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $sitepaymentmodes = yii::$app->Myclass->getSitePaymentModes();
     if($sitepaymentmodes['exchangePaymentMode'] == "1")
     {
    ?>
    <div id="exchangetoBuy">
          <div class="form-group ">
            <div class="m-b20 d-flex">
                <div class="m-r50">
                    <div class="custom-control custom-checkbox">
                      
                        <input type="checkbox" class="custom-control-input" name="Categories[exchangetoBuy]" id="exchangeMode" value="1" <?php if($model->exchangetoBuy == '1')echo 'checked'?>>
                        <label class="custom-control-label" for="exchangeMode"><?=Yii::t('app', 'Exchange To ').' '.Yii::t('app', 'Buy')?></label>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    <?php
     }
    if($sitepaymentmodes['buynowPaymentMode'] == "1")
     {
    ?>
    <div id="buyNow">

          <div class="form-group ">
            <div class="m-b20 d-flex">
                <div class="m-r50">
                    <div class="custom-control custom-checkbox">
                      
                        <input type="checkbox" class="custom-control-input" name="Categories[buyNow]" id="buynowMode" value="1" <?php if($model->buyNow == '1')echo 'checked'?>>
                        <label class="custom-control-label" for="buynowMode"><?=Yii::t('app', 'Buy').' '.Yii::t('app', 'Now')?></label>
                    </div>
                </div>
            </div>
        </div>
   
    </div>
    <?php
     }   
    ?>
    
    <div id="myOffer">
        <div class="form-group ">
            <div class="m-b20 d-flex">
                <div class="m-r50">
                    <div class="custom-control custom-checkbox">
                      
                        <input type="checkbox" class="custom-control-input" name="Categories[myOffer]" id="myOffermode" value="1" <?php if($model->myOffer == '1')echo 'checked'?>>
                        <label class="custom-control-label" for="myOffermode"><?=Yii::t('app', 'My').' '.Yii::t('app', 'Offer')?></label>
                    </div>
                </div>
            </div>
        </div>
  
    </div>
    <?php }
     else { ?>
        
    <div id="itemCondition" style='display:none;'>
    <?= $form->field($model, 'itemCondition')->checkbox(array('value'=>1, 'uncheckValue'=>0)); ?>
    </div>
    <?php
    $sitepaymentmodes = yii::$app->Myclass->getSitePaymentModes();
    if($sitepaymentmodes['exchangePaymentMode'] == "1")
    {
    ?>
    <div id="exchangetoBuy" >
     <?= $form->field($model, 'exchangetoBuy')->checkbox(array('value'=>1, 'uncheckValue'=>0,'id'=>'exchangeMode')); ?>
    </div>
    <?php
    }
    if($sitepaymentmodes['buynowPaymentMode'] == "1")
    {
    ?>
    <div id="buyNow">
     <?= $form->field($model, 'buyNow')->checkbox(array('value'=>1, 'uncheckValue'=>0,'id'=>'buynowMode')); ?>
    </div>
    <?php
    }   
    ?>
    
    <div id="myOffer" style='display:none;'>
    <?= $form->field($model, 'myOffer')->checkbox(array('value'=>1, 'uncheckValue'=>0)); ?>
    </div>


    <?php } ?>  

    <?php if($model->parentCategory!=0){  $show='block';} else { $show='none';}?>
        <div id="subcategoryVisible" style='display:<?php echo $show;?>;'>
    <?php 
    
    echo $form->field($model, 'subcategoryVisible')->checkbox(array('label'=>Yii::t('app','Show in Header'),'value'=>1, 'uncheckValue'=>0));
  
    ?>
 
    </div>


    <div class="form-group" style="display: none;">
        <label class="control-label" for="filter-type">Multi levels</label>
        <select id="multilevel-opt-parent" class="form-control" name="Filter[type]" aria-required="true">
        <option value="">Select options</option>
        <?php
            //foreach($multilevel as $levelkey=>$levelvalue)
            //{
              //  echo '<option value="'.$levelvalue->id.'">'.$levelvalue->name.'</option>';
            //} 
        ?>
        </select>
    </div>

    <div id="subleveltag">
        
    </div>
    <div class="form-group">
   <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary']) ?>
   <?= Html::a(Yii::t('app','Cancel'), ['/categories/cancel'], ['class'=>'btn btn-fw btn-danger']) ?>
    <?php ActiveForm::end(); ?>
</div>
<!-- form -->

                        </div>
                   

<script>
document.getElementById('catImage').style.display="block";
  //  $("#catImage").show();
 //  alert(0);
    if (document.getElementById('dropCat').value != "") {
        document.getElementById('catImage').style.display="none";
    } else {
       document.getElementById('catImage').style.display="block";
    }
</script>


<script type="text/javascript">
    function validateCategory() {
    var name = $("#Categories_name").val();
    var image = $("#hiddenImage").val();
    var hidImage = $("#catImagee").val();

    if (hidImage != "") {
     
        var fileInput = document.getElementById('catImagee');
    
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpeg|\.jpg|\.png)$/i;
        if(!allowedExtensions.exec(filePath)){
          $('#Categories_image_em_').html(yii.t('app',"Upload only image file"));
            fileInput.value = '';
            
            return false;

        }
    }


   // alert(hidImage);exit;
    if (name == "") {
        $("#Categories_name_em_").show();
        $("#Categories_name_em_").html(yii.t('app',"Name cannot be blank"));
        $('#Categories_name').focus()
        $('#Categories_name').keydown(function () {
            $('#Categories_name_em_').hide();
        });
        return false;
    } else {
        name = name.replace(/\s{2,}/g, ' ');
        $('#Categories_name').val(name);
        $('#Categories_name_em_').hide();
    }

    //if ($("#dropCat").val() == 0) {
        if (hidImage == "" && image == "") {
            $("#Categories_image_em_").show();
            $("#Categories_image_em_").html(yii.t('app',"Image cannot be blank"));
            $('#catImagee').focus()
            $('#catImagee').keydown(function () {
                $('#Categories_image_em_').hide();
            });
        
            return false;
        }
   // }
    return true;
}
</script>


<?php if($sitepaymentmodes['exchangePaymentMode'] == "0") { ?>
<script>

    document.getElementById("exchangeMode").disabled = true;

</script>
<?php } ?>

<?php if($sitepaymentmodes['buynowPaymentMode'] == "0") { ?>
<script>

    document.getElementById("buynowMode").disabled = true;

</script>
<?php } ?>     
