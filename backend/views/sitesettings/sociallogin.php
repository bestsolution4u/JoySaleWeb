<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use conquer\toastr\ToastrWidget;
	use kartik\alert\Alert;
?>


<?php 

$form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validate_logos()']]); 
$form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data']]); 
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php  echo Yii::t('app','Site').' '.Yii::t('app','Settings'); ?> - <?php  echo Yii::t('app','Social Login').' '.Yii::t('app','Details'); ?></h4>

			<div class="form-group ">
				<div class="m-b20 d-flex">
					<div class="m-r50">
            <div class="custom-control custom-checkbox">
						<input id="ytSitesettings_facebookstatus" type="hidden" value="0" name="Sitesettings[facebookstatus]" />
              <input type="checkbox" class="custom-control-input" name="Sitesettings[facebookstatus]" id="facebookstatus" value="1" <?php if($model->facebookstatus == '1')echo 'checked'?>>
              <label class="custom-control-label" for="facebookstatus"><?=Yii::t('app','Enable').' '.Yii::t('app','Facebook')?></label>
            </div>
					</div>
				</div>
      </div>
				
			<div class="form-group">
        <label><?php  echo Yii::t('app','Facebook').' '.Yii::t('app','App id'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'facebookappid')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter facebook app id')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_facebookappid_em_"></p>
      </div>

      <div class="form-group">
        <label><?php  echo Yii::t('app','Facebook').' '.Yii::t('app','Secret Key'); ?> </label>
				<?= $form->field($model, 'facebooksecret')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter facebook secret key')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_facebooksecret_em_"></p>
      </div>
			
			<div class="form-group ">
				<div class="m-b20 d-flex">
					<div class="m-r50">
            <div class="custom-control custom-checkbox">
							<input id="ytSitesettings_googlestatus" type="hidden" value="0" name="Sitesettings[googlestatus]" />
              <input type="checkbox" class="custom-control-input" name="Sitesettings[googlestatus]" id="googlestatus" value="1" <?php if($model->googlestatus == '1')echo 'checked'?>>
              <label class="custom-control-label" for="googlestatus"><?=Yii::t('app','Enable').' '.Yii::t('app','Google')?></label>
            </div>
					</div>
				</div>
      </div>
				
			<div class="form-group">
        <label><?php  echo Yii::t('app','Google').' '.Yii::t('app','Client id'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'googleappid')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter google client id')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_googleappid_em_"></p>
      </div>

      	<div class="form-group">
        <label><?php  echo Yii::t('app','Google').' '.Yii::t('app','Secret Key'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'googlesecret')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter google secret key')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_googlesecret_em_"></p>
      </div>





		
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>
