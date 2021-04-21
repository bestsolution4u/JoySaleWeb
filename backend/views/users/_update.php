<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
<div class="d-flex justify-content-between  flex-column flex-sm-row">
  <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Update').' '.Yii::t('app','User'); ?></h4>
  <div class="">
    <button class='btn btn-primary align-text-top border-0 m-b10'>
      <?=Html::a('<i class="fa fa-angle-double-left  p-r10"></i> '.Yii::t('app','Back'),['users/index']); ?> 
    </button> 
  </div>
</div>
<label><?php echo Yii::t('app' , 'Fields with'); ?><span class="required" style="color: red;"> * </span><?php echo Yii::t('app', 'are required.'); ?></label>
<div class="form-group">
  <label><?php echo Yii::t('app','Name') ?> </label>  <span class="required" style="color: red;"> * </span>
  <?= $form->field($model, 'name')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter name')])->label(false); ?>
</div>
<div class="form-group">
  <label><?php echo Yii::t('app','Username') ?> </label>
  <?= $form->field($model, 'username')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter  User name'),  'readonly'=> true])->label(false); ?>
</div>
<div class="form-group">
  <label><?php echo Yii::t('app','Email') ?> </label>
  <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly'=> true])->label(false)  ?>
</div>
<div class="m-t20">
  <?= Html::submitButton(Yii::t('app','Update'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
</div>
<?php ActiveForm::end(); ?>