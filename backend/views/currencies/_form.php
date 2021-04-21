<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php 
$sitesetting = yii::$app->Myclass->getSitesettings();
$sitepaymentmodes = json_decode($sitesetting->sitepaymentmodes);
//echo "<pre>"; print_r($sitepaymentmodes);die;
?>
<?= Html::csrfMetaTags() ?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20','onsubmit' => 'return validateCurrencyData()']]); ?>

		<?php if(Yii::$app->controller->action->id== 'create') { ?>
            <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Add').' '.Yii::t('app','Currency'); ?></h4>
        <?php } else { ?>
            <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Update').' '.Yii::t('app','Currency'); ?></h4>
        <?php } ?>

		<?php 
			$currency = yii::$app->Myclass->getCurrencyList();
			if(!empty($selected)) {
				$selected = $model->currency_symbol.'-'.$model->currency_name;
			} else {
				$selected = '';
			}
		?>
				
		<div class="form-group">
			<label><?php echo Yii::t('app','Shortcode'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?=$form->field($model, 'currency_shortcode')->dropDownList($currency,['onchange'=>'dropDownCur(this.value)','id'=>'curshortcode','prompt'=>Yii::t('app','Select Currency'),'options' => [$selected=>['selected'=>true]]]
            )->label(false); ?>
			<?= $form->field($model, 'currency_shortcode')->hiddenInput(['id'=>'shortcode'])->label(false) ?>
			<p class="text-danger currencySCErr"></p>
		</div>

		<div class="form-group">
			<label><?php echo Yii::t('app','Currency').' '.Yii::t('app','Name'); ?> </label>
			<?= $form->field($model, 'currency_name')->textInput(['class' => 'form-control','id'=>'currencyname','readonly'=>'readonly'])->label(false); ?>
		</div>

		<div class="form-group">
			<label><?php echo Yii::t('app','Symbol').' '.Yii::t('app','Name'); ?> </label>
			<?= $form->field($model, 'currency_symbol')->textInput(['class' => 'form-control','id'=>'currencysymbol','readonly'=>'readonly'])->label(false); ?>
		</div>
		<?php if($sitepaymentmodes->bannerPaymenttype == "braintree"){?>
		<div class="form-group">
			<label><?php echo Yii::t('app','Braintree Merchant Id'); ?> </label>
			<input type="hidden" id="paymenttype" value="1" name="Currencies[paymenttype]">
			<input class="form-control" id="currencymerchantid" name="Currencies[currency_merchant_id]" type="text" value="<?php if(!empty($merchant_sc_id)) echo $merchant_sc_id; ?>">
			
		</div>
		<?php }else{?>
			<input type="hidden" id="paymenttype" value="0" name="Currencies[paymenttype]">
			<input class="form-control" id="currencymerchantid" name="Currencies[currency_merchant_id]" type="hidden" value="<?php 
			if(!empty($merchant_sc_id)) 
				echo $merchant_sc_id;
			else
				echo ""; ?>">
		<?php } ?>
		<p class="errorMessage currencyErr text-danger m-t10"></p>
		<div class="form-group ">
				<label><?php echo Yii::t('app','Currency Mode'); ?> </label>
				<div class="m-b20 d-flex">
					<div class="m-r50">
						<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" checked id="currency_mode_symbol" name="Currencies[currency_mode]" value="symbol" <?php if($model->currency_mode == 'symbol')echo 'checked'?>>
								<label class="custom-control-label" for="currency_mode_symbol"><?php echo Yii::t('app','Symbol'); ?></label>
						</div>
					</div>
					<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="currency_mode_code" name="Currencies[currency_mode]" value="code" <?php if($model->currency_mode == 'code')echo 'checked'?>>
							<label class="custom-control-label" for="currency_mode_code"><?php echo Yii::t('app','Shortcode'); ?></label>
					</div>
					<p class="errorMessage currencymodeErr text-danger m-t10"></p>
			</div>
		</div>

			<div class="form-group ">
				<label><?php echo Yii::t('app','Position of Currency Symbol'); ?> </label>
				<div class="m-b20 d-flex">
					<div class="m-r50">
						<div class="custom-control custom-radio">
								<input type="radio" checked class="custom-control-input" id="currency_position_prefix" name="Currencies[currency_position]" value="prefix" <?php if($model->currency_position == 'prefix')echo 'checked'?>>
								<label class="custom-control-label" for="currency_position_prefix"><?php echo Yii::t('app','Prefix'); ?></label>
						</div>
					</div>
					<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="currency_position_postfix" name="Currencies[currency_position]" value="postfix" <?php if($model->currency_position == 'postfix')echo 'checked'?>>
							<label class="custom-control-label" for="currency_position_postfix"><?php echo Yii::t('app','Postfix'); ?></label>
					</div>
					<p class="errorMessage currencypositionErr text-danger m-t10"></p>
			</div>
		</div>
				

		<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
		</div>
       
							
<?php ActiveForm::end(); ?>
<style type="text/css">
	.currencySCErr , .currencyErr, .currencymodeErr, .currencypositionErr
	{
		color: red !important;
	}

</style>
<script>
var readAjax = 1;
function dropDownCur(value,label) {
	var shortcode = $('#curshortcode :selected').text();
	if(readAjax == 1){
		readAjax = 0;
		$.ajax({
			url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/currencies/getbraintreeid',
			type: "post",
			data : {'shortcode': $.trim(shortcode)},
			success: function(response){
				var currencyName = value.split("-");
				$("#shortcode").val(shortcode);
				$("#currencysymbol").val(currencyName[0]);
				$("#currencyname").val(currencyName[1]);
				$("#currencyname").val(currencyName[1]);
				$("#currencymerchantid").val($.trim(response));
				readAjax = 1;
			},
		});
	}
}
</script>
