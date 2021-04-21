<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1.'/media/logo'.'/';

?>

<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data']]); ?>
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Manage').' '.Yii::t('app','SEO').' '.Yii::t('app','Settings'); ?></h4>
				
			<div class="form-group">
        <label><?php echo Yii::t('app','Site Name'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'sitename')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your site name'),'id'=>'Sitesettings_sitename'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_sitename_em_"></p>
      </div>

      <div class="form-group">
        <label><?php echo Yii::t('app','Meta Title'); ?> </label>
				<?= $form->field($model, 'metaTitle')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter meta title for your site'),'id'=>'Sitesettings_sitename'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_metaTitle_em_"></p>
      </div>

      <div class="form-group">
        <label><?php echo Yii::t('app','Meta Description') ?> </label>
				<?= $form->field($model, 'metaDescription')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter Meta Description for your site')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_metaDescription_em_"></p>
      </div>
      
      <div class="form-group">
        <label><?php echo Yii::t('app','Meta Keywords') ?> </label>
				<?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter Meta Keywords for your site')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_metaKeywords_em_"></p>
			</div>
				<div class="form-group">
        <label><?php echo Yii::t('app','Analytics Code(Tracking Code)'); ?> </label>
				<?=$form->field($model, 'tracking_code')->textarea(['rows' => '6','class' => 'form-control'])->label(false) ?>
				<p class="text-danger" id="Sitesettings_tracking_code_em_"></p>
			</div>
		
			
		
			
			

			


		

		
		
			
	
	
		
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>
