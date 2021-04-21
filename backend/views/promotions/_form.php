<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'boxShadow p-3 bgWhite m-b20','enableAjaxValidation' => true,
'validateOnSubmit' => true],'id'=>'promo_form']); ?>

			<?php if(Yii::$app->controller->action->id== 'create') { ?>
                <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Add').' '.Yii::t('app','Promotion'); ?></h4>
            <?php } else { ?>
                <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Update').' '.Yii::t('app','Promotion'); ?></h4>
            <?php } ?>
		
				
		<div class="form-group">
			<label><?php echo Yii::t('app','Name'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'name')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your promotion name'),'id'=>'Promotions_name'])->label(false); ?>
			<p class="text-danger" id="nameerr"></p>
	  	</div>
	  
		<div class="form-group">
			<label><?php echo Yii::t('app','Days'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'days')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter your promotion days'),'id' => 'Promotions_days','onkeypress'=>'return isNumber(event)','maxlength'=>'4'])->label(false); ?>
			<p class="text-danger" id="dayserr"></p>
		</div>

		<div class="form-group">
			<label><?php echo Yii::t('app','Price'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'price')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter your promotion name'),'id' => 'Promotions_price','onkeypress'=>'return isNumber(event)','placeholder'=>$placeholder,'maxlength'=>'6'])->label(false); ?>
			<p class="text-danger" id="priceerr"></p>
	  	</div>


		<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10','id'=>'btnUpdate','onclick'=>'return validatepromotion()']) ?>
		</div>
       
							
<?php ActiveForm::end(); ?>
