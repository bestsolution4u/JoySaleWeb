<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
        <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Create').' '.Yii::t('app','User'); ?></h4>

      <label><?php echo Yii::t('app' , 'Fields with'); ?><span class="required" style="color: red;"> * </span><?php echo Yii::t('app', 'are required.'); ?></label>

      <div class="form-group">
        <label><?php echo Yii::t('app','Name') ?> </label>	<span class="required" style="color: red;"> * </span>
        <?= $form->field($model, 'name')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter name')])->label(false); ?>
      </div>

      <div class="form-group">
        <label><?php echo Yii::t('app','Username') ?> </label><span class="required" style="color: red;"> * </span>	
        <?= $form->field($model, 'username')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter  User name')])->label(false); ?>
      </div>
      
      <div class="form-group">
        <label><?php echo Yii::t('app','Email') ?> </label><span class="required" style="color: red;"> * </span>	
        <?= $form->field($model, 'email')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter Email')])->label(false); ?>
      </div>

      <div class="form-group">
            <label><?php echo Yii::t('app','Password') ?> </label><span class="required" style="color: red;"> * </span>	
              <a href="javascript:void(0);" class="" onclick="return showuserpassword();" style="position: relative; ">
                    <i class="show-button fa fa-eye fa-fw"></i>
                  </a>
  

       

            <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'id'=>'Users_password','placeholder'=>Yii::t('app','Enter your current password')])->label(false) ?>
            <?= $form->field($model, 'password')->textInput(['maxlength' => true,'id' => 'show_userpassword','style' => 'display:none;'])->label(false) ?>
            <input onclick="genpass(&#039;create&#039;)" style="display:inline;" class="btn btn-primary  pull-right user-pwd-btn" name="yt0" type="button" value="<?php echo Yii::t('app','Generate Password'); ?>" />	 
        </div>
		
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>


<script type="text/javascript">
   $('#show_userpassword').hide();
 


$(document).on('keyup', '#Users_password', function () {
console.log('f');
  $('#show_userpassword').val($('#Users_password').val());
});

$(document).on('keyup', '#show_userpassword', function () {
  $('#Users_password').val($('#show_userpassword').val());
});

</script>

