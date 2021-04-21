<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

<?php $form = ActiveForm::begin(['id'=>'stripesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validate_details()']]); ?>
				<?php 
            if(Yii::$app->session->hasFlash('success')): 
                echo Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'body' => Yii::$app->session->getFlash('success'),
                'delay' => 8000
                ]); 
            endif; 

            if(Yii::$app->session->hasFlash('error')): 
                echo Alert::widget([
                'type' => Alert::TYPE_DANGER,
                'body' => Yii::$app->session->getFlash('error'),
                'delay' => 8000
                ]); 
            endif; 
        ?>
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Stripe').' '.Yii::t('app','Payment Gateway'); ?> - <?php echo Yii::t('app','Configuration and Settings'); ?></h4>
				
			<div class="form-group">
        <label><?php echo Yii::t('app','Stripe Type'); ?> </label>	<span class="required" style="color: red;"> * </span>
					<div class="m-b20 d-flex">
						<div class="m-r50">
							<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="live" name="Sitesettings[stripeType]" value="1" <?php if($model->stripeType == '1')echo 'checked'?>>
								<label class="custom-control-label" for="live"><?php echo Yii::t('app','Live'); ?></label>
							</div>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="sandbox" name="Sitesettings[stripeType]" value="2" <?php if($model->stripeType == '2')echo 'checked'?>>
							<label class="custom-control-label" for="sandbox"><?php echo Yii::t('app','Sandbox'); ?></label>
						</div>
				</div>
      </div>

    

			<div class="form-group">
        <label><?php echo Yii::t('app','Stripe Public Key'); ?> </label><span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'stripePublicKey')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your Stripe Public Key'),'id'=>'public_key'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_stripePublicKey_em_"></p>
      </div>

			<div class="form-group">
        <label><?php echo Yii::t('app','Stripe Private Id'); ?> </label><span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'stripePrivateKey')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your Stripe Private Id'),'id'=>'Private_id'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_stripePrivateKey_em_"></p>
      </div>

			

			<div class="m-t20">
				<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
							
<?php ActiveForm::end(); ?>


