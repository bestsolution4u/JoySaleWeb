<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validate_details()']]); ?>
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Braintree').' '.Yii::t('app','Payment Gateway'); ?> - <?php echo Yii::t('app','Configuration and Settings'); ?></h4>
				
			<div class="form-group">
        <label><?php echo Yii::t('app','Brain Tree Type'); ?> </label>	<span class="required" style="color: red;"> * </span>
					<div class="m-b20 d-flex">
						<div class="m-r50">
							<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="live" name="Sitesettings[brainTreeType]" value="1" <?php if($model->brainTreeType == '1')echo 'checked'?>>
								<label class="custom-control-label" for="live"><?php echo Yii::t('app','Live'); ?></label>
							</div>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="sandbox" name="Sitesettings[brainTreeType]" value="2" <?php if($model->brainTreeType == '2')echo 'checked'?>>
							<label class="custom-control-label" for="sandbox"><?php echo Yii::t('app','Sandbox'); ?></label>
						</div>
				</div>
      </div>

      <div class="form-group">
        <label><?php echo Yii::t('app','Brain Tree Merchant Id'); ?> </label><span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'brainTreeMerchantId')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your Brain Tree Merchant Id'),'id'=>'mer_id'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_brainTreeMerchantId_em_"></p>
      </div>

			<div class="form-group">
        <label><?php echo Yii::t('app','Brain Tree Public Key'); ?> </label><span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'brainTreePublicKey')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your Brain Tree Public Key'),'id'=>'public_key'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_brainTreePublicKey_em_"></p>
      </div>

			<div class="form-group">
        <label><?php echo Yii::t('app','Brain Tree Private Id'); ?> </label><span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'brainTreePrivateKey')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your Brain Tree Private Id'),'id'=>'Private_id'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_brainTreePrivateKey_em_"></p>
      </div>

			<div class="form-group">
				<span class="required" style="color:#ff0000;"><?php echo Yii::t('app','Note: If Braintree Credentials are updated, please change the Merchant Account Id for available Currencies')?></span>
			</div> 

			<div class="m-t20">
				<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
							
<?php ActiveForm::end(); ?>


