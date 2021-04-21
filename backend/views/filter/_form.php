<?php
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   
   /* @var $this yii\web\View */
   /* @var $model common\models\Filter */
   /* @var $form yii\widgets\ActiveForm */
   $siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = $siteSettings->sitename;
$this->params['breadcrumbs'][] = $this->title;
   ?>
<div class="filter-form">
   <?php $form = ActiveForm::begin(['id'=>'filter_submit','options' =>['class' => 'boxShadow p-3 bgWhite m-b20 filterform'], 'action'=>Yii::$app->urlManager->createUrl(['filter/addfilter'])]); ?>
 
     <h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Add').' '.Yii::t('app','Filter'); ?></h4>
  
   <?= $form->field($model, 'name')->label('Label name')->textInput(['maxlength' => 18]) ?>
   <div class="help-block-name errormessage"></div>
   <?php
      echo $form->field($model, 'type')->label('Label type')->dropDownList(
                  [ 'dropdown' => 'Dropdown', 'range' => 'Range','multilevel'=> 'Multi level']
          ); ?>
   

   <input type="hidden" name="filterval" id="filterval" value="<?php echo (isset($model->value) && $model->value != '') ? $model->value : 'null'; ?>" />
   <div class="form-group" id="dropdownsection">
      
   </div>

   <div class="form-group" id="multilevelsection" style="display: none;">
    <div style="float: right;">
            <a href="javascript:void(0);" id="addlevel">Add</a>
            
            <!-- a href="javascript:void(0);" id="resetlevel">&nbsp; Reset</a --> 
    </div>

    
    <div class="form-group field-filter-name required">
        <label class="control-label" for="filter-type">Values</label>
        <input type="text"  name="Filter[dynamic][parent][]" maxlength="18" id="parent" placeholder="parent level" class="field form-control multilevel" value="" />
        <input type="text" onkeyup="Expand(this);" name="Filter[dynamic][child][]" id="child" placeholder="child level" class="form-control field" value="" data-role="tagsinput" />
        <div id=""></div>
    </div>
    <div class="inputs form-group field-filter-name required"></div>

    <div class="help-block-multilevelvalues"></div>
   </div>
   <div id="rangesection" style="display: none;">
      <div class="form-group field-filter-name required">
      <?php
        if(isset($model->value) && $model->value != '' && $model->type == 'range')
          {
            $splitValues = explode(';', $model->value);
      ?>
              <input type="number" name="Filter[dynamic][min]" placeholder="Minimum" id="dynamic_range_min" min="0" max="999999" class="" value="<?= $splitValues[0]; ?>" />
              <input type="number" min="0" name="Filter[dynamic][max]" placeholder="Maximum" id="dynamic_range_max" max="999999" class="" value="<?= $splitValues[1]; ?>" />
              <div class="help-block-range errormessage"></div>
      <?php
          } ?>
            <input type="number" min="0" max="999999" name="Filter[dynamic][min]" placeholder="Minimum" id="dynamic_range_min" class="" value="" />
            <input type="number" min="0" max="999999" name="Filter[dynamic][max]" placeholder="Maximum" id="dynamic_range_max" class="" value="" />
            <div class="help-block-range"></div>
      </div>
   </div>

   

   
   <div class="form-group">
      <?= Html::submitButton('Save', ['class' => 'btn btn-primary','id'=>'filter_submit']) ?>
   </div>
   <?php ActiveForm::end(); ?>
</div>


<style type="text/css">
  .bootstrap-tagsinput {
  box-shadow: none;
  padding: 7px 12px;
  border: 1px solid #e3e3e3;
  width:100%;
}
.bootstrap-tagsinput .label-info {
  background-color: #36404a !important;
  display: inline-block;
  padding: 8px 10px;
  font-size:14px;
  margin:5px;
}
/* Multiple */
.bootstrap-tagsinput > input {
  border: none;
}

#multilevelsection .bootstrap-tagsinput{
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>

<script type="text/javascript">

  $(document).ready(function() {

$(".bootstrap-tagsinput input").tagsinput({
  cancelConfirmKeysOnEmpty: false,
  confirmKeys: [13, 44]
});

  
$('.filterform').bind('keypress', function(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
    }
});


  });
    




</script>