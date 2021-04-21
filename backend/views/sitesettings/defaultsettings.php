<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1.'/media/logo'.'/';

?>

<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data', 'onsubmit' => 'return validateprice()']]); ?>
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Default').' '.Yii::t('app','Settings'); ?></h4>
				
	<div class="form-group">
        <label><?php echo Yii::t('app','Search List (Enter Maximum Distance Value)'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'searchList')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter Maximum Distance Value'),'maxlength' => '13','onkeypress'=>'return isNumber(event)'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_searchList_em_"></p>
			</div>
						<div class="form-group">
        <label><?php echo Yii::t('app','No of digits (Before the decimal notation)'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'pricerange[before_decimal_notation]')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter the Value'),'maxlength' => '2','onkeypress'=>'return isNumber(event)', 'id'=>'before_decimal_notation'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_pricerange_em_"></p>
				<p class="text-danger" id="Sitesettings_pricerange_before_em_"></p>
				
			</div>
							<div class="form-group">
        <label><?php echo Yii::t('app','No of digits (After the decimal notation)'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'pricerange[after_decimal_notation]')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter the Value'),'maxlength' => '2','onkeypress'=>'return isNumber(event)', 'id'=>'after_decimal_notation'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_pricerange_em_"></p>
				<p class="text-danger" id="Sitesettings_pricerange_after_em_"></p>
			</div>
			<div class="text-danger p-b10"><?=yii::t('app','Note : Stripe and Braintree will support maximum of 8 digits only. The payment will not work if more than 8 digits given.')?></div>
			<div class="form-group ">
        <label><?php echo Yii::t('app','Search Type'); ?> </label>
				<?= $form->field($model, 'searchType')->dropDownList(
            ['miles' => 'Miles','kilometer'=>'Kilometer'],['class'=>'form-control','style' => 'width:initial']
            )->label(false); ?>	
				<p class="text-danger" id="Sitesettings_metaTitle_em_"></p>
			</div>
			
			<div class="form-group ">
				<label><?php echo Yii::t('app','Signup Active'); ?> </label>
				<div class="m-b20 d-flex">
					<div class="m-r50">
						<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="enable" name="Sitesettings[signup_active]" value="yes" <?php if($model->signup_active == 'yes')echo 'checked'?>>
								<label class="custom-control-label" for="enable"><?php echo Yii::t('app','Enable'); ?></label>
						</div>
					</div>
					<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="Disable" name="Sitesettings[signup_active]" value="no" <?php if($model->signup_active == 'no')echo 'checked'?>>
							<label class="custom-control-label" for="Disable"><?php echo Yii::t('app','Disable'); ?></label>
					</div>
				</div>
			</div>

			<div class="form-group">
        <label><?php echo Yii::t('app','Api Key') ?> </label><span class="required" style="color: red;"> * </span>	
				<?= $form->field($model, 'googleapikey')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter google api key'),'id'=>'Sitesettings_googleapikey'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_googleapikey_em_"></p>
			</div>
			
			<div class="form-group">
        <label><?php echo Yii::t('app','Static Map Api Key') ?> </label>
				<?= $form->field($model, 'staticMapApiKey')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter Static Map Api Key')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_staticMapApiKey_em_"></p>
			</div>
			
			
		
		
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>
<script type="text/javascript">
	function validateprice(){
		var before_decimal_notation = $('#before_decimal_notation').val();
		var after_decimal_notation = $('#after_decimal_notation').val();
		if(before_decimal_notation > 12){
			$("#Sitesettings_pricerange_before_em_").show();
	        $("#Sitesettings_pricerange_before_em_").html(yii.t('app', "Maximum number of digits before decimal notation should not exceed 12"));
	        $("#before_decimal_notation").focus();
	        setTimeout(function () {
			$('#Sitesettings_pricerange_before_em_').fadeOut();
		}, 1000);
        return false;
		}

		if(after_decimal_notation > 3){
			$("#Sitesettings_pricerange_after_em_").show();
	        $("#Sitesettings_pricerange_after_em_").html(yii.t('app', "Maximum number of digits after decimal notation should not exceed 3"));
	        $("#before_decimal_notation").focus();
	        setTimeout(function () {
			$('#Sitesettings_pricerange_after_em_').fadeOut();
		}, 1000);
        return false;
		}
	}
</script>