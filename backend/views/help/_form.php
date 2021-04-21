<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
		<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Add').' '.Yii::t('app','Help Pages'); ?></h4>
				
		<div class="form-group">
			<label><?php echo Yii::t('app','Page'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'page')->textInput(['class' => 'form-control','maxlength' => 60,'placeholder'=>Yii::t('app','Enter help query')])->label(false); ?>
      	</div>

      <div class="form-group">

      	   <select name="Help[help_lang]" id="ad_lang" class="form-control select-box-down-arrow " onchange="dropDownLang(this.val)">
 			<option selected value="en">English</option>
 			<option  value="ar">Arabic</option>
 			<option  value="fr">French</option>
 			</select>
       </div>
      

		<div class="form-group">
            <label><?php echo Yii::t('app','Description'); ?> </label><span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'pageContent')->widget(CKEditor::className(), [
                'options' => ['rows' => 3],
                'preset' => 'basic'
            ])->label(false); ?>
        </div>

	  	<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      	</div>
       
							
<?php ActiveForm::end(); ?>