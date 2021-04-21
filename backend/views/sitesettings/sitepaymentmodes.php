<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data']]);
	//$form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validate_logos()']]);
 ?>
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
		<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Site Payment'). '  -  ' .Yii::t('app','Transaction Modes and Configurations'); ?></h4>
				
	

		<input type="hidden" name="Sitesettings[scrowPaymentMode]" value="1">

		<div class="form-group">
        	<label><?php echo Yii::t('app','Cancel order should be hide in which status ?') ?> </label>
			<?php
				echo '<select name="Sitesettings[cancelEnableStatus]" class="form-control" style="width:auto;">';
				if($model->cancelEnableStatus == "processing")
				{
					echo '<option value="processing" selected>'.Yii::t('app','Processing').'</option><option value="shipped">'.Yii::t('app','Shipped').'</option>';
				}else{
					echo '<option value="processing">'.Yii::t('app','Processing').'</option><option value="shipped" selected>'.Yii::t('app','Shipped').'</option>';
				}
				echo '</select>';
			?>
		</div>

		<div class="form-group">
        	<label><?php echo Yii::t('app','Seller Claim Enable Days') ?> </label>
			<?= $form->field($model, 'sellerClimbEnableDays')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter Seller Claim Enable Days')])->label(false); ?>
		</div>	


		   <div class="form-group ">
        <label><?php echo Yii::t('app','Payment Type(Banner & Promotion)'); ?> </label>
        <div class="m-b20 d-flex">
          <div class="m-r50">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="stripepayment" name="Sitesettings[bannerPaymenttype]" value="stripe" <?php if($model->bannerPaymenttype == 'stripe')echo 'checked'?>>
                <label class="custom-control-label" for="stripepayment"><?php echo Yii::t('app','Stripe'); ?></label>
            </div>
          </div>
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" id="braintreepayment" name="Sitesettings[bannerPaymenttype]" value="braintree" <?php if($model->bannerPaymenttype == 'braintree')echo 'checked'?>>
              <label class="custom-control-label" for="braintreepayment"><?php echo Yii::t('app','Braintree'); ?></label>
          </div>
      </div>
    </div>	

		<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
		</div>
       
							
<?php ActiveForm::end(); ?>
