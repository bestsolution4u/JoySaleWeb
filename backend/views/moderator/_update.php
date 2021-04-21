<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>
<div class="admin-form">

 <?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
    <h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Edit').' '.Yii::t('app','Moderator'); ?></h4>

<?= $form->errorSummary($model); ?>


  <p class="note">
    <?php echo Yii::t('app' , 'Fields with'); ?><span class="required" style="color: red;"> * </span><?php echo Yii::t('app', 'are required.'); ?>
</p>

<label>  <?php echo Yii::t('app' , 'Name'); ?></label><span class="required" style="color: red;"> * </span>
<?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false); ?>
<?php /*
  <label>  <?php echo Yii::t('app' , 'Username'); ?></label><span class="required" style="color: red;"> * </span>
<?=$form->field($model, 'username')->textInput(['maxlength' => true])->label(false); */ ?>
<label>  <?php echo Yii::t('app' , 'Email'); ?></label><span class="required" style="color: red;"> * </span>
<?= $form->field($model, 'email')->textInput(['maxlength' => true])->label(false) ?>
<label>  <?php echo Yii::t('app' , 'Password'); ?></label><span class="required" style="color: red;"> * </span>
<?=  $form->field($model, 'password')->passwordInput(['maxlength' => true, 'id'=>'Users_password','value'=>$password])->label(false) ?>

<label>  <?php echo Yii::t('app' , 'Role'); ?></label><span class="required" style="color: red;"> * </span>

<select name="role" id="admin-role" class="form-control">
<?php foreach($roles as $role) { ?>
<option value="<?=$role['id']?>" <?php if($model['role']==$role['id']) { ?> selected="selected" <?php } ?> ><?=$role['name']?></option>
<?php } ?>
</select>
<br><br>
<div class="form-group">
    <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app','Cancel'), ['/moderator/cancel'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
