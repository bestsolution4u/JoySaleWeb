<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return checkvalidation()']]); ?>
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
		<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Email').' '.Yii::t('app','Settings'); ?> - <?php echo Yii::t('app','SMTP').' '.Yii::t('app','Settings'); ?></h4>

		<div class="form-group ">
			<div class="m-b20 d-flex">
				<div class="m-r50">
					<div class="custom-control custom-checkbox">
						<input id="ytSitesettings_smtpEnable" type="hidden" value="0" name="Sitesettings[smtpEnable]" />
						<input type="checkbox" class="custom-control-input" name="Sitesettings[smtpEnable]" id="smtpEnable" value="1" <?php if($model->smtpEnable == '1')echo 'checked'?>>
						<label class="custom-control-label" for="smtpEnable"><?=Yii::t('app', 'Enable').' '.Yii::t('app', 'smtp')?></label>
					</div>
				</div>
			</div>
      	</div>

		<div class="form-group ">
				<label><?php echo Yii::t('app','SMTP SSL'); ?> </label>
				<div class="m-b20 d-flex">
					<div class="m-r50">
						<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="enable" name="Sitesettings[smtpSSL]" value="1" <?php if($model->smtpSSL == '1')echo 'checked'?>>
								<label class="custom-control-label" for="enable"><?php echo Yii::t('app','Enable'); ?></label>
						</div>
					</div>
					<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="Disable" name="Sitesettings[smtpSSL]" value="0" <?php if($model->smtpSSL == '0')echo 'checked'?>>
							<label class="custom-control-label" for="Disable"><?php echo Yii::t('app','Disable'); ?></label>
					</div>
			</div>
		</div>
				
		<div class="form-group">
			<label><?php echo Yii::t('app','SMTP Port'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'smtpPort')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter SMTP Port number'),'id'=>'smtpport'])->label(false); ?>
			<p class="text-danger" id="Sitesettings_smtpPort_em_"></p>
		</div>

		<div class="form-group">
			<label>SMTP <?=Yii::t('app','Host')?> </label>
			<?= $form->field($model, 'smtpHost')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter smtp host name'),'id'=>'smtphost'])->label(false); ?>
			<p class="text-danger" id="Sitesettings_smtpHost_em_"></p>
		</div>

		<div class="form-group">
			<label>SMTP <?=Yii::t('app','Email')?> </label>
			<?= $form->field($model, 'smtpEmail')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter smtp user email'),'id'=>'smtpemail'])->label(false); ?>
			<p class="text-danger" id="Sitesettings_smtpEmail_em_"></p>
		</div>
		
		<div class="form-group">
			<label>SMTP <?=Yii::t('app','Password')?></label>
			<?= $form->field($model, 'smtpPassword')->passwordInput(['maxlength' => true,'class' => 'form-control','required','id' => 'smtppass'])->label(false) ?>
			<p class="text-danger" id="Sitesettings_smtpPassword_em_"></p>
		</div>
				
		<div class="m-t20">
				<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
		</div>
       
							
<?php ActiveForm::end(); ?>
<script type="text/javascript">
		function checkvalidation() {
	//alert(0);
	var s1 = $("#smtpport").val();
	var s2 = $("#smtphost").val();
	var s3 = $("#smtpemail").val();
	var s4 = $("#smtppass").val();
	//alert(s1);
if (s1 == "") {
		$("#Sitesettings_smtpPort_em_").html(yii.t('app','smtp port cannot be empty'));
		 setTimeout(function() {
            $("#Sitesettings_smtpPort_em_").fadeOut();
        }, 3000);
		return false;
	} else {
		$("#Sitesettings_smtpPort_em_").fadeOut();
	}
	if (s2 == "") {
		$("#Sitesettings_smtpHost_em_").html(yii.t('app','smtp host cannot be empty'));
		 setTimeout(function() {
            $("#Sitesettings_smtpHost_em_").fadeOut();
        }, 3000);
		return false;
	} else {
		$("#Sitesettings_smtpHost_em_").fadeOut();
	}
	if (s3 == "") {
		$("#Sitesettings_smtpEmail_em_").html(yii.t('app','smtp email cannot be empty'));
		 setTimeout(function() {
            $("#Sitesettings_smtpEmail_em_").fadeOut();
        }, 3000);
		return false;
	} else {
		$("#Sitesettings_smtpEmail_em_").fadeOut();
	}
	if (s4 == "") {
		$("#Sitesettings_smtpPassword_em_").html(yii.t('app','smtp password cannot be empty'));
		 setTimeout(function() {
            $("#Sitesettings_smtpPassword_em_").fadeOut();
        }, 3000);
		return false;
	} else {
		$("#Sitesettings_smtpPassword_em_").fadeOut();
	}
return true;
	}
	</script>			