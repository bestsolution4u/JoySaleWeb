<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id'=>'commissions-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','onsubmit' => 'return validateCommission()']]); ?>
		<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app',ucfirst(Yii::$app->controller->action->id)).' '.Yii::t('app','Commission'); ?> </h4>

        <div class="form-group">
            <label><?php echo Yii::t('app','Fields with'); ?> </label>	<span class="required" style="color: red;"> * </span> <label><?php echo Yii::t('app','are required.'); ?> </label>
        </div>
				
		<div class="form-group">
            <label><?php echo Yii::t('app','Commission Amount In Percentage'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'percentage')->textInput(['maxlength' => true,'id'=>'commission','class'=>'form-control','maxlength'=>3,'onkeypress' => 'return isNumber(event)','placeholder'=>Yii::t('app','Enter commission Amount In Percentage')])->label(false); ?>
			<p class="text-danger" id="commission-error"></p>
        </div>

        <div class="form-group">
            <label><?php echo Yii::t('app','Minimum Range'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'minRate')->textInput(['maxlength' => true,'id'=>'minrange','class'=>'form-control','maxlength'=>9,'onkeypress' => 'return isNumber(event)','placeholder'=>Yii::t('app','Enter Minimum Range')])->label(false); ?>
			<p class="text-danger" id="minError"></p>
        </div>

        <div class="form-group">
            <label><?php echo Yii::t('app','Maximum Range'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'maxRate')->textInput(['maxlength' => true,'id'=>'maxrange','class'=>'form-control','maxlength'=>9,'onkeypress' => 'return isNumber(event)','placeholder'=>Yii::t('app','Enter Maximum Range')])->label(false); ?>
			<p class="text-danger" id="maxError"></p>
        </div>

        <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
        </div>    
							
<?php ActiveForm::end(); ?>
