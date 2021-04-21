<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>

			<?php if(Yii::$app->controller->action->id== 'create') { ?>
        <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Add').' '.Yii::t('app','Product Conditions'); ?></h4>
      <?php } else { ?>
        <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Update').' '.Yii::t('app','Product Conditions'); ?></h4>
      <?php } ?>
		
				
		<div class="form-group">
        <label><?php echo Yii::t('app','Product condition'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'condition')->textInput(['class' => 'form-control','maxlength' => 30,'placeholder'=>Yii::t('app','Enter Product condition'),'id'=>'Sitesettings_sitename'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_sitename_em_"></p>
        </div>

        <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
         </div>
       
							
<?php ActiveForm::end(); ?>

