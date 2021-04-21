<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1.'/media/logo'.'/';
$productpath = $path1.'/media/item'.'/';

?>

<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validate_logos()']]); ?>
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Logo').''.Yii::t('app','Settings'); ?></h4>
				
			

     

    
      
			
			<div class="form-group">
				<label><?php echo Yii::t('app','Logo') ?> </label><span class="required" style="color: red;"> * </span>
				<input id="ytSitesettings_logo" type="hidden" value="" name="Sitesettings[logo]" />	
				<?= $form->field($model, 'logo')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'logo'])->label(false); ?>
				<?=Html::img($path.$model->logo.'', array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'))?>
				<img src="" class="borderCurve borderGradient picture-src dnone" id="logoPreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
				<p class="text-danger m-t20" id="Sitesettings_logo_em_"></p>
			</div>

			<div class="form-group">
				<label><?php echo Yii::t('app','Logo Dark Version') ?> </label><span class="required" style="color: red;"> * </span>
				<input id="ytSitesettings_logoDarkVersion" type="hidden" value="" name="Sitesettings[logoDarkVersion]" />	
				<?= $form->field($model, 'logoDarkVersion')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'darklogo'])->label(false); ?>
				<?=Html::img($path.$model->logoDarkVersion.'', array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'))?>
				<img src="" class="borderCurve borderGradient picture-src dnone" id="darklogoPreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
				<p class="text-danger m-t20" id="Sitesettings_logoDarkVersion_em_"></p>
			</div>

			<div class="form-group">
				<label><?php echo Yii::t('app','Default User Image') ?> </label><span class="required" style="color: red;"> * </span>
				<input id="ytSitesettings_default_userimage" type="hidden" value="" name="Sitesettings[default_userimage]" />	
				<?= $form->field($model, 'default_userimage')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','onchange' => 'check_extension()','id' => 'userimage'])->label(false); ?>
				<?=Html::img($path.$model->default_userimage.'', array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'))?>
				<img src="" class="borderCurve borderGradient picture-src dnone" id="userPreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
				<p class="text-danger m-t20" id="Sitesettings_default_userimage_em_"></p>
			</div>

					<div class="form-group">
				<label><?php  echo Yii::t('app','Default Product Image') ?> </label><span class="required" style="color: red;"> * </span>
				<input id="ytSitesettings_default_productimage" type="hidden" value="" name="Sitesettings[default_productimage]" />	
				<?= $form->field($model, 'default_productimage')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','onchange' => 'check_extension()','id' => 'productimage'])->label(false); ?>
				<?=Html::img($productpath.$model->default_productimage.'', array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'))?>
				<img src="" class="borderCurve borderGradient picture-src dnone" id="productPreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
				<p class="text-danger m-t20" id="Sitesettings_default_productimage_em_"></p>
			</div>

			<div class="form-group">
				<label><?php echo Yii::t('app','Favicon') ?> </label><span class="required" style="color: red;"> * </span>
				<input id="ytSitesettings_favicon" type="hidden" value="" name="Sitesettings[favicon]" />
				<?= $form->field($model, 'favicon')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'favicon'])->label(false); ?>
				<?=Html::img($path.$model->favicon.'', array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'))?>
				<img src="" class="borderCurve borderGradient picture-src dnone" id="faviconPreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
				<p class="text-danger m-t20" id="Sitesettings_favicon_em_"></p>
			</div>

				<div class="form-group">
				<label><?php echo Yii::t('app','Watermark Image') ?> </label><span class="required" style="color: red;"> * </span>
				<p class="text-danger">Note: Upload the image size below(200 X 200).</p>
				<input id="ytSitesettings_watermark" type="hidden" value="" name="Sitesettings[watermark]" />
				<?= $form->field($model, 'watermark')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'watermark'])->label(false); ?>
				<?=Html::img($path.$model->watermark.'', array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'))?>
				<img src="" class="borderCurve borderGradient picture-src dnone" id="watermarkPreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
				<p class="text-danger m-t20" id="Sitesettings_watermark_em_"></p>
			</div>

		
		
			
		
		
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>
