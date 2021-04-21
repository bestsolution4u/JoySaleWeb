<?php


use common\models\Categories;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\Request;


?>

    
           
    
<div class="form">

<?php $form = ActiveForm::begin(['options' => ['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validateCategory()']]); ?>
  <?php if(Yii::$app->controller->action->id== 'subadd') {?>
               <h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Add').' '.Yii::t('app','Subcategory'); ?></h4>
            <?php  } else {?>
              <h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Update').' '.Yii::t('app','Subcategory'); ?></h4>
              <?php } ?>
 
    <div class="form-group">
    <label for="Categories_name" class="required"><?php echo Yii::t('app','Subcategory').' '.Yii::t('app', 'Name'); ?> <span class="required">*</span></label>  
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'id' => 'Categories_name'])->label(false) ?>  
     <?php if(Yii::$app->controller->action->id!= 'subadd') {?>
    <div class="errorMessage" style="color:red;" id="Categories_name_em_"></div> <?php } ?>

    </div>
    <?= $form->field($model, 'parentCategory')->hiddenInput(['value' => $_GET['id']])->label(false) ?>  

    



<?php  if(isset($model->parentCategory) && $model->parentCategory == 0 || !isset($model->parentCategory)) { ?>


<div id="itemCondition" style="display:none;">

  <?= $form->field($model, 'itemCondition')->checkbox(array('value'=>1, 'uncheckValue'=>0)); ?>
    </div>
    <?php
    $sitepaymentmodes = yii::$app->Myclass->getSitePaymentModes();
    // if($sitepaymentmodes['exchangePaymentMode'] == "1")
    // {
    ?>
    <div id="exchangetoBuy" style="display:none;">
     <?= $form->field($model, 'exchangetoBuy')->checkbox(array('value'=>1, 'uncheckValue'=>0,'id'=>'exchangeMode')); ?>
    </div>
    <?php
    // }
    // if($sitepaymentmodes['buynowPaymentMode'] == "1")
    // {
    ?>
    <div id="buyNow" style="display:none;">
    <?= $form->field($model, 'buyNow')->checkbox(array('value'=>1, 'uncheckValue'=>0,'id'=>'buynowMode')); ?>
    </div>
    <?php
    // }   
    ?>
    
    <div id="myOffer" style="display:none;">
     <?= $form->field($model, 'myOffer')->checkbox(array('value'=>1, 'uncheckValue'=>0)); ?>
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
    <div id="exchangetoBuy" style="display:none;" >
     <?= $form->field($model, 'exchangetoBuy')->checkbox(array('value'=>1, 'uncheckValue'=>0,'id'=>'exchangeMode')); ?>
    </div>
    <?php
    }
    if($sitepaymentmodes['buynowPaymentMode'] == "1")
    {
    ?>
    <div id="buyNow" style="display:none;">
     <?= $form->field($model, 'buyNow')->checkbox(array('value'=>1, 'uncheckValue'=>0,'id'=>'buynowMode')); ?>
    </div>
    <?php
    }   
    ?>
    
    <div id="myOffer" style='display:none;'>
    <?= $form->field($model, 'myOffer')->checkbox(array('value'=>1, 'uncheckValue'=>0)); ?>
    </div>


     <?php } ?> 


    <div id="subcategoryVisible" style='display:none;'>
        <?php 
          $form->field($model, 'subcategoryVisible')->checkbox(array('label'=>Yii::t('app','Show in Header'),'value'=>1, 'uncheckValue'=>0));
        ?> 
    </div>   

    <div class="form-group" id="catImage">
    <label for="Categories_image">Choose Subcategory Attributes</label>
    <div>

    <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><a href="" data-sort="image">Options</a></th>
            <th><a href="" data-sort="image">Filter name</a></th>
            <th><a href="" data-sort="parentCategory">Filter type</a></th>
            <th style="width:15%;text-align:center;">Values</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($attributes as $key=>$value)
        {
            

            if(!empty($parentAttributes))
            {

                if(in_array($value->id, $parentAttributes))
                {
                    //$checkboxStatus = 'checked';
                    $inputStatus = 'disabled checked="checked"';
                }else{
                    //$checkboxStatus = '';
                    $inputStatus = '';
                }
            }else{
                $inputStatus = '';
            }

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
                   </td>
                <td><?php echo substr($value->name, 0, 30); ?></td>
                <td><?php echo ucfirst($value->type); ?></td>
                <td><?php echo str_replace(',', ' | ', ucfirst($value->value)); ?></td>
            </tr> 
            <?php
                }
            ?>
    
    </tbody>
    </table>
    
    </div>
    </div>
    

    <div class="form-group">
    <?php
    $getCatid = (isset($_GET['cat'])) ? $_GET['cat'] : $_GET['id'];
    ?>
   <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary']) ?>
   <?= Html::a(Yii::t('app','Cancel'), ['/categories/sub_subcategory/'.$getCatid], ['class'=>'btn btn-fw btn-danger']) ?>
    <?php ActiveForm::end(); ?>
</div>
<!-- form -->

                        </div>
                   



<script type="text/javascript">
    function validateCategor() {
    var name = $("#Categories_name").val();

    if (name == "") {
        $("#Categories_name_em_").show();
        $("#Categories_name_em_").html(yii.t('app',"Subcategory Name cannot be blank"));
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

    return true;
}
</script>

